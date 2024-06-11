<?php
include('db.php');

$grado = $_GET['grado'];

$query = "SELECT * FROM atleti WHERE grado = '$grado' AND (presenze = 'Sufficienti' OR presenze = 'Appena sufficienti')";
$result = mysqli_query($conn, $query);

$atleti = [];
while ($row = mysqli_fetch_assoc($result)) {
    $atleti[] = $row;
}

echo json_encode($atleti);
?>
