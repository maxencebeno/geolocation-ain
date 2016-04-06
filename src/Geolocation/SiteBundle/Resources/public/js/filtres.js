$(document).ready(function () {

    $('#sections').change(function () {
        var sectionId = $(this).val();
        //Vide les autres select du form (Division & Groupe)
        resetRessources();

        if (sectionId !== "-1") {
            //récupération division
            getDivision(sectionId, $('.division'));
        }

    });

    $('#division').change(function () {
        var sectionId = $('#sections').val();
        var divisionId = $(this).val();
        resetGroupe();
        getGroupe(sectionId, divisionId, $('.groupe'));


    });

    function getDivision(sectionId, division) {
        $.ajax({
            url: baseUrl + 'ajax/getDivision?id=' + sectionId,
            success: function (data) {
                for (var i = 0; i < data.length; i++) {
                    division.append("<option value='" + data[i].id + "'>" + data[i].libelle + "</option>");
                }
                $('.division-form').removeClass('hide');
            }
        });
    }

    function getGroupe(sectionId, divisionId, groupe) {
        $.ajax({
            url: baseUrl + 'ajax/getGroupe?sectionid=' + sectionId + '&divisionid=' + divisionId,
            success: function (data) {
                for (var i = 0; i < data.length; i++) {
                    groupe.append("<option value='" + data[i].id + "'>" + data[i].libelle + "</option>");
                }
                $('.groupe-form').removeClass('hide');
            }
        });
    }

    function resetRessources() {
        resetDivision();
        resetGroupe();
    }

    function resetDivision() {
        $('#division').empty();
        $('#division').append('<option label="Division" value="-1"></option>');
        $('#division').parent().addClass('hide');
    }

    function resetGroupe() {
        $('#groupe').empty();
        $('#groupe').append('<option label="Groupe" value="-1"></option>');
        $('#groupe').parent().addClass('hide');
    }

    $('#form-filters').submit(function (e) {
        e.preventDefault();
        e.stopPropagation();

        $.ajax({
            url: baseUrl + 'ajax/filtres/get-entreprises-by-filters',
            method: "POST",
            data: $(this).serialize(),
            //dataType: 'json',
            success: function (data) {
                clearMarker();
                initMarker(data);
                /*if (typeof data.ville !== 'undefined') {
                    centerMap(data.ville.lat, data.ville.lng);
                } else {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function (position) {
                            var pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };
                            centerMap(pos.lat, pos.lng);
                        });
                    } else {
                        centerMap(46.204960, 5.225797);
                    }
                }*/
            }
        });

        return false;
    });
});