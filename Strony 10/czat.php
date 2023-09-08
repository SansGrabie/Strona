<?php

session_start();

if(!isset($_SESSION['zalogowany'])) 
{
    header('Location: main.php');
}

?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Czat</title>
</head>
<body>
<?php
include ('header.html');
?>
    <div id="content">
        <main id="login">
            <?php
                echo "<p>Witaj ".$_SESSION['user'].'! [<a href="logout.php">Wyloguj się</a>]</p>'
            ?>
        </main>
    </div>
<!--<script src="navbar.js"></script>-->
</body>
</html>