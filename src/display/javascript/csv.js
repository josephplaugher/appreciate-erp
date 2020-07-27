
$(document).ready(function() {
	$('#csv').on('click', function() {
	opencsv();
	});
});

function opencsv() {
window.open("../csv/stmtcsv.php", "_blank", "toolbar=no, scrollbars=yes,");
}
