<a href="#"><img class="shizhong" src="images/jianjie_1.png" width="35" height="35" alt="时钟"></a>
<h2>工作时间</h2>
<hr>
<p>周一至周五：9:00-18:00</p>
<p>周六至周日：休息</p>
<hr style="border: 1px dashed #ccc; width: 100%; size: 1px;" />  
<!-- <a href="#"><img class="qqkf" src="images/jianjie_2.png" width="155" height="35" alt="QQ客服"></a> 
 -->
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