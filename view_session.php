<?php
include('php/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$session_id = $_GET['session_id'] ?? null;
$grado = $_GET['grado'] ?? null;

if (!$session_id || !$grado) {
    die('Invalid session or grade');
}

$query = "SELECT * FROM sessions WHERE id = $session_id";
$result = mysqli_query($conn, $query);
$session = mysqli_fetch_assoc($result);

$query = "SELECT * FROM atleti WHERE grado = '$grado' AND (presenze = 'Sufficienti' OR presenze = 'Appena sufficienti')";
$result = mysqli_query($conn, $query);
$atleti = [];
while ($row = mysqli_fetch_assoc($result)) {
    $atleti[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Session</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include('templates/header.php'); ?>
    <h2>Session Details</h2>
    <p>Date: <?php echo $session['date']; ?></p>
    <p>Time: <?php echo $session['time']; ?></p>
    <p>Commission Members: <?php echo $session['commission_members']; ?></p>

    <h2>Athletes List</h2>
    <?php if (count($atleti) > 0): ?>
        <ul>
            <?php foreach ($atleti as $atleta): ?>
                <li><?php echo $atleta['nome'] . ' - ' . $atleta['grado'] . ' - ' . $atleta['presenze']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No athletes found for the selected grade.</p>
    <?php endif; ?>
    <?php include('templates/footer.php'); ?>
</body>
</html>
