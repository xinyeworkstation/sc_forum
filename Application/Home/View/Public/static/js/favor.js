$(function(){
	//点爱心
	$('#favor').click(function(){
		var favorobj = document.getElementById("favor");
		var favortext = favorobj.innerHTML;
		var id = $(this).attr('_id');
		var flove = "love"+id;
		if (favortext == '加入收藏') {

			if(getCookie(flove) != id){
				//建立cookie,参数对应：键，值，过期时间以年为单位
				setCookie(flove,id,100);
				//保存最新推荐指数
				$.get($(this).attr('link'),{"id":id,"al":1},function(result){
					//自增
					if(result>0){
						favorobj.innerHTML = '取消收藏';
						var loveobj = document.getElementById("flove");
						var lovetext = loveobj.innerHTML;
						lovetext++;
						loveobj.innerHTML = lovetext;
					}
				});
			}else{
				console.log('exist')
			}
		} else {
			clearCookie(flove);//取消收藏时清除cookie
			$.get($(this).attr('link'),{"id":id,"al":2},function(result){
					//自增
					if(result>0){
						favorobj.innerHTML = '加入收藏';
						var loveobj = document.getElementById("flove");
						var lovetext = loveobj.innerHTML;
						lovetext--;
						loveobj.innerHTML = lovetext;
					}
			});
		}
		
	});
});


