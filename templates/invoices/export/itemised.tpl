<tr>
  <td class="tbl1 col1"><b>{$LANG.quantity_short}</b></td>
  <td colspan="3" class="tbl1 col1"><b>{$LANG.item}</b></td>
  <td class="tbl1 col1"><b>{$LANG.unit_cost}</b></td>
  <td class="tbl1 col1 si_right"><b>{$LANG.price}</b></td>
</tr>
{foreach $invoiceItems as $invoiceItem}
  <tr>
    <td>{$invoiceItem.quantity|utilNumberTrim}</td>
    <td colspan="3">{$invoiceItem.product.description|htmlSafe}</td>
    <td>{$preference.pref_currency_sign}{$invoiceItem.unit_price|utilNumber}</td>
    <td class="si_right">{$preference.pref_currency_sign}{$invoiceItem.gross_total|utilNumber}</td>
  </tr>
  {if isset($invoiceItem.description)}
  <tr>
    <td></td>
    <td colspan="5">{$LANG.description_uc}:&nbsp;{$invoiceItem.description|htmlSafe}</td>
  </tr>
  {/if}
  <tr>
    <td class="tbl1-left"></td>
    <td class="tbl1-right" colspan="5">
      <table style="width:100%;;">
        <tr>
        {if !empty($customFieldLabels.product_cf1)}
          {inv_itemised_cf label=$customFieldLabels.product_cf1 field=$invoiceItem.product.custom_field1}
          {do_tr number=1 class="blank-class"}
        {/if}
        {if !empty($customFieldLabels.product_cf2)}
          {inv_itemised_cf label=$customFieldLabels.product_cf2 field=$invoiceItem.product.custom_field2}
          {do_tr number=2 class="blank-class"}
        {/if}
        {if !empty($customFieldLabels.product_cf3)}
          {inv_itemised_cf label=$customFieldLabels.product_cf3 field=$invoiceItem.product.custom_field3}
          {do_tr number=3 class="blank-class"}
        {/if}
        {if !empty($customFieldLabels.product_cf4)}
          {inv_itemised_cf label=$customFieldLabels.product_cf4 field=$invoiceItem.product.custom_field4}
          {do_tr number=4 class="blank-class"}
        {/if}
        </tr>
      </table>
    </td>
  </tr>
{/foreach}
{if $invoice.note }
<tr>
  <td class="tbl1-left tbl1-right" colspan="6"><br /></td>
</tr>
<tr>
  <td class="tbl1-left tbl1-right" colspan="6"><b>{$LANG.notes}:</b></td>
</tr>
<tr>
  <td class="tbl1-left tbl1-right" colspan="6">{$invoice.note|outHtml}</td>
</tr>
{/if}
<tr class="tbl1-left tbl1-right">
  <td class="tbl1-left tbl1-right" colspan="6"><br /></td>
</tr>
