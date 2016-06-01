$(document).ready(function () {
    var checkbox = $('#geolocation_adminbundle_adresse_iso .checkbox input[type=checkbox]');
    //ajout du container des autres champs
    $(checkbox).each(function () {
        $('<div class="certification"></div>').appendTo($(this).parent('label').parent('.checkbox'));
    });

    //première initialisation des checkbox : utiliser notamment dans la mise à jour des sites de production
    initCheckbox();

    //gestion des interactions avec les checkbox pour afficher les choix de certification, et la date si certifié
    $(checkbox).on('change', function () {
        if ($(this).is(':checked')) {
            var id = $(this).attr("value");
            certificationRadio($(this), id);
            //gestion des intercations avec les radio button
              $("input[type=radio]").on('change', function () {
                dateCertification($(this), id);
            });
            
        } else {
            viderCertification($(this));
        }
    });

    
    $("input[type=radio].certifie").on('change', function () {
        //récupération de la valeur de la checkbox
        var checkId =$(this).parent("label").parent(".certification").prev("label").children().attr("value");
        dateCertification($(this), checkId);
    });
    
    //initialisation des checkbox avant interaction
    function initCheckbox() {
        $(checkbox).each(function () {
            if ($(this).is(':checked')) {
                var id = $(this).attr("value");
                certificationRadio($(this), id);
            }
        });
    }

    //initialisation des radio button avant interaction
    function initRadioButton() {
        $('input[type=radio]').each(function () {
            if ($(this).is(':checked')) {
                var check =$(this).parent().parent().next("label").children();
                var id = $(check).attr("value");
                certificationRadio($(check), id);
            }
        });
    }
    
    //affiche les boutons radio du choix de certification (en cours ou certifié)
    function certificationRadio(box, id) {
        if ($(box).parent().text().trim() !== "Autre") {

            $('     <label> Etes-vous certifié ? ' +
                    '<input type="radio" name="certifie-' + id + '" value="oui" class="certifie" > Oui' +
                    '<input type="radio" name="certifie-' + id + '" class="certifie" value="en-cours" checked> En cours de certification' +
                    '</label>' +
                    '<div class="date_certif"></div>').appendTo($(box).parent().siblings('.certification'));
        } else {
            $('     <label>Entrer un code ISO *<input type="text" name="other" id="other" placeholer="Entrer un code ISO" required /></label>' +
                    '<label> Etes-vous certifié ? ' +
                    '<input type="radio" name="certifie-other" class="certifie" value="oui"> Oui' +
                    '<input type="radio" name="certifie-other" class="certifie" value="en-cours" checked> En cours de certification' +
                    '</label>' +
                    '<div class="date_certif"></div>').appendTo($(box).parent().siblings('.certification'));
            id = "other";
        }
    }

    //Affiche le champ pour entrer la date de certification
    function dateCertification(radio, id) {
        if ($(radio).val().trim() === "oui") {
            $('     <label> Depuis quand ? ' +
                    '<input type="text" name="date_certification-' + id + '" class="date_certification datepicker" placeholder="jj/mm/aaaa" data-provide="datepicker" data-date-format="DD/MM/Y" />' +
                    '</label>').appendTo($(radio).parent().siblings('.date_certif'));

            $('.date_certification').datetimepicker({
                locale: "fr",
                format: "d/m/Y"
            });
        } else {
            $(radio).parent().siblings('.date_certif').empty();
        }
    }

    //Supprimer le contenu de la certification
    function viderCertification(box) {
        $(box).parent().siblings('.certification').empty();
    }

});
