<?php

session_start(); // Assurez-vous que session_start() est appelé

require_once("config.php");

$token = $_SESSION["token"];
$identifiant = $_SESSION["identifiant"];

// Ajoutez des messages de débogage
/* echo "Token saisi: $token<br>";
echo "Identifiant saisi: $identifiant<br>"; */

$stmt = $pdo->prepare("SELECT token FROM intern_account WHERE identifiant = :identifiant");
$stmt->execute(['identifiant' => $identifiant]);
$resultat = $stmt->fetch(PDO::FETCH_ASSOC);

// Ajoutez des messages de débogage
/* echo "Token en base de données: " . $resultat['token'] . "<br>"; */

/*  if ($resultat && isset($resultat['token']) && $token == $resultat['token']) {
    setcookie("validate", true);
    echo "Validation réussie";
} else {
    setcookie("validate", false);
    echo "Échec de la validation";
}   */

?>
