$(function(){

var oka=false;
var okb=false;
var okc=false;
//验证用户名
	$('#messageuser').focus(function(){
		$(this).next().text('用户名为6~16位').removeClass('state1').addClass('state2');
	}).blur(function(){
		if ($(this).val().length>=6&&$(this).val().length<=16&&$(this).val()!=' ') {
			 $(this).next().text('').removeClass('state1').addClass('state4');
			oka=true;
		}else{
			$(this).next().text('用户名为6~16位').removeClass('state4').addClass('state3');
			oka=false;
		}
	});

//验证邮箱
	 $('#messageemail').focus(function(){
		$(this).next().text('请输入正确的emial').removeClass('state1').addClass('state2');
	}).blur(function(){
		if($(this).val().search(/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/)==-1){
			$(this).next().text('请输入正确的emial').removeClass('state4').addClass('state3');
			okb=false;
              }else{                  
             $(this).next().text('').removeClass('state1').addClass('state4');
            okb=true;
              }	
	 });

//验证QQ
	 $('#messageqq').focus(function(){
	 	$(this).next().text('请输入正确的QQ').removeClass('state1').addClass('state2');
	   }).blur(function(){
	   	if ($(this).val().search(/^[1-9][0-9]{4,}$/)==-1){
	   		$(this).next().text('请输入正确的QQ号码').removeClass('state4').addClass('state3');
	  		okc=false;
	  	}else{
	  		$(this).next().text('').removeClass('state1').addClass('state4');
	   		okc=true;
	   	}
	   });	


$('#Messagebtn').click(function(){
			$.ajax({
				async:false,
				cache:true,
				type:'POST',
				url:'/sc_forum/Login/email',
				dataType:'json',
				data:$('#personMessageRight').serialize(),
				success:function(response,data,status){
					if(response.info=='YES'){
						alert('邮箱发送成功请注意查收！');
					}else{
						$('#star2').html(response.info);
					}
				}
			});
			return false;
		}
	


});