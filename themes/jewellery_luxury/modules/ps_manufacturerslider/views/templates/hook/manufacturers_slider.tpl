{**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<section class="manufacture_image">
	<div class="linia_tytul">
		<div class="tytuly">
		{if $display_link_brand}<a class="manufacturers_link" href="{$page_link}" title="{l s='Top Manufacturers' d='Modules.Brandlist.Shop'}">{/if}
			{l s='Top Manufacturers' d='Modules.Brandlist.Shop'}
		{if $display_link_brand}</a>{/if}
		</div>
	</div>
  
	<div class="products">
    	{if $manufacturers}
		
			{assign var='sliderFor' value=6}
			{assign var='brandCount' value=count($manufacturers)}
	        {if $slider == 1 && $brandCount >= $sliderFor}
			
				<div class="manufacture-navigator">
				<a class="btn prev manufacture_prev"></a>
				<a class="btn next manufacture_next"></a>
				</div>
				
				<ul id="manufacture-slider" class="manufacturers product_list">
			{else}
			{/if}
	 
			{foreach from=$manufacturers item=brand name=brand_list}
				<li class="{if $slider == 1 && $brandCount >= $sliderFor}item{else}product_item col-xs-12 col-sm-4 col-md-3{/if}">
					<div class="brand-image">
					<a href="{$link->getmanufacturerLink($brand['id_manufacturer'], $brand['link_rewrite'])}" title="{$brand.name}">
						<img src="{$link->getManufacturerImageLink($brand['id_manufacturer'])}" alt="{$brand.name}" />
					</a>
					</div>
					
					{if $brandname}
						<span class="h3 product-title" itemprop="name">
							<a class="product-name" itemprop="url"  href="{$link->getmanufacturerLink($brand['id_manufacturer'], $brand['link_rewrite'])}" title="{$brand.name}">{$brand.name}</a>
						</span>
					{/if}
				</li>
			{/foreach}
			</ul>
		{else}
			<p>{l s='Add at the moment manufacturer logo' d='Modules.Brandlist.Shop'}</p>
		{/if}
	</div>
</section>
