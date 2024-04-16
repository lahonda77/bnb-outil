<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
require('config.php');

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = isset($_POST["name"]) ? $_POST["name"] : null;
    $comment = isset($_POST["comment"]) ? $_POST["comment"] : null;
    $latitude = isset($_POST["latitude"]) ? $_POST["latitude"] : null;
    $longitude = isset($_POST["longitude"]) ? $_POST["longitude"] : null;

    try {
        // Ajouter l'utilisateur dans la table users

        // Récupérer l'ID de l'utilisateur ajouté

        
        //Ajouter le lieu dans la table data_form
        $sqlLocation = "INSERT INTO data_form (name, latitude, longitude, comment) VALUES (:name, :latitude, :longitude, :comment)";
        $stmtLocation = $pdo->prepare($sqlLocation);
        $stmtLocation -> bindParam(':name', $name);
        $stmtLocation -> bindParam(':latitude', $latitude);
        $stmtLocation -> bindParam(':longitude', $longitude);
        $stmtLocation -> bindParam(':comment', $comment);
        $stmtLocation->execute();

        // Récupérer l'ID de la location ajouté


        echo "Enregistrement ajouté avec succès !";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    } catch (PDOException $e) {
        echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/Group 1.png" />
    <link rel="stylesheet" href="form.css">
    <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5N6NIdED2nEKA69TK8aLkpBdMANxHCzY&libraries=geometry&callback=initMap" defer></script> -->
    <!-- <script src="turf.min.js" async></script> -->


    <title>Formulaire de suggestions</title>
</head>
<body>
    <!-- <header>
         <div class="banniere">
            <img src="images/new-band.png" alt="">
        </div>
        <div class="navbar">
            <a href="accueil.html" class="case">ACCUEIL</a>
            <a href="form.php" class="case">CARTE</a>
            <a href="#" class="case">CONTACT</a>
            <a href="login.php" class="case">CONNEXION</a>
        </div>
    </header> -->

    <div class="container1 lime pullUp">
        <div class="banniere">
            <img src="images/new-band.png" alt="">
        </div>
        <a href="accueil.html" class="current">Accueil</a>
        <a href="form.php" class="">Carte</a>
        <a href="contact.html">Contact</a>
        <a href="login.php">Connexion</a>
    </div>

    <div class="container">
        <h3>Ajoutez une suggestion</h3>

<!--         <div class="subcontainer"> -->
            <form method="POST" name="suggest_form" class="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"onsubmit="return validateForm()">
                <label for="name" class="name">Nom et prénom :</label><br>
                <input type="text" name="name" class="input-name" required>
                <div id="map" ></div>
                <input type="hidden" id="latitude" name="latitude" required>
                <input type="hidden" id="longitude" name="longitude" required>

                <br>

                <label for="comment" class="comment">Pourquoi avez-vous sélectionné cet emplacement ?</label><br>
                <textarea required name="comment" class="input-comment" rows="4" cols="50" ></textarea>

                <br>

                <script>

                    var map;
                    var geojsonLayer;
                    var currentMarker = null;


                    function initMap() {
                        var center = { lat: 48.7572, lng: 1.9470 };
                        var mapOptions = {
                            zoom: 15,
                            center: center
                        };

                        map = new google.maps.Map(document.getElementById('map'), mapOptions);

                        geojsonLayer = new google.maps.Data();

                        // pour attendre le chargement du GeoJSON
                        var geoJsonPromise = new Promise(function (resolve, reject) {
                            google.maps.event.addListenerOnce(geojsonLayer, 'addfeature', resolve);
                        });

                        // charge le fichier GeoJSON
                        geojsonLayer.loadGeoJson('la_verriere.geojson');

                        geoJsonPromise.then(function () {
                            // ajout écouteur d'événement une fois chargement du GeoJSON terminé
                            map.addListener('click', function (event) {
                                if (!currentMarker) {
                                    placeMarker(event.latLng, map);
                                } else {
                                    alert('Un seul marker est autorisé à la fois. Soumettez le formulaire pour enregistrer votre emplacement.');
                                }
                            });

                            geojsonLayer.setStyle({
                                strokeColor: '#02BD64',
                                strokeWeight: 2,
                                fillColor: 'transparent',
                                fillOpacity: 0,
                                
                            });

                            // ajoute le GeoJSON à la carte
                            geojsonLayer.setMap(map);
                        });
                        
                        // charge les marqueurs depuis la bdd
                        loadMarkersFromDatabase();

                        // clusters de marqueurs
        /*             var markerCluster = new MarkerClusterer(map, Array.from(geojsonLayer.getFeatures()), { imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m' }); */
                    }

                    function loadMarkersFromDatabase() {
                        try {
                            fetch('markers.php')  // fichier "markers.php" qui contient le code pour récupérer les marqueurs depuis la bdd
                                .then(response => response.json())
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
                                });
                        } catch (error) {
                            console.error('Erreur lors du chargement des marqueurs : ' + error.message);
                        }
                    }

                    function placeMarker(location, map) {
                        // supprime l'ancien marker s'il existe
                        if (currentMarker) {
                            currentMarker.setMap(null);
                        }

                        // ajoute le nouveau marker
                        currentMarker = new google.maps.Marker({
                            position: location,
                            map: map
                        });

                        // récupère le commentaire associé au marker
                        var comment = "Ajoutez un commentaire dans le champ dédié"; // Remplacez cela par la façon dont vous récupérez le commentaire

                        // rempli les champs de formulaire avec les coordonnées
                        document.getElementById('latitude').value = location.lat();
                        document.getElementById('longitude').value = location.lng();

                        // bulle d'information pour le marqueur qui vient d'être créé
                        var infowindow = new google.maps.InfoWindow({
                            content: comment
                        });

                        // ajout événement de clic pour afficher l'info-bulle
                        currentMarker.addListener('click', function () {
                            infowindow.open(map, currentMarker);
                        });
                    }

                    function validateForm() {
                        var latitude = document.forms["suggest_form"]["latitude"].value;
                        var longitude = document.forms["suggest_form"]["longitude"].value;

                        if (latitude === "" || longitude === "") {
                            alert("Veuillez sélectionner un emplacement sur la carte");
                            return false;
                        }

                        
                        return true;
                    }

                    // charger l'API Google Maps de manière asynchrone
                    (function() {
                        var script = document.createElement('script');
                        script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyD5N6NIdED2nEKA69TK8aLkpBdMANxHCzY&libraries=places&callback=initMap';
                        script.defer = true;
                        script.async = true;
                        document.head.appendChild(script);
                    })();

                </script>
                <input type="submit" value="Ajouter" class="cta">
            </form>

    </div>
</body>
</html>