<?php
require('config.php');
    try {
        $sql = "SELECT comment FROM data_form";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute();

        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retournez les coordonnées des marqueurs sous forme de tableau JSON
        echo json_encode($comments);
    } catch (PDOException $e) {
        // Gérez les erreurs de base de données ici
        echo json_encode(['error' => 'Erreur lors de la récupération des marqueurs : ' . $e->getMessage()]);
    }

?>