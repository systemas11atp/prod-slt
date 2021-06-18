<div id="_desktop_cart">
<div class="cart_top">
  <div class="blockcart cart-preview {if $cart.products_count > 0}active{else}inactive{/if}" data-refresh-url="{$refresh_url}">
    <div class="header">
      {if $cart.products_count > 0}
        <a rel="nofollow" href="{$cart_url}">
      {/if}
         <div class="cart-icons"></div>
        <span class="cart-products-count">({$cart.products_count})</span>
      {if $cart.products_count > 0}
        </a>
      {/if}
	  
	  				<div id="koszykajax">

					<ul class="cart_products">

					{if $cart.products_count == 0}
						<li>{l s='There are no more items in your cart' d='Shop.Theme.Checkout'}</li>
					{/if}

					{foreach from=$cart.products item=product}
						<li>
							{include 'module:ps_shoppingcart/ps_shoppingcart-product-line.tpl' product=$product}
						</li>
					{/foreach}
					</ul>

					<ul class="cart-podsumowanie">
						<li>
							<span class="text">{$cart.subtotals.shipping.label}</span>
							<span class="value">{$cart.subtotals.shipping.value}</span>
							<span class="clearfix"></span>
						</li>

						<li>
							<span class="text">{$cart.totals.total.label}</span>
							<span class="value">{$cart.totals.total.value}</span>
							<span class="clearfix"></span>
						</li>

					</ul>

					<div class="cart-przyciski">
					<a class="btn btn-primary viewcart koszyk" rel="nofollow" href="{$cart_url}">{l s='Cart' d='Shop.Theme.Checkout'}</a>
						<a class="btn btn-primary viewcart" href="{$urls.pages.order}">{l s='Proceed to checkout' d='Shop.Theme.Actions'}</a>
					</div>

				</div>
	     </div>
    </div>
  </div>
</div>
