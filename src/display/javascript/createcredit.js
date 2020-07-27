$(document).ready(function() {
        
$(document).on('change','#customerid', function(){
    
    var data = ({"customerid": $(this).val() });
    cred = new ajaxCall('ajax','getInvoices','json');
    var credProm = cred.execQuery(data);
    credProm.then(function(value){
        $('#invnum').html(value);
    });
});

});