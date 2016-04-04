var latlngCentre = new google.maps.LatLng(46.204960, 5.225797);
var options = {
    center: latlngCentre,
    zoom: 12,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};
var carte = new google.maps.Map(document.getElementById("map"), options);

//chargement des données 1 seule fois
google.maps.event.addListenerOnce(carte, 'idle', function () {
    var latlng = new Array();
    var markers = new Array();
    var contentString = "";
    var infowindow = new Array();

    //appel de l'action pour charger les markers
    $.ajax({
        url: '/app_dev.php/json/markers',
        success: function (data) {
            var i;
            var index = 0;
            for (i in data) {
                console.log(data[i]);
                latlng.push(new google.maps.LatLng(data[i].latitude, data[i].longitude));
                markers.push(new google.maps.Marker({
                    position: latlng[index],
                    map: carte,
                    title: 'Test ' + index
                }));
                contentString =
                        '<div id="content">' +
                        '<h2>Nom de l\'entreprise ' + data[i].name + '</h2>' +
                        'adresse & contact' +
                        'lien vers la fiche entreprise' +
                        '</div>';

                infowindow[index] = new google.maps.InfoWindow({
                    content: contentString
                });
                index++;

            }
            //alert(url);


        }
    });
});

$(document).ready(function () {

    function initialize() {

        var latlngCentre = new google.maps.LatLng(46.204960, 5.225797);
        var options = {
            center: latlngCentre,
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
    }

    initialize();


    /*var latlng2 = new Array();
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
     }*/



});

