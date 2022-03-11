<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once("config.php");
require_once("functions.php");

$db = new mysqli();
$db->connect($hostname, $username, $pass, $database);
$mainTitle = "Zadanie2 Admin";

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
            <h2>Admin</h2>

            <form action="create.php" method="get">
                <div class="add container">
                    <div class="formitem">
                        <label for="">Pridaj pojem:</label>
                        <input type="text" name="" id="">

                        <label for="">Pridaj Definíciu:</label>
                        <input type="text" name="" id="">

                        <label for="">Pridaj jazyk:</label>
                        <select id="lang" name="lang">
                            <?php
                            langueges2Option($db);
                            ?>
                        </select>
                    </div>

                    <div class="formitem">
                        <label for="">Pridaj preklad pojem:</label>
                        <input type="text" name="" id="">

                        <label for="">Pridaj preklad Definíciu:</label>
                        <input type="text" name="" id="">

                        <label for="">Pridaj jazyk:</label>
                        <select id="lang" name="lang">
                            <?php
                            langueges2Option($db);
                            ?>
                        </select>
                    </div>

                    <div class="formitem"></div>

                    <div class="formitem fullSizeField">
                        <input type="submit" value="Pridaj nový pojem">
                    </div>
                </div>
            </form>

            <form action="uploadCSV.php" method="post" enctype="multipart/form-data">
                <div class="uploadCSV container">
                    <input type="file" name="csv_data" id="csv_data">
                    <input type="submit" value="Submit" name="submit">
                </div>
            </form>


            <form action="index.php" method="get">
                <div class="search container">
                    <div class="formitem">
                        <input type="text" name="searchField" id="searchField">
                    </div>
                    <div class="formitem">
                        <input type="submit" value="Hľadaj">
                    </div>
            </form>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Pojem (en)</th>
                <th>Definicia (en)</th>
                <th>Pojem (sk)</th>
                <th>Definicia (sk)</th>
                <th>Oparation</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($_GET["search"]))
                $searchText = $_GET["search"];
            else $searchText = "";

            $glossaryEntries = readGlossary($db, $searchText);
            foreach ($glossaryEntries as $row) {
                $eid = $row["eid"];
                $sid = $row["sid"];
                echo sprintf(
                    "<tr>
                            <td><p>%s</p></td>
                            <td><p>%s</p></td>
                            <td><p>%s</p></td>
                            <td><p>%s</p></td>
                            <td><a href='delete.php?eid=%d&sid=%d'>delete</a><br><br><a href='update.php?id=%d'>update</a></td>
                            </tr>",
                    $row["pojem_en"],
                    $row["def_en"],
                    $row["pojem_sk"],
                    $row["def_sk"],
                    $eid,
                    $sid,
                    $eid
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