$(document).ready(function () {

    $('#datetimepicker1').datetimepicker({
        locale: "fr",
        format: "d/m/Y"
    });

    $('#sections').change(function () {
        var sectionId = $(this).val();

        getDivision(sectionId, $('.division'));

    });

    function getDivision(sectionId, division) {
        $.ajax({
            url: '/app_dev.php/ajax/getDivision?id=' + sectionId,
            success: function (data) {
                for (var i = 0; i < data.length; i++) {
                    division.append("<option value='" + data[i].id + "'>" + data[i].libelle + "</option>");
                }
                $('.division-form').removeClass('hide');
            }
        });
    }
});