<?php 
require_once '../db_config.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $res["athlete"] = $dbh->searchAthleteFromId($id);
    $res["error"] = false;

    header("Content-Type: application/json");
    echo(json_encode($res));
} else {
    $res["error"] = true;
    header("Content-Type: application/json");
    echo(json_encode($res));
}
