<!-- {foreach from=$product_category item=cate} 一级分类 -->
<div class="tab1">
 <ul class="place">         <li {if $cate.cur} class="sort" {/if}>
    <a href="{$cate.url}">{$cate.cat_name}
    </a>
   </li>
   <li class="by"><img src="images/do.png" alt=""></li>
    <div class="clearfix"> </div>
</ul>
  <!-- {if $cate.child} -->
<div class="single-bottom">        <!-- {foreach from=$cate.child item=child} 二级分类 -->    
    <a {if $child.cur} class="cur"{/if} href="{$child.url}">
    <p>{$child.cat_name}</p>
    </a>
  
     <!-- {/foreach} -->
   </div>
  <!-- {/if} -->
</div>
<!-- {/foreach} -->