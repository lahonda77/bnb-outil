   <?php
/* session_start(); */

require('config.php');

/* require("map_script.php"); */

// Vérifier si l'utilisateur est validé
if ($_SESSION["validate"]) {
    // La page s'affiche normalement
} else {
    // Rediriger ou afficher un message d'erreur si l'utilisateur n'est pas validé
    header("Location: login.php");
    exit();
} 

/* try {
    $query = "SELECT users.name, locations.comment, locations.date_suggestion, locations.latitude, locations.longitude
              FROM users
              JOIN locations ON users.user_id = locations.user_id
              ORDER BY locations.date_suggestion DESC";
              
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur d'exécution de la requête : " . $e->getMessage();
}

echo '<script async src="map_script.js"></script>'; */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/Group 1.png" />
    <link href="admin_space.css" rel="stylesheet" />
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5N6NIdED2nEKA69TK8aLkpBdMANxHCzY&callback=initMap" async defer></script>
    <script  src="map_script.js" async></script>
    <title>Espace administrateur</title>
</head>

<body>
    <div class="hamburger-menu">
        <input id="menu__toggle" type="checkbox" />
        <label class="menu__btn" for="menu__toggle">
            <span></span>
        </label>

        <ul class="menu__box">
            <li><a class="menu__item" href="#">Villes partenaires</a></li>
            <li><a class="menu__item" href="#">Gestion des comptes</a></li>
            <li><a class="menu__item" id="menu__item" href="#">Mon compte</a></li>
            <li><a class="menu__item" id="menu__item2" href="logout_admin.php">Déconnexion <img src="images/se-deconnecter.png" alt=""></a></li>


            <p class="rights">©2024 BNBXTECH. Tous droits réservés.</p>
        </ul>

        
    </div>

    <header>
        <p class="session">Session : <?php echo $_SESSION["first_name"]; ?> </p>
        <img src="./images/logo bnbxtech.svg" alt="">
    </header>

    <div class="table-container">
        <div class="wrap-table">
            <div class="table">
                <div class="row-header">
                    <div class="cell-first">NOM</div>
                    <div class="cell">COMMENTAIRE</div>
                    <div class="cell">DATE</div>
                    <div class="cell">LIEU</div>
                </div>
                
                    <?php if (count($result) > 0) {
                        foreach ($result as $row) {
                            
                            $coordinates = (isset($row["latitude"]) && isset($row["longitude"])) ? $row["latitude"] . "," . $row["longitude"] : "Coordonnées non disponibles";
                            ?>
                            <div class="row">
                                <div class="cell" data-title="Full Name">
                                    <?php echo $row["name"]; ?>
                                </div>
                                <div class="cell" data-title="Comment">
                                    <?php echo $row["comment"]; ?>
                                </div>
                                <div class="cell" data-title="Date">
                                <?php echo date('d-m-Y', strtotime($row["date_suggestion"])); ?>
                                </div>
                                <div class="cell" data-title="Coordinates">
                                <a href="#" class="map-link" data-coordinates="<?php echo $coordinates; ?>"> Afficher sur la carte</a>
                                </div>
                            </div>
                        <?php       
                        }
                    } else {
                        echo "Aucun résultat trouvé.";
                    }
                    ?>
            </div>
        </div>
    </div>

    <div id="map-container"></div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var mapContainer = document.getElementById('map-container');

            function afficherCarte(coordinates) {
                // Créer une nouvelle carte Google Maps
                var map = new google.maps.Map(mapContainer, {
                    center: { lat: parseFloat(coordinates.split(',')[0]), lng: parseFloat(coordinates.split(',')[1]) },
                    zoom: 12
                });


                // Afficher le conteneur de la carte
                mapContainer.style.display = 'block';
                loadMarkersFromDatabase();
            }

            // Ajoutez un gestionnaire d'événement pour tous les liens avec la classe "map-link"
            var mapLinks = document.querySelectorAll('.map-link');
            mapLinks.forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();

                    var coordinates = link.getAttribute('data-coordinates');

                    // Afficher la carte dans le conteneur
                    afficherCarte(coordinates);
                    
                });
            });
        });
    </script>
</body>
</html>