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
	//验证找回密码邮箱
	$('#email2').focus(function(){
		$(this).next().text('请输入正确的emial').removeClass('state1').addClass('state2');
	}).blur(function(){
		if($(this).val().search(/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/)==-1){
			$(this).next().text('请输入正确的emial').removeClass('state4').addClass('state3');
			ok9=false;
		}else{
			$(this).next().text('').removeClass('state1').addClass('state4');
			ok9=true;
		}
	});
//登录找回密码验证码
	$('#Code1').focus(function(){
		$(this).next().text('').removeClass('state1').addClass('state2');
	}).blur(function(){
		if ($(this).val()!=' ') {
			$(this).next().text('').removeClass('state1').addClass('state4');
			ok10=true;
		}else{
			$(this).next().text('请输入验证码').removeClass('state4').addClass('state3');
			ok10=false;
		}
	});
// 注册
	$('#Button1').click(function(){
		 if(ok1&&ok2&&ok3&&ok4&&ok5){
		 		$.ajax({
		 			async:false,
		 			cache:true,
					type:'POST',
					url:'/sc_forum/index.php/login/register',
					dataType:'json',
					data:$('#form1').serialize(),
					success:function(response,data,status){
						if(response.info=='YES'){
							window.location.href ='/sc_forum/index.php/Person/PersonMessage'
						}else{
							$('#star').html(response.info);
						}
					}
				});
			 return false;
			//alert($('#form1').serialize());
		 }else{
		 	return false;
		 }
	
	});

// 登录

	$('#Button2').click(function(){
		 if(ok6&&ok7){
		 		$.ajax({
					async:false,
		 			cache:true,
					type:'POST',
					url:'/sc_forum/index.php/Login/login',
					dataType:'json',
					data:$('#form2').serialize(),
					success:function(response,data,status){
						if(response.info=='YES'){
							window.location.href ='/sc_forum/index.php/Person/PersonMessage'
						}else{
							$('#star1').html(response.info);
						}
					}
				});
			  return false;
			//alert($('#form2').serialize());
		 }else{
			 return false;
		 }
	
	});

//找回密码

	$('#Button3').click(function(){
		if(ok9){
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
		}else{
			return false;
		}
	});




// 登录注册弹窗

	/*$(window).click(function(){
		$.ajax({
			type:'POST',
			//url:$("#sub").attr('link'),
			url:'/sc_forum/index.php/Login/_initialize',
			success:function(response,status,xhr){
				if(response.info=='YES') {
					alert('你是成功登陆的！');
				}else{
					showpage();

				}



			}
		});
	})


function showpage(){
	$('body').append("<div id='mask'></div>");
			$('#mask').addClass('mask').fadeIn('slow');
			$('#dialog').fadeIn('slow');
			center('dialog');
}
	if (!$_SESSOIN['headimg']) {
		$(window).click();
	}
$(window).click=showpage();




$('.BuyClose').on('click',function(){
			$('#dialog').fadeOut("fast");
			$('#mask').css({display:'none'});
		})




function center(id){
			var h=$(window).height();
			var w=$(window).width();
			var fh=$('#'+id).height();
			var fw=$('#'+id).width();
			$('#'+id).css({
				'top':(h-fh)/2,
				'left':(w-fw)/2
			});
		}
		$(window).resize(function(){
			center('dialog');
			function center(id){
			h=$(window).height();
			w=$(window).width();
			fh=$('#'+id).height();
			fw=$('#'+id).width();
			$('#'+id).css({
				'top':(h-fh)/2,
				'left':(w-fw)/2
			});
		}
		});
	*/

});


