<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "budokan";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

class DatabaseManager {

    private $db;
    public function __construct($servername, $username, $password, $dbname, $port) {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if($this->db->connect_error) {
            die("Connessione fallita al db");
        }
    }

    public function __destruct()
    {
        $this->db->close();
    }

    public function searchAthleteFromId($id) {
        $stmt = $this->db->prepare("SELECT * from atleti where id=?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC)[0];
    }



}