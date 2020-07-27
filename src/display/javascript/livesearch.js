$(document).ready(function() {

$(document).on('focus','.textinput', function(){
    var id = $(this).attr('id').split("_")[0];
    if(!(id === 'opt')) {
       $('.options').hide();//hide result options if user clicks away
    }
});
//On pressing a key in input field, this function will be called.
$(document).on('keyup','.account,.accountno,#acctno,#acctname,#bankno,#bankname,\n\
#customer,#customerid,#supplier,#supplierid,#prop-id,#prop-ein,#prop-entityname,#prop-shortname,\n\
#prop-type,#prop-street,#prop-city,#prop-state,#prop-zip', function() {
   var data = {};
   data.id = $(this).attr("id");
   data.val = $(this).val();
   var optid = data.id + '_opt'; //set the target element id

   if (data.val === '') { //Validating, if "name" is empty.
   $('#' + optid).html('').show();
   }else {//If input is not empty.
    //alert('input:' + data.id + 'data:'+ data.val);
        ls = new ajaxCall('livesearch','search','html');
        var lsprom = ls.execQuery(data);
        lsprom.then(function(value){
            $('#' + optid).html(value).show().css({"position":"absolute","z-index":"+1","cursor":"pointer","color":"black","background-color":"white","border-radius": "5px"});   
        }).then(function(){
                $(document).on('click', '.options', function(event) {
                var item =  $(event.target).text(); 
                var clickedid = $(this).attr('id');
                var targetid = clickedid.split('_'); //remove the '_opt' suffice to get the target id
                $('#' + targetid).val(item);//place chosed result into the acctname input
                $('.options').hide();//hide result options after click
                autoFill(clickedid,item);
                });
            });    
           
   }//if
});//keyup

        
function autoFill(name,value) {
    var data = {};
    var newid = name.split('_'); 
    data.id = newid[0];
    //alert('new id' + data.id);
    data.val = value;
    af = new ajaxCall('autofill','fill','json');
    var afprom = af.execQuery(data);
    afprom.then(function(value){
        if(value.autofill === 'error'){
            $('#usernotify').css({"color":"red","font-weight":"bold",'display': 'block'});
            $('#usernotify').html(value.autofill);
        }else{
        //alert('autofill response' + value.autofill);
        switch(data.id) {
            //accounting    
            case 'acctname':
            case 'cor_acct':    
                $('#acctno').val(value.autofill);
                break;
            case 'acctno':
            case 'cor_acctno':    
                $('#acctname').val(value.autofill);
                break;
            case 'parent':
                $('#parentno').val(value.autofill);
                break;
            case 'parentno':
                $('#parent').val(value.autofill);
                break;
            //banking    
            case 'bankname':
                $('#bankno').val(value.autofill);
                break;
            case 'bankno':
                $('#bankname').val(value.autofill);
                break;    
            //customer    
            case 'customer':
                $('#customerid').val(value.autofill);
                break;   
            case 'customerid':
                $('#customer').val(value.autofill);
                break;  
            case 'supplier':
                $('#supplierid').val(value.autofill);
                break;   
            case 'supplierid':
                $('#supplier').val(value.autofill);
                break;
        }//switch
        }//else
    });//then
}//autofill


});//document