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

            <h3>Pridanie nového pojmu:</h3>
            <form action="create.php" method="post">
                <div class="add container">
                    <div class="formitem">
                        <label for="">Pridaj pojem en:</label>
                        <input type="text" name="expression_en" id="exp_en" required>

                        <label for="def_en">Pridaj Definíciu en:</label>
                        <input type="text" name="def_en" id="def_en" required>

                    </div>

                    <div class="formitem">
                        <label for="exp_sk">Pridaj preklad pojem:</label>
                        <input type="text" name="expression_sk" id="exp_sk" required>

                        <label for="def_sk">Pridaj preklad Definíciu:</label>
                        <input type="text" name="def_sk" id="def_sk" required>
                    </div>

                    <div class="formitem"></div>

                    <div class="formitem fullSizeField">
                        <input type="submit" value="Pridaj nový pojem">
                    </div>
                </div>
            </form>

            <h3>Pridanie CSV súboru:</h3>
            <form action="uploadCSV.php" method="post" enctype="multipart/form-data">
                <div class="container">
                    <input type="file" name="csv_data" id="csv_data">
                    <input type="submit" value="Nahrať" name="submit">
                </div>
            </form>
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
                    <td><a href='delete.php?eid=%d&sid=%d'>delete</a><br><br><a href='updateView.php?enid=%d&skid=%d'>update</a></td>
                    </tr>",
                            $row["pojem_en"],
                            $row["def_en"],
                            $row["pojem_sk"],
                            $row["def_sk"],
                            $eid,
                            $sid,
                            $eid,
                            $sid
                        );
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
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