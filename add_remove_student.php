<?php
include('php/db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$session_id = $_GET['session_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $query = "INSERT INTO students (session_id, name) VALUES ('$session_id', '$name')";
    mysqli_query($conn, $query);
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];
$query = "DELETE FROM students WHERE id='$id'";
mysqli_query($conn, $query);
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();

$students_query = "SELECT * FROM students WHERE session_id='$session_id'";
$students = mysqli_query($conn, $students_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include('templates/header.php'); ?>
    <h2>Manage Students</h2>
    <form method="post">
        <label for="name">Student Name:</label>
        <input type="text
