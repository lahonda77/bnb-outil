<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des marqueurs</title>
</head>
<body>
    <h1>Afficher/ Supprimer</h1>
    <div id="map" style="height: 400px;"></div>

    <script>
            var map; // Déclarez la variable map en tant que variable globale

            function initMap() {
                var center = { lat: 48.7572, lng: 1.9470 };
                var mapOptions = {
                    zoom: 15,
                    center: center
                };
                map = new google.maps.Map(document.getElementById('map'), mapOptions);

                map.addListener('click', function (event) {
                    placeMarker(event.latLng, map);
                });

                //Chargez les marqueurs depuis la bdd
                loadMarkersFromDatabase();
            }

             function loadMarkersFromDatabase() {
                try {
                    fetch('markers.php')  // File "markers.php" qui contient le code pour récupérer les marqueurs depuis la bdd
                        .then(response => response.json())
                        .then(markers => {
                            console.log('Markers:', markers);
                            markers.forEach(function (marker) {
                                var position = { lat: parseFloat(marker.latitude), lng: parseFloat(marker.longitude) };
                                new google.maps.Marker({
                                    position: position,
                                    map: map
                                });
                            });
                        });
                } catch (error) {
                    console.error('Erreur lors du chargement des marqueurs : ' + error.message);
                }
            }

            marker.setMap(null);
            
/*             function marker.setMap(null) {
                var marker = new google.maps.Marker({
                    position: location,
                    map: map
                });
 
                // Appel au service de géocodage pour obtenir l'adresse
                 var geocoder = new google.maps.Geocoder();
                    geocoder.geocode({ 'location': location }, function (results, status) {
                        if (status === 'OK') {
                            if (results[0]) {
                                var address = results[0].formatted_address;

                                // Remplir les champs de formulaire avec les coordonnées et l'adresse
                                document.getElementById('latitude').value = location.lat();
                                document.getElementById('longitude').value = location.lng();
                                document.getElementById('address').value = address;
                            } else {
                                console.error('Aucun résultat trouvé');
                            }
                        } else {
                            console.error('Le géocodage a échoué avec le statut : ' + status);
                        }
                }); 

            }*/

            // Chargez l'API Google Maps de manière asynchrone
            (function() {
                var script = document.createElement('script');
                script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyD5N6NIdED2nEKA69TK8aLkpBdMANxHCzY&libraries=places&callback=initMap';
                script.defer = true;
                script.async = true;
                document.head.appendChild(script);
            })();
        </script>
</body>
</html>

<?php
    require('config.php');

    try{
        $sql = "SELECT latitude, longitude FROM locations";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute();

        $markers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($markers);
    } catch (PDOException $e) {
        // Gérez les erreurs de base de données ici
        echo json_encode(['error' => 'Erreur lors de la récupération des marqueurs : ' . $e->getMessage()]);
    }


?>