$(document).ready(function () {

        /*************************************************************************
         * Création de la map
         ************************************************************************/

        // Constantes
        var BASE_URL = 'http://upop.dev/app_dev.php/';
        var globalMarkersMap = {};
        globalMarkersJson = {};

        // On récupère d'éventuels paramètres Google Maps passés en URL
        var params = jQuery.deparam.querystring();
        var latitude = Cookies.getJSON('villecoordinates').villelat;
        var longitude = Cookies.getJSON('villecoordinates').villelng;
        var zoom = 12;

        // Valeurs par défaut pour Lyon
        // Récupère le JSON des markers de la map


        // On modifie les valeurs par défaut via l'URL si on peut
        var latitudeURL = params.lat;
        var longitudeURL = params.lng;
        var zoomURL = params.zoom;

        if (typeof latitudeURL !== 'undefined') {
            latitudeURL = parseFloat(latitudeURL);

            if (latitudeURL >= -90 && latitudeURL <= 90) {
                latitude = latitudeURL;
            }
        }

        if (typeof longitudeURL !== 'undefined') {
            longitudeURL = parseFloat(longitudeURL);

            if (longitudeURL >= -180 && longitudeURL <= 180) {
                longitude = longitudeURL;
            }
        }

        if (typeof zoomURL !== 'undefined') {
            zoomURL = parseInt(zoomURL);
            if (zoomURL >= 4 && zoomURL <= 21) {
                zoom = zoomURL;
            }
        }

        // Puis on crée la Google map
        var latlng = new google.maps.LatLng(latitude, longitude);
        var options = {
            center: latlng,
            zoom: zoom,
            minZoom: 4,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map"), options);

        // Fonctions custom
        function updateGoogleURI() {
            var newQueryString = '';

            var params = {
                'nelat': map.getBounds().getNorthEast().lat(),
                'nelng': map.getBounds().getNorthEast().lng(),
                'swlat': map.getBounds().getSouthWest().lat(),
                'swlng': map.getBounds().getSouthWest().lng(),
                'lat': map.getCenter().lat().toFixed(6),
                'lng': map.getCenter().lng().toFixed(6),
                'zoom': map.getZoom()
            };

            for (var key in params) {
                newQueryString += '&' + key + '=' + params[key];
            }

            newQueryString = newQueryString.toString();

            var newUrl = jQuery.param.querystring(window.location.href, newQueryString);

            history.pushState({}, newUrl, newUrl);
        }


        /*************************************************************************
         * Marqueurs
         ************************************************************************/

        function fetchMarkersFromQuery() {
            var params = jQuery.deparam.querystring();

            // Récupère le JSON des markers de la map
            $.ajax({
                url: BASE_URL + 'get/json/map-places',
                type: 'GET',
                data: ({
                    city: params.city,
                    date: params.date,
                    location: params.location,
                    nbpeople: params.nbpeople,
                    date: params.date,
                    dateFin: params.dateFin,
                    filtres: params.filtres,

                    query: true,
                    ajax: true
                }),
                success: function (jsonObject) {
                    var jsonMarkersList = jsonObject.lieux;

                    // Ajoute les markers de la map
                    populateMap(jsonMarkersList, true);

                    // Mets à jour les markers json
                    globalMarkersJson = jsonMarkersList;
                }
            });
        }

        function populateMap(jsonMarkersList, populateIsFromQuery) {
            // Permet de vérifier s'il y a besoin de replacer les markers (s'ils ont changé en bougeant la map à la main)
            var markersChanged;
            if (globalMarkersJson.length !== jsonMarkersList.length) {
                // Les 2 objets ne font pas la même taille donc il y a forcément eu un changement
                markersChanged = true;
            } else {
                // Si le populate ne vient pas du premier chargement de page
                if (populateIsFromQuery == undefined) {

                    // On parcourt les marqueurs déjà placés
                    for (var i in globalMarkersJson) {

                        // On le met à true parce qu'on considère qu'à chaque tour de boucle il y a eu un changement
                        markersChanged = true;
                        // Dès qu'on ne trouve pas de changement en comparant avec le json reçu
                        // On dit qu'il n'y a pas eu de changement
                        for (var j in jsonMarkersList) {
                            if (jsonMarkersList[j].id == globalMarkersJson[i].id) {
                                markersChanged = false;
                            }
                        }
                    }
                } else {
                    markersChanged = true;
                }
            }

            if (markersChanged) {
                // Supprime les markers
                map.clearMarkers();

                for (var i in jsonMarkersList) {
                    var marker = jsonMarkersList[i];

                    // Source : http://stackoverflow.com/a/21517192
                    var markerTemp = new RichMarker({
                        position: new google.maps.LatLng(marker.latitude, marker.longitude),
                        map: map,
                        content: '<div id="map-marker-' + marker.id + '" class="map-custom-marker">' + marker.prix + '€</div>',
                        idlieu: marker.id
                    });
                    markerTemp.setShadow('none');

                    // Ajoute à la liste des markers
                    globalMarkersMap.push(markerTemp);

                    google.maps.event.addListener(markerTemp, "click", function () {
                        var url = BASE_URL + '/' + marker.id + '/detail-lieu';
                        window.open(url + this.idlieu, '_blank');
                    });
                }

                globalMarkersJson = jsonMarkersList;
            }
        }

        // Clear map markers
        google.maps.Map.prototype.clearMarkers = function () {
            for (var i = 0; i < globalMarkersMap.length; i++) {
                globalMarkersMap[i].setMap(null);
            }
            globalMarkersMap = new Array();
        };

        /***************************************************************************
         * Mises à jour des données
         **************************************************************************/
        google.maps.event.addListener(map, 'dragend', function () {
            fetchMarkersFromMapView(map);
            fetchTemplateFromMapView(map);
            updateGoogleURI();
        });

        // Listeners
        google.maps.event.addListener(map, 'zoom_changed', function () {
            fetchMarkersFromMapView(map);
            fetchTemplateFromMapView(map);
            updateGoogleURI();
        });

        // Rempli la map des markers  au premier chargement de la page
        google.maps.event.addListenerOnce(map, 'idle', function () {
            fetchMarkersFromQuery();
        });

        $('#apply-filters').click(function () {
            fetchMarkersFromMapView(map);
        });

        function fetchMarkersFromMapView(map) {
            var params = jQuery.deparam.querystring();

            var nelat = map.getBounds().getNorthEast().lat();
            var nelng = map.getBounds().getNorthEast().lng();
            var swlat = map.getBounds().getSouthWest().lat();
            var swlng = map.getBounds().getSouthWest().lng();

            // Récupère le JSON des markers de la map
            $.ajax({
                url: BASE_URL + 'get/json/map-places',
                type: 'GET',
                data: ({
                    nelat: nelat,
                    nelng: nelng,
                    swlat: swlat,
                    swlng: swlng,
                    date: params.date,
                    dateFin: params.dateFin,
                    ajax: true,
                    filtres: params.filtres
                }),
                success: function (jsonMarkersList) {
                    populateMap(jsonMarkersList);
                    $('#nb-result').text(jsonMarkersList.length > 1 ? jsonMarkersList.length + " résultats" : jsonMarkersList.length + " résultat");
                }
            });
        }

        function fetchTemplateFromMapView(map) {
            var params = jQuery.deparam.querystring();

            var nelat = map.getBounds().getNorthEast().lat();
            var nelng = map.getBounds().getNorthEast().lng();
            var swlat = map.getBounds().getSouthWest().lat();
            var swlng = map.getBounds().getSouthWest().lng();

            // Récupère le JSON des markers de la map
            $.ajax({
                url: BASE_URL + 'get/html/items-places',
                type: 'GET',
                data: ({
                    nelat: nelat,
                    nelng: nelng,
                    swlat: swlat,
                    swlng: swlng,
                    date: params.date,
                    dateFin: params.dateFin,
                    ajax: true,
                    filtres: params.filtres
                }),
                success: function (htmlTemplate) {
                    // Remplir les markers de la map
                    $('#items-lieux-list').html(htmlTemplate);
                }
            });
        }


    }
);

