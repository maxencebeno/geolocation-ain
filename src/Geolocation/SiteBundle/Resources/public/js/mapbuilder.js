$(document).ready(function () {
    var carte;
    var infowindow = new Array();


    function initialize() {
        var latlng = new google.maps.LatLng(45.7578, 4.8400);
        var options = {
            center: latlng,
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var carte = new google.maps.Map(document.getElementById("map"), options);

        var latlng2 = new Array();
        var markers = new Array();
        var contentString = "";
        latlng2.push(new google.maps.LatLng(45.7578, 4.8400));
        latlng2.push(new google.maps.LatLng(45.771633, 4.890169));
        for (index = 0; index < latlng2.length; ++index) {

            markers.push(new google.maps.Marker({
                position: latlng2[index],
                map: carte,
                title: 'Test ' + index
            }));
            contentString =
                    '<div id="content">' +
                    '<h2>Nom de l\'entreprise '+ index+'</h2>' +
                    'adresse & contact' +
                    'lien vers la fiche entreprise' +
                    '</div>';

            infowindow[index] = new google.maps.InfoWindow({
                content: contentString
            });

            google.maps.event.addListener(markers[index], 'click', function (index) {
                return function () {
                    closeWindowInfos(); //fermer toutes les infoWindows ouvertes
                    infowindow[index].open(carte, markers[index]);
                }
            }(index));
        }

    }

    function closeWindowInfos() {
        for (var i = 0; i < infowindow.length; i++)
        {
            infowindow[i].close();
        }
    }

    function bindInfoWindow(marker, content) {
        google.maps.event.addListener(marker, 'click', function () {
            infowindow.setContent(content);
            infowindow.open(carte, marker);
            alert(content);
        });
    }

    initialize();


});

