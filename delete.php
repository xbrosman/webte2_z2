<?php
require_once("config.php");


echo "Delete id: ".$_GET["id"];
$eid = $_GET["eid"];
$sid = $_GET["sid"];

$db = new mysqli();
$db->connect($hostname, $username, $pass, $database);

$stmt = $db->prepare("DELETE FROM `expressions` as e  WHERE e.id = ?");
$stmt->bind_param("i",$eid);
$stmt->execute();

$stmt = $db->prepare("DELETE FROM `expressions` as e  WHERE e.id = ?");
$stmt->bind_param("i",$sid);
$stmt->execute();

$db->commit();
$db->close();
