$(function() {
	// 悬浮窗口
	$(".yb_conct").hover(function() {
		$(".yb_bar ul li").css("width", "180px");
		/*$(".yb_conct").css("left", "0px");*/
		$(".yb_bar .yb_ercode").css('height', '200px');
	}, function() {
		$(".yb_bar ul li").css("width", "54px");
		/*$(".yb_conct").css("left", "-127px");*/
		$(".yb_bar .yb_ercode").css('height', '53px');
	});
	// 返回顶部
	$(".yb_top").click(function() {
		$("html,body").animate({
			'scrollTop': '0px'
		}, 300)
	});
});