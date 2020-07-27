$(document).ready(function (){
  $('#close').bind('click', function() {
      var pageURL = window.location.href;
      window.close(pageURL);  
  });
  
  $(document).on('click', '#edit', function () {
      //$('#close').undbind();
      $(':input').removeAttr('readonly');
      $("form[name='ajaxform'] :input:not([type='button'])").css({"background-color":"white"});
      $('#edit').attr({'value':'Save','id':'ajaxsubmit'});
      $('#close').attr({'value':'Cancel','id':'cancel','name':'cancel'});
  });
  
  $(document).on('click', '#cancel', function () {
      $(':input').attr('readonly', true);
      $("form[name='ajaxform'] :input:not([type='button'])").css({"background-color":"#D3D3D3"});
      $('#save').attr({'value':'Edit','id':'edit','name':'edit'});
  });
  
  $(document).on('click', '#clear', function () {
      $("form[name='ajaxform']").reset(); 
  });
  
});
