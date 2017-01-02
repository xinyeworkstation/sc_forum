$(function(){
	var tabs=0;
	$('.loginTab>ul>li>a').click(function(){
		var j=$('.loginTab>ul>li>a').index($(this));
		if(tabs==j)return false;tabs=j;
		 $('.loginTab>ul>li>a').attr('class','');
		$('.TabForm').css('display','none').eq(tabs).css('display','block');
		$(this).attr('class','selected');
	});


var ok1=false;
var ok2=false;
var ok3=false;
var ok4=false;
var ok5=false;
var ok6=false;
var ok7=false;
var ok8=false;
//验证注册用户名
	$('#user1').focus(function(){
		$(this).next().text('用户名为6~16位').removeClass('state1').addClass('state2');
	}).blur(function(){
		if ($(this).val().length>=6&&$(this).val().length<=16&&$(this).val()!=' ') {
			 $(this).next().text('').removeClass('state1').addClass('state4');
			ok1=true;
		}else{
			$(this).next().text('用户名为6~16位').removeClass('state4').addClass('state3');
			ok1=false;
		}
	});
//验证注册邮箱
	 $('#email').focus(function(){
		$(this).next().text('请输入正确的emial').removeClass('state1').addClass('state2');
	}).blur(function(){
		if($(this).val().search(/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/)==-1){
			$(this).next().text('请输入正确的emial').removeClass('state4').addClass('state3');
			ok2=false;
              }else{                  
             $(this).next().text('').removeClass('state1').addClass('state4');
            ok2=true;
              }	
	 });
//验证密码
	$('#pass').focus(function(){
		$(this).next().text('请输入密码').removeClass('state1').addClass('state2');
	 });
	$('#pass').blur(function(){
		if ($(this).val().length>=6&&$(this).val().length<=20&&$(this).val()!=' ') {
	 		 $(this).next().text('').removeClass('state1').addClass('state4');
			ok3=true;
		}else{
			$(this).next().text('密码不少于6位').removeClass('state4').addClass('state3');
			ok4=false;
	 	}	
	 });
//确认注册密码
	 $('#msgpass').focus(function(){
		$(this).next().text('请确认密码').removeClass('state1').addClass('state2');
	 }).blur(function(){
	 	if($(this).val().length>=6&&$(this).val().length<=20&&$(this).val()!=' '&& $(this).val() == $('#msgpass').val()){
			  $(this).next().text('').removeClass('state1').addClass('state4');
			 ok4=true;
	 	}else{
			$(this).next().text('密码不一致').removeClass('state4').addClass('state3');
	 		ok4=false;
	 	}	
	});	
//注册QQ
	 $('#QQ').focus(function(){
	 	$(this).next().text('请输入正确的QQ').removeClass('state1').addClass('state2');
	   }).blur(function(){
	   	if ($(this).val().search(/^[1-9][0-9]{4,}$/)==-1){
	   		$(this).next().text('请输入正确的QQ号码').removeClass('state4').addClass('state3');
	  		ok5=false;
	  	}else{
	  		$(this).next().text('').removeClass('state1').addClass('state4');
	   		ok5=true;
	   	}
	   });	

//验证登录用户名
	$('#user2').focus(function(){
		$(this).next().text('用户名为6~16位').removeClass('state1').addClass('state2');
	}).blur(function(){
		if ($(this).val().length>=6&&$(this).val().length<=16&&$(this).val()!=' ') {
			 $(this).next().text('').removeClass('state1').addClass('state4');
			ok6=true;
		}else{
			$(this).next().text('用户名为6~16位').removeClass('state4').addClass('state3');
			ok6=false;
		}
	}); 
//验证登录密码
	 $('#pass2').focus(function(){
		$(this).next().text('请输入密码').removeClass('state1').addClass('state2');
	 });
	$('#pass2').blur(function(){
		if ($(this).val().length>=6&&$(this).val().length<=20&&$(this).val()!=' ') {
	 		 $(this).next().text('').removeClass('state1').addClass('state4');
			ok7=true;
		}else{
			$(this).next().text('密码少于6位').removeClass('state4').addClass('state3');
			ok7=false;
	 	}	
	 });
//登录验证码
	 $('#Code').focus(function(){
		$(this).next().text('').removeClass('state1').addClass('state2');
	}).blur(function(){
		if ($(this).val()!=' ') {
			 $(this).next().text('').removeClass('state1').addClass('state4');
			ok8=true;
		}else{
			$(this).next().text('请输入验证码').removeClass('state4').addClass('state3');
			ok8=false;
		}
	});

// 注册
	$('#Button1').click(function(){
		 if(ok1&&ok2&&ok3&&ok4&&ok5){
		 		$.ajax({
		 			cache:true,
					type:'POST',
					url:'',
					dataType:'json',
					data:$('#form1').serialize(),
					success:function(data,status){
						if (data==1) {
							alert('success');
						}else{
							alert('fiale')
						}
						
					}
				});
			//alert($('#form1').serialize());
		 }else{
		 	return false;
		 }
	
	});

// 登录
	$('#Button2').click(function(){
		 if(ok6&&ok7){
		 		$.ajax({
		 			cache:true,
					type:'GET',
					url:'',
					dataType:'json',
					data:$('#form2').serialize(),
					success:function(data,status){
						if (data==1) {
							alert('success');
						}else{
							alert('fiale')
						}
						
					}
				});
			//alert($('#form2').serialize());
		 }else{
			 return false;
		 }
	
	});




});


