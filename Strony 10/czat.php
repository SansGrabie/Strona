<?php

session_start();

if(!isset($_SESSION['zalogowany'])) 
{
    header('Location: main.html');
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
    <p>ELO</p>
<?php
include ('nav.html');
?>
    <div class="content">
        <main id="index">
            <?php
                echo "<p>Witaj ".$_SESSION['user'].'! [<a href="logout.php">Wyloguj się</a>]</p>'
            ?>
        </main>
    </div>
<!--<script src="navbar.js"></script>-->
</body>
</html>
