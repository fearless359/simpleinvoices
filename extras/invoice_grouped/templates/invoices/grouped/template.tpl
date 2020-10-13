<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="{$css|urlSafe}" media="all">
  <title>{$preference.pref_inv_wording|htmlSafe} {$LANG.numberShort}: {$invoice.id|htmlSafe}</title>
</head>
<body>
  <br>
  <div id="container">
    <div id="header"></div>
    <table class="center">
      <tr>
        <th style="text-align:centered;" colspan="6">
          <span class="font1">{$biller.name|htmlSafe}</span>
        </th>
      </tr>
      <tr>
        <th style="text-align:centered;" colspan="6">
          <span class="font1">{$preference.pref_inv_heading|htmlSafe}</span>
        </th>
      </tr>
      <tr>
        <td colspan="6" class="tbl1-top">&nbsp;</td>
      </tr>
    </table>
    <!-- Summary - start -->
    <table>
      <tr>
        <td>To:</td>
        <td colspan="3">{$customer.name|htmlSafe}</td>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td>Description:</td>
        <td colspan="3"></td>
        {if !empty($customFieldLabels.invoice_cf1)}
          <td>{$customFieldLabels.invoice_cf1|htmlSafe}</td>
          <td>{$invoice.custom_field1|htmlSafe}</td>
        {/if}
      </tr>
      <tr>
        <td colspan=4 valign="top" rowspan="4">{$invoice.note|outHtml}</td>
        <td>Job #</td>
        <td>{$invoice.id|htmlSafe}</td>
      </tr>
      <tr>
        <td>Date</td>
        <td>{$invoice.date|htmlSafe}</td>
      </tr>
      {if !empty($customFieldLabels.invoice_cf2)}
        <tr>
        <td>{$customFieldLabels.invoice_cf2|htmlSafe}</td>
        <td>{$invoice.custom_field2|htmlSafe}</td>
      </tr>
      {/if}
      {if !empty($customFieldLabels.invoice_cf3)}
      <tr>
        <td>{$customFieldLabels.invoice_cf3|htmlSafe}</td>
        <td>{$invoice.custom_field3|htmlSafe}</td>
      </tr>
      {/if}
    </table>
{* ************************ BEGIN COMMENTED OUT SECTION ****************************
    <table class="right">
      <tr>
        <td class="col1 tbl1-bottom" colspan="4" ><b>{$preference.pref_inv_wording|htmlSafe} {$LANG.summaryUc}</b></td>
      </tr>
      <tr>
        <td class="">{$preference.pref_inv_wording|htmlSafe} {$LANG.numberShort}:</td>
        <td class="" align="right" colspan="3">{$invoice.id|htmlSafe}</td>
      </tr>
      <tr>
        <td nowrap class="">{$preference.pref_inv_wording|htmlSafe} {$LANG.dateUc}:</td>
        <td class="" align="right" colspan="3">{$invoice.date|htmlSafe}</td>
      </tr>
      <!-- Show the Invoice Custom Fields if valid -->
      {if !empty($customFieldLabels.invoice_cf1) && isset($invoice.custom_field1)}
        <tr>
          <td nowrap class="">{$customFieldLabels.invoice_cf1|htmlSafe}:</td>
          <td class="" align="right" colspan="3">{$invoice.custom_field1|htmlSafe}</td>
        </tr>
      {/if}
      {if !empty($customFieldLabels.invoice_cf2) && isset($invoice.custom_field2)}
        <tr>
          <td nowrap class="">{$customFieldLabels.invoice_cf2|htmlSafe}:</td>
          <td class="" align="right"  colspan="3">{$invoice.custom_field2|htmlSafe}</td>
        </tr>
      {/if}
      {if !empty($customFieldLabels.invoice_cf3) && isset($invoice.custom_field3)}
        <tr>
          <td nowrap class="">{$customFieldLabels.invoice_cf3|htmlSafe}:</td>
          <td class="" align="right" colspan="3">{$invoice.custom_field3|htmlSafe}</td>
        </tr>
      {/if}
      {if !empty($customFieldLabels.invoice_cf4) && isset($invoice.custom_field4)}
        <tr>
          <td nowrap class="">{$customFieldLabels.invoice_cf4|htmlSafe}:</td>
          <td class="" align="right" colspan="3">{$invoice.custom_field4|htmlSafe}</td>
        </tr>
      {/if}
      <tr>
        <td class="" >{$LANG.totalUc}: </td>
        <td class="" align="right" colspan="3">
          {$preference.pref_currency_sign} {$invoice.total|utilNumber:2}
        </td>
      </tr>
      <tr>
        <td class="">{$LANG.paidUc}:</td>
        <td class="" align="right" colspan="3" >
          {$preference.pref_currency_sign} {$invoice.paid|utilNumber:2}
        </td>
      </tr>
      <tr>
        <td nowrap class="">{$LANG.owingUc}:</td>
        <td class="" align="right" colspan="3" >
          {$preference.pref_currency_sign} {$invoice.owing|utilNumber:2}
        </td>
      </tr>
    </table>
    <!-- Summary - end -->
    
    <table class="left">
      <!-- Biller section - start -->
      <tr>
        <td class="tbl1-bottom col1"><b>{$LANG.billerUc}:</b></td>
        <td class="col1 tbl1-bottom">{$biller.name|htmlSafe}</td>
      </tr> 
      {if isset($biller.street_address)}
        <tr>
          <td class=''>{$LANG.addressUc}:</td>
          <td class='' align="left" colspan="3">{$biller.street_address|htmlSafe}</td>
        </tr>
      {/if}
      {if isset($biller.street_address2) }
        {if !isset($biller.street_address) }
          <tr>
            <td class=''>{$LANG.addressUc}:</td>
            <td class='' align="left" colspan="3">{$biller.street_address2|htmlSafe}</td>
          </tr>   
        {/if}
        {if isset($biller.street_address) }
          <tr>
            <td class=''></td>
            <td class='' align="left" colspan="3">{$biller.street_address2|htmlSafe}</td>
          </tr>   
        {/if}
      {/if}
      {merge_address field1=$biller.city field2=$biller.state field3=$biller.zip_code street1=$biller.street_address street2=$biller.street_address2 class1="" class2="" colspan="3"}
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
        <td class="" colspan="4"> </td>
      </tr>
      <!-- Biller section - end -->
      <tr>
        <td colspan="4"><br /></td>
      </tr>
      <!-- Customer section - start -->
      <tr>
        <td class="tbl1-bottom col1" ><b>{$LANG.customerUc}:</b></td>
        <td class="tbl1-bottom col1" colspan="3">{$customer.name}</td>
      </tr>
      {if isset($customer.attention) }
        <tr>
          <td class=''>{$LANG.attentionShort}:</td>
          <td align="left" class='' colspan="3" >{$customer.attention|htmlSafe}</td>
        </tr>
      {/if}
          {if isset($customer.street_address) }
      <tr>
        <td class=''>{$LANG.addressUc}:</td>
        <td class='' align="left" colspan="3">{$customer.street_address|htmlSafe}</td>
      </tr>   
      {/if}
      {if isset($customer.street_address2)}
        {if !isset($customer.street_address)}
          <tr>
            <td class=''>{$LANG.addressUc}:</td>
            <td class='' align="left" colspan="3">{$customer.street_address2|htmlSafe}</td>
          </tr>   
        {/if}
        {if isset($customer.street_address)}
          <tr>
            <td class=''></td>
            <td class='' align="left" colspan="3">{$customer.street_address2|htmlSafe}</td>
          </tr>   
        {/if}
      {/if}
      
      {merge_address field1=$customer.city field2=$customer.state field3=$customer.zip_code street1=$customer.street_address street2=$customer.street_address2 class1="" class2="" colspan="3"}
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
  ************************** END COMMENTED OUT SECTION *************************** *}
    <table class="left" style="width:100%;;">
      <tr>
        <td colspan="6"><br /></td>
      </tr>
      {if $invoice.type_id == ITEMIZED_INVOICE }
        {foreach $product_group as $group_id => $group}
          <tr>
            <td class="col1 si_center" colspan="6">{$group.name|htmlSafe}</td>
          </tr>
          <tr>
            <td class="tbl1-bottom col1"><b>{$LANG.quantityShort}</b></td>
            <td class="tbl1-bottom col1" colspan="3"><b>{$LANG.item}</b></td>
            <td class="tbl1-bottom col1 si_right"><b>{$LANG.unitCost}</b></td>
            <td class="tbl1-bottom col1 si_right"><b>{$LANG.priceUc}</b></td>
          </tr>
          {foreach $invoiceItems as $k => $invoiceItem}
            {if $invoiceItem.product.custom_field1 == $group.name}
              <tr class="" >
                <td class="">{$invoiceItem.quantity|utilNumberTrim}</td>
                <td class="" colspan="3">{$invoiceItem.product.description|htmlSafe}</td>
                <td class="si_right">{$preference.pref_currency_sign} {$invoiceItem.unit_price|utilNumber}</td>
                <td class="si_right">{$preference.pref_currency_sign} {$invoiceItem.gross_total|utilNumber}</td>
              </tr>
              {if isset($invoiceItem.description)}
                <tr class="">
                  <td class=""></td>
                  <td class="" colspan="5">{$LANG.descriptionUc}: {$invoiceItem.description|htmlSafe}</td>
                </tr>
              {/if}
            {/if}
          {/foreach}
          <tr>
            <td colspan="5" class="si_right">Subtotal:</td>
            <td class="si_right">
              {$preference.pref_currency_sign} {subtotal cost=$invoiceItems group=$group.name}
            </td>
          </tr>
          <tr>
            <td colspan="5" class="si_right">
              Markup {markup_percentage cost=$invoiceItems group=$group.name}%{* {$group.markup|htmlSafe} *}:
            </td>
            <td class="si_right">
              {$preference.pref_currency_sign} {markup cost=$invoiceItems group=$group.name}
            </td>
          </tr>
          <tr>
            <td colspan="5" class="si_right">Total:</td>
            <td class="si_right">
              {$preference.pref_currency_sign} {total cost=$invoiceItems group=$group.name}
            </td>
          </tr>
          <tr>
            <td><br></td>
          </tr>
        {/foreach}
      {/if}
{* ************************ BEGIN COMMENTED OUT SECTION ****************************
      {if ($invoice.type_id == ITEMIZED_INVOICE && $invoice.note != "") ||
          ($invoice.type_id == CONSULTING_INVOICE && $invoice.note != "" )  }
        <tr>
          <td class="" colspan="6"><br></td>
        </tr>
        <tr>
          <td class="" colspan="6" align="left"><b>{$LANG.notes}:</b></td>
        </tr>
        <tr>
          <td class="" colspan="6">{$invoice.note|outHtml}</td>
        </tr>
      {/if}
  ************************** END COMMENTED OUT SECTION *************************** *}
      <tr class="">
        <td class="" colspan="6" ><br></td>
      </tr>
{* ************************ BEGIN COMMENTED OUT SECTION ****************************
      {if $invoice.type_id == TOTAL_INVOICE} <!-- Only Type TOTAL_INVOICE is a single entry - hence last row gross is valid as gross_total - see Invoice 2 in sample data-->
        <tr>
          <td class="" colspan="2"></td>
          <td align="right" colspan="3">{$LANG.grossTotal}</td>
          <td align="right" class="">{$preference.pref_currency_sign} {$invoiceItems.0.gross_total|utilNumber}</td>
        </tr>
      {/if}
      {section name=line start=0 loop=$invoice.tax_grouped step=1}
        {if ($invoice.tax_grouped[line].tax_amount != "0") }  
          <tr class=''>
            <td colspan="2"></td>
            <td colspan="3" align="right">{$invoice.tax_grouped[line].tax_name|htmlSafe}</td>
            <td colspan="1" align="right">
              {$preference.pref_currency_sign} {$invoice.tax_grouped[line].tax_amount|utilNumber}
            </td>
          </tr>
        {/if}
      {/section}
      <tr class=''>
        <td colspan="2"></td>
        <td colspan="3" align="right">{$LANG.taxTotal}</td>
        <td colspan="1" align="right" style="text-decoration:underline;">
          {$preference.pref_currency_sign} {$invoice.total_tax|utilNumber}
        </td>
      </tr>
  ************************** END COMMENTED OUT SECTION *************************** *}
      <tr class="">
        <td class="" colspan="6" ><br></td>
      </tr>
      <tr class="">
        <th colspan="6" class="si_center">
          <span class="font1 double_underline" >
            {$LANG.totalFullUc} {$preference.pref_currency_sign} {$invoice.total|utilNumber}
          </span>
        </th>
      </tr>
{* ************************ BEGIN COMMENTED OUT SECTION ****************************
      <tr>
        <td colspan="6"><br /><br /></td>
      </tr>
      <!-- invoice details section - start -->
      <tr>
        <td class="tbl1-bottom col1" colspan="6"><b>{$preference.pref_inv_detail_heading|htmlSafe}</b></td>
      </tr>
      <tr>
        <td class="" colspan="6"><i>{$preference.pref_inv_detail_line|htmlSafe}</i></td>
      </tr>
      <tr>
        <td class="" colspan="6">{$preference.pref_inv_payment_method|htmlSafe}</td>
      </tr>
      <tr>
        <td class="" colspan="6">
          {$preference.pref_inv_payment_line1_name|htmlSafe} {$preference.pref_inv_payment_line1_value|htmlSafe}
        </td>
      </tr>
      <tr>
        <td class="" colspan="6">
          {$preference.pref_inv_payment_line2_name|htmlSafe} {$preference.pref_inv_payment_line2_value|htmlSafe}
        </td>
      </tr>
      <tr>
        <td><br></td>
      </tr>
      <tr>
        <td colspan="6"><div style="font-size:8pt;" align="center">{$biller.footer|outHtml}</div></td>
      </tr>
  ************************** END COMMENTED OUT SECTION *************************** *}
    <!-- invoice details section - end -->
    </table>
  </div>
</body>
</html>
