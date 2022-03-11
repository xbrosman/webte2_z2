<?php
require_once("config.php");


echo "Delete id: ".$_GET["id"];
$id = $_GET["id"];
$db = new mysqli();
$db->connect($hostname, $username, $pass, $database);
$db->query("DELETE FROM expressions WHERE expressions.id = $id");


$db->close();
