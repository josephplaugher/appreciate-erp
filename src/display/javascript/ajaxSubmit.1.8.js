$(document).ready(function() { 
        
    $('#email, #promo,#loginemail').focusout(function(){
        var lower = $(this).val().toLowerCase();
        $(this).val(lower);
        });
        
    $('#state').focusout(function(){
    var upper = $(this).val().toUpperCase();
    $(this).val(upper);
    });
        
    $("form[name='ajaxform']").validate({
    debug: true,      
     // Specify validation rules 
    rules: { 
        //setting prior to specific rules
        ignore: ":hidden",
            // The key name on the left side is the name attribute 
            // of an input field. Validation rules are defined 
            // on the right side 

            //account settings
                    plan: {required: true},
                    fname: {required: true},
                    lname: {required: true},
                    industry: {required: true},
                    loginemail: {required: true, email: true}, // Specify that email should be validated by the built-in "email" rule 
                    password: {required: true, minlength: 5}, 
                    passwordmatch: { equalTo: '#password'},
                    company_name: {required: true},
            //person and address rules
                    position: {required: true},
                    /*street: {required: true},
                    city: {required: true},*/
                    state: {minlength: 2, maxlength: 2},
                    zip: {digits: true, minlength: 5, maxlength: 5},
                    salary: {digits: true},
            //date rules	
                    startdate: {required: true, date: true},
                    enddate: {required: true, date: true},
                    date: {required: true, date: true},
                    duedate: {required: true, date: true},
            //miscellaneous
                    description: {required: true},
                    amount: {required: true, number: true},
            //for invoices, credits, and journal enteries 
                    'price[]': {required: false, number: true},
                    'item[]': {required: false},
                    'quant[]': {required: false, number: true},
            //journal entries        
                    'acct[]': {required: false},
                    'dorc[]': {required: false},
                    'amt[]': {required: false, number: true},
            //end journal entries        
                    terms: {required: true},
                    customer: {required: true},
                    customerid: {required: true},
                    revacct: {required: true},
            //accounts payable
                    invnum: {required: true},
                    supplier: {required: true},
            //deposits and withdrawals section  
                    docno: {required: false},
                    supplier: {required: true},
                    supplierid: {required: true},
                    transtype: {required: true},
            //banking section
                    bank: {required: true},
                    stmtenddate: {required: true, date:true},
                    stmtendbal: {required:true, number:true},
            //accounts section
                    acctno: {required:true, number:true},
                    acctname: {required:true},
                    accttype: {required:true},
                    cor: {required:true},
                    cor_acctno: {required:true},
                    cor_type: {required:true}
    },

      //Specify validation error messages 
    messages: { 
            //account settings
                    plan: {required: "Please choose a plan"},
                    fname: {required: "First name is required"},
                    lname: {required: "Last name is required"},
                    industry: {required: "Industry is required"},
                    loginemail: {required: "Email required", email: "Please enter a valid email address"}, 
                    password: {required: "Password is required", minlength: "Your password must be at least 5 characters long"}, 
                    passwordmatch: {equalTo: "Your passwords don't match"},
                    company_name: {required: "Company name required"},
            //person and address rules		
                    position: {required: "Position requiered"}, 
                    /*street: {required: "Street required"}, 	
                    city: {required: "City required"}, 	*/	
                    state: {minlength: "Please use a two character state" , maxlength: "Please use a two character state"}, 	
                    zip: {digits: "Only numbers allowed for zipcode", minlength: "zip must be 5 characters long", maxlength: "zip must be 5 characters long"}, 	
                    salary: {digits: "Only numbers and a period allow for salary"},
            //date rules
                    startdate: {required: "Start Date Required", date:"Required format: yyyy-mm-dd"},
                    enddate: {required: "End Date Required", date:"Required format: yyyy-mm-dd"},
                    date: {required: "Date Required", date:"yyyy-mm-dd"},
                    duedate: {required: "Date Required", date:"yyyy-mm-dd"},
            //miscellaneous
                    description: {required: "Description required"},
                    amount: {required: "Please enter an amount", number: "Enter dollars and cents only for amount"},
            // for invoice generation 
                    'price[]': {number: "Dollar values only for price"},
                    'quant[]': {number: "Only whole numbers or fractions for quantity"},
            //for journal entries
                    'amt[]': {number: "Only dollar amounts allowed"},
                    terms: {required: "Terms Required"},
                    customer: {required: "Customer Name Required"},
                    customerid: {required: "Customer ID Required"},
            //accounts payable
                    invnum: {required: "Invoice number required"},
            //accounting section
                    acctname: {required: "Account Name required"},
                    acctno: {required: "Account Number required"},
                    accttype: {required: "Account Type required"},
                    cor: {required: "Related account name required"},
                    cor_acctno: {required: "Related account number required"},
                    docno: {required: "Document number required"},
                    supplier: {required: "Supplier name required"},
                    supplierid: {required: "Supplier ID required"},
                    transtype: {required: "Transaction type required"},
            //banking section
                    stmtenddate: {required: "Statement end date required", date: "yyyy-mm-dd"},
                    stmtendbal: {required: "Statement end balance required", number:"Dollars and cents only"}
                    
    }, 

    errorElement : 'div', 
    errorLabelContainer: '#usernotify'
});

$(document).on('click', '#ajaxsubmit', function () { 
    validateForm();
});

$(document).on('keypress', '.textinput', function (e) { 
    if(e.which === 13) {
        validateForm();
    }
});

function validateForm(){   
    if($("form[name='ajaxform']").valid()) {
    var formid = $("form[name='ajaxform']").attr('id');
    var action = $("form[name='ajaxform']").attr('action');
    var formname = 'ajaxform';
    var formdata = $("form[name='ajaxform']").serialize();
    //alert('action = ' + action + ', formname = '+ formname);
    submitForm(formid,action,formname,formdata);
    }
};

function submitForm(formid,action,formname,formdata){ 
 $("#ajaxsubmit").attr("disabled", true);    
   // alert('go form: action = ' + action + ', formname = '+ formname);
 if(formid === 'Bank_Ledger' || formid === 'Bank_Reconciliation' || formid === 'Property_Search' || formid === 'Trial_Balance'){
   var dt = 'html';
   }else{
   var dt = 'json';
   }
 
 $.ajax({
        cache   : false,
        url     : action,
        type    : 'post',
        dataType: dt,
        data    : formdata,
        success : function(data) {
                if(typeof data.error !== 'undefined'){
                    $('#usernotify').css({"color":"red","font-weight":"bold",'display': 'block'});
                    $('#usernotify').html(data.error);
                    }
                if(typeof data.success !== 'undefined'){
                    $('#usernotify').css({"color":"#00b300","font-weight":"bold",'display': 'block'});
                    $('#usernotify').html(data.success);
                        if($('input[type="button"][value="Save"]').length) {
                           $('input[type="button"][value="Save"]').attr({'value':'Edit','id':'edit'});
                        }
                        $('.welcomeback').css({"display":"none"});//for user signup
                        $('#cancel').attr({'value':'Close','id':'close','name':'close'});
                    }
                if(typeof data.page !== 'undefined'){
                    $('#form').html(data.page);
                    }
                if(formid === 'Bank_Ledger' || formid === 'Bank_Reconciliation' || formid === 'Property_Search' || formid === 'Trial_Balance'){
                    $('#scroll').html(data);
                    $('#scroll').css({"color":"black","font-weight":"normal",'display': 'block'});
                    
                    }    
                if(typeof data.goto !== 'undefined'){
                    //alert(data.goto);
                    window.location = data.goto;
                    }    
                if(typeof data.stmt !== 'undefined'){
                    opencsv();
                    }    
                
                    
                    //lastly, reenable the submit button
                    $("#ajaxsubmit").prop('disabled', false); 
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
              $("#ajaxsubmit").prop('disabled', false);
                }
        });
        $("#ajaxsubmit").prop('disabled', false);
 } //submit form
    

});