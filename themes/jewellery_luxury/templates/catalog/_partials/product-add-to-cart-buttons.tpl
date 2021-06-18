{**
 * 2007-2017-2017-2016 PrestaShop
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
 * @copyright 2007-2017-2017-2016 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<div class="product-add-to-cart-buttons">
  {if !$configuration.is_catalog}
    {block name='product_quantity'}
      <div class="product-quantity">
        <div class="qty hidden-xl-down">
          <input type="text" name="qty" id="quantity_wanted" value="1" class="input-group" min="1" />
        </div>
<div class="add" {if !$product.quantity}disabled{/if}>
						 {if $product.quantity}
                          <button class="add-to-cart-buttons" style="outline: none; text-decoration: none;" data-button-action="add-to-cart" title="{l s='Add to cart' d='Shop.Theme.Actions'}" type="submit">{l s='' d='Shop.Theme.Actions'} 
						</button>
                               {else}
						<div class="add add-to-cart-button-none">
                             <i class="fa fa-ban" title="{l s='Out of stock' d='Shop.Theme.Catalog'}"></i>
						 </div>
                   {/if}
	    </div>
      </div>
    {/block}
  {/if}
</div>
