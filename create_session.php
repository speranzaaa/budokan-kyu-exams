<?php
include('php/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $commission_members = $_POST['commission_members'];

    $query = "INSERT INTO sessions (date, time, commission_members) VALUES ('$date', '$time', '$commission_members')";
    mysqli_query($conn, $query);
    header('Location: view_session.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Session</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include('templates/header.php'); ?>
    <h2>Create Session</h2>
    <form method="post">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
        <label for="time">Time:</label>
        <input type="time" id="time" name="time" required>
        <label for="commission_members">Commission Members:</label>
        <textarea id="commission_members" name="commission_members" required></textarea>
        <button type="submit">Create</button>
    </form>
    <?php include('templates/footer.php'); ?>
</body>
</html>
