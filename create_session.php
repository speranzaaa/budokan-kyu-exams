<?php
include('php/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$atleti = [];
$tuttiAtleti = [];
$session_id = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $commission_members = $_POST['commission_members'];
    $grado = $_POST['grado'];

    // Inserisci la sessione nel database
    $query = "INSERT INTO sessions (date, time, commission_members) VALUES ('$date', '$time', '$commission_members')";
    mysqli_query($conn, $query);
    $session_id = mysqli_insert_id($conn);

    // Recupera la lista degli atleti
    $query = "SELECT * FROM atleti WHERE Grado = '$grado' AND (Presenze = 'Sufficienti' OR Presenze = 'Appena sufficienti')";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $atleti[] = $row;
    }

    // Recupera tutti gli atleti
    $query = "SELECT * FROM atleti";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $tuttiAtleti[] = $row;
    }

    // Ordina gli atleti per cognome
    usort($atleti, function($a, $b) {
        return strcmp($a['Cognome'], $b['Cognome']);
    });
    usort($tuttiAtleti, function($a, $b) {
        return strcmp($a['Cognome'], $b['Cognome']);
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .hidden { display: none; }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchAthlete = document.getElementById('searchAthlete');
            const atletiList = document.getElementById('atleti_list');
            const hiddenAtletiInput = document.getElementById('hidden_atleti_ids');

            searchInput.addEventListener('input', function() {
                const filter = searchInput.value.toLowerCase();
                const options = searchAthlete.options;
                for (let i = 0; i < options.length; i++) {
                    const option = options[i];
                    const text = option.textContent.toLowerCase();
                    option.classList.toggle('hidden', !text.includes(filter));
                }
            });

            searchAthlete.addEventListener('change', function() {
                const selectedOption = searchAthlete.selectedOptions[0];
                const id = selectedOption.value;
                const text = selectedOption.textContent;
                const li = document.createElement('li');
                li.dataset.id = id;
                li.innerHTML = `${text} <button type="button" class="remove-btn">Rimuovi</button>`;
                atletiList.appendChild(li);
                selectedOption.disabled = true;
                updateHiddenInput();
            });

            atletiList.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-btn')) {
                    const li = event.target.closest('li');
                    const id = li.dataset.id;
                    li.remove();
                    const option = searchAthlete.querySelector(`option[value="${id}"]`);
                    if (option) option.disabled = false;
                    updateHiddenInput();
                }
            });

            function updateHiddenInput() {
                const selectedIds = Array.from(atletiList.children).map(li => li.dataset.id);
                hiddenAtletiInput.value = selectedIds.join(',');
            }
        });
    </script>
</head>
<body>
    <?php include('templates/header.php'); ?>
    <h1>Creazione Sessione</h1>
    <div id="sessionId">
    <form id="create-session-form" method="post">
        <label for="date">Data:</label>
        <input type="date" id="date" name="date" required>
        <label for="time">Ora:</label>
        <input type="time" id="time" name="time" required>
        <label for="commission_members">Commissione:</label>
        <textarea id="commission_members" name="commission_members" required></textarea>
        <label for="grado">Seleziona il grado attuale:</label>
        <select id="grado" name="grado" required>
            <option value="">Kyu attuale</option>
            <option value="1° kyu">1° kyu</option>
            <option value="2° kyu">2° kyu</option>
            <option value="3° kyu">3° kyu</option>
            <option value="4° kyu">4° kyu</option>
            <option value="5° kyu">5° kyu</option>
            <option value="6° kyu">6° kyu</option>
            <option value="7° kyu">7° kyu</option>
            <option value="8° kyu">8° kyu</option>
            <option value="9° kyu">9° kyu</option>
        </select>
        <button type="submit">Vedi atleti</button>
    </form>
    </div>
    <div>
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <h2>Elenco degli Atleti</h2>
            <input type="text" id="searchInput" placeholder="Cerca atleti...">
            <select id="searchAthlete" size="10">
                <?php foreach($tuttiAtleti as $atl): ?>
                    <option value='<?php echo $atl['Id'] ?>'><?php echo $atl['Nome'] . ' ' . $atl['Cognome'] ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (count($atleti) > 0): ?>
                <ul id="atleti_list">
                    <?php foreach ($atleti as $atleta): ?>
                        <li data-id="<?php echo $atleta['Id']; ?>"><?php echo $atleta['Nome'] . ' ' . $atleta['Cognome'] . ' - ' . $atleta['Grado'] . ' - ' . $atleta['Presenze']; ?>
                            <button type="button" class="remove-btn">Rimuovi</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <form id="update-scores-form" action="update_scores.php" method="post">
                    <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                    <input type="hidden" name="time" value="<?php echo $time; ?>">
                    <input type="hidden" name="commission_members" value="<?php echo $commission_members; ?>">
                    <input type="hidden" id="hidden_atleti_ids" name="atleti_ids" value="">
                    <input type="submit" value="Aggiorna voti"></input>
                </form>
            <?php else: ?>
                <p>Nessun atleta corrisponde ai criteri di ricerca.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php include('templates/footer.php'); ?>
</body>
</html>
