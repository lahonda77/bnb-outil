<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('config.php');

$methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
$error = null;
?>

<body>
<form action="" class="form" method="POST">
    <input class="saisir" placeholder="Entrez votre identifiant" type="text" name="login" id="login">
    <input placeholder="Entrez votre mot de passe" type="password" name="password" class="password">
    <input type="submit" name="submit" value="Connexion" class="connexion" />
</form>

<?php
    var_dump($methode);
    if ($methode == "POST") {
        $login = filter_input(INPUT_POST, "login");
        $password = filter_input(INPUT_POST, "password");
        $_SESSION["identifiant"] = $login;
    
        $requete = $pdo->prepare("SELECT * FROM intern_account WHERE identifiant = :login");
        $requete->execute([":login" => $login]);
    
        $user = $requete->fetch(PDO::FETCH_ASSOC);
    
        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["loggedin"] = true;
            $error = null;               
            $token = uniqid('', true);
            $_SESSION['token'] = $token;
            $requete1 = $pdo->prepare("UPDATE intern_account SET token = :token WHERE identifiant = :login");
            $requete1->execute(["token" => $token, ":login" => $login]);
            header("Location: admin_space.php");
        } else {
            echo '<p class="message-erreur">Identifiants invalides</p>';
        }
    }
?>
</body>