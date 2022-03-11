<?php
require_once("config.php");


echo "Create";
$db = new mysqli();
$db->connect($hostname, $username, $pass, $database);


$db->close();
