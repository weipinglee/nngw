$(function() {
    $(".an_dj").click(function() {
       $(this).parents(".toggles").find(".cont_txt").toggle();
       if(!$(this).parents(".toggles").find(".cont_txt").is(":hidden")){
          $(this).removeClass("icon-angle-down");
           $(this).addClass("icon-angle-up");
       }else{
           $(this).removeClass("icon-angle-up");
           $(this).addClass("icon-angle-down");
       }
    
    });
});
//选择内容的移入移出事件
/*$(function(){


	 $(".workplace li a").find(".hover_s").on('mouseenter',function(){	
        $(this).addClass("a");
    }).on('mouseleave',function(){
		 
        $(this).removeClass("a");
    })
  })
*/