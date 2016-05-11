$(document).ready(function () {
    var checkbox = $('#geolocation_adminbundle_adresse_iso .checkbox input[type=checkbox]');
    //ajout du container des autres champs
    $(checkbox).each(function () {
        $('<div class="certification"></div>').appendTo($(this).parent('label').parent('.checkbox'));
    });

    $(checkbox).on('change', function () {
        if ($(this).is(':checked')) {
            var id= $(this).attr("value");
            if ($(this).parent().text().trim() !== "Autre") {
                
                $('     <label> Etes-vous certifié ? ' +
                        '<input type="radio" name="certifie-' + id + '" value="oui" class="certifie" > Oui' +
                        '<input type="radio" name="certifie-' + id + '" class="certifie" value="en-cours" checked> En cours de certification' +
                        '</label>' +
                        '<div class="date_certif"></div>').appendTo($(this).parent().siblings('.certification'));
            } else {
                $('     <label>Entrer un code ISO *<input type="text" name="other" placeholer="Entrer un code ISO" required/></label>' +
                        '<label> Etes-vous certifié ? ' +
                        '<input type="radio" name="certifie-other" class="certifie" value="oui"> Oui' +
                        '<input type="radio" name="certifie-other" class="certifie" value="en-cours" checked> En cours de certification' +
                        '</label>' +
                        '<div class="date_certif"></div>').appendTo($(this).parent().siblings('.certification'));
                id = "other";
            }
            $("input[type=radio]").on('change', function () {
                console.log($(this).val().trim() );
                if ($(this).val().trim() === "oui") {
                    $('     <label> Depuis quand ? ' +
                            '<input type="text" name="date_certification-' + id + '" class="date_certification datepicker" placeholder="jj/mm/aaaa" data-provide="datepicker" data-date-format="DD/MM/Y" />' +
                            '</label>').appendTo($(this).parent().siblings('.date_certif'));

                    $('.date_certification').datetimepicker({
                        locale: "fr",
                        format: "d/m/Y"
                    });
                } else {
                    $(this).parent().siblings('.date_certif').empty();
                }
            });
        } else {
            $(this).parent().siblings('.certification').empty();
        }
    });
});
