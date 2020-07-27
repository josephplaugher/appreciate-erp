$(document).ready(function() {
    $('.mainlinks, .sidenavlinks').click(function() {
        var button = $(this).attr('id');
        if(button === 'props'){
            window.open("display/epicenter.php", "_blank", "toolbar=no, scrollbars=yes, resizable=yes");

        }else{
            $.ajax({
            url: 'sublinks/' + button + '.php',
            type: 'POST',
            dataType: 'text',
            data: {button: button},
            success: function(data) {
                    $('#maincontent').html(data);
                    //alert(data);//this is for debugging. Uncomment to see what's wrong in the url file.
                    },
            error: function(e) {
                    //alert(e.message);
                    console.log(e.message);
                    }
            });
        }
    });
});

$(document).ready(function() {
        $('.propsublinks').hide();
	$('.propsublinks').click(function() {
            
		$(this).attr('id').show();
                
                    });
});


function openNav() {
    document.getElementById("mySidenav").style.width = "90%";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

$(document).on('click','#logout', function(){
lo = new ajaxCall('user','logOut','json');
    var logout = lo.execQuery(null);
    logout.then(function(value){
        window.location = value.goto;
    });
});
