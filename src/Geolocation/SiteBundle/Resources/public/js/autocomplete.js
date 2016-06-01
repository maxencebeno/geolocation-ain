$(".autocomplete-search-city").autocomplete({
    source: function (request, response) {
        var objData = {};
        var url = $(this.element).attr('data-url');
        objData = {ville: request.term};

        $.ajax({
            url: url,
            dataType: "json",
            data: objData,
            type: 'POST',
            success: function (data) {
                //Ajout de reponse dans le cache
                response($.map(data, function (item) {
                    return {
                        label: item.Ville,
                        value: function () {
                            return item.Ville;
                        }
                    };
                }));
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    },
    change: function (event, ui) {
        if (ui.item == null) {
            $('#search-cp').val("");
        } else {
            searchCpfromCity(ui.item.label);
        }

    },
    select: function (event, ui) {
        searchCpfromCity(ui.item.label);
    },
    minLength: 2,
    delay: 300
});

$(".autocomplete-search-code-naf").autocomplete({
    source: function (request, response) {
        var objData = {};
        var url = $(this.element).attr('data-url');
        objData = {codeNaf: request.term};

        $.ajax({
            url: url,
            dataType: "json",
            data: objData,
            type: 'POST',
            success: function (data) {
                //Ajout de reponse dans le cache
                response($.map(data, function (item) {
                    return {
                        label: item.Codes.libelle,
                        value: item.Codes.libelle,
                        id: item.Codes.id
                    };
                }));
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    },
    select: function (event, ui) {
        $('#search-code-naf-input').val(ui.item.id);
    },
    minLength: 2,
    delay: 300
});


$(".autocomplete-search-ressources").autocomplete({
    source: function (request, response) {
        var objData = {};
        var url = $(this.element).attr('data-url');
        objData = {ressources: request.term};

        $.ajax({
            url: url,
            dataType: "json",
            data: objData,
            type: 'POST',
            success: function (data) {
                //Ajout de reponse dans le cache
                response($.map(data, function (item) {
                    return {
                        label: item.Ressources,
                        value: function () {
                            return item.Ressources;
                        }
                    };
                }));
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    },
    minLength: 3,
    delay: 300
});

$(".autocomplete-search-entreprise").autocomplete({
    source: function (request, response) {
        var objData = {};
        var url = $(this.element).attr('data-url');
        objData = {entreprise: request.term};

        $.ajax({
            url: url,
            dataType: "json",
            data: objData,
            type: 'POST',
            success: function (data) {
                //Ajout de reponse dans le cache
                response($.map(data, function (item) {
                    return {
                        label: item.Entreprise,
                        value: function () {
                            return item.Entreprise;
                        }
                    };
                }));
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    },
    minLength: 3,
    delay: 300
});

$('#search-code-naf-form').submit(function (e) {
    e.preventDefault();
    e.stopPropagation();

    var idCpf = $('#search-code-naf-input').val();

    if (idCpf !== '') {
        $.ajax({
            url: baseUrl + 'json/markers/' + idCpf,
            method: "GET",
            success: function (data) {
                clearMarker();
                initMarker(data);
            }
        });
    }

    return false;
});

$('#send-form').click(function () {
    if ($('#search-code-naf-input').val() !== '') {
        $('#search-code-naf-form').submit();
    }
});

function searchCpfromCity(city) {
    $.ajax({
        url: baseUrl + 'json/getcodepostalfromcity',
        dataType: "json",
        data: {
            city: city
        },
        type: 'POST',
        success: function (data) {
            var codePostal = "";
            if (data[0].CodePostal.includes("-")) {
                codePostal = data[0].CodePostal.substr(0, 2) + "000";
            } else {
                codePostal = data[0].CodePostal;
            }
            $('#search-cp').val(codePostal);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}