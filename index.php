<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once("config.php");
require_once("functions.php");

$db = new mysqli();
$db->connect($hostname, $username, $pass, $database);


$searchText = "";
if (isset($_GET["search"]))
    $searchText = $_GET["search"];
else $searchText = "";


// searchLang=1&isFullText=on&isTranslated=on&search=

if (isset($_GET["searchLang"]))
    $searchLang = $_GET["searchLang"];
else
    $searchLang = 1;

if (isset($_GET["isFullText"]))
    $isFullText = $_GET["isFullText"];
else
    $isFullText = null;

if (isset($_GET["isTranslated"]))
    $isTranslated = $_GET["isTranslated"];
else
    $isTranslated = null;


?>

<!DOCTYPE html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?php echo $mainTitle; ?></title>
</head>

<body>
    <div class="wrapper">

        <header class="header">
            <h1 class="jumbatron"><?php echo $mainTitle; ?></h1>
            <nav>
                <ul>
                    <li>
                        <a href="/zadanie2/">Home</a>
                    </li>
                    <li>
                        <a href="/zadanie2/admin.php">Register as Admin</a>
                    </li>
                </ul>
            </nav>
        </header>

        <section class="core">
            <h2>Používateľ</h2>

            <form action="index.php" method="get">
                <div class="search container">
                    <div class="formitem">
                        <select name="searchLang" id="searchLang">
                            <?php languages2Option($db, getAllLangueges($db), $searchLang); ?>
                        </select>
                    </div>
                    <div class="formitem">
                        <input type="checkbox" name="isFullText" id="isFullText" <?php echo ($isFullText != null) ?  "checked" :  "";  ?>> <label for="">FullTextové hľadanie</label>
                        <br>
                        <br>
                        <input type="checkbox" name="isTranslated" id="isTranslated" <?php echo ($isTranslated != null) ?  "checked" :  ""; ?>> <label for="">Zobraziť preklad</label>
                    </div>

                    <div class="formitem">
                        <input type="text" name="search" id="search" value="<?php echo $searchText; ?>">
                    </div>

                    <div class="formitem">
                        <input type="submit" value="Hľadaj">
                    </div>
                </div>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>Pojem</th>
                        <th>Definicia</th>
                        <?php if ($isTranslated != null)
                            echo  "<th>Pojem (preklad)</th>
                        <th>Definicia (preklad)</th>";
                        ?>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $glossaryEntries = readGlossaryParameters($db, $searchText, $searchLang, $isTranslated, $isFullText);
                    if ($isTranslated == null)
                            foreach ($glossaryEntries as $row) {
                                echo sprintf(
                                    "<tr><td><p>%s</p></td><td><p>%s</p></td></tr>",
                                    $row["pojem"],
                                    $row["def"],
                                );
                            }                           
                    else
                        foreach ($glossaryEntries as $row) {
                            echo sprintf(
                                "<tr>
                            <td><p>%s</p></td>
                            <td><p>%s</p></td>
                            <td><p>%s</p></td>
                            <td><p>%s</p></td>
                            </tr>",
                                $row["pojem"],
                                $row["def"],
                                $row["pojem_translated"],
                                $row["def_translated"]
                            );
                        }

                    ?>
                </tbody>
            </table>
        </section>
        <footer class="footer">
            Author: Filip Brosman
        </footer>
</body>

</html>

<!-- SELECT een.id,een.expression,een.definition, esk.id,esk.expression ,esk.definition 
    FROM translations as t 
    LEFT JOIN expressions as een ON t.expression_en = een.id 
    LEFT JOIN expressions as esk ON t.expression_sk = esk.id; -->

<?php $db->close(); ?>