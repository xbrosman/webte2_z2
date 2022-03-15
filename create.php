<?php
require_once("config.php");
require_once("functions.php");


echo "Create<br>";
$db = new mysqli();
$db->connect($hostname, $username, $pass, $database);

$data[0] = $_POST["expression_en"];
$data[1] = $_POST["def_en"];
$data[2] = $_POST["expression_sk"];
$data[3] = $_POST["def_sk"];

foreach ($data as $exp)
{
    if (!isset($exp) || empty($exp))
    {
        die("All fields are required! Record was not created!");
    }

}

createGlossaryRecord($db, $data);

$db->close();
