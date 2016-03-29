$(document).ready(function () {

    function initialize() {
        var latlng = new google.maps.LatLng(45.7578, 4.8400);

        var options = {
            center: latlng,
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var carte = new google.maps.Map(document.getElementById("map"), options);
    }

    initialize();

});

