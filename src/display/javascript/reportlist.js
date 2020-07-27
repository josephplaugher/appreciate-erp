$(document).ready(function() {
    $('.row').click(function() {
    var id = $(this).attr('id');
    var param = id.split(':');
    var page = param[0];
    var id = param[1];
	window.open("ui.php?class=dataview&method=" + page +"&id=" + id, "toolbar=no, scrollbars=yes, resizable=yes, height=500, width=500");
	});
});