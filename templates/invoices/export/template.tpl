<!--suppress HtmlRequiredLangAttribute -->
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>{$preference.pref_inv_wording|htmlSafe} {$LANG.numberShort}: {$invoice.index_id|htmlSafe}</title>
</head>
<body>
  <br />
  <div id="container">
    <div id="header"></div>
    <table class="center">
      <tr>
        <td colspan="5">
          <img src="{$logo|urlSafe}" style="border:0; margin: 0 0;" alt=""></td>
        <th class="si_right"><span>{$preference.pref_inv_heading|htmlSafe}</span></th>
      </tr>
      <tr>
        <td colspan="6"><hr></td>
      </tr>
    </table>
    <table>
      <tr>
        <td colspan="4"><b>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.summaryUc}</b></td>
      </tr>
      <tr>
        <td>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.numberShort}:</td>
        <td colspan="3">{$invoice.index_id|htmlSafe}</td>
      </tr>
      <tr>
        <td nowrap>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.dateUc}:</td>
        <td colspan="3">{$invoice.date|htmlSafe}</td>
      </tr>
      <!-- Show the Invoice Custom Fields if valid -->
      {if !empty($customFieldLabels.invoice_cf1) && isset($invoice.custom_field1)}
      <tr>
        <td nowrap>{$customFieldLabels.invoice_cf1|htmlSafe}:</td>
        <td colspan="3">{$invoice.custom_field1|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.invoice_cf2) && isset($invoice.custom_field2)}
      <tr>
        <td nowrap>{$customFieldLabels.invoice_cf2|htmlSafe}:</td>
        <td colspan="3">{$invoice.custom_field2|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.invoice_cf3) && isset($invoice.custom_field3)}
      <tr>
        <td nowrap>{$customFieldLabels.invoice_cf3|htmlSafe}:</td>
        <td colspan="3">{$invoice.custom_field3|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.invoice_cf4) && isset($invoice.custom_field4)}
      <tr>
        <td nowrap>{$customFieldLabels.invoice_cf4|htmlSafe}:</td>
        <td colspan="3">{$invoice.custom_field4|htmlSafe}</td>
      </tr>
      {/if}
      <tr>
        <td>{$LANG.total}:</td>
        <td colspan="3">{$preference.pref_currency_sign}{$invoice.total|utilNumber:2}</td>
      </tr>
      <tr>
        <td>{$LANG.paid}:</td>
        <td colspan="3">{$preference.pref_currency_sign}{$invoice.paid|utilNumber:2}</td>
      </tr>
      <tr>
        <td nowrap>{$LANG.owingUc}:</td>
        <td colspan="3">{$preference.pref_currency_sign}{$invoice.owing|utilNumber:2}</td>
      </tr>
    </table>
    <!-- Summary - end -->
    <table>
      <!-- Biller section - start -->
      <tr>
        <td><b>{$LANG.biller}:</b></td>
        <td colspan="3">{$biller.name|htmlSafe}</td>
      </tr>
      {if isset($biller.street_address)}
      <tr>
        <td>{$LANG.addressUc}:</td>
        <td colspan="3">{$biller.street_address|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($biller.street_address2) }
        {if !isset($biller.street_address) }
        <tr>
          <td>{$LANG.addressUc}:</td>
          <td colspan="3">{$biller.street_address2|htmlSafe}</td>
        </tr>
        {/if}
        {if isset($biller.street_address)}
        <tr>
          <td></td>
          <td colspan="3">{$biller.street_address2|htmlSafe}</td>
        </tr>
        {/if}
      {/if}
      {merge_address field1=$biller.city field2=$biller.state field3=$biller.zip_code
                     street1=$biller.street_address street2=$biller.street_address2
                     class1="" class2="" colspan="3"}
      {if isset($biller.country) }
      <tr>
        <td></td>
        <td colspan="3">{$biller.country|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($biller.phone) }
      <tr>
        <td>{$LANG.phoneShort}:</td>
        <td colspan="3">{$biller.phone|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($biller.fax) }
      <tr>
        <td>{$LANG.fax}:</td>
        <td colspan="3">{$biller.fax|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($biller.mobile_phone) }
      <tr>
        <td>{$LANG.mobileShort}:</td>
        <td colspan="3">{$biller.mobile_phone|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($biller.email) }
      <tr>
        <td>{$LANG.email}:</td>
        <td colspan="3">{$biller.email|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.biller_cf1) && isset($biller.custom_field1)}
      <tr>
        <td>{$customFieldLabels.biller_cf1|htmlSafe}:</td>
        <td colspan="3">{$biller.custom_field1|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.biller_cf2) && isset($biller.custom_field2)}
      <tr>
        <td>{$customFieldLabels.biller_cf2|htmlSafe}:</td>
        <td colspan="3">{$biller.custom_field2|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.biller_cf3) && isset($biller.custom_field3)}
      <tr>
        <td>{$customFieldLabels.biller_cf3|htmlSafe}:</td>
        <td colspan="3">{$biller.custom_field3|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.biller_cf4) && isset($biller.custom_field4)}
      <tr>
        <td>{$customFieldLabels.biller_cf4|htmlSafe}:</td>
        <td colspan="3">{$biller.custom_field4|htmlSafe}</td>
      </tr>
      {/if}
      <tr>
        <td colspan="4"></td>
      </tr>
      <!-- Biller section - end -->
      <tr><td><br /></td></tr>
      <tr>
        <td colspan="3"><br />
        <td>
      </tr>
      <!-- Customer section - start -->
      <tr>
        <td><b>{$LANG.customer}:</b></td>
        <td colspan="3">{$customer.name|htmlSafe}</td>
      </tr>
      {if isset($customer.attention)}
      <tr>
        <td>{$LANG.attentionShort}:</td>
        <td colspan="3">{$customer.attention|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($customer.street_address) }
      <tr>
        <td>{$LANG.addressUc}:</td>
        <td colspan="3">{$customer.street_address|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($customer.street_address2) }
        {if !isset($customer.street_address) }
        <tr>
          <td>{$LANG.addressUc}:</td>
          <td colspan="3">{$customer.street_address2|htmlSafe}</td>
        </tr>
        {/if}
        {if isset($customer.street_address)}
        <tr>
          <td></td>
          <td colspan="3">{$customer.street_address2|htmlSafe}</td>
        </tr>
        {/if}
      {/if}
      {merge_address field1=$customer.city field2=$customer.state field3=$customer.zip_code
                     street1=$customer.street_address street2=$customer.street_address2
                     class1="" class2="" colspan="3"}
      {if isset($customer.country) }
      <tr>
        <td></td>
        <td colspan="3">{$customer.country|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($customer.phone) }
      <tr>
        <td>{$LANG.phoneShort}:</td>
        <td colspan="3">{$customer.phone|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($customer.fax) }
      <tr>
        <td>{$LANG.fax}:</td>
        <td colspan="3">{$customer.fax|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($customer.mobile_phone) }
      <tr>
        <td>{$LANG.mobileShort}:</td>
        <td colspan="3">{$customer.mobile_phone|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($customer.email) }
      <tr>
        <td>{$LANG.email}:</td>
        <td colspan="3">{$customer.email|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.customer_cf1) && isset($customer.custom_field1)}
      <tr>
        <td>{$customFieldLabels.customer_cf1|htmlSafe}:</td>
        <td colspan="3">{$customer.custom_field1|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.customer_cf2) && isset($customer.custom_field2)}
      <tr>
        <td>{$customFieldLabels.customer_cf2|htmlSafe}:</td>
        <td colspan="3">{$customer.custom_field2|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.customer_cf3) && isset($customer.custom_field3)}
      <tr>
        <td>{$customFieldLabels.customer_cf3|htmlSafe}:</td>
        <td colspan="3">{$customer.custom_field3|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.customer_cf4) && isset($customer.custom_field4)}
      <tr>
        <td>{$customFieldLabels.customer_cf4|htmlSafe}:</td>
        <td colspan="3">{$customer.custom_field4|htmlSafe}</td>
      </tr>
      {/if}
      <tr>
        <td colspan="4"></td>
      </tr>
      <!-- Customer section - end -->
    </table>
    <table style="width:100%;">
      <tr>
        <td colspan="6"><br /></td>
      </tr>
      {if $invoice.type_id == ITEMIZED_INVOICE }
        {include file="$template_path/itemised.tpl"}
      {/if}
      {if $invoice.type_id == CONSULTING_INVOICE}
        {include file="$template_path/consulting.tpl"}
      {/if}
      {if $invoice.type_id == TOTAL_INVOICE}
        {include file="$template_path/total.tpl"}
      {/if}
      {* tax section - start *}
      {if $invoiceNumberOfTaxes > 0}
      <tr>
        <td colspan="2"></td>
        <td colspan="3" class="si_right">{$LANG.subTotal}&nbsp;</td>
        <td colspan="1" class=="si_right"
            {if $invoiceNumberOfTaxes > 1}style="text-decoration:underline;"{/if}>
          {$preference.pref_currency_sign}{$invoice.gross|utilNumber|htmlSafe}
        </td>
      </tr>
      {/if}
      {if $invoiceNumberOfTaxes > 1 }
      <tr>
        <td colspan="6"><br /></td>
      </tr>
      {/if}
      {section name=line start=0 loop=$invoice.tax_grouped step=1}
        {if ($invoice.tax_grouped[line].tax_amount != "0") }
        <tr>
          <td colspan="2"></td>
          <td colspan="3" class="si_right">{$invoice.tax_grouped[line].tax_name|htmlSafe}&nbsp;</td>
          <td colspan="1" class="si_right">
            {$preference.pref_currency_sign}{$invoice.tax_grouped[line].tax_amount|utilNumber|htmlSafe}
          </td>
        </tr>
        {/if}
      {/section}
      {if $invoiceNumberOfTaxes > 1}
      <tr>
        <td colspan="2"></td>
        <td colspan="3" class="si_right">{$LANG.taxTotal}&nbsp;</td>
        <td colspan="1" class="si_right" style="text-decoration:underline;">
          {$preference.pref_currency_sign}{$invoice.total_tax|utilNumber}
        </td>
      </tr>
      {/if}
      {if $invoiceNumberOfTaxes > 1}
      <tr>
        <td colspan="6"><br /></td>
      </tr>
      {/if}
      <tr>
        <td colspan="2"></td>
        <td colspan="3" class="si_right">
          <b>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.amountUc}&nbsp;</b>
        </td>
        <td colspan="1" class="si_right">
          <span class="double_underline">
            {$preference.pref_currency_sign}{$invoice.total|utilNumber}
          </span>
        </td>
      </tr>
      {* tax section - end *}
      {* ***************************************************************************
      {section name=line start=0 loop=$invoice.tax_grouped step=1}
        {if ($invoice.tax_grouped[line].tax_amount != "0") }
        <tr class='details_screen'>
          <td colspan="2"></td>
          <td colspan="3" align="right">{$invoice.tax_grouped[line].tax_name|htmlSafe}</td>
          <td colspan="1" align="right">
            {$preference.pref_currency_sign}{$invoice.tax_grouped[line].tax_amount|utilNumber}
          </td>
        </tr>
        {/if}
      {/section}
      <tr>
        <td colspan="3"></td>
        <td align="right" colspan="2">{$LANG.taxTotal}</td>
        <td align="right">{$preference.pref_currency_sign}{$invoice.total_tax|utilNumber:2}</td>
      </tr>
      <tr>
        <td colspan="6"><br /></td>
      </tr>
      <tr>
        <td colspan="3"></td>
        <td align="right" colspan="2">
          <b>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.amountUc}</b>
        </td>
        <td align="right" style="text-decoration:underline;">
          {$preference.pref_currency_sign}{$invoice.total|utilNumber:2}
        </td>
      </tr>
      *************************************************************************** *}
      <tr>
        <td colspan="6"><br /><br /></td>
      </tr>
      <!-- invoice details section - start -->
      <tr>
        <td colspan="6"><b>{$preference.pref_inv_detail_heading|htmlSafe}</b></td>
      </tr>
      <tr>
        <td colspan="6"><i>{$preference.pref_inv_detail_line|htmlSafe}</i></td>
      </tr>
      <tr>
        <td colspan="6">{$preference.pref_inv_payment_method|htmlSafe}</td>
      </tr>
      <tr>
        <td colspan="6">
          {$preference.pref_inv_payment_line1_name|htmlSafe}
          {$preference.pref_inv_payment_line1_value|htmlSafe}
        </td>
      </tr>
      <tr>
        <td colspan="6">
          {$preference.pref_inv_payment_line2_name|htmlSafe}
          {$preference.pref_inv_payment_line2_value|htmlSafe}
        </td>
      </tr>
      <tr>
        <td><br /></td>
      </tr>
      <tr>
        <td colspan="6">
          <div style="font-size: 8pt;" class="si_center">{$biller.footer|outHtml}</div>
        </td>
      </tr>
    </table>
    <div id="footer"></div>
  </div>
</body>
</html>
