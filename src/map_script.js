var map;
var geojsonLayer;
var currentMarker = null;


document.addEventListener('DOMContentLoaded', function () {
    var mapContainer = document.getElementById('map-container');


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
/*             map.addListener('click', function (event) {
                if (!currentMarker) {
                    placeMarker(event.latLng, map);
                } else {
                    alert('Un seul marker est autorisé à la fois. Soumettez le formulaire pour enregistrer votre emplacement.');
                }
            }); */

            geojsonLayer.setStyle({
                strokeColor: '#02BD64',
                strokeWeight: 2,
                fillColor: '#7FD856',
                fillOpacity: 0.2,
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
            // Nettoie tous les marqueurs existants sur la carte
            geojsonLayer.forEach(function (feature) {
                geojsonLayer.remove(feature);
            });
    
            fetch('markers.php')  // fichier "markers.php" qui contient le code pour récupérer les marqueurs depuis la bdd
                .then(response => response.json())
                .then(markers => {
                    console.log('Markers:', markers);
    
                    markers.forEach(function (marker) {
                        var position = { lat: parseFloat(marker.latitude), lng: parseFloat(marker.longitude) };
                        var content = "Commentaire : " + marker.comment;
    
                        // Ajoute le marqueur à la carte
                        var loadedMarker = new google.maps.Marker({
                            position: position,
                            map: map
                        });
    
                        // Crée une fenêtre d'info pour le marqueur
                        var infowindow = new google.maps.InfoWindow({
                            content: content
                        });
    
                        // Ajoute un gestionnaire d'événement pour ouvrir la fenêtre d'info lorsque le marqueur est cliqué
                        loadedMarker.addListener('click', function () {
                            infowindow.open(map, loadedMarker);
                        });
    
                        // Ajoute le marqueur à la couche GeoJSON
                        geojsonLayer.add(new google.maps.Data.Feature({
                            geometry: new google.maps.Data.Point(position),
                            properties: { comment: marker.comment }
                        }));
                    });
                });
        } catch (error) {
            console.error('Erreur lors du chargement des marqueurs : ' + error.message);
        }
    }
    
    
    

    // charger l'API Google Maps de manière asynchrone
    (function() {
        var script = document.createElement('script');
        script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyD5N6NIdED2nEKA69TK8aLkpBdMANxHCzY&libraries=places&callback=initMap';
        script.defer = false;
        script.async = false;
        document.head.appendChild(script);
    })();



});
