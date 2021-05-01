$(document).ready(function(){
    
function loadItems(formd){
     let midContent = $("div#midContent");
      let formdata = $(formd).serializeArray();
      
      $.ajax({ //start ajax function
        beforeSend: function(){
          midContent.html("<div class='text-center'><i class='spinner-border'></div>");
        },
        type:"POST",
        url:"post_display_search.php",
        cache:false,
        data: $.param(formdata),
        success: function(data){
            setTimeout(function(){
                midContent.html(data);             
            },1000);
                 
          }
      });
 }

$("form#FormSearch").on("click","button#searchbtn", function(){
    loadItems(this);   
});


});