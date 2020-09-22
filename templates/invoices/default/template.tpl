<!--suppress HtmlRequiredLangAttribute -->
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="{$css|urlSafe}" media="all">
  <title>{$preference.pref_inv_wording|htmlSafe}
         {$LANG.numberShort}: {$invoice.index_id|htmlSafe}</title>
</head>
<body>
  <br />
  <div id="container">
    <div id="header"></div>
    <table style="width:100%;" class="center">
      <tr>
        <td colspan="5"><img src="{holiday_logo logo=$logo|urlSafe}" style="margin: 0 0; border-width:0; text-align:left;" alt=""></td>
        <th class="si_right"><span class="font1">{$preference.pref_inv_heading|htmlSafe}</span></th>
      </tr>
      <tr>
        <td colspan="6" class="tbl1-top">&nbsp;</td>
      </tr>
    </table>
    <!-- Summary - start -->
    <table class="right">
      <tr>
        <td class="col1 tbl1-bottom" colspan="4">
          <b>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.summaryUc}</b>
        </td>
      </tr>
      <tr>
        <td class="">{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.numberShort}:</td>
        <td class="si_right" colspan="3">{$invoice.index_id}</td>
      </tr>
      <tr>
        <td nowrap class="">{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.dateUc}:</td>
        <td class="si_right" colspan="3">{$invoice.date|utilDate}</td>
      </tr>
      <!-- Show the Invoice Custom Fields if valid -->
      {if !empty($customFieldLabels.invoice_cf1) && isset($invoice.custom_field1)}
      <tr>
        <td nowrap class="">{$customFieldLabels.invoice_cf1|htmlSafe}:</td>
        <td class="si_right" colspan="3">{$invoice.custom_field1|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.invoice_cf2) && isset($invoice.custom_field2)}
      <tr>
        <td nowrap class="">{$customFieldLabels.invoice_cf2|htmlSafe}:</td>
        <td class="si_right" colspan="3">{$invoice.custom_field2|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.invoice_cf3) && isset($invoice.custom_field3)}
      <tr>
        <td nowrap class="">{$customFieldLabels.invoice_cf3|htmlSafe}:</td>
        <td class="si_right" colspan="3">{$invoice.custom_field3|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.invoice_cf4) && isset($invoice.custom_field4)}
      <tr>
        <td nowrap class="">{$customFieldLabels.invoice_cf4|htmlSafe}:</td>
        <td class="si_right" colspan="3">{$invoice.custom_field4|htmlSafe}</td>
      </tr>
      {/if}
      <tr>
        <td class="">{$LANG.total}:</td>
        <td class="si_right" colspan="3">{$preference.pref_currency_sign}
          {$invoice.total|utilNumber}</td>
      </tr>
      <tr>
        <td class="">{$LANG.paid}:</td>
        <td class="si_right" colspan="3">{$preference.pref_currency_sign}
          {$invoice.paid|utilNumber}</td>
      </tr>
      <tr>
        <td nowrap class="">{$LANG.owingUc}:</td>
        <td class="si_right" colspan="3">
          {$preference.pref_currency_sign}{$invoice.owing|utilNumber}
        </td>
      </tr>
    </table>
    <!-- Summary - end -->
    <table class="left">
      <!-- Biller section - start -->
      <tr>
        <td class="tbl1-bottom col1"><b>{$LANG.biller}:</b></td>
        <td class="col1 tbl1-bottom" colspan="3">{$biller.name|htmlSafe}</td>
      </tr>
      {if isset($biller.street_address)}
      <tr>
        <td class=''>{$LANG.addressUc}:</td>
        <td class='' colspan="3">{$biller.street_address|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($biller.street_address2) }
        {if !isset($biller.street_address) }
        <tr>
          <td class=''>{$LANG.addressUc}:</td>
          <td class='' colspan="3">{$biller.street_address2|htmlSafe}</td>
        </tr>
        {/if}
        {if isset($biller.street_address) }
        <tr>
          <td class=''></td>
          <td class='' colspan="3">{$biller.street_address2|htmlSafe}</td>
        </tr>
        {/if}
      {/if}
      {merge_address field1=$biller.city field2=$biller.state field3=$biller.zip_code
                     street1=$biller.street_address street2=$biller.street_address2
                     class1="" class2="" colspan="3"}
      {if isset($biller.country) }
      <tr>
        <td class=''></td>
        <td class='' colspan="3">{$biller.country|htmlSafe}</td>
      </tr>
      {/if}
      {print_if_not_null label=$LANG.phoneShort field=$biller.phone class1='' class2='' colspan="3"}
      {print_if_not_null label=$LANG.fax field=$biller.fax class1='' class2='' colspan="3"}
      {print_if_not_null label=$LANG.mobileShort field=$biller.mobile_phone class1='' class2='' colspan="3"}
      {print_if_not_null label=$LANG.email field=$biller.email class1='' class2='' colspan="3"}
      {if !empty($customFieldLabels.biller_cf1)}
        {print_if_not_null label=$customFieldLabels.biller_cf1 field=$biller.custom_field1 class1='' class2='' colspan="3"}
      {/if}
      {if !empty($customFieldLabels.biller_cf2)}
        {print_if_not_null label=$customFieldLabels.biller_cf2 field=$biller.custom_field2 class1='' class2='' colspan="3"}
      {/if}
      {if !empty($customFieldLabels.biller_cf3)}
        {print_if_not_null label=$customFieldLabels.biller_cf3 field=$biller.custom_field3 class1='' class2='' colspan="3"}
      {/if}
      {if !empty($customFieldLabels.biller_cf4)}
        {print_if_not_null label=$customFieldLabels.biller_cf4 field=$biller.custom_field4 class1='' class2='' colspan="3"}
      {/if}
      <tr>
        <td class="" colspan="4"></td>
      </tr>
      <!-- Biller section - end -->
      <tr>
        <td colspan="4"><br /></td>
      </tr>
      <!-- Customer section - start -->
      <tr>
        <td class="tbl1-bottom col1"><b>{$LANG.customer}:</b></td>
        <td class="tbl1-bottom col1" colspan="3">{$customer.name|htmlSafe}</td>
      </tr>
      {if isset($customer.attention) }
      <tr>
        <td class=''>{$LANG.attentionShort}:</td>
        <td class='' colspan="3">{$customer.attention|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($customer.street_address) }
      <tr>
        <td class=''>{$LANG.addressUc}:</td>
        <td class='' colspan="3">{$customer.street_address|htmlSafe}</td>
      </tr>
      {/if}
      {if isset($customer.street_address2)}
        {if !isset($customer.street_address)}
        <tr>
          <td class=''>{$LANG.addressUc}:</td>
          <td class='' colspan="3">{$customer.street_address2|htmlSafe}</td>
        </tr>
        {/if}
        {if isset($customer.street_address)}
        <tr>
          <td class=''></td>
          <td class='' colspan="3">{$customer.street_address2|htmlSafe}</td>
        </tr>
        {/if}
      {/if}
      {merge_address field1=$customer.city field2=$customer.state field3=$customer.zip_code
                     street1=$customer.street_address street2=$customer.street_address2
                     class1="" class2="" colspan="3"}
      {if isset($customer.country)}
      <tr>
        <td class=''></td>
        <td class='' colspan="3">{$customer.country|htmlSafe}</td>
      </tr>
      {/if}
      {print_if_not_null label=$LANG.phoneShort field=$customer.phone class1='' class2='t' colspan="3"}
      {print_if_not_null label=$LANG.fax field=$customer.fax class1='' class2='' colspan="3"}
      {print_if_not_null label=$LANG.mobileShort field=$customer.mobile_phone class1='' class2='' colspan="3"}
      {print_if_not_null label=$LANG.email field=$customer.email class1='' class2='' colspan="3"}
      {if !empty($customFieldLabels.customer_cf1)}
        {print_if_not_null label=$customFieldLabels.customer_cf1 field=$customer.custom_field1 class1='' class2='' colspan="3"}
      {/if}
      {if !empty($customFieldLabels.customer_cf2)}
        {print_if_not_null label=$customFieldLabels.customer_cf2 field=$customer.custom_field2 class1='' class2='' colspan="3"}
      {/if}
      {if !empty($customFieldLabels.customer_cf3)}
        {print_if_not_null label=$customFieldLabels.customer_cf3 field=$customer.custom_field3 class1='' class2='' colspan="3"}
      {/if}
      {if !empty($customFieldLabels.customer_cf4)}
        {print_if_not_null label=$customFieldLabels.customer_cf4 field=$customer.custom_field4 class1='' class2='' colspan="3"}
      {/if}
      <tr>
        <td class="" colspan="4"></td>
      </tr>
    </table>
    <!-- Customer section - end -->
    <table class="left" style="width:100%;;">
      <tr>
        <td colspan="6"><br /></td>
      </tr>
      {if $invoice.type_id == ITEMIZED_INVOICE}
        <tr>
          <td class="tbl1-bottom col1"><b>{$LANG.quantityShort}</b></td>
          <td class="tbl1-bottom col1" colspan="3"><b>{$LANG.item}</b></td>
          <td class="tbl1-bottom col1 si_right"><b>{$LANG.unitCost}</b></td>
          <td class="tbl1-bottom col1 si_right"><b>{$LANG.price}</b></td>
        </tr>
        {foreach $invoiceItems as $invoiceItem}
          <tr class="">
            <td class="">{$invoiceItem.quantity|utilNumberTrim}</td>
            <td class="" colspan="3">{$invoiceItem.product.description|htmlSafe}</td>
            <td class="si_right">{$preference.pref_currency_sign}
              {$invoiceItem.unit_price|utilNumber}</td>
            <td class="si_right">{$preference.pref_currency_sign}
              {$invoiceItem.gross_total|utilNumber}</td>
          </tr>
          {if isset($invoiceItem.attribute)}
          <tr class="si_product_attribute">
            <td></td>
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
          <tr class="">
            <td class=""></td>
            <td class="" colspan="5">{$LANG.descriptionUc}:&nbsp;{$invoiceItem.description|htmlSafe}</td>
          </tr>
          {/if}
          <tr class="tbl1-bottom">
            <td class=""></td>
            <td class="" colspan="5">
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
      {/if}
      {if $invoice.type_id == CONSULTING_INVOICE}
        <tr class="tbl1-bottom col1">
          <td class="tbl1-bottom"><b>{$LANG.quantityShort}</b></td>
          <td class="tbl1-bottom" colspan="3"><b>{$LANG.item}</b></td>
          <td class="tbl1-bottom si_right"><b>{$LANG.unitCost}</b></td>
          <td class="tbl1-bottom si_right"><b>{$LANG.price}</b></td>
        </tr>
        {foreach $invoiceItems as $invoiceItem}
          <tr class=" ">
            <td class="">{$invoiceItem.quantity|utilNumberTrim}</td>
            <td>{$invoiceItem.product.description|htmlSafe}</td>
            <td class="" colspan="4"></td>
          </tr>
          <tr>
            <td class=""></td>
            <td class="" colspan="5">
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
          <tr class="">
            <td class=""></td>
            <td class="" colspan="5"><i>{$LANG.descriptionUc}: </i>{$invoiceItem.description|htmlSafe}</td>
          </tr>
          <tr class="">
            <td class=""></td>
            <td class=""></td>
            <td class=""></td>
            <td class=""></td>
            <td class="si_right">{$preference.pref_currency_sign}{$invoiceItem.unit_price|utilNumber}</td>
            <td class="si_right">{$preference.pref_currency_sign}{$invoiceItem.total|utilNumber}</td>
          </tr>
        {/foreach}
      {/if}
      {if $invoice.type_id == TOTAL_INVOICE}
        <tr>
          <td>
            <table class="left" style="width:100%;;">
              <tr class="col1">
                <td class="tbl1-bottom col1" colspan="6"><b>{$LANG.descriptionUc}</b></td>
              </tr>
              {foreach $invoiceItems as $invoiceItem}
              <tr class="">
                  <td class="t" colspan="6">{$invoiceItem.description|outHtml}</td>
              </tr>
              {/foreach}
            </table>
          </td>
        </tr>
      {/if}
      {if ($invoice.type_id == ITEMIZED_INVOICE && $invoice.note != "") ||
          ($invoice.type_id == CONSULTING_INVOICE && $invoice.note != "" ) }
        <tr>
          <td class="" colspan="6"><br /></td>
        </tr>
        <tr>
          <td class="" colspan="6"><b>{$LANG.notes}:</b></td>
        </tr>
        <tr>
          <td class="" colspan="6">{$invoice.note|outHtml}</td>
        </tr>
      {/if}
      <tr class="">
        <td class="" colspan="6"><br /></td>
      </tr>
      {* tax section - start *}
      {if $invoiceNumberOfTaxes > 0}
      <tr>
        <td colspan="2"></td>
        <td class="si_right" colspan="3">{$LANG.subTotal}&nbsp;</td>
        <td class="si_right" colspan="1" {if $invoiceNumberOfTaxes > 1}style="text-decoration:underline;"{/if}>
          {$preference.pref_currency_sign}{$invoice.gross|utilNumber}
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
            <td class="si_right" colspan="3">{$invoice.tax_grouped[line].tax_name|htmlSafe}&nbsp;</td>
            <td class="si_right" colspan="1">{$preference.pref_currency_sign}
              {$invoice.tax_grouped[line].tax_amount|utilNumber}
            </td>
          </tr>
        {/if}
      {/section}
      {if $invoiceNumberOfTaxes > 1}
      <tr>
        <td colspan="2"></td>
        <td class="si_right" colspan="3">{$LANG.taxTotal}&nbsp;</td>
        <td class="si_right" colspan="1" style="text-decoration:underline;">
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
        <td class="si_right" colspan="3">
          <b>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.amountUc}</b>
        </td>
        <td class="si_right" colspan="1">
          <span class="double_underline" style="text-decoration:underline;">
            {$preference.pref_currency_sign}{$invoice.total|utilNumber}
          </span>
        </td>
      </tr>
      {* tax section - end *}
      {* ***************************************************************************
      <tr>
        <td class="" colspan="2"></td>
        <td align="right" colspan="3">{$LANG.subTotal}</td>
        <td align="right" class="">{$preference.pref_currency_sign}{$invoice.gross|utilNumber}</td>
      </tr>
      {section name=line start=0 loop=$invoice.tax_grouped step=1}
        {if ($invoice.tax_grouped[line].tax_amount != "0") }
          <tr class=''>
            <td colspan="2"></td>
            <td colspan="3" align="right">{$invoice.tax_grouped[line].tax_name|htmlSafe}</td>
            <td colspan="1" align="right">
              {$preference.pref_currency_sign}{$invoice.tax_grouped[line].tax_amount|utilNumber}
            </td>
          </tr>
        {/if}
      {/section}
      <tr class=''>
        <td colspan="2"></td>
        <td colspan="3" align="right">{$LANG.taxTotal}</td>
        <td colspan="1" align="right" style="text-decoration:underline;">
          {$preference.pref_currency_sign}{$invoice.total_tax|utilNumber}
        </td>
      </tr>
      <tr class="">
        <td class="" colspan="6"><br /></td>
      </tr>
      <tr class="">
        <td class="" colspan="2"></td>
        <td class="" align="right" colspan="3">
          <b>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.amountUc}</b>
        </td>
        <td class="" align="right">
          <span class="double_underline">
            {$preference.pref_currency_sign}{$invoice.total|utilNumber}
          </span>
        </td>
      </tr>
      *************************************************************************** *}
      <tr>
        <td colspan="6">
          <br />
          <br />
        </td>
      </tr>
      <!-- invoice details section - start -->
      <tr>
        <td class="tbl1-bottom col1" colspan="6"><b>{$preference.pref_inv_detail_heading|htmlSafe}</b></td>
      </tr>
      <tr>
        <td class="" colspan="6"><i>{$preference.pref_inv_detail_line|outHtml}</i></td>
      </tr>
      <tr>
        <td class="" colspan="6">{$preference.pref_inv_payment_method|htmlSafe}</td>
      </tr>
      <tr>
        <td class="" colspan="6">
          {$preference.pref_inv_payment_line1_name|htmlSafe}
          {$preference.pref_inv_payment_line1_value|htmlSafe}
        </td>
      </tr>
      <tr>
        <td class="" colspan="6">
          {$preference.pref_inv_payment_line2_name|htmlSafe}
          {$preference.pref_inv_payment_line2_value|htmlSafe}
        </td>
      </tr>
      <tr>
        <td><br /></td>
      </tr>
      <tr>
        <td colspan="6">
          <div class="si_center" style="font-size: 8pt;">{$biller.footer|outHtml}</div>
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
