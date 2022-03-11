<?php


function getAllLangueges($db)
{
    return $db->query("SELECT * FROM languages;")->fetch_all(MYSQLI_ASSOC);
}

function langueges2Option($db)
{
    foreach (getAllLangueges($db) as $option)
        echo sprintf("<option value='%d'>%s</option>", $option["id"], ($option["code"] . " " . $option["name"]));
}

/**
 * Function gets all glossaries entries from databese and could filter just searched text.
 * @return: Associative array of glossaries exprestions from database.
 */
function getGlossary($db, $searchText)
{
    $noSearchQuery = "SELECT een.id AS eid,een.expression AS pojem_en,een.definition AS def_en, esk.id AS sid,esk.expression AS pojem_sk ,esk.definition AS def_sk
    FROM translations as t 
    LEFT JOIN expressions as een ON t.expression_en = een.id 
    LEFT JOIN expressions as esk ON t.expression_sk = esk.id\n";

    if (isset($searchText) && $searchText !== "") {
        $searchQuery = $noSearchQuery+"WHERE een.expression LIKE '%$searchText%' OR esk.expression LIKE '%$searchText%'  OR een.definition LIKE '%$searchText%' OR esk.definition LIKE '%$searchText%'";
        $result = $db->query($searchQuery);
    } else
        $result = $db->query($noSearchQuery);

    return $result->fetch_all(MYSQLI_ASSOC);
}

function errorFormated($e)
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
function createGlossaryRecord($db, $data,$jazyky)
{
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
    $result = $db->query(("SELECT * FROM languages"));
    $jazyky = $result->fetch_all(MYSQLI_ASSOC);   
    while (($data = fgetcsv($file, 1000, ";")) !== FALSE) {
        try {
            createGlossaryRecord($db, $data,$jazyky);
        } catch (Exception $e) {
            errorFormated($e);
            continue;
        }
    }
}
