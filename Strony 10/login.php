<?php

  session_start();

  if((!isset($_POST['login'])) || (!isset($_POST['password']))) 
  {
    header('Location: main.php');
    exit(); // po udanym wykonaniu tej części kodu reszta kodu staje się nieważna i jest nieodczytywana przez komputer
  }

  require_once "mysqli_connect.php";

  $polaczenie = @new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

  if ($polaczenie->connect_errno!=0) 
  {
    echo "Error: ".$polaczenie->connect_errno." Opis: ".$polaczenie->connect_error;
  } 
  else 
  {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $login = htmlentities($login, ENT_QUOTES, "UTF-8"); // html entities sprawia, że znaki specjalne np. <>! będą rozumiane przez komputer jako zwykłe znaki a nie jako części kodu
    $password = htmlentities($password, ENT_QUOTES, "UTF-8");

    $sql = "SELECT * FROM uzytkownicy WHERE user='$login' AND pass='$password'";

    if ($rezultat = @$polaczenie->query(
    sprintf("SELECT * FROM uzytkownicy WHERE user='%s'", // %s czyli %string to oznaczenie miejsca w którym będzie przebywała zmienna ( login i hasło) || sprintf wstawi nam zmienne do %s
    mysqli_real_escape_string($polaczenie, $login))))  // mysqli_real_escape_string to kod który zabezpiecza $polaczenie i $login
    {
      $ilu_userow = $rezultat->num_rows;
      if($ilu_userow>0) 
      {
        $wiersz = $rezultat->fetch_assoc(); // fetch_assoc poznaje wiersze w bazie danych nie jako numery lecz jako nazwy np. 1 = id, 2 = user itd.

        if (password_verify($password, $wiersz['pass']))  // W tabeli użytkownicy ( w bazie danych ) kolumna password ma nazwę pass
          {
          $_SESSION['zalogowany'] = true;
          $_SESSION['id'] = $wiersz['id'];
          $_SESSION['user'] = $wiersz['user'];

          unset($_SESSION['error']);
          $rezultat->close();
          header('Location: czat.php');
        }
        else 
        {
          $_SESSION['error'] = '<p><span>Nieprawidłowy login lub hasło!</span></p>';
          header('Location: logowanie.php');
        }
      } else {
        $_SESSION['error'] = '<p><span>Nieprawidłowy login lub hasło!</span></p>';
        header('Location: logowanie.php');
      }

    }

    $polaczenie->close(); // zamknięcie połączenia z bazą danych
  }

?>