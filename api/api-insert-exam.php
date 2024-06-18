<?php 

include('../php/db.php');
session_start();

$session_id = $_POST['session_id'];
$atleti_ids = $_POST['id'];

if (isset($session_id)) {

    foreach ($atleti_ids as $index => $id) {
        $status = $_POST['exam_status'][$index];

        if ($status !== 'Absent') {
            $kihon_score = intval($_POST['kihon_score'][$index]);
            $kata_score = intval($_POST['kata_score'][$index]);
            $kumite_score = intval($_POST['kumite_score'][$index]);
            
            $average_score = ($kihon_score + $kata_score + $kumite_score) / 3;            

            echo $id. ' '. $session_id . ' '.$kihon_score. ' ' .$kata_score.' '.$kumite_score.' '.$average_score.' '.$status;
            

            $query1 = "INSERT INTO `esami`(`id_studente`, `session_id`, `kihon_score`, `kata_score`, `kumite_score`, `average`, `esito`) 
            VALUES ('$id', '$session_id', '$kihon_score', '$kata_score', '$kumite_score', '$average_score', '$status')";
            
            mysqli_query($conn, $query1);

            //$dbh->addToExamTable($id, $session_id, $kihon_score, $kata_score, $kumite_score, $average_score, $status);

        } else {
            // Gestione dello stato 'Absent'
            $query2 = "INSERT INTO `esami`(`id_studente`, `session_id`, `kihon_score`, `kata_score`, `kumite_score`, `average`, `esito`) 
            VALUES ('$id', '$session_id', NULL, NULL, NULL, NULL, 'Absent')";
            
            mysqli_query($conn, $query2);
        }
    }
}

header('Location: ../create_session.php');