$(function() {
    $("#slider-range").slider({
        range: true,
        min: 0,
        max: 500,
        values: [75, 300],
        slide: function(event, ui) {
            $("#distance").val(ui.values[ 0 ] + "km - " + ui.values[ 1 ] + "km");
        }
    });
    $("#distance").val($("#slider-range").slider("values", 0) +
            "km - " + $("#slider-range").slider("values", 1)+ "km");
});