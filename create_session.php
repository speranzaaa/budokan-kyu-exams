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
    // Ordina gli atleti per cognome
    usort($tuttiAtleti, function($a, $b) {
        return strcmp($a['Cognome'], $b['Cognome']);
    });

    //prendo gli atleti
    $query = "SELECT * FROM atleti";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $tuttiAtleti[] = $row;
    }

    // Ordina gli atleti per cognome
    usort($atleti, function($a, $b) {
        return strcmp($a['Cognome'], $b['Cognome']);
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
<body>
    <?php include('templates/header.php'); ?>
    <h2>Creazione Sessione</h2>
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
    <div>
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <h2>Elenco degli Atleti</h2>
            <button id="addNewAthlete" type="button">Aggiungi un atleta</button>
            <select id="searchAthlete">
                <?php foreach($tuttiAtleti as $atl): ?>
                    <option value='<?php echo $atl['Id'] ?>'><?php echo $atl['Nome'] . ' ' . $atl['Cognome'] ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (count($atleti) > 0): ?>
                <ul id="atleti_list">
                    <?php foreach ($atleti as $atleta): ?>
                        <li><?php echo $atleta['Nome'] . ' ' . $atleta['Cognome'] . ' - ' . $atleta['Grado'] . ' - ' . $atleta['Presenze']; ?></li>
                    <?php endforeach; ?>
                </ul>
                <form id="update-scores-form" action="update_scores.php" method="post">
                    <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                    <input type="hidden" name="time" value="<?php echo $time; ?>">
                    <input type="hidden" name="commission_members" value="<?php echo $commission_members; ?>">
                    <?php foreach ($atleti as $atleta): ?>
                        <input type="hidden" name="atleti_ids[]" value="<?php echo $atleta['Id']; ?>">
                    <?php endforeach; ?>
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
