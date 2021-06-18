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

 <div class="user-info dropdown js-dropdown">
   <span class="user-info-title expand-more _gray-darker" data-toggle="dropdown"><div class="login-icons"></div><div class="select-icons-arrow"></div></span>
   <ul class="dropdown-menu">
     {if $logged}
  <!--
	  <li>
      <a class="account dropdown-item" href="{$my_account_url}" title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}"rel="nofollow">
        <span class="">{$customerName}</span>
      </a>
	  </li>
  -->
  <li>
    <a class="account dropdown-item" id="identity-link" href="{$urls.pages.identity}">
      <span class="link-item">
        <i class="material-icons">&#xE853;</i>
        {l s='Information' d='Shop.Theme.Customeraccount'}
      </span>
    </a>
  </li>
  {if $customer.addresses|count}
  <li>
    <a class="account dropdown-item" id="addresses-link" href="{$urls.pages.addresses}">
      <span class="link-item">
        <i class="material-icons">&#xE56A;</i>
        {l s='Addresses' d='Shop.Theme.Customeraccount'}
      </span>
    </a>
  </li>
  {else}
  <li>
    <a class="account dropdown-item" id="address-link" href="{$urls.pages.address}">
      <span class="link-item">
        <i class="material-icons">&#xE567;</i>
        {l s='Add first address' d='Shop.Theme.Customeraccount'}
      </span>
    </a>
  </li>
  {/if}
  {if !$configuration.is_catalog}
  <li>
    <a class="account dropdown-item" id="history-link" href="{$urls.pages.history}">
      <span class="link-item">
        <i class="material-icons">&#xE916;</i>
        {l s='Order history and details' d='Shop.Theme.Customeraccount'}
      </span>
    </a>
  </li>
  {/if}

  {if !$configuration.is_catalog}
  <li>
    <a class="account dropdown-item" id="order-slips-link" href="{$urls.pages.order_slip}">
      <span class="link-item">
        <i class="material-icons">&#xE8B0;</i>
        {l s='Credit slips' d='Shop.Theme.Customeraccount'}
      </span>
    </a>
  </li>
  {/if}

  {if $configuration.voucher_enabled && !$configuration.is_catalog}
  <li>
    <a class="account dropdown-item" id="discounts-link" href="{$urls.pages.discount}">
      <span class="link-item">
        <i class="material-icons">&#xE54E;</i>
        {l s='Vouchers' d='Shop.Theme.Customeraccount'}
      </span>
    </a>
  </li>
  {/if}

  {if $configuration.return_enabled && !$configuration.is_catalog}
  <li>
    <a class="account dropdown-item" id="returns-link" href="{$urls.pages.order_follow}">
      <span class="link-item">
        <i class="material-icons">&#xE860;</i>
        {l s='Merchandise returns' d='Shop.Theme.Customeraccount'}
      </span>
    </a>
  </li>
  {/if}
  <li>
    {block name='display_customer_account'}
        {hook h='displayCustomerAccountDropdown'}
      {/block}
  </li>
  <li>
   <a class="logout dropdown-item" href="{$logout_url}" rel="nofollow">
    {l s='Sign out' d='Shop.Theme.Actions'}
  </a>
</li>
{else}
<li>
 <a class="dropdown-item" href="{$my_account_url}" title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}" rel="nofollow">
  <span>{l s='Sign in' d='Shop.Theme.Actions'}</span></a>
</li>
{/if}
</ul>
</div>