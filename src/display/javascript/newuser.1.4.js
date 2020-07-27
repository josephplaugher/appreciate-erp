$(document).ready(function() {

//get the plan details
$('#plandata').on('change',function(){
    data = $(this).val();
    var dataset = data.split(':');
    var plan = dataset[0];
    var price = dataset[1];
    $('#plan').val(plan);
    $('#price').val(price);
    calculatePrice();
    });
    
///search for the discount details    
$(document).on('focusout','#promo', function(){
    var val = $(this).val();
    var plan = $('#plandata').val();
    var regPrice = $('#price').val();
    data = ({promo: val, plan: plan,price: regPrice});
    promo = new ajaxCall('ajax','getPromo','json');
    var promiseObject = promo.execQuery(data);
    promiseObject.then(function(value){
        getPromoData(value);
    });
});

//retrieve the discount details
function getPromoData(data) {
    if(typeof data.error !== 'undefined'){
        $('#usernotify').css({"color":"#00b300","font-weight":"bold",'display': 'block'});
        $('#usernotify').html(data.error);
    }else{
    if(typeof data.success !== 'undefined'){
        $('#usernotify').css({'display': 'none'});
        $('#discount').val(data.percentoff + ' percent');
        var regPrice = parseFloat($('#price').val());
        var discount = regPrice * parseFloat(data.percentoff/100);
        var newPrice = regPrice - discount;
        $('#price').val(parseFloat(newPrice));
        calculatePrice();
    }}
}


//calculate the final price
function calculatePrice(){
    var percentoff = ($('#discount').val());
    var price = ($('#price').val()/100);
    if(percentoff >0 ) {
    var amountoff = ((percentoff/100) * price);
    var newtotal = (price - amountoff);
    var msg = (percentoff +'% Discount Applied. Your new total is '+ newtotal + '.');
    $('#usernotify').html(msg);
    }
 }

$('#createaccount').on('click', function(){
    window.location = "newuser.php?class=userview&method=createaccount";
});

$('#resetpassword').on('click', function(){
    window.location = "ui.php?class=userview&method=recovery";
});

$('#login').on('click', function(){
    window.location = "ui.php?class=userview&method=login";
});

$('#home').on('click', function(){
    window.location = "ui.php?class=home&method=main";
});

function goto(location){
    if(location === 'index'){
    window.location = "index.php";
    }
    if(location === 'profile'){
    window.location = "ui.php?class=home&method=main";
    }
}

});
