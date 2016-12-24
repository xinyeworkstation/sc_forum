$(document).ready(function() {     
    //On Click Event  
    $(".failed").click(function() {  
    	$(".productions").hide();
    	$(".proFContent").css("display","block");  
    	$(".pass").css("color","#000000");
    	$(".audit").css("color","#000000");
    	$(".failed").css("color","#ff6633");
        return false;  
    });  
    $(".audit").click(function() {  
    	$(".productions").hide();
    	$(".proFContent").css("display","block");  
    	$(".failed").css("color","#000000");
    	$(".pass").css("color","#000000");
    	$(".audit").css("color","#ff6633");
        return false;  
    }); 
  	$(".pass").click(function() {  
    	$(".productions").show();
    	$(".proFContent").css("display","none");
    	$(".audit").css("color","#000000");
    	$(".failed").css("color","#000000"); 
    	$(".pass").css("color","#ff6633"); 
        return false;  
    }); 
});  
  

