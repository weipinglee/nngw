
<div class="header-top">
	<nav>
  		<ul class="pagination">
			<li {if $pager.page eq 1}class="disabled" {/if} >
				<a {if $pager.page gt 1} href="{$pager.first}" {/if} aria-label="Previous">
					<span aria-hidden="true">«</span>
				</a>
			</li>
			<!-- {foreach from=$pageBar name=pageBar item=bar} --> 
			<li {if $bar.code eq $pager.page } class="active" {/if} >
				<a href="{$bar.link}">{$bar.code} <span class="sr-only">(current)</span>
				</a>
			</li>
			<!--{/foreach}-->
			<li {if $pager.page eq $pager.page_count}class="disabled" {/if} >
				<a {if $pager.page lt $pager.page_count} href="{$pager.last}" {/if} aria-label="Next">
					<span aria-hidden="true">»</span>
				</a>
			</li>
	 	</ul>
	</nav>
</div>