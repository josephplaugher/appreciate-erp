$(document).ready(function() {
    $('.mainlinks').click(function() {
    var data = {};
    data.field = $(this).attr('id');
    data.id = ({"id": $('#propheader').text()});
        if(data.field === 'find') {
            $('#data').hide();
            $('#search').show();
        }else{
            searchAlert(data);
        }
     });
     
function searchAlert(data) {
    if(!$('#propheader').is(':empty')) {
        switchView(data);
    }else{
        function backToWhite(){
            $('#prop-id').css({"background-color": "white"});
        }
        $('#prop-id').focus().css({"background-color": "green"});
        setTimeout(backToWhite, 200);
    }
}

function switchView(data) {
    getView = new ajaxCall('properties',data.field,'html');
    var view = getView.execQuery(data.id);
    view.then(function(value){
        $('#search').hide();
        $('#data').html(value).css({"display":"block"});   
    });
}
    
    $("#scroll").on("click",".row", function() {
        var id = $(this).attr('id');
        var param = id.split(':');
        var page = param[0];
        var id = param[1];
            $('#propheader').html(id);
    });    

});