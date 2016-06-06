$(document).ready(function () {
    var checkbox = $('#geolocation_adminbundle_adresse_iso .checkbox input[type=checkbox]');
    //ajout du container des autres champs
    $(checkbox).each(function () {
        $('<div class="certification"></div>').appendTo($(this).parent('label').parent('.checkbox'));
    });

    //première initialisation des checkbox : utiliser notamment dans la mise à jour des sites de production
    initCheckbox();

    /*
     * gestion des interactions avec les checkbox pour afficher les choix de certification, et la date si certifié
     */
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

 
    /*
     * gestion des interaction avec les radio button
     */
    $("input[type=radio].certifie").on('change', function () {
        //récupération de la valeur de la checkbox
        var checkId =$(this).parent("label").parent(".certification").prev("label").children().attr("value");
        dateCertification($(this), checkId);
    });
    
    
    /*
     * Initialisation des checkbox avant interaction
     * 
     */
    function initCheckbox() {
        $(checkbox).each(function () {
            if ($(this).is(':checked')) {
                var id = $(this).attr("value");
                certificationRadio($(this), id);
            }
        });
    }

    /*
     * initialisation des radio button avant interaction
     */
    function initRadioButton() {
        $('input[type=radio]').each(function () {
            if ($(this).is(':checked')) {
                //récupération du label du container précédent pour récupérer par la suite sa valeur qui servira
                //d'id pour la fonction certificationRadio
                var checkbox =$(this).parent().parent().next("label").children(); 
                var id = $(checkbox).attr("value");
                certificationRadio($(checkbox), id);
            }
        });
    }
    
    
    /*
     * Affiche les boutons radio du choix de certification (en cours ou certifié) 
     * @param {type} checkbox 
     * @param {type} id : id qui est la valeur de la checkbox
     */
    function certificationRadio(checkbox, id) {
        //si c'est un choix différent de "Autre" qui est fait
        if ($(checkbox).parent().text().trim() !== "Autre") {

            $('     <label> Etes-vous certifié ? ' +
                    '<input type="radio" name="certifie-' + id + '" value="oui" class="certifie" > Oui' +
                    '<input type="radio" name="certifie-' + id + '" class="certifie" value="en-cours" checked> En cours de certification' +
                    '</label>' +
                    '<div class="date_certif"></div>').appendTo($(checkbox).parent().siblings('.certification'));
        } else {
            $('     <label>Entrer un code ISO *<input type="text" name="other" id="other" placeholer="Entrer un code ISO" required /></label>' +
                    '<label> Etes-vous certifié ? ' +
                    '<input type="radio" name="certifie-other" class="certifie" value="oui"> Oui' +
                    '<input type="radio" name="certifie-other" class="certifie" value="en-cours" checked> En cours de certification' +
                    '</label>' +
                    '<div class="date_certif"></div>').appendTo($(checkbox).parent().siblings('.certification'));
            id = "other";
        }
    }

    
    /*
     * Affiche le champ pour entrer la date de certification
     * @param {type} radio
     * @param {type} id
     */
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
            //suppression de champ de date certification
            $(radio).parent().siblings('.date_certif').empty();
        }
    }

    //Supprimer la totalité du contenu de la certification
    function viderCertification(box) {
        $(box).parent().siblings('.certification').empty();
    }

});
