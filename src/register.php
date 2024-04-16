<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require('config.php');

$methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");

if($methode == "POST")
{
    if(isset($_POST['submit'])){
        var_dump($methode);
        $first_name = filter_input(INPUT_POST, "first_name");
        $last_name = filter_input(INPUT_POST, "last_name");
        $identifiant = filter_input(INPUT_POST, "identifiant");
        $bnb_mail = filter_input(INPUT_POST, "bnb_mail");
        $password = filter_input(INPUT_POST, "password");
        $confirm_password = filter_input(INPUT_POST, "confirm_password");

        if ($password == $confirm_password) {
            try {
                $requete = $pdo->prepare("INSERT INTO intern_account (first_name, last_name, identifiant, bnb_mail, password) VALUES(:first_name, :last_name, :identifiant, :bnb_mail, :password)");

                $requete->execute([
                    ":first_name" => $first_name,
                    ":last_name" => $last_name,
                    ":identifiant" => $identifiant,
                    ":bnb_mail" => $bnb_mail,
                    ":password" => password_hash($password, PASSWORD_DEFAULT),
                ]);

                if ($requete->rowCount() > 0) {
                    header("Location: login.php");
                    exit();
                } else {
                    echo "Erreur lors de l'insertion dans la base de données.";
                }
            } catch (PDOException $e) {
                echo "Erreur d'insertion : " . $e->getMessage();
            }
        }

        /* if($password == $confirm_password){
            
            $requete = $pdo->prepare("INSERT INTO users_account (first_name, last_name, identifiant, bnb_mail, password  ) VALUES(:first_name, :last_name, :identifiant, :bnb_mail, :password)");

/*             if (!$requete) {
                echo "\nPDO::errorInfo():\n";
                print_r($pdo->      errorInfo());
                exit();
            }

            if ($requete->rowCount() > 0) {
                header("Location: login.php");
                exit();
            } else {
                echo "Erreur lors de l'insertion dans la base de données.";
            }
 
            $requete->execute([
                ":first_name" => $first_name,
                ":last_name" => $last_name,
                ":identifiant" => $identifiant,
                ":bnb_mail" => $bnb_mail,
                ":password" => password_hash($password, PASSWORD_DEFAULT),
            ]); 

            header("Location: login.php");
            exit();
        } */
    } 
} 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/Group 1.png" />
    <link rel="stylesheet" href="register.css">
    <title>Inscription</title>
</head>
<body>
    <div class="container">

        <div class="inline-block">
            <a href="accueil.html"><img src="images/back-arrow.png" alt=""></a> 
            <a href="accueil.html" class="back-text">Retour à la page principale </a><!-- <p class="back-text"></p> -->
        </div>

<!--             <h1 class="titre-connexion">Inscription</h1> -->

        <form class="form" action="" method="POST">
            <h3>Ajoutez un compte</h3>            
            <div class="input-container">               
                <div class="nom">
                    <label>Nom<br>
                        <input type="text" class="box-input" name="last_name" value="" placeholder="Nom" required>
                    </label>
                </div>
                <div class="prenom">
                    <label>Prénom<br>
                        <input type="text" class="box-input" name="first_name" value="" placeholder="Prénom" required>
                    </label>
                </div>
                <div class="identifiant">
                    <label>Identifiant <br>
                        <input type="text" class="box-input" name="identifiant" value="" placeholder="Identifiant" required>
                    </label>
                </div>
                <div class="bnb_mail">
                    <label>Email BNBxTECH <br>
                        <input type="email" class="box-input" name="bnb_mail" value="" placeholder="Email BNB" required>
                    </label>
                </div>
                <div class="mdp">
                    <label>Mot de passe <br>
                        <input type="password" class="box-input" name="password" value="" placeholder="Choisissez un mot de passe" required>
                    </label>    
                </div>
                <div class="mdp">
                    <label>Confirmation du mot de passe<br>
                        <input type="password" class="box-input" name="confirm_password" value="" placeholder="Confirmez le mot de passe" required>
                    </label>    
                </div>
            </div>

            
            <input type="submit" name="submit" value="Enregistrer" class="inscrire" />
            <p class="box-register"><a href="login.php">J'ai déjà un compte.</a></p>

            <div class="pp">
                <img src="./images/logo bnbxtech.svg" alt="" class="logo">
            </div>
        </form>
        
    </div>
<!--     <div class="footer">
        <div class="texte">
            <p>Confidentialité&emsp;&emsp;Conditions générales&emsp;&emsp;Infos sur l'entreprise&emsp;&emsp;Cookies&emsp;&emsp;Mentions légales&emsp;&emsp;© 2023 Biltmore, Inc</p>
        </div>
        <hr class="hr1">
    </div> -->
</body>
</html>