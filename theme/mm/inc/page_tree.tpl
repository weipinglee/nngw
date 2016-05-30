<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{literal}
<style>
	.jianjie_main .main_left .qqkf{ height:22px; width:100px;}
</style>
{/literal}
<div class="main_left" style="float:left; width:30%; height:auto; overflow:hidden;margin-top:20px;">
<a href="#"><img class="shizhong" src="images/jianjie_1.png" width="35" height="35" alt="时钟" style="width:20px; height:20px; float:left;"></a>
<h2 style="color:#2c2c2c; font-size:16px;line-height: 20px; ">工作时间</h2>
<hr style="size:1px; color:#c1c1c1;margin:15px 0;">
<p style="line-height:25px; color:#666666; font-size:14px;">周一至周五：9:00-18:00</p>
<p style="line-height:25px; color:#666666; font-size:14px;">周六至周日：休息</p>
<hr style="border: 1px dashed #ccc; width: 100%; size: 1px;" /> 
<ul class="service" style="margin-top:15px;">
   <li>
		<!-- {foreach from=$site.qq item=qq} -->
		  <!-- {if is_array($qq)} -->
		  <a href="http://wpa.qq.com/msgrd?v=3&uin={$qq.number}&site=qq&menu=yes" target="_blank">
				<img class="qqkf" src="images/jianjie_2.png" width="155" height="35" alt="QQ客服" style="height:22px; width:100px;">
		  </a>
		  <!-- {else} -->
		  <a href="http://wpa.qq.com/msgrd?v=3&uin={$qq}&site=qq&menu=yes" target="_blank">
			<img class="qqkf" src="images/jianjie_2.png" width="155" height="35" alt="QQ客服" style="height:22px; width:100px;">
		  </a>
		  <!-- {/if} -->
		  <!-- {/foreach} -->
   	</li>
</ul>
<p class="goTop"><a href="javascript:;" onfocus="this.blur();" class="goBtn"></a></p>
</div>