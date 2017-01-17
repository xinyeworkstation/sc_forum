$(function(){
	$('#ShowPicSmall li a').click(function(){
		$('#ShowPicBig img').hide().attr({"src" : $(this).attr("href"), "title": $("img", this).attr("title") });
		// $('#ShowPicSmall li.current').removeClass('current');
		// $(this).parents("li").addClass('current');
		return false;
	});
	$('#ShowPicBig>img').load(function(){
		$('#ShowPicBig>img:hidden').show();
	});


// 弹窗
	//按钮的透明度
	$('#ShowWorksActionBuy').on('click',function(){
			$('body').append("<div id='mask'></div>");
			$('#mask').addClass('mask').fadeIn('slow');
			$('#BuyDialog').fadeIn('slow');
			center('BuyDialog');
		});
		$('#Buy-Btn1').hover(function(){
			$(this).stop().animate({
				opacity:'1'
			},600);
		},function(){
			$(this).stop().animate({
				opacity:'0.8'
			},1000);
		});
		//关闭
		$('#BuyClose').on('click',function(){
			$('#BuyDialog').fadeOut("fast");
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
			center('BuyDialog');
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
		})
		
	$('.Buydown').on('click',function(){
		$('body').append("<div id='mask'></div>");
		$('#mask').addClass('mask').fadeIn('slow');
		$('#BuyDialog').fadeIn('slow');
		center('BuyDialog');
	});


});		