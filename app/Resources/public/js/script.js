/*Variables globales*/
var baseUrl = "/app_dev.php/";

/*Afficher cacher les filtres de recherche*/
$(function () {


    nbClic = 0;
    $("#filtreRechercheCarte").click(function () {

        toggleFiltres();
        if ($(window).width() < 752) {
            if (!$('#zoneRechercheCarte').hasClass("reduire")) {
                $('.container-map').css("margin-bottom", "500px");
            } else {
                $('.container-map').css("margin-bottom", "60px");
            }
        }

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

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    $('.container-map').css("margin-bottom", "40px");

    $(window).resize(function () {
        if ($(window).width() < 752) {
            if ($('#zoneRechercheCarte').css("height") == "500px") {
                if ($('.container-map').css("margin-bottom") == "40px") {
                    toggleFiltres();
                }
            }
        }
        if ($(window).width() > 752) {
            if ($('.container-map').css("margin-bottom") != "40px") {
                $('.container-map').css("margin-bottom", "40px");
                toggleFiltres();
            }
        }
    });

});


function toggleFiltres() {
    $("#filtreRechercheCarte").siblings().toggleClass("disabled");
    $("#filtreRechercheCarte").siblings().toggleClass("hidden");
    $("#zoneRechercheCarte").toggleClass("reduire");
}