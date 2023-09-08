<?php 
session_start();    // Niejawne połączenie plików (użytkownik nie może tego zobaczyć)

if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true)) 
{
    header('Location: czat.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/logowanie.css">
    <title>Logowanie</title>
</head>
<body>
<?php
include ('header.html');
?>
    <div id="content">
        <div id="main-content">
            <main id="login">
                <h3>Podaj dane żeby zalogować</h3>
                <form action="login.php" method="post">
                    <h4>Login:</h4> 
                    <input type="text" name="login">
                    <h4>Hasło:</h4> 
                    <input type="password" name="password">
                    <?php
                        if(isset($_SESSION['error'])) 
                        {  
                            echo '<div class="error">'.$_SESSION['error'].'</div>';  
                            unset($_SESSION['error']); 
                        } 
                    ?><br>
                    <input type="submit" value="Zaloguj"/>
                </form>
                <p>Nie posiadasz konta? <a href="rejestracja.php" id="link">Zarejestruj się</a></p>
            </main>
        </div>
    </div>
</body>
</html>