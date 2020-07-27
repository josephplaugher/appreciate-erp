
function opencsv() {
    window.open("../model/stmtcsv.php", "toolbar=no, scrollbars=yes, resizable=yes, width=100, height=100");
}

$(document).ready(function() {
    $('#stmt').change(function(){
       var val = $(this).val();
       if(val === 'bal') { 
           $('#startdate').hide();
           $('#startdate').prop({"disabled":"true"});
          // $('#startdate').css({"background-color":"#D3D3D3"}).val('');
       }else{
           $('#startdate').show();
           $('#startdate').prop({"disabled":"false"});
           //$('#startdate').css({"background-color":"white"});
       }
    });
    
});
    