<?php
include('php/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$atleti = [];
$tuttiAtleti = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $commission_members = $_POST['commission_members'];
    $grado = $_POST['grado'];

    // Inserisci la sessione nel database
    $query = "INSERT INTO sessions (date, time, commission_members) VALUES ('$date', '$time', '$commission_members')";
    mysqli_query($conn, $query);

    // Recupera la lista degli atleti
    $query = "SELECT * FROM atleti WHERE Grado = '$grado' AND (Presenze = 'Sufficienti' OR Presenze = 'Appena sufficienti')";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $atleti[] = $row;
    }
    
    // Ordina gli atleti per cognome
    usort($atleti, function($a, $b) {
        return strcmp($a['Cognome'], $b['Cognome']);
    });

    //prendo gli atleti

    $query = "SELECT * FROM atleti";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $tuttiAtleti[] = $row;
    }

    // Ordina gli atleti per cognome
    usort($tuttiAtleti, function($a, $b) {
        return strcmp($a['Cognome'], $b['Cognome']);
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Session</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include('templates/header.php'); ?>
    <h2>Create Session</h2>
    <form id="create-session-form" method="post">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
        <label for="time">Time:</label>
        <input type="time" id="time" name="time" required>
        <label for="commission_members">Commission Members:</label>
        <textarea id="commission_members" name="commission_members" required></textarea>
        <label for="grado">Select Grade:</label>
        <select id="grado" name="grado" required>
            <option value="">Select Grade</option>
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
        <button type="submit">Create</button>
    </form>
    <div>
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <h2>Athletes List</h2>
            <button id="addNewAthlete" type="submit">Aggiungi un atleta</button>
            <select id="searchAthlete">
                <?php foreach($tuttiAtleti as $atl): ?>
                    <option value='<?php echo $atl['PresenzeNumero'] ?>'><?php echo $atl['Nome'] . ' ' . $atl['Cognome'] ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (count($atleti) > 0): ?>
                <ul id="atleti_list">
                    <?php foreach ($atleti as $atleta): ?>
                        <li><?php echo $atleta['Nome'] . ' ' . $atleta['Cognome'] . ' - ' . $atleta['Grado'] . ' - ' . $atleta['Presenze']; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No athletes found for the selected grade.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <?php include('templates/footer.php'); ?>
</body>
</html>
