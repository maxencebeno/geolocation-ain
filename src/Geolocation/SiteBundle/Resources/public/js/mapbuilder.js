//initialisation Google Maps
var latlngCentre = new google.maps.LatLng(46.204960, 5.225797);
var options = {
    scrollwheel: false,
    center: latlngCentre,
    zoom: 12,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};
var carte = new google.maps.Map(document.getElementById("map"), options);

//initialisation des variables de la carte
var infowindow = new Array();
var latlng = new Array();
var markers = new Array();
var contentString = "";

//chargement des données 1 seule fois
google.maps.event.addListenerOnce(carte, 'idle', function () {
    //appel de l'action pour charger les markers
    var params = jQuery.deparam.querystring();
    var codeNaf = typeof params.codeNaf === 'undefined' ? '' : params.codeNaf;
    $.ajax({
        url: baseUrl + 'json/markers',
        success: function (data) {
            clearMarker();
            initMarker(data, false);
        }
    });
});

google.maps.event.addListenerOnce(carte, 'tilesloaded', function () {
    //appel de l'action pour charger les markers
    $('html').css('overflow', 'auto');
    $('body').css('overflow', 'auto');
    $('#loading-root').fadeOut();
    $('#loading-root').addClass('hide');
});

//vider la liste des markers
function clearMarker() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    latlng = [];
}

