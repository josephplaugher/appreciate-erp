$(document).ready(function() {
    
$('#stmtendbal').focusout(function(){
    var dollar = parseFloat($(this).val()).toFixed(2);
        if(isNan(dollar)){ }else{
        $(this).val(dollar);
        }
    });
        
$(document).on('click','#ajaxsubmit', function(){
    var data = ({"bankno": $('#bankno').val() });
    rec = new ajaxCall('ajax','getBankRec','json');
    var promiseObject = rec.execQuery(data);
    promiseObject.then(function(value){
        $('[name="lastrecbal"]').val(value.bal);
        checkClearedAmount();
    });
});
    
$(document).on('change', '.transclear, #stmtendbal, #scroll', function() {
    checkClearedAmount();
});

function checkClearedAmount(){
if(!$('#scroll').is(':empty')) {
    var sum = parseFloat($('#lastrecbal').val());
    var stmtendbal = parseFloat($('#stmtendbal').val());
    var elem = $('.transclear').map(function() {
            if(this.checked) {
                var amt = this.value.split(':');
                return amt[1];
            }
        }).get();
        for (i=0; i< elem.length ;i++) {
            sum = sum + parseFloat(elem[i]);
            }
       
    $('[name="clearedbal"]').val((sum).toFixed(2));
    $('[name="balDiff"]').val((stmtendbal - sum).toFixed(2));
    }
}
          
 
$(document).on('change', '.transclear', function() {
    if($(this).is(":checked")) {
        var data = ({"transinfo": $(this).val()});
        clear = new ajaxCall('ajax','markTransCleared','json');
        var clrpromiseObject = clear.execQuery(data);
        clrpromiseObject.then(function(value){
            //console.log('returnval '+ value.success);
        });
    }else{
        var data = ({"transinfo": $(this).val()});
        clear = new ajaxCall('ajax','markTransUncleared','json');
        var unclrpromiseObject = clear.execQuery(data);
        unclrpromiseObject.then(function(value){
            //console.log('returnval '+ value.success);
        });
    }
});//end clearing function


$(document).on('click', '#reconcile', function() {
    if($('[name="balDiff"]').val() !== '0.00'){
        if(confirm('The total of cleared transactions does not match the statement \n\
ending balance entered. If you continue, future statements may not \n\
reconcile correctly. Are you sure you want to reconcile now?')=== true) {
            setRec();
        }
    }else if($('[name="balDiff"]').val() === '0.00'){
            setRec();
    }
});
  
         
function setRec() {
    var data = {};
    data.clearedbal = $('#clearedbal').val();
    data.bankno = $('#bankno').val();
    data.bankname = $('#bankname').val();
    data.stmtenddate = $('#stmtenddate').val();
    data.stmtendbal = $('#stmtendbal').val();
    idArray = getIdArray();
    data.transids = idArray; 
    rec = new ajaxCall('ajax','reconcileBank','json');
    var promiseObject = rec.execQuery(data);
    promiseObject.then(function(value){
        if(value.error){
            $('#usernofity').val(value.error);
            $('#usernotify').css({"color":"red","font-weight":"bold",'display': 'block'});
            $('#usernotify').html(value.error);
        }else if(value.success){
            $('#usernotify').css({"color":"#00b300","font-weight":"bold",'display': 'block'});
                    $('#usernotify').html(value.success);
        }
        console.log(value.error);
        $('#scroll').empty();
    });//end then
}//end setrec function

function getIdArray(){
    ids = $('.transclear').map(function() {
        if(this.checked) {
            var id = this.value.split(':');
            return id[0];
            }
         }).get();
        var returnVal = [];
        for (i=0; i< ids.length ;i++) {
            returnVal.push(ids[i]);
            }
    return returnVal;
}
         
});//end document