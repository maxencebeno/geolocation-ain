/*Variables globales*/
var baseUrl = "/app_dev.php/";
/*Gestion du slider range pour le filtre de distance*/
$(function() {
    $("#slider-range").slider({
        range: true,
        min: 0,
        max: 500,
        values: [75, 300],
        slide: function(event, ui) {
            $("#distance").val(ui.values[ 0 ] + "km - " + ui.values[ 1 ] + "km");
        }
    });
    $("#distance").val($("#slider-range").slider("values", 0) +
            "km - " + $("#slider-range").slider("values", 1) + "km");
});

/*Afficher cacher les filtres de recherche*/
$(function() {
    nbClic = 0;
    $("#filtreRechercheCarte").click(function() {
        $(this).siblings().toggleClass("disabled");
        $("#zoneRechercheCarte").toggleClass("reduire");
        nbClic++;
        if (nbClic % 2 === 0) {
            $(this).children("span").removeClass("glyphicon-arrow-down");
            $(this).children("span").addClass("glyphicon-arrow-up");
        } else {
            $(this).children("span").removeClass("glyphicon-arrow-up");
            $(this).children("span").addClass("glyphicon-arrow-down");
        }

    });
});

$(document).ready(function () {
    $('#datetimepicker1').datetimepicker({
        locale: "fr",
        format: "d/m/Y"
    });
    $('#geolocation_adminbundle_user_dateCreation').datetimepicker({
        locale: "fr",
        format: "d/m/Y"
    });

    $('#geolocation_adminbundle_user_dateCreationEntreprise').datetimepicker({
        locale: "fr",
        format: "d/m/Y"
    });
    
    $('#fos_user_registration_form_url').on('focus', function() {
        $(this).val('http://');
    });
    $('#fos_user_registration_form_url').on('blur', function() {
        $(this).val('');
    });
});

