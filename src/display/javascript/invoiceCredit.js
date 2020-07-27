$(document).ready(function() {

$('#unallocated').attr("readonly","true");

$(document).on('focusout','#customerid,#customer', function(){
    if(($('#customerid').val() !== null) && ($('#customer').val() !== null) ) {
        var data = {};    
        data.id = $('#customerid').val();
        inv = new ajaxCall('ajaxHTML','getInvoices','html');
        var prom = inv.execQuery(data);
        prom.then(function(value){
            $('#multipleDiv').html(value);
        });
    }
});

$(document).on('change','#invoices', function(){
    var sum = 0.00;
    var val;
        $.each($("#invoices option:selected"), function(){   
            val = $(this).val().split(':');
            sum = sum + parseFloat(val[1]);
        });
    if(val[0] === 'dna') {
        $('#selectedtotal').attr("placeholder", "Enter amount");
    }else{
        $('#selectedtotal').val(sum).toFixed(2);
    }
}); 

$(document).on('click','#lineitems', function(){
    var invnum = $('#invnum').val();
    var creditnum = $('#creditnum').val();
    if(typeof invnum !== 'undefined'){
        var id = invnum;
    }
    if(typeof creditnum !== 'undefined'){
        var id = creditnum;
    }
    window.open("../display/ui.php?class=reportlist&method=invoicelines&id=" + id, "_blank", "toolbar=no, scrollbars=yes, resizable=yes, width=700, height=500");
});    


});