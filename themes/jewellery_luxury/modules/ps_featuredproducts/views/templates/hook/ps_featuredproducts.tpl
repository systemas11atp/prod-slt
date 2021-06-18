<section class="featured-products clearfix">
  <div class="linia_tytul">
  <div class="tytuly">
    {l s='Popular Products' d='Shop.Theme.Catalog'}
  </div></div>
  <div class="products">
  
   <ul class="special_grid product_list grid row gridcount">{foreach from=$products item="product"}
	<li class="product_item col-xs-12 col-sm-6 col-md-4 col-lg-3">{include file="catalog/_partials/miniatures/product.tpl" product=$product}</li>
    {/foreach}
  </ul>
  
  </div>
  <a class="all-product-link pull-xs-left pull-md-right h4" href="{$allProductsLink}">
    {l s='All products' d='Shop.Theme.Catalog'}<i class="material-icons">&#xE315;</i>
  </a>
</section>