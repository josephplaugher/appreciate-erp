$(document).ready(function() {
    $("#addline").click(function(event) {
    var div = $("#toclone");
    div.clone().appendTo("#lines");
});

$('#lineitems').click(function() {
    var invnum = $('#invnum').val();
    window.open("../display/report.php?view=invoicelines&param=" + invnum, "_blank", "toolbar=no, scrollbars=yes, resizable=yes");
});

$('#void').click(function() {
    var admin = "<?php echo $_SESSION['administrator']; ?>";
    if (admin !== '1') {
        alert('the admin value is' + admin);
        //alert('You do not have sufficient preveledges to void an invoice');
    } else {
        if (confirm("Are you sure you want to void this invoice?") == true) {
        var invnum = $('#invnum').val();
        var action = 'void';
        var data = ({action:action,invum:invnum});
            ajaxCall('../model/sscontroller.php?','POST','text',data,success);
            } 
    }
}); 

$('#amount,#docno,#bankname,#bankno').css({"background-color":"#FFFFF"});
/*
    $(document).on('change','#terms, #date', function() {
    var date = $('#date').val();
    var terms = $('#terms').val();
    var today = new Date();
    
    
    switch (terms) {
        case 'Net 30':
            var dd = today.getDate();
            var mm = today.getMonth()+2;
            var yyyy = today.getFullYear();
            if(dd<10) {dd = '0'+dd } 
            if(mm<10) {mm = '0'+mm } 
            due = yyyy + '-' + mm + '-' + dd;
            $('#date').val(due);
            break;
        case 'Net 15':
            var due = date.getDate() + 15;
            $('#date').val(due);
            break;    
        case 'Do Upon Receipt':
            $('#date').val(date);
            break;     
   }
});
*/


});