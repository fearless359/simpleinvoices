<!--suppress HtmlRequiredLangAttribute -->
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="{$css|urlSafe}" media="all">
  <title>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.numberShort}:&nbsp;{$invoice.index_id|htmlSafe}</title>
  <link rel="shortcut icon" type="image/png" href="images/printer.png">
</head>
<body>
<br />
<div id="container">
  <div class="header"></div>
  <table class="center width_100">
    <tr>
      <td colspan="5"><img src="{holiday_logo logo=$logo|urlSafe}" class="logo" alt="logo"></td>
      <th class="text_right font1">&nbsp;{$preference.pref_inv_heading|htmlSafe}</th>
    </tr>
    <tr>
      <td colspan="6" class="tbl1-top">&nbsp;</td>
    </tr>
  </table>
  <!-- Summary - start -->
  <table class="right">
    <tr>
      <th class="col1 tbl1-bottom" colspan="4">{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.summaryUc}</th>
    </tr>
    {print_if_not_empty label=[$preference.pref_inv_wording|htmlSafe, ' ', $LANG.numberShort] field=$invoice.index_id class1='text_left' class2='right' colspan="3"}
    {print_if_not_empty label=[$preference.pref_inv_wording|htmlSafe, ' ', $LANG.dateUc] field=$invoice.date|utilDate class1='text_left' class2='right' colspan="3"}
    <!-- Show the Invoice Custom Fields if valid -->
    {if !empty($customFieldLabels.invoice_cf1)}
      {print_if_not_empty label=$customFieldLabels.invoice_cf1 field=$invoice.custom_field1 class1='text_left' class2='right' colspan="3"}
    {/if}
    {if !empty($customFieldLabels.invoice_cf2)}
      {print_if_not_empty label=$customFieldLabels.invoice_cf2 field=$invoice.custom_field2 class1='text_left' class2='right' colspan="3"}
    {/if}
    {if !empty($customFieldLabels.invoice_cf3)}
      {print_if_not_empty label=$customFieldLabels.invoice_cf3 field=$invoice.custom_field3 class1='text_left' class2='right' colspan="3"}
    {/if}
    {if !empty($customFieldLabels.invoice_cf4)}
      {print_if_not_empty label=$customFieldLabels.invoice_cf4 field=$invoice.custom_field4 class1='text_left' class2='right' colspan="3"}
    {/if}
    <tr>
      <th class="text_left">{$LANG.totalUc}:&nbsp;</th>
      <td class="right" colspan="3">{$preference.pref_currency_sign}{$invoice.total|utilNumber}</td>
    </tr>
    <tr>
      <th class="text_left">{$LANG.paidUc}:&nbsp;</th>
      <td class="right" colspan="3">{$preference.pref_currency_sign}{$invoice.paid|utilNumber}</td>
    </tr>
    <tr>
      <th class="text_left">{$LANG.owingUc}:&nbsp;</th>
      <td class="right" colspan="3">{$preference.pref_currency_sign}{$invoice.owing|utilNumber}</td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
  </table>
  <!-- Summary - end -->
  <table class="left">
    <!-- Biller section - start -->
    {print_if_not_empty label=$LANG.billerUc field=$biller.name class1='tbl1-bottom col1 text_left' class2='tbl1-bottom col1' colspan="3"}
    {print_if_not_empty label=$LANG.addressUc field=$biller.street_address class1='text_left' class2='' colspan="3"}
    {* If odd case that addres is empty but address 2 is not, print lable for address 2 *}
    {if !empty($biller.street_address2) }
      {if empty($biller.street_address) }
        {print_if_not_empty label=$LANG.addressUc field=$biller.street_address2 class1='text_left' class2='' colspan="3"}
      {else}
        {print_if_not_empty label='' field=$biller.street_address2 class1='text_left' class2='' colspan="3"}
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
    {print_if_not_empty label=$LANG.phoneShort field=$biller.phone class1='text_left' class2='' colspan="3"}
    {print_if_not_empty label=$LANG.fax field=$biller.fax class1='text_left' class2='' colspan="3"}
    {print_if_not_empty label=$LANG.mobileShort field=$biller.mobile_phone class1='text_left' class2='' colspan="3"}
    {print_if_not_empty label=$LANG.email field=$biller.email class1='text_left' class2='' colspan="3"}
    {if !empty($customFieldLabels.biller_cf1)}
      {print_if_not_empty label=$customFieldLabels.biller_cf1 field=$biller.custom_field1 class1='text_left' class2='' colspan="3"}
    {/if}
    {if !empty($customFieldLabels.biller_cf2)}
      {print_if_not_empty label=$customFieldLabels.biller_cf2 field=$biller.custom_field2 class1='text_left' class2='' colspan="3"}
    {/if}
    {if !empty($customFieldLabels.biller_cf3)}
      {print_if_not_empty label=$customFieldLabels.biller_cf3 field=$biller.custom_field3 class1='text_left' class2='' colspan="3"}
    {/if}
    {if !empty($customFieldLabels.biller_cf4)}
      {print_if_not_empty label=$customFieldLabels.biller_cf4 field=$biller.custom_field4 class1='text_left' class2='' colspan="3"}
    {/if}
    <!-- Biller section - end -->
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <!-- Customer section - start -->
    {print_if_not_empty label=$LANG.customerUc field=$customer.name class1='tbl1-bottom col1 text_left' class2='tbl1-bottom col1' colspan="3"}
    {print_if_not_empty label=$LANG.attentionShort field=$customer.attention class1='text_left' class2='' colspan="3"}
    {print_if_not_empty label=$LANG.addressUc field=$customer.street_address class1='text_left' class2='' colspan="3"}
    {* If odd case that addres is empty but address 2 is not, print lable for address 2 *}
    {if !empty($customer.street_address2) }
      {if empty($customer.street_address) }
        {print_if_not_empty label=$LANG.addressUc field=$customer.street_address2 class1='text_left' class2='' colspan="3"}
      {else}
        {print_if_not_empty label='' field=$customer.street_address2 class1='text_left' class2='' colspan="3"}
      {/if}
    {/if}
    {merge_address field1=$customer.city field2=$customer.state field3=$customer.zip_code street1=$customer.street_address street2=$customer.street_address2 class1="text_left" class2="" colspan="3"}
    {print_if_not_empty label='' field=$customer.country|htmlSafe class1='text_left' class2='' colspan="3"}
    {print_if_not_empty label=$LANG.phoneShort field=$customer.phone class1='text_left' class2='' colspan="3"}
    {print_if_not_empty label=$LANG.fax field=$customer.fax class1='text_left' class2='' colspan="3"}
    {print_if_not_empty label=$LANG.mobileShort field=$customer.mobile_phone class1='text_left' class2='' colspan="3"}
    {print_if_not_empty label=$LANG.email field=$customer.email class1='text_left' class2='' colspan="3"}
    {if !empty($customFieldLabels.customer_cf1)}
      {print_if_not_empty label=$customFieldLabels.customer_cf1 field=$customer.custom_field1 class1='text_left' class2='' colspan="3"}
    {/if}
    {if !empty($customFieldLabels.customer_cf2)}
      {print_if_not_empty label=$customFieldLabels.customer_cf2 field=$customer.custom_field2 class1='text_left' class2='' colspan="3"}
    {/if}
    {if !empty($customFieldLabels.customer_cf3)}
      {print_if_not_empty label=$customFieldLabels.customer_cf3 field=$customer.custom_field3 class1='text_left' class2='' colspan="3"}
    {/if}
    {if !empty($customFieldLabels.customer_cf4)}
      {print_if_not_empty label=$customFieldLabels.customer_cf4 field=$customer.custom_field4 class1='text_left' class2='' colspan="3"}
    {/if}
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
  </table>
  <!-- Customer section - end -->
  <table class="left width_100">
    {if $invoice.type_id == ITEMIZED_INVOICE}
      <tr>
        <th class="tbl1-bottom col1">{$LANG.quantityShort}</th>
        <th class="tbl1-bottom col1" colspan="3">{$LANG.item}</th>
        <th class="tbl1-bottom col1 text_right">{$LANG.unitCost}</th>
        <th class="tbl1-bottom col1 text_right">{$LANG.priceUc}</th>
      </tr>
      {foreach $invoiceItems as $invoiceItem}
        <tr>
          <td>{$invoiceItem.quantity|utilNumberTrim}</td>
          <td colspan="3">{$invoiceItem.product.description|htmlSafe}</td>
          <td class="text_right">{$preference.pref_currency_sign}{$invoiceItem.unit_price|utilNumber}</td>
          <td class="text_right">{$preference.pref_currency_sign}{$invoiceItem.gross_total|utilNumber}</td>
        </tr>
        {if isset($invoiceItem.attribute)}
          <tr class="si_product_attribute">
            <th>&nbsp;</th>
            <td>
              <table>
                <tr class="si_product_attribute">
                  {foreach $invoiceItem.attribute_json as $k => $v}
                    {if $v.visible ==true }
                      <td class="si_product_attribute">
                        {if $v.type == 'decimal'}
                          {$v.name}: {$preference.pref_currency_sign} {$v.value|utilNumber};
                        {elseif $v.value !=''}
                          {$v.name}: {$v.value};
                        {/if}
                      </td>
                    {/if}
                  {/foreach}
                </tr>
              </table>
            </td>
          </tr>
        {/if}
        {if isset($invoiceItem.description)}
          <tr>
            <th>&nbsp;</th>
            <td colspan="5">{$LANG.descriptionUc}:&nbsp;{$invoiceItem.description|htmlSafe}</td>
          </tr>
        {/if}
        <tr class="tbl1-bottom">
          <th>&nbsp;</th>
          <td colspan="5">
            <table class="width_100">
              <tr>
                {if !empty($customFieldLabels.product_cf1)}
                  {inv_itemized_cf label=$customFieldLabels.product_cf1 field=$invoiceItem.product.custom_field1}
                  {do_tr number=1}
                {/if}
                {if !empty($customFieldLabels.product_cf2)}
                  {inv_itemized_cf label=$customFieldLabels.product_cf2 field=$invoiceItem.product.custom_field2}
                  {do_tr number=2}
                {/if}
                {if !empty($customFieldLabels.product_cf3)}
                  {inv_itemized_cf label=$customFieldLabels.product_cf3 field=$invoiceItem.product.custom_field3}
                  {do_tr number=3}
                {/if}
                {if !empty($customFieldLabels.product_cf4)}
                  {inv_itemized_cf label=$customFieldLabels.product_cf4 field=$invoiceItem.product.custom_field4}
                  {do_tr number=4}
                {/if}
              </tr>
            </table>
          </td>
        </tr>
      {/foreach}
    {elseif $invoice.type_id == TOTAL_INVOICE}
      <tr>
        <td>
          <table class="left width_100">
            <tr class="col1">
              <th class="tbl1-bottom col1" colspan="6">{$LANG.descriptionUc}</th>
            </tr>
            {foreach $invoiceItems as $invoiceItem}
              <tr>
                <td colspan="6">{$invoiceItem.description|outHtml}</td>
              </tr>
            {/foreach}
          </table>
        </td>
      </tr>
    {/if}
    {if ($invoice.type_id == ITEMIZED_INVOICE && $invoice.note != "")}
      <tr>
        <td colspan="6">&nbsp;</td>
      </tr>
      <tr>
        <th colspan="6">{$LANG.notes}:</th>
      </tr>
      <tr>
        <td colspan="6">{$invoice.note|outHtml}</td>
      </tr>
    {/if}
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    {* tax section - start *}
    {if $invoiceNumberOfTaxes > 0}
      <tr>
        <th class="text_right" colspan="5">{$LANG.subtotalUc}:&nbsp;</th>
        <td class="text_right {if $invoiceNumberOfTaxes > 1}underline;{/if}" colspan="1">{$preference.pref_currency_sign}{$invoice.gross|utilNumber}</td>
      </tr>
    {/if}
    {if $invoiceNumberOfTaxes > 1 }
      <tr>
        <td colspan="6">&nbsp;</td>
      </tr>
    {/if}
    {section name=line start=0 loop=$invoice.tax_grouped step=1}
      {if ($invoice.tax_grouped[line].tax_amount != "0") }
        <tr>
          <th class="text_right" colspan="5">{$invoice.tax_grouped[line].tax_name|htmlSafe}:&nbsp;</th>
          <td class="text_right" colspan="1">{$preference.pref_currency_sign}{$invoice.tax_grouped[line].tax_amount|utilNumber}</td>
        </tr>
      {/if}
    {/section}
    {if $invoiceNumberOfTaxes > 1}
      <tr>
        <th class="text_right" colspan="5">{$LANG.taxTotal}:&nbsp;</th>
        <td class="text_right {if $invoiceNumberOfTaxes > 1}underline{/if}" colspan="1">{$preference.pref_currency_sign}{$invoice.total_tax|utilNumber}</td>
      </tr>
    {/if}
    {if $invoiceNumberOfTaxes > 1}
      <tr>
        <td colspan="6">&nbsp;</td>
      </tr>
    {/if}
    <tr>
      <th class="text_right" colspan="5">{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.amountUc}:&nbsp;</th>
      <td class="text_right" colspan="1"><span class="underline double_underline">{$preference.pref_currency_sign}{$invoice.total|utilNumber}</span></td>
    </tr>
    {* tax section - end *}
    <tr>
      <td colspan="6">&nbsp;</td>
    </tr>
    <!-- invoice details section - start -->
    <tr>
      <th class="tbl1-bottom col1" colspan="6">{$preference.pref_inv_detail_heading|htmlSafe}</th>
    </tr>
    <tr>
      <td colspan="6"><i>{$preference.pref_inv_detail_line|outHtml}</i></td>
    </tr>
    <tr>
      <td colspan="6">{$preference.pref_inv_payment_method|htmlSafe}</td>
    </tr>
    <tr>
      <td colspan="6">{$preference.pref_inv_payment_line1_name|htmlSafe}&nbsp;{$preference.pref_inv_payment_line1_value|htmlSafe}</td>
    </tr>
    <tr>
      <td colspan="6">{$preference.pref_inv_payment_line2_name|htmlSafe}&nbsp;{$preference.pref_inv_payment_line2_value|htmlSafe}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6">
        <div class="text_center font3">{$biller.footer|outHtml}</div>
      </td>
    </tr>
    <tr>
      <td>
        {online_payment_link type=$preference.include_online_payment
        business=$biller.paypal_business_name
        item_name=$invoice.index_name
        invoice=$invoice.id
        amount=$invoice.owing
        currency_code=$preference.currency_code
        link_wording=$LANG.paypalLink
        notify_url=$biller.paypal_notify_url
        return_url=$biller.paypal_return_url
        domain_id = $invoice.domain_id
        include_image=true api_id = $biller.paymentsgateway_api_id
        customer = $customer }
      </td>
    </tr>
    <!-- invoice details section - end -->
  </table>
  <div id="footer"></div>
</div>
</body>
</html>
