<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once("config.php");
require_once("functions.php");

$db = new mysqli();
$db->connect($hostname, $username, $pass, $database);

if (($handle = fopen($_FILES['csv_data']['tmp_name'], "r")) != FALSE) {
    createAllFromCSV($db, $handle);
    fclose($handle);
}

$db->close();
