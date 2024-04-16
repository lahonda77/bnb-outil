<?php
require('config.php');

// Effectuez la requête SQL pour récupérer les marqueurs depuis la base de données
/* header('Content-Type: application/json'); */
try {
    $sql = "SELECT latitude, longitude, comment FROM data_form";
    $stmt = $pdo -> prepare($sql);
    $stmt -> execute();

    $markers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retournez les coordonnées des marqueurs sous forme de tableau JSON
    echo json_encode($markers);
} catch (PDOException $e) {
    // Gérez les erreurs de base de données ici
    echo json_encode(['error' => 'Erreur lors de la récupération des marqueurs : ' . $e->getMessage()]);
}
?>