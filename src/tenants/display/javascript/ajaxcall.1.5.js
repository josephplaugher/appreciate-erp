function ajaxCall(clss,method,datatype) {
    this.clss = clss;
    this.method = method;
    this.datatype = datatype;
}

ajaxCall.prototype = {  
    constructor: ajaxCall,
    execQuery:function (input) {
        var self = this;
        var obj = new Promise(function (resolve, reject) {
        $.ajax({
            url     : '../../model/sscont.php?class=' + self.clss + '&method=' + self.method,
            type    : 'post',
            dataType: self.datatype,
            data    : input,
            context : this,
            success : function(data) {
                    resolve(data);
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
                    var error = new Error(msg);
                    reject(error);
                    }
            }); //end of ajax request
                
        }); //end of Promise
        return obj;
    }
};