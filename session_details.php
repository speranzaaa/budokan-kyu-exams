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
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dettagli esame</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php include('templates/header.php'); ?>
    <section id="print">
        <h1>Dettagli esame</h1>
        <table id="table">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Punteggio Kihon</th>
                <th>Punteggio Kata</th>
                <th>Punteggio Kumite</th>
                <th>Media</th>
                <th>Esito</th>
                <th>Membri della Commissione</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sessioni as $key ): ?>
            <tr>
                <td><?php echo $key['Nome']; ?></td>
                <td><?php echo $key['Cognome']; ?></td>
                <td><?php echo $key['kihon_score']; ?></td>
                <td><?php echo $key['kata_score']; ?></td>
                <td><?php echo $key['kumite_score']; ?></td>
                <td><?php echo $key['average']; ?></td>
                <td><?php echo $key['esito']; ?></td>
                <td><?php echo $key['commission_members']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button id="btn" type="button">Stampa</button>
    </section>
    <?php include('templates/footer.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
    <script src="js/printPdf.js"></script>
</body>
</html>