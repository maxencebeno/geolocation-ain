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
    minLength: 3,
    delay: 300
});


$(".autocomplete-search-cp").autocomplete({
    source: function (request, response) {
        var objData = {};
        var url = $(this.element).attr('data-url');
        objData = {cp: request.term};

        $.ajax({
            url: url,
            dataType: "json",
            data: objData,
            type: 'POST',
            success: function (data) {
                //Ajout de reponse dans le cache
                response($.map(data, function (item) {
                    return {
                        label: item.CodePostal,
                        value: function () {
                            return item.CodePostal;
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