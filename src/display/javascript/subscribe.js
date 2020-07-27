// Created by Larry Ullman, www.larryullman.com, @LarryUllman
// Posted as part of the series "Processing Payments with Stripe"
// http://www.larryullman.com/series/processing-payments-with-stripe/
function reportError(msg) {
    // Show the error in the form:
    $('#usernotify').css({"color":"red","font-weight":"bold",'display': 'block'});
    $('#usernotify').html(msg);
    // re-enable the submit button:
    $('#subscribe').prop('disabled', false);
}

// Assumes jQuery is loaded!
// Watch for the document to be ready:
$(document).ready(function() {
// Watch for a form submission:
$(document).on('click', '#subscribe', function() {
    var error = false;
    // disable the submit button to prevent repeated clicks:
    $('#subscribe').attr("disabled", "disabled");
    // Get the values:
    var ccNum = $('#card-number').val(), 
    cvcNum = $('#card-cvc').val(), 
    expMonth = $('#card-expiry-month').val(), 
    expYear = $('#card-expiry-year').val();

    // Validate the number:
    if (!Stripe.card.validateCardNumber(ccNum)) {
            error = true;
            reportError('The credit card number appears to be invalid.');
    }

    // Validate the CVC:
    if (!Stripe.card.validateCVC(cvcNum)) {
            error = true;
            reportError('The CVC number appears to be invalid.');
    }

    // Validate the expiration:
    if (!Stripe.card.validateExpiry(expMonth, expYear)) {
            error = true;
            reportError('The expiration date appears to be invalid.');
    }
    
    
    
    // Check for errors:
    if (!error) {
        // Get the Stripe token:
        Stripe.card.createToken({
            number: ccNum,
            cvc: cvcNum,
            exp_month: expMonth,
            exp_year: expYear
        }, stripeResponseHandler);
    }
    // Prevent the form from submitting:
    return false;
}); // Form submission

}); // Document ready.

// Function handles the Stripe response:
function stripeResponseHandler(status, response) {
    if (response.error) {
        reportError(response.error.message);
    } else { // No errors, submit the form:
    var f = $("#Payment");
    // Token contains id, last4, and card type:
    var token = response['id'];
    // Insert the token into the form so it gets submitted to the server
    f.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
    // Submit the form:
    var formid = "Payment"; 
    var action = $("form[name='ajaxform']").attr('action');
    var formname = 'ajaxform';
    var formdata = $("form[name='ajaxform']").serialize();
    $.ajax({
        url     : action,
        type    : 'post',
        dataType: 'json',
        data    : formdata,
        success : function(data) {
            if(typeof data.error !== 'undefined'){
                $('#usernotify').css({"color":"red","font-weight":"bold",'display': 'block'});
                $('#usernotify').html(data.error);
                }
            if(typeof data.success !== 'undefined'){
                $('#usernotify').css({"color":"#00b300","font-weight":"bold",'display': 'block'});
                $('#usernotify').html(data.success);
                }
            if(typeof data.goto !== 'undefined'){
                //alert(data.goto);
                window.location = data.goto;
                }     
            $("#subscribe").attr("disabled", false);
        },
        error   : function(jqXHR, exception) {
              if (jqXHR.status === 0) { 
              msg = 'Not connect.\n Verify Network.'; } 
              else if (jqXHR.status == 404) { 
              msg = 'Requested page not found. [404]'; } 
              else if (jqXHR.status == 500) { 
              msg = 'Internal Server Error [500].'; } 
              else if (exception === 'parsererror') { 
              msg = 'Requested JSON parse failed.'; } 
              else if (exception === 'timeout') { 
              msg = 'Time out error.'; } 
              else if (exception === 'abort') { 
              msg = 'Ajax request aborted.'; } 
              else { msg = 'Uncaught Error.\n' + jqXHR.responseText; }
              $('#usernotify').css({"color":"red","font-weight":"bold",'display': 'block'});
              $('#usernotify').html(msg);
              $("#subscribe").attr("disabled", false);
                }
            });
    }   
} // End of stripeResponseHandler() function.
