<?php
include('php/db.php');
session_start();

if($_SERVER['REQUEST_METHOD']=="POST"){
    $id = $_POST["id"];
    $query = "SELECT atleti.Nome, atleti.Cognome, esami.kihon_score, esami.kata_score, esami.kumite_score, esami.average, 
    esami.esito, sessions.commission_members 
    FROM esami, atleti, sessions 
    WHERE esami.id_studente = atleti.Id and esami.session_id = sessions.id and esami.session_id='$id';";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query Failed: " . mysqli_error($conn));
    }

    $sessioni = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $sessioni[] = $row;
        
    }
    echo $sessioni[0]['Nome'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dettagli esame</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include('templates/header.php'); ?>
    <section>
        <h1>Dettagli esame</h1>
        <?php echo $id; ?>
    </section>
    <?php include('templates/footer.php'); ?>
</body>
</html>