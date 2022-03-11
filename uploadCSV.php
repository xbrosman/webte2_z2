<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once("config.php");
require_once("functions.php");

$db = new mysqli();
$db->connect($hostname, $username, $pass, $database);


$result = $db->query(("SELECT * FROM languages"));
$jazyky = $result->fetch_all(MYSQLI_ASSOC);

$id_sk = $jazyky[0]['id'];
$id_en = $jazyky[1]['id'];

function errorNotCreated($e)
{
    echo "<pre>";
    echo 'Message: ' . $e->getMessage();
    echo "</pre>";
    echo "<pre>";
    echo '.....entry not created';
    echo "</pre>";
}

/**
 * Data format:
 *  $data[0] => pojem_en
 *  $data[1] => def_en 
 *  $data[2] => pojem_sk 
 *  $data[3] => def_sk
 */
function createGlossaryRecord($db, $data)
{
    $result = $db->query(("SELECT * FROM languages"));
    $jazyky = $result->fetch_all(MYSQLI_ASSOC);
    $id_sk = $jazyky[0]['id'];
    $id_en = $jazyky[1]['id'];

    $stmt = $db->prepare("INSERT INTO expressions(expression, definition, lang_id) VALUES (?,?,?)");

    $pojem_en = $data[0];
    $def_en = $data[1];

    $stmt->bind_param("ssi", $pojem_en, $def_en, $id_en);
    $stmt->execute();

    $preklad_en = $db->insert_id;
    $stmt = $db->prepare("INSERT INTO expressions(expression, definition, lang_id) VALUES (?,?,?)");

    $pojem_sk = $data[2];
    $def_sk = $data[3];
    $stmt->bind_param("ssi", $pojem_sk, $def_sk, $id_sk);
    $stmt->execute();

    $preklad_sk = $db->insert_id;

    $stmt = $db->prepare("INSERT INTO translations(expression_sk, expression_en) VALUES (?,?)");
    $stmt->bind_param("ii", $preklad_sk, $preklad_en);
    $stmt->execute();

    $db->commit();
}

function addNewEntries($db, $file)
{
    while (($data = fgetcsv($file, 1000, ";")) !== FALSE) {
        try {
            createGlossaryRecord($db, $data);
        } catch (Exception $e) {
            errorNotCreated($e);
            continue;
        }
    }
}

if (($handle = fopen($_FILES['csv_data']['tmp_name'], "r")) != FALSE) {
    addNewEntries($db, $handle);
    fclose($handle);
}

$db->close();
