$(document).ready(function(){
  $('input[type="button"]').click(function(){
      var inputValue = $(this).attr("value");
      $("." + inputValue).show();
  });
  
  $("#hide").click(function(){
  $("#red").hide();
});
});