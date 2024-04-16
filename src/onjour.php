<?php
/* session_start(); */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('config.php');
/* require("token.php"); */

$methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
$error = null;

?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="images/Group 1.png" />
        <link href="login.css" rel="stylesheet" />
        <title>Connexion à L'espace administrateur</title>
    </head>

    <body>
                    
            <form action="" class="form" method="POST">
                <h3>Espace administrateur</h3>
                <div class="input-container">
                    
                    <div class="ident-container">
                        <label for="id">Identifiant :</label>
                        <input class="saisir" placeholder="Entrez votre identifiant" type="text" name="login" id="login">
                    </div>
                        <label for="id">Mot de passe :</label>
                        <input class="saisir" placeholder="Entrez votre mot de passe" type="password" name="password" class="password">
                    

                    <p class="mdp_oublie"><a href="#">Mot de passe oublié</a></p>
                </div>
                <input type="submit" name="submit" value="Connexion" class="connexion" />
            </form>

            <?php

                if ($methode == "POST") {
                    $login = filter_input(INPUT_POST, "login");
                    $password = filter_input(INPUT_POST, "password");
                    $_SESSION["identifiant"]=$login;

                    $requete = $pdo->prepare("SELECT * FROM intern_account WHERE identifiant = :identifiant");
                    $requete->execute([":identifiant" => $login]);

                    $user = $requete->fetch(PDO::FETCH_ASSOC);
                    /* var_dump($user);
                    var_dump($_SESSION); */
                    /* $_SESSION["id"]=$user['id'];
                    $_SESSION["name"]=$user['name']; */

                    /* var_dump(password_hash($password, PASSWORD_DEFAULT));
                    var_dump($user["password"]); */
                    if (password_verify($password, $user["password"])) {

                    $_SESSION["loggedin"] = true;
                    /* $error = null; */
                    $_SESSION["validate"] = true;
                    
                    $token = uniqid('', true);
                    $_SESSION['token']=$token;

                    $requete1 = $pdo->prepare("UPDATE intern_account SET token = :token WHERE identifiant = :identifiant");
                    $requete1->execute([":token" => $token, ":identifiant" => $login]);

                    header('Location: admin_space.php');

                    } else {
                        /* echo "Identifiants invalides. Réessayez."; */

                        echo '<p class="message-erreur">Identifiants invalides</p>';
                /*     $error = "Identifiants invalides";
                    var_dump($error); */
                    }
                }
            ?>

    </body>
    
</html>