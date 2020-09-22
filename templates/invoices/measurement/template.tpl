<!--suppress HtmlRequiredLangAttribute -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" href="{$css}" media="all">
    <title>{$preference.pref_inv_wording} {$LANG.numberShort}: {$invoice.index_id}</title>
</head>
<body>
<br/>
<div id="container">
    <div id="header"></div>
    <table style="width:100%;" class="center">
        <tr>
            <td colspan="5"><img src="{$logo}" style="border:0; margin:0 0;" alt=""></td>
            <th class="si_right"><span class="font1">{$preference.pref_inv_heading}</span></th>
        </tr>
        <tr>
            <td colspan="6" class="tbl1-top">&nbsp;</td>
        </tr>
    </table>

    <!-- Summary - start -->
    <table class="right">
        <tr>
            <td class="col1 tbl1-bottom" colspan="4"><b>{$preference.pref_inv_wording} {$LANG.summaryUc}</b></td>
        </tr>
        <tr>
            <td class="">{$preference.pref_inv_wording} {$LANG.numberShort}:</td>
            <td class="si_right" colspan="3">{$invoice.index_id}</td>
        </tr>
        <tr>
            <td nowrap class="">{$preference.pref_inv_wording} {$LANG.dateUc}:</td>
            <td class="si_right" colspan="3">{$invoice.date}</td>
        </tr>
        <!-- Show the Invoice Custom Fields if valid -->
        {if !empty($customFieldLabels.invoice_cf1) && isset($invoice.custom_field1)}
            <tr>
                <td nowrap class="">{$customFieldLabels.invoice_cf1}:</td>
                <td class="si_right" colspan="3">{$invoice.custom_field1}</td>
            </tr>
        {/if}
        {if !empty($customFieldLabels.invoice_cf2) && isset($invoice.custom_field2)}
            <tr>
                <td nowrap class="">{$customFieldLabels.invoice_cf2}:</td>
                <td class="si_right" colspan="3">{$invoice.custom_field2}</td>
            </tr>
        {/if}
        {if !empty($customFieldLabels.invoice_cf3) && isset($invoice.custom_field3)}
            <tr>
                <td nowrap class="">{$customFieldLabels.invoice_cf3}:</td>
                <td class="si_right" colspan="3">{$invoice.custom_field3}</td>
            </tr>
        {/if}
        {if !empty($customFieldLabels.invoice_cf4) && isset($invoice.custom_field4)}
            <tr>
                <td nowrap class="">{$customFieldLabels.invoice_cf4}:</td>
                <td class="si_right" colspan="3">{$invoice.custom_field4}</td>
            </tr>
        {/if}

        <tr>
            <td class="">{$LANG.total}:</td>
            <td class="si_right" colspan="3">{$invoice.total|utilNumber} mm</td>
        </tr>

    </table>
    <!-- Summary - end -->

    <table class="left">
        <!-- Biller section - start -->
        <tr>
            <td class="tbl1-bottom col1" style="border:1px; padding:2px; border-spacing:1px;"><b>{$LANG.biller}:</b></td>
            <td class="col1 tbl1-bottom" style="border:1px; padding:2px; border-spacing:1px;" colspan="3">{$biller.name}</td>
        </tr>

        {if isset($biller.street_address)}
            <tr>
                <td class=''>{$LANG.addressUc}:</td>
                <td class='' colspan="3">{$biller.street_address}</td>
            </tr>
        {/if}
        {if isset($biller.street_address2) }
            {if !isset($biller.street_address) }
                <tr>
                    <td class=''>{$LANG.addressUc}:</td>
                    <td class='' colspan="3">{$biller.street_address2}</td>
                </tr>
            {/if}
            {if isset($biller.street_address) }
                <tr>
                    <td class=''></td>
                    <td class='' colspan="3">{$biller.street_address2}</td>
                </tr>
            {/if}
        {/if}

        {merge_address field1=$biller.city field2=$biller.state field3=$biller.zip_code street1=$biller.street_address street2=$biller.street_address2 class1="" class2="" colspan="3"}

        {if isset($biller.country) }
            <tr>
                <td class=''></td>
                <td class='' colspan="3">{$biller.country}</td>
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
            <td colspan="4"><br/></td>
        </tr>

        <!-- Customer section - start -->
        <tr>
            <td class="tbl1-bottom col1"><b>{$LANG.customer}:</b></td>
            <td class="tbl1-bottom col1" colspan="3">{$customer.name}</td>
        </tr>

        {if isset($customer.attention) }
            <tr>
                <td class=''>{$LANG.attentionShort}:</td>
                <td class='' colspan="3">{$customer.attention}</td>
            </tr>
        {/if}
        {if isset($customer.street_address) }
            <tr>
                <td class=''>{$LANG.addressUc}:</td>
                <td class='' colspan="3">{$customer.street_address}</td>
            </tr>
        {/if}
        {if isset($customer.street_address2)}
            {if !isset($customer.street_address)}
                <tr>
                    <td class=''>{$LANG.addressUc}:</td>
                    <td class='' colspan="3">{$customer.street_address2}</td>
                </tr>
            {/if}
            {if isset($customer.street_address)}
                <tr>
                    <td class=''></td>
                    <td class='' colspan="3">{$customer.street_address2}</td>
                </tr>
            {/if}
        {/if}

        {merge_address field1=$customer.city field2=$customer.state field3=$customer.zip_code street1=$customer.street_address street2=$customer.street_address2 class1="" class2="" colspan="3"}

        {if isset($customer.country)}
            <tr>
                <td class=''></td>
                <td class='' colspan="3">{$customer.country}</td>
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

    <table class="left" style="width:100%;">
        <tr>
            <td colspan="6"><br/></td>
        </tr>

        {if $invoice.type_id == ITEMIZED_INVOICE}
            <tr>
                <td class="tbl1-bottom col1"><b>{$LANG.quantityShort}</b></td>
                <td class="tbl1-bottom col1"></td>
                <td class="tbl1-bottom col1" colspan="3"><b>mm</b></td>
                <td class="tbl1-bottom col1 si_right"><b>{$LANG.total}</b></td>
            </tr>
            {foreach $invoiceItems as $invoiceItem}
                <tr class="">
                    <td class="">{$invoiceItem.quantity|utilNumberTrim}</td>
                    <td class="">*</td>
                    <td class="" colspan="3">{$invoiceItem.unit_price|utilNumber}</td>
                    <td class="si_right">{$invoiceItem.gross_total|utilNumber} mm</td>
                </tr>
                {if isset($invoiceItem.description)}
                    <tr class="">
                        <td class=""></td>
                        <td class="" colspan="5">{$LANG.descriptionUc}: {$invoiceItem.description}</td>
                    </tr>
                {/if}
                <tr class="tbl1-bottom">
                    <td class=""></td>
                    <td class="" colspan="5">
                        <table style="width:100%;">
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
                <td class="tbl1-bottom "><b>{$LANG.quantityShort}</b></td>
                <td colspan="3" class=" tbl1-bottom"><b>{$LANG.item}</b></td>
                <td class=" tbl1-bottom si_right"><b>{$LANG.unitCost}</b></td>
                <td class=" tbl1-bottom si_right"><b>{$LANG.price}</b></td>
            </tr>
            {foreach $invoiceItems as $invoiceItem}
                <tr class=" ">
                    <td class="">{$invoiceItem.quantity|utilNumber}</td>
                    <td>{$invoiceItem.product.description}</td>
                    <td class="" colspan="4"></td>
                </tr>
                <tr>
                    <td class=""></td>
                    <td class="" colspan="5">
                        <table style="width:100%;">
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
                    <td class="" colspan="5"><i>{$LANG.descriptionUc}: </i>{$invoiceItem.description}</td>
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
        <table class="left" style="width:100%;">

            <tr class="col1">
                <td class="tbl1-bottom col1" colspan="6"><b>{$LANG.descriptionUc}</b></td>
            </tr>

            {foreach $invoiceItems as $invoiceItem}
                <tr class="">
                    <td class="t" colspan="6">{$invoiceItem.description|unescape}</td>
                </tr>
            {/foreach}
            {/if}

            {if ($invoice.type_id == ITEMIZED_INVOICE && $invoice.note != "") || ($invoice.type_id == CONSULTING_INVOICE && $invoice.note != "" )  }
                <tr>
                    <td class="" colspan="6"><br/></td>
                </tr>
                <tr>
                    <td class="" colspan="6"><b>{$LANG.notes}:</b></td>
                </tr>
                <tr>
                    <td class="" colspan="6">{$invoice.note|unescape}</td>
                </tr>
            {/if}

            <tr class="">
                <td class="" colspan="6"><br/></td>
            </tr>

            <tr class="">
                <td class="" colspan="6"><br/></td>
            </tr>
            <tr class="">
                <td class="" colspan="2"></td>
                <td class="si_right" colspan="3"><b>{$preference.pref_inv_wording} {$LANG.amountUc}</b></td>
                <td class="si_right"><span class="double_underline">{$invoice.total|utilNumber} mm</span></td>
            </tr>
            <tr>
                <td colspan="6"><br/><br/></td>
            </tr>

            <!-- invoice details section - start -->
            <tr>
                <td class="tbl1-bottom col1" colspan="6"><b>{$preference.pref_inv_detail_heading}</b></td>
            </tr>
            <tr>
                <td class="" colspan="6"><i>{$preference.pref_inv_detail_line}</i></td>
            </tr>
            <tr>
                <td class="" colspan="6">{$preference.pref_inv_payment_method}</td>
            </tr>
            <tr>
                <td class="" colspan="6">{$preference.pref_inv_payment_line1_name} {$preference.pref_inv_payment_line1_value}</td>
            </tr>
            <tr>
                <td class="" colspan="6">{$preference.pref_inv_payment_line2_name} {$preference.pref_inv_payment_line2_value}</td>
            </tr>
            <tr>
                <td><br/></td>
            </tr>
            <tr>
                <td colspan="6">
                    <div style="font-size:8pt;" class="si_center">{$biller.footer}</div>
                </td>
            </tr>
            <tr>
                <td>
                    {online_payment_link
                    type=$preference.include_online_payment business=$biller.paypal_business_name
                    item_name=$invoice.index_name invoice=$invoice.index_id
                    amount=$invoice.total currency_code=$preference.currency_code
                    link_wording=$LANG.paypalLink
                    notify_url=$biller.paypal_notify_url return_url=$biller.paypal_return_url
                    domain_id = $invoice.domain_id include_image=true
                    }

                </td>
            </tr>
            <!-- invoice details section - end -->
        </table>
        <div id="footer"></div>
</div>
</body>
</html>
