<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="{$css|urlsafe}" media="all">
    <title>{$preference.pref_inv_wording|htmlsafe}
        {$LANG.number_short}: {$invoice.index_id|htmlsafe}</title>
</head>
<body>
<br/>
<div id="container">
    <div id="header"></div>
    <table width="100%" class="center">
        <tr>
            <td colspan="5"><img src="{holiday_logo logo=$logo|urlsafe}" border="0" hspace="0" align="left"></td>
            <th align="right"><span class="font1">{$preference.pref_inv_heading|htmlsafe} #{$invoice.index_id}</span></th>
        </tr>
        <tr>
            <td colspan="6" class="tbl1-top">&nbsp;</td>
        </tr>
    </table>
    <table class="top-table">
        <tr>
            <td style="width:50%;">
                <!-- Biller section - start -->
                <table class="top-left">
                    <tr>
                        <td>
                    <tr>
                        <td class="tbl1-bottom col1"><b>{$LANG.biller}:</b></td>
                        <td class="col1 tbl1-bottom" colspan="3">{$biller.name|htmlsafe}</td>
                    </tr>
                    {if isset($biller.street_address)}
                        <tr>
                            <td class=''>{$LANG.address_uc}:</td>
                            <td class='' align=left colspan="3">{$biller.street_address|htmlsafe}</td>
                        </tr>
                    {/if}
                    {if isset($biller.street_address2) }
                        {if !isset($biller.street_address) }
                            <tr>
                                <td class=''>{$LANG.address_uc}:</td>
                                <td class='' align=left colspan="3">{$biller.street_address2|htmlsafe}</td>
                            </tr>
                        {/if}
                        {if isset($biller.street_address) }
                            <tr>
                                <td class=''></td>
                                <td class='' align=left colspan="3">{$biller.street_address2|htmlsafe}</td>
                            </tr>
                        {/if}
                    {/if}
                    {merge_address field1=$biller.city field2=$biller.state field3=$biller.zip_code
                    street1=$biller.street_address street2=$biller.street_address2
                    class1="" class2="" colspan="3"}
                    {if isset($biller.country) }
                        <tr>
                            <td class=''></td>
                            <td class='' colspan="3">{$biller.country|htmlsafe}</td>
                        </tr>
                    {/if}
                    {print_if_not_null label=$LANG.phone field=$biller.phone class1='' class2='' colspan="3"}
                    {print_if_not_null label=$LANG.mobile_short field=$biller.mobile_phone class1='' class2='' colspan="3"}
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
                        <td colspan="4"><br/></td>
                    </tr>
                </table>
                <!-- Biller section - end -->
                <table class="top-left">
                    <!-- Customer section - start -->
                    <tr style="background-color: green;">
                        <td class="tbl1-bottom col1"><b>{$LANG.customer}: </b></td>
                        <td class="tbl1-bottom col1" colspan="3">{$customer.name|htmlsafe}</td>
                    </tr>
                    {if isset($customer.attention) }
                        <tr>
                            <td class=''>{$LANG.attention_short}:</td>
                            <td align=left class='' colspan="3">{$customer.attention|htmlsafe}</td>
                        </tr>
                    {/if}
                    {if isset($customer.street_address) }
                        <tr>
                            <td class=''>{$LANG.address_uc}:</td>
                            <td class='' align=left colspan="3">{$customer.street_address|htmlsafe}</td>
                        </tr>
                    {/if}
                    {if isset($customer.street_address2)}
                        {if !isset($customer.street_address)}
                            <tr>
                                <td class=''>{$LANG.address_uc}:</td>
                                <td class='' align=left colspan="3">{$customer.street_address2|htmlsafe}</td>
                            </tr>
                        {/if}
                        {if isset($customer.street_address)}
                            <tr>
                                <td class=''></td>
                                <td class='' align=left colspan="3">{$customer.street_address2|htmlsafe}</td>
                            </tr>
                        {/if}
                    {/if}
                    {merge_address field1=$customer.city field2=$customer.state field3=$customer.zip_code
                    street1=$customer.street_address street2=$customer.street_address2
                    class1="" class2="" colspan="3"}
                    {if isset($customer.country)}
                        <tr>
                            <td class=''></td>
                            <td class='' colspan="3">{$customer.country|htmlsafe}</td>
                        </tr>
                    {/if}
                    {print_if_not_null label=$LANG.phone field=$customer.phone class1='' class2='t' colspan="3"}
                    {print_if_not_null label=$LANG.mobile_short field=$customer.mobile_phone class1='' class2='' colspan="3"}
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
            </td>
            <td>
                <!-- Summary - start -->
                <table class="top-right">
                    <tr style="text-align: left; max-width:250px;">
                        <td class="col1 tbl1-bottom" colspan="4" style="text-align: left;vertical-align: top;">
                            <b>{$preference.pref_inv_wording|htmlsafe}&nbsp;{$LANG.summary_uc}</b>
                        </td>
                    </tr>
                    <tr style="text-align: left;">
                        <td style="text-align: left;" class="">{$preference.pref_inv_wording|htmlsafe}&nbsp;{$LANG.number_short}:</td>
                        <td class="" style="text-align:right;" colspan="3">{$invoice.index_id}</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;" class="">{$preference.pref_inv_wording|htmlsafe}&nbsp;{$LANG.date}:</td>
                        <td class="" style="text-align:right;" colspan="3">{$invoice.date|siLocal_date}</td>
                    </tr>
                    <!-- Show the Invoice Custom Fields if valid -->
                    {if !empty($customFieldLabels.invoice_cf1) && isset($invoice.custom_field1)}
                        <tr>
                            <td style="text-align: left;" class="">{$customFieldLabels.invoice_cf1|htmlsafe}:</td>
                            <td style="text-align: left;" class="" align="right" colspan="3">{$invoice.custom_field1|htmlsafe}</td>
                        </tr>
                    {/if}
                    {if !empty($customFieldLabels.invoice_cf2) && isset($invoice.custom_field2)}
                        <tr>
                            <td style="text-align:left" class="">{$customFieldLabels.invoice_cf2|htmlsafe}:</td>
                            <td style="text-align:left" class="" align="right" colspan="3">{$invoice.custom_field2|htmlsafe}</td>
                        </tr>
                    {/if}
                    {if !empty($customFieldLabels.invoice_cf3) && isset($invoice.custom_field3)}
                        <tr>
                            <td nowrap class="">{$customFieldLabels.invoice_cf3|htmlsafe}:</td>
                            <td class="" align="right" colspan="3">{$invoice.custom_field3|htmlsafe}</td>
                        </tr>
                    {/if}
                    {if !empty($customFieldLabels.invoice_cf4) && isset($invoice.custom_field4)}
                        <tr>
                            <td nowrap class="">{$customFieldLabels.invoice_cf4|htmlsafe}:</td>
                            <td class="" align="right" colspan="3">{$invoice.custom_field4|htmlsafe}</td>
                        </tr>
                    {/if}
                    <tr>
                        <td class="">{$LANG.total}:</td>
                        <td class="" style="text-align:right;" colspan="3">
                            {$preference.pref_currency_sign}{$invoice.total|siLocal_number}
                        </td>
                    </tr>
                    <tr>
                        <td class="">{$LANG.paid}:</td>
                        <td class="" style="text-align:right;" colspan="3">
                            {$preference.pref_currency_sign}{$invoice.paid|siLocal_number}
                        </td>
                    </tr>
                    <tr>
                        <td class="">{$LANG.owing_uc}:</td>
                        <td class="" style="text-align:right;" colspan="3">
                            {$preference.pref_currency_sign}{$invoice.owing|siLocal_number}
                        </td>
                    </tr>
                </table>
                <!-- Summary - end -->
            </td>
        </tr>
    </table>
    <table class="left items" style="width:100%;;">
        <tr>
            <td colspan="6"><br/></td>
        </tr>
        {if $invoice.type_id == ITEMIZED_INVOICE}
            <tr>
                <td class="tbl1-bottom col1"><b>{$LANG.quantity_short}</b></td>
                <td class="tbl1-bottom col1" colspan="3"><b>{$LANG.item}</b></td>
                <td class="tbl1-bottom col1"style="text-align:right;"><b>{$LANG.unit_cost}</b></td>
                <td class="tbl1-bottom col1"style="text-align:right;"><b>{$LANG.price}</b></td>
            </tr>
            {foreach from=$invoiceItems item=invoiceItem}
                <tr class="">
                    <td class="">{$invoiceItem.quantity|siLocal_number_trim}</td>
                    <td class="" colspan="3">{$invoiceItem.product.description|htmlsafe}</td>
                    <td class="" style="text-align:right;">
                        {$preference.pref_currency_sign}{$invoiceItem.unit_price|siLocal_number}</td>
                    <td class="" style="text-align:right;">
                        {$preference.pref_currency_sign}{$invoiceItem.gross_total|siLocal_number}</td>
                </tr>
                {if isset($invoiceItem.attribute)}
                    <tr class="si_product_attribute">
                        <td></td>
                        <td>
                            <table>
                                <tr class="si_product_attribute">
                                    {foreach from=$invoiceItem.attribute_json key=k item=v}
                                        {if $v.visible ==true }
                                            <td class="si_product_attribute">
                                                {if $v.type == 'decimal'}
                                                    {$v.name}: {$preference.pref_currency_sign} {$v.value|siLocal_number};
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
                <td class="tbl1-bottom "><b>{$LANG.quantity_short}</b></td>
                <td colspan="3" class=" tbl1-bottom"><b>{$LANG.item}</b></td>
                <td class=" tbl1-bottom" style="text-align:right"><b>{$LANG.unit_cost}</b></td>
                <td class=" tbl1-bottom" style="text-align:right"><b>{$LANG.price}</b></td>
            </tr>
            {foreach from=$invoiceItems item=invoiceItem}
                <tr class=" ">
                    <td class="">{$invoiceItem.quantity|siLocal_number_trim}</td>
                    <td>{$invoiceItem.product.description|htmlsafe}</td>
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
                    <td class="" colspan="5"><i>{$LANG.description}: </i>{$invoiceItem.description|htmlsafe}</td>
                </tr>
                <tr class="">
                    <td class=""></td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class=""></td>
                    <td class="" style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.unit_price|siLocal_number}</td>
                    <td class="" style="text-align:right">{$preference.pref_currency_sign}{$invoiceItem.total|siLocal_number}</td>
                </tr>
            {/foreach}
        {/if}
        {if $invoice.type_id == TOTAL_INVOICE}
            <tr>
                <td>
                    <table class="left" style="width:100%;;">
                        <tr class="col1">
                            <td class="tbl1-bottom col1" colspan="6"><b>{$LANG.description}</b></td>
                        </tr>
                        {foreach from=$invoiceItems item= invoiceItem}
                            <tr class="">
                                <td class="t" colspan="6">{$invoiceItem.description|outhtml}</td>
                            </tr>
                        {/foreach}
                    </table>
                </td>
            </tr>
        {/if}
        {if ($invoice.type_id == ITEMIZED_INVOICE   && $invoice.note != "") ||
            ($invoice.type_id == CONSULTING_INVOICE && $invoice.note != "" ) }
            <tr>
                <td class="" colspan="6"><br/></td>
            </tr>
        {/if}
        <tr class="">
            <td class="" colspan="6"><br/></td>
        </tr>
        {* tax section - start *}
        {if $invoice_number_of_taxes > 0}
            <tr>
                <td colspan="2"></td>
                <td colspan="3" style="text-align:right">{$LANG.sub_total}&nbsp;</td>
                <td colspan="1" style="text-align:right" {if $invoice_number_of_taxes > 1}style="text-decoration:underline;"{/if}>
                    {$preference.pref_currency_sign}{$invoice.gross|siLocal_number}
                </td>
            </tr>
        {/if}
        {if $invoice_number_of_taxes > 1 }
            <tr>
                <td colspan="6"><br/></td>
            </tr>
        {/if}
        {section name=line start=0 loop=$invoice.tax_grouped step=1}
            {if ($invoice.tax_grouped[line].tax_amount != "0") }
                <tr>
                    <td colspan="2"></td>
                    <td colspan="3" style="text-align:right">{$invoice.tax_grouped[line].tax_name|htmlsafe}&nbsp;</td>
                    <td colspan="1" style="text-align:right">
                        {$preference.pref_currency_sign}{$invoice.tax_grouped[line].tax_amount|siLocal_number}
                    </td>
                </tr>
            {/if}
        {/section}
        {if $invoice_number_of_taxes > 1}
            <tr>
                <td colspan="2"></td>
                <td colspan="3" style="text-align:right">{$LANG.tax_total}&nbsp;</td>
                <td colspan="1" style="text-align:right;text-decoration:underline;">
                    {$preference.pref_currency_sign}{$invoice.total_tax|siLocal_number}
                </td>
            </tr>
        {/if}
        {if $invoice_number_of_taxes > 1}
            <tr>
                <td colspan="6"><br/></td>
            </tr>
        {/if}
        <tr>
            <td colspan="2"></td>
            <td colspan="3" style="text-align:right">
                <b>{$preference.pref_inv_wording|htmlsafe}&nbsp;{$LANG.amount_uc}</b>
            </td>
            <td colspan="1" style="text-align:right">
                <span class="double_underline" style="text-decoration:underline;">
                    {$preference.pref_currency_sign}{$invoice.total|siLocal_number}
                </span>
            </td>
        </tr>
        {* tax section - end *}
        {* ***************************************************************************
        <tr>
          <td class="" colspan="2"></td>
          <td align="right" colspan="3">{$LANG.sub_total}</td>
          <td align="right" class="">{$preference.pref_currency_sign}{$invoice.gross|siLocal_number}</td>
        </tr>
        {section name=line start=0 loop=$invoice.tax_grouped step=1}
          {if ($invoice.tax_grouped[line].tax_amount != "0") }
            <tr class=''>
              <td colspan="2"></td>
              <td colspan="3" align="right">{$invoice.tax_grouped[line].tax_name|htmlsafe}</td>
              <td colspan="1" align="right">
                {$preference.pref_currency_sign}{$invoice.tax_grouped[line].tax_amount|siLocal_number}
              </td>
            </tr>
          {/if}
        {/section}
        <tr class=''>
          <td colspan="2"></td>
          <td colspan="3" align="right">{$LANG.tax_total}</td>
          <td colspan="1" align="right" style="text-decoration:underline;">
            {$preference.pref_currency_sign}{$invoice.total_tax|siLocal_number}
          </td>
        </tr>
        <tr class="">
          <td class="" colspan="6"><br /></td>
        </tr>
        <tr class="">
          <td class="" colspan="2"></td>
          <td class="" align="right" colspan="3">
            <b>{$preference.pref_inv_wording|htmlsafe}&nbsp;{$LANG.amount_uc}</b>
          </td>
          <td class="" align="right">
            <span class="double_underline">
              {$preference.pref_currency_sign}{$invoice.total|siLocal_number}
            </span>
          </td>
        </tr>
        *************************************************************************** *}
        <tr>
            <td colspan="6">
                <br/>
                <br/>
            </td>
        </tr>
        <tr>
            <td class="tbl1-bottom col1" colspan="6" align="left"><b>{$LANG.notes}:</b></td>
        </tr>
        <tr>
            <td class="" colspan="6">{$invoice.note|outhtml}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <!-- invoice details section - start -->
        <tr>
            <td class="tbl1-bottom col1 details" colspan="6"><b>{$preference.pref_inv_detail_heading|htmlsafe}</b></td>
        </tr>
        <tr>
            <td class="" colspan="6"><i>{$preference.pref_inv_detail_line|outhtml}</i></td>
        </tr>
        <tr>
            <td class="" colspan="6">{$preference.pref_inv_payment_method|htmlsafe}</td>
        </tr>
        <tr>
            <td class="" colspan="6">
                </br>
            </td>
        </tr>
        <tr>
            <td class="" colspan="6">
                </br>{$preference.pref_inv_payment_line1_name|htmlsafe}</br>
            </td>
        </tr>
        <tr>
            <td class="" colspan="6">
                {$preference.pref_inv_payment_line1_value|htmlsafe}
            </td>
        </tr>
        <tr>
            <td class="" colspan="6">
                {$preference.pref_inv_payment_line2_name|htmlsafe}</br>
            </td>
        </tr>
        <tr>
            <td class="" colspan="6">
                {$preference.pref_inv_payment_line2_value|htmlsafe}
            </td>
        </tr>
        <tr>
            <td><br/></td>
        </tr>
        <tr>
            <td colspan="6">
                <div style="font-size: 8pt;" align="center">{$biller.footer|outhtml}</div>
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
                link_wording=$LANG.paypal_link
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
