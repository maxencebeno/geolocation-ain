$(document).ready(function () {

    $('#sections').change(function () {
        var sectionId = $(this).val();
        //Vide et cache les autres select du form (Division & Groupe)
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

    $('#groupe').change(function () {
        var sectionId = $('#sections').val();
        var divisionId = $('#division').val();
        var groupId = $('#groupe').val();

        resetClasse();
        getClasse(sectionId, divisionId, groupId, $('.classe'));

    });

    /*$('#classe').change(function () {
     var sectionId = $('#sections').val();
     var divisionId = $('#division').val();
     var groupId = $('#groupe').val();
     var classeId = $('#classe').val();

     getCategorie(sectionId, divisionId, groupId, classeId, $('.categorie'));

     });

     $('#categorie').change(function () {
     var sectionId = $('#sections').val();
     var divisionId = $('#division').val();
     var groupId = $('#groupe').val();
     var classeId = $('#classe').val();
     var categorieId = $('#categorie').val();

     getSousCategorie(sectionId, divisionId, groupId, classeId, categorieId, $('.sous-categorie'));

     });*/

    function getDivision(sectionId, division) {
        $.ajax({
            url: baseUrl + 'ajax/getDivision?id=' + sectionId + '&filter=false',
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
            url: baseUrl + 'ajax/getGroupe?sectionid=' + sectionId + '&divisionid=' + divisionId + '&filter=false',
            success: function (data) {
                for (var i = 0; i < data.length; i++) {
                    groupe.append("<option value='" + data[i].id + "'>" + data[i].libelle + "</option>");
                }
                $('.groupe-form').removeClass('hide');
            }
        });
    }

    function getClasse(sectionId, divisionId, groupeId, classe) {
        $.ajax({
            url: baseUrl + 'ajax/getClasse?sectionid=' + sectionId + '&divisionid=' + divisionId + '&groupeid=' + groupeId + '&filter=false',
            success: function (data) {
                for (var i = 0; i < data.length; i++) {
                    classe.append("<option value='" + data[i].id + "'>" + data[i].libelle + "</option>");
                }
                $('.classe-form').removeClass('hide');
            }
        });
    }

    /*function getCategorie(sectionId, divisionId, groupeId, classeId, categorie) {
     $.ajax({
     url: baseUrl + 'ajax/getCategorie?sectionid=' + sectionId + '&divisionid=' + divisionId + '&groupeid=' + groupeId + '&classeid=' + classeId + '&filter=false',
     success: function (data) {
     for (var i = 0; i < data.length; i++) {
     categorie.append("<option value='" + data[i].id + "'>" + data[i].libelle + "</option>");
     }
     $('.categorie-form').removeClass('hide');
     }
     });
     }

     function getSousCategorie(sectionId, divisionId, groupeId, classeId, categorieId, souscategorie) {
     $.ajax({
     url: baseUrl + 'ajax/getSousCategorie?sectionid=' + sectionId + '&divisionid=' + divisionId + '&groupeid=' + groupeId + '&classeid=' + classeId + '&categorieid=' + categorieId + '&filter=false',
     success: function (data) {
     for (var i = 0; i < data.length; i++) {
     souscategorie.append("<option value='" + data[i].id + "'>" + data[i].libelle + "</option>");
     }
     $('.sous-categorie-form').removeClass('hide');
     }
     });
     }*/

    function resetRessources() {
        resetDivision();
        resetGroupe();
        resetClasse();
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

    function resetClasse() {
        $('#classe').empty();
        $('#classe').append('<option label="Classe" value="-1"></option>');
        $('#classe').parent().addClass('hide');
    }
});