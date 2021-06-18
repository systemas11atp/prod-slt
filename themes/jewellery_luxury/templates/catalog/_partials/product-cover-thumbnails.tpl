{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<div class="images-container">
  {block name='product_cover'}
    <div class="product-cover">
      <img class="js-qv-product-cover" src="{$product.cover.bySize.large_default.url}" alt="{$product.cover.legend}" title="{$product.cover.legend}" style="width:100%;" itemprop="image">
      <div class="layer hidden-sm-down" data-toggle="modal" data-target="#product-modal">
	   <i class="material-icons zoom-in">&#xE8FF;</i>
		{hook h='displayHeader'}
      </div>
    </div>
  {/block}

{block name='product_images'}

	{assign var='sliderFor' value=2}
	
	{assign var='thumbCount' value=count($product.images)}
	<div class="js-qv-mask mask {if $thumbCount >= $sliderFor}additional_slider{else}additional_grid{/if}">		
		{if $thumbCount >= $sliderFor}
			<ul id="thumbnailCarousel" class="product_list thumbnail-carousel">
		{else}
			<ul class="thumb_grid product_list grid row gridcount">
		{/if}

		{foreach from=$product.images item=image}
         <li class="thumb-container {if $thumbCount >= $sliderFor}item{else}product_item col-xs-12 col-sm-6 col-md-4 col-lg-3{/if}">
            <img
              class="thumb js-thumb {if $image.id_image == $product.cover.id_image} selected {/if}"
              data-image-medium-src="{$image.bySize.medium_default.url}"
              data-image-large-src="{$image.bySize.large_default.url}"
              src="{$image.bySize.home_default.url}"
              alt="{$image.legend}"
              title="{$image.legend}"
              width="100"
              itemprop="image"
            >
          </li>
        {/foreach}
      </ul>
	  
	  {if $thumbCount >= $sliderFor}
		<div class="SliderNavigation">
			<a class="btn prev slider_thumb_prev">&nbsp;</a>
			<a class="btn next slider_thumb_next">&nbsp;</a>
		</div>
	  {/if}
    </div>
  {/block}
</div>
{if isset($HOOK_ELEVATEZOOM) && !empty($HOOK_ELEVATEZOOM) && $page.page_name=='product'}
    {$HOOK_ELEVATEZOOM nofilter}
{/if}
{hook h='displayAfterProductThumbs'}
