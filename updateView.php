<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once("config.php");
require_once("functions.php");

$db = new mysqli();
$db->connect($hostname, $username, $pass, $database);
$enid= $_GET["enid"];
$skid= $_GET["skid"];
$result = readExpression($db, $enid);
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
            <h2><?php echo "update id: ".$enid; ?></h2>

            <form action="update.php" method="post">
                <div class="add container">
                    <div class="formitem">
                        <input type="hidden" name="enid" id="enid" value="<?php echo $enid ?>">
                        <label for="">Pridaj pojem en:</label>
                        <input type="text" name="expression_en" id="exp_en" value="<?php echo $result[0]["pojem_en"];?>" required>

                        <label for="def_en">Pridaj Definíciu en:</label>

                        <textarea name="def_en" id="def_en" rows = "6" required><?php echo $result[0]["def_en"];?></textarea> 
                        
                    </div>

                    <div class="formitem">
                        <input type="hidden" name="skid" id="skid" value="<?php echo $skid ?>">
                        <label for="exp_sk">Pridaj preklad pojem:</label>
                        <input type="text" name="expression_sk" id="exp_sk" value="<?php echo $result[0]["pojem_sk"];?>" required>

                        <label for="def_sk">Pridaj preklad Definíciu:</label>

                        <textarea name="def_sk" id="def_sk" rows = "6" required><?php echo $result[0]["def_sk"];?></textarea>                      
                    </div>

                    <div class="formitem"></div>

                    <div class="formitem fullSizeField">
                        <input type="submit" value="Upraviť pojem">
                    </div>
                </div>
            </form>


            
            
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