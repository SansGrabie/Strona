<?php

session_start();
	
	if (isset($_POST['email']))
	{
		//Udana walidacja
		$wszystko_ok=true;
		
		$name = $_POST['name'];     // Zmienna $name jest tym samym co name="name" w formularzu (metodzie $_POST)
		
		//Sprawdzenie długości nazwy użytkownika
		if ((strlen($name)<3) || (strlen($name)>20))    // strlen = string lenght
		{
			$wszystko_ok=false;
			$_SESSION['e_name']= 'Nazwa użytkownika musi posiadać od 3 do 20 znaków!';
		}

        //Sprawdzenie składni nazwy użytkownika
        if (ctype_alnum($name) == false) 
        {
            $wszystko_ok = false;
            $_SESSION['e_name'] = 'Nazwa użytkownika może składać się tylko z liter i cyfr (bez polskich znaków)';
        }

        // Sprawdź poprawność adresu email
        $email = $_POST['email'];
        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

        if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false ) || ($emailB != $email)) 
        {
            $wszystko_ok = false;
            $_SESSION['e_email'] = 'Podaj poprawny adres e-mail';
        }

        // Sprawdź poprawność hasła
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];


        if ((strlen($password1) < 6) || (strlen($password1) > 20)) 
        {    
            $wszystko_ok = false;
            $_SESSION['e_password'] = 'Hasło musi posiadać od 6 do 20 znaków';
        }

        if ($password1 != $password2) 
        {
            $wszystko_ok = false;
            $_SESSION['e_password'] = 'Podane hasła nie są identyczne';
        }

        $password_hash = password_hash($password1, PASSWORD_DEFAULT);

        // Czy zaakceptowano regulamin
        if (!isset($_POST['regulamin']))
        {
            $wszystko_ok = false;
            $_SESSION['e_regulamin'] = 'Potwierdź akceptację regulaminu';
        }

        // Sprawdzenie czy użytkownik nie jest botem
		$secret_key = "6Lez2eImAAAAAAJ8rqOok0TyuSf_R9DPanvmosew";
		
		$check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);
		
		$answer = json_decode($check);
		
		if ($answer->success==false)
		{
			$wszystko_ok=false;
			$_SESSION['e_bot']="Potwierdź, że nie jesteś botem";
		}	

        require_once "mysqli_connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);

        try
        {
            $polaczenie = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
            if ($polaczenie->connect_errno!=0)
        {
            throw new Exception(mysqli_connect_errno());
        }
        else
        {
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");
            
            if (!$rezultat) throw new Exception($polaczenie->error);

            $ile_takich_maili = $rezultat->num_rows;
            if ($ile_takich_maili>0) // Ktoś już posiada taki e-mail
            {
                $wszystko_ok = false;
                $_SESSION['e_email'] = 'Istnieje już konto o takim adresie e-mail';
            }

            //Czy nazwa użytkownika jest zajęta
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$name'");
				
			if (!$rezultat) throw new Exception($polaczenie->error);
				
			$ile_takich_uzytkownikow = $rezultat->num_rows;
			if($ile_takich_uzytkownikow>0)
			{
				$wszystko_ok=false;
				$_SESSION['e_name']="Istnieje już konto o takiej nazwie! Wybierz inną.";
			}

            if ($wszystko_ok == true)
            {
                if ($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$name', '$password_hash', '$email', 100, 100, 100, 14)"))
                {
                    $_SESSION['udanarejestracja']=true;
                }
                else
                {

                }
            }

            $polaczenie->close();
        }
        } 
        catch(Exception $e)
        {
            echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
        }
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
    <link rel="stylesheet" href="css/rejestracja.css">
    <title>Załóż konto</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body onload="closeNav()">
<?php
include ('header.html');
?>
    <div id="content">
        <main id="rejestracja">
            <h3>Podaj dane żeby zarejestrować</h3>
            <form method="post">

                <h4>Nazwa Użytkownika:</h4>
                <input type="text" name="name">
                <!-- Wyświetla komunikat o błędzie -->
                <?php 
                    if(isset($_SESSION['e_name'])) 
                    {  
                        echo '<div class="error">'.$_SESSION['e_name'].'</div>';  
                        unset($_SESSION['e_name']); 
                    } 
                ?>

                <h4>E-mail:</h4>
                <input type="text" name="email">
                <?php 
                    if(isset($_SESSION['e_email'])) 
                    {  
                        echo '<div class="error">'.$_SESSION['e_email'].'</div>';  
                        unset($_SESSION['e_email']); 
                    } 
                ?>

                <h4>Hasło:</h4>
                <input type="password" name="password1">
                <?php 
                    if(isset($_SESSION['e_password'])) 
                    {  
                        echo '<div class="error">'.$_SESSION['e_password'].'</div>';  
                        unset($_SESSION['e_password']); 
                    } 
                ?>

                <h4>Powtórz Hasło:</h4>
                <input type="password" name="password2"><br>

                <label>
                    <input type="checkbox" name="regulamin"/> Akceptuje Regulamin
                </label>
                <?php 
                    if (isset($_SESSION['e_regulamin'])) 
                    {  
                        echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';  
                        unset($_SESSION['e_regulamin']); 
                    } 
                ?><br>

                <div class="g-recaptcha" data-sitekey="6Lez2eImAAAAADLXQ1uTQ7abyxuUBg2mFTFQpvzK"></div>
                <?php
                    if (isset($_SESSION['e_bot']))
                    {
                        echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
                        unset($_SESSION['e_bot']);
                    }
		        ?><br>
                
                <input type="submit" value="Utwórz Konto"/>
            </form>
        </main>
    </div>
</body>
</html>