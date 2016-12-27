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
});