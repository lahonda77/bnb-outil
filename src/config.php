<?php
//CORRECT CONFIG FOR LOCAL LAPTOP

$moteur = "mysql";
$hote = "localhost:3306";
$port = 3306;

$nomBdd = "bnbxtech_form_db";

$nomUtilisateur = "root";

$motDePasse = "";

$pdo = new PDO("$moteur:host=$hote:$port;dbname=$nomBdd", $nomUtilisateur, $motDePasse);






// Je vous déconseille mysqli
/* echo "ok"; */

// Etape 1 : je prépare la requête
//$requete = $pdo->prepare("SELECT * FROM actor");
// Etape 2 : j'exécute la requête
//$requete->execute();
// Etape 3 : je récupère les résultats
// FETCH_ASSOC : De manière associative (noms de colonnes)
// FETCH_NUM : Avec les numéros de colonnes (0, 1, 2, 3, ...)
// FETCH_BOTH : A la fois FETCH_ASSOC et FETCH_NUM (par défaut)
// FETCH_OBJ : Renvoyer des objets
// FETCH_CLASS : Renvoyer des instances de la classe spécifiée

//$resultats = $requete->fetchAll(PDO::FETCH_ASSOC);
// On peut également utiliser fetch(), et dans ce cas, on ne récupère
// qu'une seule ligne.

/* foreach($resultats as $resultat)
{
    echo $resultat["first_name"] . " " . $resultat["last_name"] . "\n";
} 


<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Ces six informations sont nécessaires pour vous connecter à une BDD :
// Type de moteur de BDD : mysql
$moteur = "mysql";
// Hôte : localhost
$hote = "localhost:3306";
// Port : 3306 (par défaut pour MySQL, avec MAMP macOS c'est 8889)
$port = 3306;
// Nom de la BDD (facultatif) : sakila
$nomBdd = "test_stations_suggest";
// Nom d'utilisateur : root
$nomUtilisateur = "root";
// Mot de passe : 
$motDePasse = "";

$pdo = new PDO("$moteur:host=$hote:$port;dbname=$nomBdd", $nomUtilisateur, $motDePasse); */