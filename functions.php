<?php

use LDAP\Result;

require_once("config.php");
$mainTitle = "Zadanie2";

function getAllLangueges($db)
{
    return $db->query("SELECT * FROM languages;")->fetch_all(MYSQLI_ASSOC);
}

function langueges2Option($db, $langs)
{
    foreach ($langs as $option)
        echo sprintf("<option value='%d'>%s</option>", $option["id"], ($option["code"] . " " . $option["name"]));
}

/**
 * Function gets all glossaries entries from databese and could filter just searched text.
 * @return: Associative array of glossaries exprestions from database.
 */
function readGlossary($db, $searchText)
{
    $noSearchQuery = "SELECT een.id AS eid,een.expression AS pojem_en,een.definition AS def_en, esk.id AS sid,esk.expression AS pojem_sk ,esk.definition AS def_sk
    FROM translations as t 
    LEFT JOIN expressions as een ON t.expression_en = een.id 
    LEFT JOIN expressions as esk ON t.expression_sk = esk.id";

    if (isset($searchText) && $searchText !== "") {
        $searchQuery = "SELECT een.id AS eid,een.expression AS pojem_en,een.definition AS def_en, esk.id AS sid,esk.expression AS pojem_sk ,esk.definition AS def_sk
        FROM translations as t 
        LEFT JOIN expressions as een ON t.expression_en = een.id 
        LEFT JOIN expressions as esk ON t.expression_sk = esk.id
        WHERE een.expression LIKE '%$searchText%' OR esk.expression LIKE '%$searchText%'  OR een.definition LIKE '%$searchText%' OR esk.definition LIKE '%$searchText%'";
        $result = $db->query($searchQuery);
    } else
        $result = $db->query($noSearchQuery);

    return $result->fetch_all(MYSQLI_ASSOC);
}

function readExpression($db, $id)
{
    $searchQuery = "SELECT een.id AS eid,een.expression AS pojem_en,een.definition AS def_en, esk.id AS sid,esk.expression AS pojem_sk ,esk.definition AS def_sk
    FROM translations as t 
    LEFT JOIN expressions as een ON t.expression_en = een.id 
    LEFT JOIN expressions as esk ON t.expression_sk = esk.id
    WHERE een.id=?
    ";

    $stmt = $db->prepare($searchQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function updateExpression($db, $data, $id)
{
    try {
        $stmt = $db->prepare("SELECT esk.id AS sid
            FROM translations as t 
            LEFT JOIN expressions as een ON t.expression_en = een.id 
            LEFT JOIN expressions as esk ON t.expression_sk = esk.id
            WHERE een.id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_row();
        $skid = $result[0];

        $stmt = $db->prepare("UPDATE expressions
            SET expression = '$data[0]', definition = '$data[1]'
            WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();


        $stmt = $db->prepare("UPDATE expressions
            SET expression = '$data[2]', definition = '$data[3]'
            WHERE id=?");
        $stmt->bind_param("i", $skid);
        $stmt->execute();

    } catch (Exception $e) {
        errorFormated($e);
    }
}

function errorFormated($e)
{
    echo "<pre>";
    echo 'Message: ' . $e->getMessage();
    echo "</pre>";
    echo "<pre>";
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
    $jazyky = readLanguages($db);
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

function createAllFromCSV($db, $file)
{
    while (($data = fgetcsv($file, 1000, ";")) !== FALSE) {
        try {
            createGlossaryRecord($db, $data);
        } catch (Exception $e) {
            errorFormated($e);
            continue;
        }
    }
}


function readLanguages($db)
{
    $result = $db->query(("SELECT * FROM languages"));
    return $result->fetch_all(MYSQLI_ASSOC);
}
