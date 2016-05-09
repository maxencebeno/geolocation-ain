/*Variables globales*/
var baseUrl = "/app_dev.php/";

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

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

});

