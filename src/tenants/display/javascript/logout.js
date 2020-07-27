$(document).ready(function() {
	$('#logout').on('click', function() {
                var logout = '1';
		$.ajax({
		url: '../ajax/logout.php',
		type: 'POST',
		dataType: 'text',
		data: {logout: logout},
		success: function(data) {
			//alert(data);//this is for debugging. Uncomment to see what's wrong in the url file.
			},
		error: function(e) {
			alert(e.message);
			console.log(e.message);
			}
		});
	});
});