//initialiser les marker en fonctions de la requête ajax
function initMarker(data, centerMarkers) {
    centerMarkers = typeof centerMarkers === 'undefined';
    var i;
    var index = 0;
    var bounds;
    var result = 0;
    
    if (typeof data.ville !== 'undefined') {
        bounds = new google.maps.LatLngBounds(new google.maps.LatLng(data.ville.lat, data.ville.lng));
    } else {
        bounds = new google.maps.LatLngBounds();
    }
    // On affiche les maisons meres
    for (i in data) {
        if (i !== "ville" && i !== "connectedUser" && i !== "distances") {
            latlng.push(new google.maps.LatLng(data[i].user.latitude, data[i].user.longitude));
            markers.push(new google.maps.Marker({
                position: latlng[index],
                map: carte,
                title: data[i].user.nom
            }));
            var pilier = "";
            if (data[i].adresse.pilier != null) {
                pilier = data[i].adresse.pilier;
            }

            choosePilier(markers[index], pilier);
            //if (centerMarkers === true) {
                bounds.extend(new google.maps.LatLng(data[i].user.latitude, data[i].user.longitude));
            //}

            contentString =
                '<div id="content">' +
                '<h3>' + data[i].user.nom + '</h3>' +
                data[i].user.adresse + '<br>' +
                data[i].user.codePostal + ' ' + data[i].user.ville + '<br>' +
                '<h4>Ressources</h4>';

            if (data[i].besoin !== null) {
                contentString += '<h5>Besoin</h5><p>' + data[i].besoin.cpf.souscategorie.libelle + '</p>';
            }

            if (data[i].proposition !== null) {
                contentString += '<h5>Proposition</h5><p>' + data[i].proposition.cpf.souscategorie.libelle + '</p>';
            }

            if (data[i].proposition === null && data[i].besoin === null) {
                contentString += '<p>Pas de ressources pour le moment</p>';
            }

            contentString += '<a href = "' + baseUrl + 'details/' + data[i].adresse.id + '">Plus d\'informations<a>' +
                '</div>';
            infowindow[index] = new google.maps.InfoWindow({
                content: contentString
            });

            google.maps.event.addListener(markers[index], 'click', function (index) {
                return function () {
                    closeWindowInfos(); //fermer toutes les infoWindows ouvertes
                    infowindow[index].open(carte, markers[index]); //ouverture de l'infobulle du point choisi
                }
            }(index));

            index++;
            result++;
        }
    }
    // On affiche les sites de production
    for (i in data) {
        if (typeof data[i].sites !== 'undefined') {
            for (var j in data[i].sites) {

                latlng.push(new google.maps.LatLng(data[i].sites[j].adresse.latitude, data[i].sites[j].adresse.longitude));

                markers.push(new google.maps.Marker({
                    position: latlng[index],
                    map: carte,
                    title: data[i].sites[j].adresse.nom
                }));
                var pilier = "";
                if (data[i].sites[j].adresse.pilier != null) {
                    pilier = data[i].sites[j].adresse.pilier;
                }
                choosePilier(markers[index], pilier);

                bounds.extend(new google.maps.LatLng(data[i].sites[j].adresse.latitude, data[i].sites[j].adresse.longitude));

                contentString =
                    '<div id="content">' +
                    '<h3>' + data[i].sites[j].adresse.nom + '</h3>' +
                    data[i].sites[j].adresse.adresse + '<br>' +
                    data[i].sites[j].adresse.codePostal + ' ' + data[i].sites[j].adresse.ville + '<br>' +
                    '<h4>Ressources</h4>';

                if (data[i].sites[j].besoin !== null) {
                    contentString += '<h5>Besoin</h5><p>' + data[i].sites[j].besoin.cpf.souscategorie.libelle + '</p>';
                }

                if (data[i].sites[j].proposition !== null) {
                    contentString += '<h5>Proposition</h5><p>' + data[i].sites[j].proposition.cpf.souscategorie.libelle + '</p>';
                }

                if (data[i].sites[j].proposition === null && data[i].sites[j].besoin === null) {
                    contentString += '<p>Pas de ressources pour le moment</p>';
                }
                contentString += '<a href = "' + baseUrl + 'details/' + data[i].sites[j].adresse.id + '">Plus d\'informations<a>' +
                    '</div>';
                infowindow[index] = new google.maps.InfoWindow({
                    content: contentString
                });

                google.maps.event.addListener(markers[index], 'click', function (index) {
                    return function () {
                        closeWindowInfos(); //fermer toutes les infoWindows ouvertes
                        infowindow[index].open(carte, markers[index]); //ouverture de l'infobulle du point choisi
                    }
                }(index));

                index++;
                result++;
            }
        }
    }

    if (typeof data.connectedUser !== 'undefined' && markers.length === 0) {
        //centerMap(data.connectedUser.latitude, data.connectedUser.longitude);
        /*Gestion du slider range pour le filtre de distance*/
        $(function () {
            $("#slider-range").slider({
                range: true,
                min: data.distances.min.value,
                max: data.distances.max.value,
                values: [data.distances.min.value, data.distances.max.value],
                slide: function (event, ui) {
                    $("#distance").val(ui.values[0] + " - " + ui.values[1] + "");
                }
            });
            $("#distance").val(data.distances.min.string +
                " - " + data.distances.max.string);
        });
    }
    $('.results').removeClass('hide');
    if (markers.length > 0) {
        carte.fitBounds(bounds);

        if (markers.length === 1) {
            carte.setZoom(14);
        }
        if (markers.length > 1) {
            $('#nb_firm_found').text(markers.length + " entreprises trouvées");
        } else {
            $('#nb_firm_found').text(markers.length + " entreprise trouvée");
        }

    } else {
        $('#nb_firm_found').text("Aucune entreprise ne correspond à votre recherche");
    }
}

//fermeture de toutes les infosWindows
function closeWindowInfos() {
    for (var i = 0; i < infowindow.length; i++) {
        infowindow[i].close();
    }
}

//liaison d'un marker avec une info window
function bindInfoWindow(marker, content) {
    google.maps.event.addListener(marker, 'click', function () {
        infowindow.setContent(content);
        infowindow.open(carte, marker);
    });
}

function centerMap(lat, lng) {
    var pos = {
        lat: lat,
        lng: lng
    };
    carte.setCenter(pos);
}

//Définit la couleur du marker sur la carte en fonction du pilier
function choosePilier(marker, pilier) {
    if (pilier !== null && pilier !== "") {
        marker.setIcon(pilier.urlPicto);
    } else {
        marker.setIcon('http://maps.google.com/mapfiles/ms/icons/red-dot.png');
    }
}
$(document).ready(function () {


});

