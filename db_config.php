<?php
session_start();
require 'php/db.php';
$dbh = new DatabaseManager("localhost", "root", "", "budokan", 3306);
