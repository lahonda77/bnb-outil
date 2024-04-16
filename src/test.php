<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require('config.php');
?>

<?php
try {
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

/* echo '<script async src="map_script.js"></script>'; */
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/Group 1.png" />
    <link rel="stylesheet" href="test.css">
   <!--  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDslHhaYuaaxzUKiMyTjNeP1HdfHaq1wHc&libraries=geometry&callback=initMap" defer></script> -->

    <title>TEST</title>
</head>
<body>

    
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

    <div id="map-container"></div>
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            // charger l'API Google Maps de manière asynchrone
(function() {
    var script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDslHhaYuaaxzUKiMyTjNeP1HdfHaq1wHc&libraries=places';
    script.defer = true;
    script.async = true;
    script.onload = function () {
        console.log('API Google Maps chargée avec succès');
        initializeMap(); // Appeler la fonction qui initialise la carte une fois que l'API est chargée
    };
    document.head.appendChild(script);
})();

// Fonction pour initialiser la carte
function initializeMap() {
    var map;
    var mapContainer = document.getElementById('map-container');
    var center = { lat: 48.7572, lng: 1.9470 };
    var mapOptions = {
        zoom: 15,
        center: center
    };

    // Reste du code...
}



            var map;
            var mapContainer = document.getElementById('map-container');
            var center = { lat: 48.7572, lng: 1.9470 };
            var mapOptions = {
                zoom: 15,
                center: center
            };

            function afficherCarte(coordinates) {console.log(coordinates);
        // Assurez-vous que la carte est définie avant d'essayer de la manipuler
        if (map) {
            // Centrez la carte sur les nouvelles coordonnées
            map.setCenter({ lat: parseFloat(coordinates.split(',')[0]), lng: parseFloat(coordinates.split(',')[1]) });
            map.setZoom(12); // Zoom sur les nouvelles coordonnées

            // Affichez les marqueurs depuis la base de données
            
            loadMarkersFromDatabase();
        }
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

            function loadMarkersFromDatabase() {
    console.log('Chargement des marqueurs depuis la base de données...');
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
    try {
        fetch('markers.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Réponse du serveur non valide');
                }
                return response.json();
            })
            .then(markers => {
                console.log('Markers:', markers);
                markers.forEach(function (marker) {
                    var position = { lat: parseFloat(marker.latitude), lng: parseFloat(marker.longitude) };
                    var content = "Commentaire : " + marker.comment;

                    var infowindow = new google.maps.InfoWindow({
                        content: content
                    });

                    var loadedMarker = new google.maps.Marker({
                        position: position,
                        map: map
                    });

                    loadedMarker.addListener('click', function () {
                        infowindow.open(map, loadedMarker);
                    });
                });
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des marqueurs :', error.message);
            });
    } catch (error) {
        console.error('Erreur lors du chargement des marqueurs : ' + error.message);
    }
}

        });
    </script>
</body>
</html>