$(document).ready(function() {
    $('.mainlinks, .sidenavlinks').click(function() {
    var data = {};
    data.id = $(this).attr('id');
       getView = new ajaxCall('tenantfunctions',data.id,'html');
        var view = getView.execQuery(data);
        view.then(function(value){
            $('#maincontent').html(value).css({"display": "block"});   
        });
    });
});
