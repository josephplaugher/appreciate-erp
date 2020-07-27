$(document).ready(function() {
    $(document).on('focusout', '.textinput', function(){
	var data = {};
	data.id = $(this).attr('name');
        data.val = $(this).val();
	if(name === 'acctname' || name === 'acctno' || name === 'customer' || name === 'customerid' || 
            name === 'bankname' || name === 'bankno' || name === 'parent' || name === 'parentno') {
            
            ls = new ajaxCall('livesearch','fill','json');
            var lsprom = ls.execQuery(data);
            lsprom.then(function(value){
            
                    switch(name) {
                    //accounting    
                    case 'acctname':
                    case 'cor_acct':    
                        $('#acctno').val(data.autofill);
                        break;
                    case 'acctno':
                    case 'cor_acctno':    
                        $('#acctname').val(data.autofill);
                        break;
                    case 'parent':
                        $('#parentno').val(data.autofill);
                        break;
                    case 'parentno':
                        $('#parent').val(data.autofill);
                        break;
                    //banking    
                    case 'bankname':
                        $('#bankno').val(data.autofill);
                        break;
                    case 'bankno':
                        $('#bankname').val(data.autofill);
                        break;    
                    //customer    
                    case 'customer':
                        $('#customerid').val(data.autofill);
                        break;   
                    case 'customerid':
                        $('#customer').val(data.autofill);
                        break;   
                    }
                })//then
                };//if
                });//focusout
                });//document ready