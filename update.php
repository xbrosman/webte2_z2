<?php
require_once("config.php");


echo "update id: ". $_GET["id"];
$db = new mysqli();
$db->connect($hostname, $username, $pass, $database);


$db->close();
