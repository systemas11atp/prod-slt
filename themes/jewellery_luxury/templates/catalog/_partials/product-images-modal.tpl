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
<div class="modal fade js-product-images-modal" id="product-modal">
  <div class="modal-dialog" role="document">
       <button type="button" class="closed" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span>
       </button>
    <div class="modal-content">
      <div class="modal-body">
		
		{assign var=imagesCount value=$product.images|count}
		{assign var='sliderFor' value=1}
	
	<div class="mask {if $imagesCount >= $sliderFor}additional_slider{else}additional_grid{/if}">	
		{if $imagesCount >= $sliderFor}
			<ul id="carousel_imageproduct" class="product_list thumbnail-carousel">
		{else}
			<ul class="carousel_imageproduct product_list grid row gridcount">
		{/if}
                {foreach from=$product.images item=image}
				<li class="{if $imagesCount >= $sliderFor}item{else}product_item col-xs-12 col-sm-6 col-md-4 col-lg-3{/if}">
                <div class="item">
				<figure>
                    <img class="js-modal-product-cover-1 product-cover-modal" src="{$image.large.url}" title="{$image.legend}" itemprop="image">
					<figure>
                  </div>
				 </li> 
                {/foreach}
		</ul>

			 <div class="SliderNavigation">
				<a class="btn prev zdjecia_prev">&nbsp;</a>
				<a class="btn next zdjecia_next">&nbsp;</a>
			</div>
    </div>
    </div>

      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->