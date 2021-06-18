{**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2016 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
 
<div class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
  <div class="thumbnail-container">
    {block name='product_thumbnail'}
      <a href="{$product.url}" class="thumbnail product-thumbnail">
        <img
          src = "{$product.cover.bySize.home_default.url}"
          alt = "{$product.cover.legend}"
          data-full-size-image-url = "{$product.cover.large.url}"
        >
		{hook h="displayCzHoverImage" id_product=$product.id_product home='home_default' large='large_default'}
      </a>
    {/block}
	
	<a href="#" class="quick-view" data-link-action="quickview">
		<i class="material-icons search">&#xE417;</i> {l s='Quick view' d='Shop.Theme.Actions'}
	</a>
		
	{block name='product_flags'}
	  <ul class="product-flags">
		{foreach from=$product.flags item=flag}
		  <li class="{$flag.type}">{$flag.label}</li>
		{/foreach}
	  </ul>
	{/block}
	
 </div>

    <div class="product-description">
      {block name='product_name'}
        <h3 class="h3 product-title" itemprop="name"><a href="{$product.url}">{$product.name|truncate:30:'...'}</a></h3 >
      {/block}

      {block name='product_price_and_shipping'}
        {if $product.show_price}
          <div class="product-price-and-shipping">
            {if $product.has_discount}
              {hook h='displayProductPriceBlock' product=$product type="old_price"}

              <span class="regular-price">{$product.regular_price}</span>
              {if $product.discount_type === 'percentage'}
                <span class="discount-percentage">{$product.discount_percentage}</span>
              {/if}
            {/if}

            {hook h='displayProductPriceBlock' product=$product type="before_price"}

            <span itemprop="price" class="price">{$product.price}</span>

            {hook h='displayProductPriceBlock' product=$product type='unit_price'}

            {hook h='displayProductPriceBlock' product=$product type='weight'}
          </div>
        {/if}
      {/block}
    	
		{block name='product_description_short'}
		  <div class="product-detail" itemprop="description">{$product.description_short nofilter}</div>
		{/block}
		
		<div class="product-actions">
			<a href="{$product.url}" class="btn btn-primary add-to-cart">
				{l s='View Detail' d='Shop.Theme.Actions'}
			</a>
		</div>
		<div class="highlighted-informations{if !$product.main_variants} no-variants{/if} hidden-sm-down">
	
		  {block name='product_variants'}
			{if $product.main_variants}
			  {include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
			{/if}
		  {/block}
		  
		  {block name='product_availability'}
            <span class="product-availability">
              {if $product.show_availability && $product.availability_message}
                {if $product.availability == 'available'}
                  <span class="product-available">
				  <i class="material-icons">&#xE5CA;</i>
                {elseif $product.availability == 'last_remaining_items'}
                  <span class="product-last-items">
				  <i class="material-icons">&#xE002;</i>
                {else}
				  <span class="product-unavailable">
                  <i class="material-icons">&#xE14B;</i>
                {/if}
                {$product.availability_message}
				</span>
              {/if}
            </span>
          {/block}
		</div>
		
	</div>
</div>
