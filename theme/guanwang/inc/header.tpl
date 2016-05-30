{literal}
<script> 
   (function() {
     if (! 
     /*@cc_on!@*/
     0) return;
     var e = "abbr, article, aside, audio, canvas, datalist, details, dialog, eventsource, figure, footer, header, hgroup, mark, menu, meter, nav, output, progress, section, time, video".split(', ');
     var i= e.length;
     while (i--){
         document.createElement(e[i])
     } 
})() 
</script>
{/literal}
<!--header-->	
	<header class="header">
		<div class="main">
			<div class="header_left">
				<iframe name="weather_inc" allowTransparency="true" src="http://i.tianqi.com/index.php?c=code&id=52&icon=1&num=3" width="280" height="25" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" id="weather"></iframe>
			</div>
			<div class="header_right" style="font-family: 'Lato', sans-serif;font-size:14px;">
				<span><a href="javascript:void(0);"  onclick="SetHome(this,'{$site.root_url}');" style="color:#333;line-height:30px;">设为首页</a></span>
				&nbsp;|&nbsp;
				<span><a href="javascript:void(0);"onclick="AddFavorite('{$site.root_url}', '{$site.site_name}')"style="color:#333;line-height:30px;">{$lang.add_favorite}</a></span>
			</div>
		</div>
	</header>
<script src="images/responsiveslides.min.js"></script>
{literal}
<script type="text/javascript">
function SetHome(obj,url){
    try{
        obj.style.behavior='url(#default#homepage)';
       obj.setHomePage(url);
   }catch(e){
       if(window.netscape){
          try{
              netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
         }catch(e){
              alert("抱歉，此操作被浏览器拒绝！\n\n请在浏览器地址栏输入“about:config”并回车然后将[signed.applets.codebase_principal_support]设置为'true'");
          }
       }else{
        alert("抱歉，您所使用的浏览器无法完成此操作。\n\n您需要手动将【"+url+"】设置为首页。");
       }
  }
}
</script>
{/literal}
{literal}
<script type="text/javascript">
function AddFavorite(title, url) {
  try {
      window.external.addFavorite(url, title);
  }
catch (e) {
     try {
       window.sidebar.addPanel(title, url, "");
    }
     catch (e) {
         alert("抱歉，您所使用的浏览器无法完成此操作。\n\n加入收藏失败，请使用Ctrl+D进行添加");
     }
  }
}
</script>
{/literal}
{literal}
<script>  
    $(function () {
      $("#slider").responsiveSlides({
      	auto: true,
      	nav: true,
      	speed: 500,
        namespace: "callbacks",
        pager: false,
      });
    });
  </script>
  {/literal}
<div class="header-top">
	 <div class="header-bottom">			
				<div class="logo">
					<img src="images/logo.png">					
				</div>

			 <!---->
		 <div id="MT_nav">
			 <ul>
				 <li class="MT_item">
					 <a href="{$site.root_url}" hidefocus="true" class="MT_bg {if $index.cur}hover{/if}">{$lang.home}</a>
				 </li>
				 <!-- {foreach from=$nav_middle_list name=nav_middle_list item=nav} -->
				 <li class="MT_item">
					 <a href="{$nav.url}" hidefocus="true" class="MT_bg {if $nav.cur} hover {/if}" {if $nav.target} target="_blank"{/if}>{$nav.nav_name}</a>
					 <!-- {if $nav.child} -->
					 <div>
					 <!-- {foreach from=$nav.child item=child} -->

						 <a href="{$child.url}" hidefocus="true" {if $child.child}{/if}>{$child.nav_name}</a>
					 <!-- {/foreach} -->
					 </div>
					 <!-- {/if} -->
				 </li>
				 <!-- {/foreach} -->
			 </ul>
		 </div>

			 <!---->
			 <div class="cart box_1">
				<form action="{$site.root_url}" method="get" class="search" id="search" name="search">
					<div class="kuan">
						<input name="s" type="text" class="keyword" title="{$lang.search_product_cue}" autocomplete="off" maxlength="128" placeholder="{if $keyword}{$keyword|escape}{else}{$lang.search_product}{/if}" onclick="formClick(this,'{$lang.search_product}')"/></div><!--搜索框-->
						<input name="module" value="product" type="hidden">
					<div class="an"> <input type="submit" class="btnSearch" value=""></div><!--搜索按钮-->
				</form>
				{literal}
				<style type="text/css">
				.search{border:1px solid #ccc; height: 62px;}
				.kuan ,.an{display: inline-block;float: left;}
				.kuan input{ width:100%; height:60px; border:none ; float:left; padding-left:10px; color: #ccc;}
				.an input{ max-width:87px; min-width: 60px; height:60px;  background:url(theme/guanwang/images/search.png) no-repeat;border:none;  float:left;}
				</style>
				{/literal}
			 	<div class="clearfix"> </div>
			 </div>
			 <div class="clearfix"> </div>
			 <!---->			 
			 </div>
			<div class="clearfix"> </div>
</div>
<!--{if $controller=='page'}-->
	<!--{if $num==1}-->
	<div class="top_bg" style="margin:0 auto;"><div class="main_top"><img src="images/jianj.png"></div></div>
	<!--{/if}-->
	<!--{if $num==8}-->
	<div class="top_bg" style="margin:0 auto;"><div class="main_top"><img src="images/jl.png"></div></div>
	<!--{/if}-->
	<!--{if $num==10}-->
	<div class="top_bg" style="margin:0 auto;"><div class="main_top"><img src="images/lx.png"></div></div>
	<!--{/if}-->
<!--{/if}-->

<!--{if $controller=='product'}-->
	<div class="top_bg" style="margin:0 auto;"><div class="main_top"><img src="images/cp.jpg"></div></div>

<!--{/if}-->
<!--{if $controller=='zhaopin'}-->
	<div class="top_bg" style="margin:0 auto;"><div class="main_top"><img src="images/zp.png"></div></div>

<!--{/if}-->
<!---->	
<div class="slider">
	  <div class="callbacks_container">
	     <ul class="rslides" id="slider">

	     	<!-- {foreach from=$show_list name=show item=show} -->
	         <li>
				<div class="banner1" >
					<img src="{$show.show_img}" width="100%" style='max-height:640px;'/>
				</div>
				 <!-- <div class="word">
					<img src="../images/word.png" style="width:100%;height:100%;"/>
				  </div>-->
	         </li>
	         
	        <!-- {/foreach} -->
	      </ul>
	  </div>
  </div>
<!---->
{literal}<script src="images/bootstrap.js"> </script>{/literal}


