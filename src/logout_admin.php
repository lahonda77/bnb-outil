

<?php
require('config.php');
session_start();

$login = $_SESSION["identifiant"];
$requete = $pdo->prepare("UPDATE users_account SET token = :token WHERE identifiant = :login");
$requete->execute([":token" => "", ":login" => $login]);

//On supprime tout le contenu de la session
session_destroy();

//On redirige la personne vers le login
header('Location: login.php');

?>