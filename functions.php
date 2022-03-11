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
    $noSearchQuery = "SELECT een.id AS id,een.expression AS pojem_en,een.definition AS def_en, esk.id,esk.expression AS pojem_sk ,esk.definition AS def_sk
                FROM translations as t 
                LEFT JOIN expressions as een ON t.expression_en = een.id 
                LEFT JOIN expressions as esk ON t.expression_sk = esk.id;";

    if (isset($searchText) && $searchText !== "") {
        $searchQuery = "SELECT een.id,een.expression AS pojem_en,een.definition AS def_en, esk.id,esk.expression AS pojem_sk ,esk.definition AS def_sk
                    FROM translations as t 
                    LEFT JOIN expressions as een ON t.expression_en = een.id 
                    LEFT JOIN expressions as esk ON t.expression_sk = esk.id
                    WHERE een.expression LIKE '%$searchText%' OR esk.expression LIKE '%$searchText%'";
        $result = $db->query($searchQuery);
    } else
        $result = $db->query($noSearchQuery);

    return $result->fetch_all(MYSQLI_ASSOC);
}

