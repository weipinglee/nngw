
<div class="footer">
	<div class="line">
   <!--  <img class="hezuo_img" src="images/hezuo.jpg" width="97" height="22" alt="合作企业"> -->

		<div class="img_box">
			{foreach from=$frdlink item=item key=key name=frdlink}
				{if $key eq 11}{php}break;{/php}{/if}
			<div class="hztb"><a href="{$item.url}" target="_blank"><img src="./{$item.img}"></a></div>
			{/foreach}{*
			<div class="hztb"><a><img src="../images/icon7.png"></a></div>
			<div class="hztb"><a><img src="../images/icon8.png"></a></div>
			<div class="hztb"><a><img src="../images/icon7.png"></a></div>
			<div class="hztb"><a><img src="../images/icon6.png"></a></div>
			<div class="hztb"><a><img src="../images/icon7.png"></a></div>
			<div class="hztb"><a><img src="../images/icon6.png"></a></div>
			<div class="hztb"><a><img src="../images/icon7.png"></a></div>
			<div class="hztb"><a><img src="../images/icon6.png"></a></div>*}

		   <!-- <a href="http://bank.ecitic.com/" target="_blank" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image2','','theme/guanwang/images/hezuo_1.png',1)"><img src="images/hezuo_1.jpg" width="44" height="44" id="Image2">
		   </a><a href="#" target="_blank" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image3','','theme/guanwang/images/hezuo_2.png',1)"><img src="images/hezuo_2.jpg" width="44" height="44" id="Image3"></a><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image2','','theme/guanwang/images/hezuo_1.png',1)"> </a>
		   <a href="http://www.cebbank.com/" target="_blank" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image4','','theme/guanwang/images/hezuo_3.png',1)"><img src="images/hezuo_3.jpg" width="44" height="44" id="Image4"></a>
		   <a href="http://www.ccb.com/cn/home/index.html" target="_blank" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image5','','theme/guanwang/images/hezuo_4.png',1)"><img src="images/hezuo_4.jpg" width="44" height="44" id="Image5"></a>
		   <a href="http://www.icbc.com.cn/ICBC/html/guanggao/2011nian/0830ywkhghh/payment.html" onMouseOut="MM_swapImgRestore()" target="_blank" onMouseOver="MM_swapImage('Image6','','theme/guanwang/images/hezuo_5.png',1)"><img src="images/hezuo_5.jpg" width="44" height="44" id="Image6"></a>
		   <a href="http://www.bankcomm.com/BankCommSite/default.shtml"  target="_blank" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image7','','theme/guanwang/images/hezuo_6.png',1)"><img src="images/hezuo_6.jpg" width="44" height="44" id="Image7"></a>
		   <a href="#" onMouseOut="MM_swapImgRestore()"  target="_blank" onMouseOver="MM_swapImage('Image8','','theme/guanwang/images/hezuo_7.png',1)"><img src="images/hezuo_7.jpg" width="44" height="44" id="Image8"></a> -->
		   
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

