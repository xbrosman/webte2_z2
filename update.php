<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once("config.php");
require_once("functions.php");


echo "Update<br>";
$db = new mysqli();
$db->connect($hostname, $username, $pass, $database);

$enid =  $_POST["enid"];
$skid =  $_POST["skid"];

echo $enid."<br>";
echo $skid."<br>";

$data[0] = $_POST["expression_en"];
$data[1] = $_POST["def_en"];
$data[2] = $_POST["expression_sk"];
$data[3] = $_POST["def_sk"];

foreach ($data as $exp)
{
    echo $exp. "<br>";
    // if (!isset($exp) || empty($exp))
    // {
    //     die("All fields are required! Record was not created!");
    // }

}

updateExpression($db,$data, $enid);


$db->close();
