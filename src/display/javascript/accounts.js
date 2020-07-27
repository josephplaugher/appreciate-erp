$(document).ready(function() {
            
$(document).on('click','#inactive', function(){
    var data = {};
    data.acctno = $('#acctno').val();
    data.status = $('#status').val();
    acct = new ajaxCall('ajax','changeAccountStatus','json');
    var promiseObject = acct.execQuery(data);
    promiseObject.then(function(value){
        if(value.error){
            $('#usernofity').val(value.error);
            $('#usernotify').css({"color":"red","font-weight":"bold",'display': 'block'});
            $('#usernotify').html(value.error);
        }else if(value.status){
            $('#status').val(value.status);
        }
    });
});

$(document).on('change', 'select[name=accttype]', function() {
    var data = {};
    data.type = $(this).val();
    data.nametoappend = $('#acctname').val();
    acct = new ajaxCall('ajaxHTML','acctModifier','html');
    var promiseObject = acct.execQuery(data);
    promiseObject.then(function(value){
        $('#modifier').html(value).css({'display': 'block','box-sizing': 'border-box'});
    });
});

$(document).on('click', '#getTrans', function() {
    var data = ({"startdate": $('#startdate').val(), "enddate": $('#enddate').val(), "acctno": $('#acctno').val() });
    trans = new ajaxCall('getAccountTrans','accountData','html');
    var promiseObject = trans.execQuery(data);
    promiseObject.then(function(value){
        $('#scroll').html(value).css({"color":"black","font-weight":"normal",'display': 'block'});
    });
});


});
