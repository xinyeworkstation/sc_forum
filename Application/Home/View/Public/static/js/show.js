<<<<<<< HEAD
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
=======
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
>>>>>>> 944688e41070c68288cf6388614af1a38219df43
});