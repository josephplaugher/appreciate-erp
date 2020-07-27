$('#saverec').onclick(function() {
	var transid = $(this).attr('id');
	$.ajax({
  	url: 'cleartrans.php',
	type: 'POST',
	dataType: 'text',
	data: {transactionid: transid},
	success: function(data) {
		//alert('you cleared transaction ' + data)
		},
	error: function(e) {
		alert(e.message);
		console.log(e.message);
		}
	});
});
