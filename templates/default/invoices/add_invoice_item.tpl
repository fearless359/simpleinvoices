{*
/*
* Script: add_invoice_item.tpl
*    Add new item to an existing invoice 
*
* License:
*   GPL v2 or above
*
* Website:
*  https://simpleinvoices.group
*/
*}
{if isset($smarty.post.submit)}
  <meta http-equiv="refresh"
        content="2;URL=index.php?module=invoices&amp;view=details&amp;id={if isset($smarty.post.id)}{$smarty.post.id|urlencode}{/if}&amp;type={if isset($smarty.post.type)}{$smarty.post.type|urlencode}{/if}" />
  <br />
  <br />
  {$LANG.save_invoice_items_success};
  <br />
  <br />
{else}
  <div id="top"><h3>{$LANG.add_invoice_item}</h3></div>
  <div id="gmail_loading" class="gmailLoader" style="float:right; display: none;">
    <img src="images/common/gmail-loader.gif" alt="{$LANG.loading} ..." />
    {$LANG.loading}
  </div>
  <hr />
  <form name="add_invoice_item" action="index.php?module=invoices&amp;view=add_invoice_item" method="post">
    <table>
      <tr>
        <td class="details_screen">{$LANG.quantity}</td>
        <td><input type="text" id="quantity1" name="quantity1" size="5" /></td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.product}</td>
        <td>
          <input type="text" name="description" />
          {if !isset($products) }
          <p><em>{$LANG.no_products}</em></p>
          {else}
          <select name="product1"
                  onchange="invoice_product_change_price($(this).val(), 1, jQuery('#quantity1').val() );" >
            <option value=""></option>
            {foreach from=$products item=product}
              <option {if $product.id == $defaults.product} selected {/if} value="{if isset($product.id)}{$product.id|htmlsafe}{/if}">
                {$product.description|htmlsafe}
              </option>
            {/foreach}
          </select>
          {/if}                                        
        </td>
      </tr>
      <tr>
        <td class="details_screen">{$LANG.unit_price}</td>
        <td>
          <input id="unit_price1" name="unit_price1" size="7" value="{$invoiceItem.unit_price|siLocal_number:2}" />
        </td>
      </tr>
      {if $type == 3}
      <tr>
        <td class="details_screen" colspan="2" >{$LANG.description}</td>
      </tr>
      <tr>
        <td colspan="2"><textarea class="editor" name="description" rows="3" cols="80"></textarea></td>
      </tr>
      {/if}
    </table>
    <hr />
    <div style="text-align:center;">
      <input type="submit" name="submit" value="{$LANG.add_item|htmlsafe}" />
      <input type="hidden" name="id" value="{if isset($smarty.get.id)}{$smarty.get.id|htmlsafe}{/if}" />
      <input type="hidden" name="type" value="{if isset($smarty.get.type)}{$smarty.get.type|htmlsafe}{/if}" />
      <input type="hidden" name="tax_id" value="{if isset($smarty.get.tax_id)}{$smarty.get.tax_id|htmlsafe}{/if}" />
    </div>
  </form>
{/if}
