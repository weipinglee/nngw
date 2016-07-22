
<div class="footer">

	<div class="line">
   <!--  <img class="hezuo_img" src="images/hezuo.jpg" width="97" height="22" alt="合作企业"> -->

		<div class="img_box">
			{foreach from=$frdlink item=item key=key name=frdlink}
				{if $key eq 11}{php}break;{/php}{/if}
			<div class="hztb"><a href="{$item.url}" target="_blank"><img src="./{$item.img}"></a></div>
			{/foreach}
		</div>
    </div>
    <div class="contact">
    	<div class="erm_img"><img src="images/{$site.weixin}"></div>
    	<div class="lxfs">
    		<p>联系电话</p>
			<p>{$site.tel}</p>
			<p>咨询QQ</p>
			{foreach from=$site.qq item=item}
			<p>{$item}</p>
			{/foreach}
			<p><a class="lxwm">联系我们</a></p>
    	</div>
    </div>

    <div class="bottom">

	<hr>
	<p>Copyright&copy;2000-2015&nbsp;{$site.site_name} 版权所有&nbsp;&nbsp;&nbsp;{$site.icp}</p>
	<p>服务电话：{$site.tel}&nbsp;地址：{$site.site_address}</p>

	</p>
    </div>  
</div>

