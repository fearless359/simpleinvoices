<!--suppress HtmlRequiredLangAttribute -->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="{$css|urlSafe}" media="all" />
        <title>{$preference.pref_inv_wording|htmlSafe} {$LANG.numberShort}: {$invoice.index_id|htmlSafe}</title>
    </head>
    <body>
        <header>
            <!-- Parties -->
            <table class="clean">
                <tbody>
                    <tr>
                        <!-- Biller area -->
                        <td style="text-align: left; width: 40%;">
                            <table style="table-layout: fixed;vertical-align: bottom;">
                                <tbody>
                                    <!-- Biller fixed fields -->
                                    <tr>
                                        <td class="clean left emph"><strong>{$LANG.billerUc}</strong></td>
                                        <td class="clean left emph" style="font-weight: bold;">{$biller.name|htmlSafe}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$LANG.addressUc}:</td>
                                        <td class="clean left">
                                            {if $biller.street_address != null}{$biller.street_address|htmlSafe}{/if} {if $biller.city != null }{$biller.city|htmlSafe},{/if} {if $biller.state != null } {$biller.state|htmlSafe},{/if} {if
                                            $biller.zip_code != null } {$biller.zip_code|htmlSafe} {/if} {if $biller.country != null }, {$biller.country|htmlSafe}{/if}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="clean left"></td>
                                        <td class="clean left">{if $biller.street_address2 != null}{$biller.street_address2|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$LANG.email}:</td>
                                        <td class="clean left">{if $biller.email != null }{$biller.email|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$LANG.mobileShort}:</td>
                                        <td class="clean left">{if $biller.mobile_phone != null }{$biller.mobile_phone|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$LANG.phoneUc}:</td>
                                        <td class="clean left">{if $biller.phone != null }{$biller.phone|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$LANG.fax}:</td>
                                        <td class="clean left">{if $biller.fax != null }{$biller.fax|htmlSafe}{/if}</td>
                                    </tr>
                                    <!-- Biller custom fields -->
                                    <tr>
                                        <td class="clean left">{$customFieldLabels.biller_cf1}:</td>
                                        <td class="clean left">{if $biller.custom_field1 != null }{$biller.custom_field1|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$customFieldLabels.biller_cf2}:</td>
                                        <td class="clean left">{if $biller.custom_field2 != null }{$biller.custom_field2|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$customFieldLabels.biller_cf3}:</td>
                                        <td class="clean left">{if $biller.custom_field3 != null }{$biller.custom_field3|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$customFieldLabels.biller_cf4}:</td>
                                        <td class="clean left">{if $biller.custom_field4 != null }{$biller.custom_field4|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">&nbsp;</td>
                                        <td class="clean left">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">&nbsp;</td>
                                        <td class="clean left">&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <!-- Invoice details & Logo area -->
                        <td style="text-align: center; width: 20%;">
                            <table style="table-layout: fixed;vertical-align: bottom;">
                                <tbody>
                                    <tr class="clean" style="font-weight: bold;height: 40px;">
                                        <td class="clean center"  style="font-weight: bold;margin: 0px;" colspan="2" >
                                            <img src="{$logo|urlSafe}" alt="" style="margin-left: auto; margin-right: auto; max-width: 140px;" class="clean center" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">&nbsp;</td>
                                        <td class="clean left"></td>
                                    </tr>
                                    <tr class="clean center">
                                        <td class="clean center" style="font-weight: bold;" colspan="2">
                                            <h2 class="inv-heading">{$preference.pref_inv_heading|htmlSafe}</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">&nbsp;</td>
                                        <td class="clean left"></td>
                                    </tr>
                                    <tr>
                                        <td class="clean left emph" style="font-weight: bold;">{$LANG.numberShort}: {$invoice.index_id}</td>
                                        <td class="clean left emph" style="font-weight: bold;">{$LANG.dateUc}: {$invoice.date|date_format:"%d.%m.%Y"}</td>
                                    </tr>
                                    <!-- Invoice custom fields -->
                                    <tr>
                                        {if $invoice.custom_field1 != null}
                                        <td class="clean left">{$customFieldLabels.invoice_cf1|htmlSafe}:</td>
                                        <td class="clean left">{$invoice.custom_field1|htmlSafe}</td>
                                        {else}
                                        <td class="clean left">&nbsp;</td>
                                        <td class="clean left">&nbsp;</td>
                                        {/if}
                                    </tr>
                                    <tr>
                                        {if $invoice.custom_field2 != null}
                                        <td class="clean left">{$customFieldLabels.invoice_cf2|htmlSafe}:</td>
                                        <td class="clean left">{$invoice.custom_field2|htmlSafe}</td>
                                        {else}
                                        <td class="clean left">&nbsp;</td>
                                        <td class="clean left">&nbsp;</td>
                                        {/if}
                                    </tr>
                                    <tr>
                                        {if $invoice.custom_field3 != null}
                                        <td class="clean left">{$customFieldLabels.invoice_cf3|htmlSafe}:</td>
                                        <td class="clean left">{$invoice.custom_field3|htmlSafe}</td>
                                        {else}
                                        <td class="clean left">&nbsp;</td>
                                        <td class="clean left">&nbsp;</td>
                                        {/if}
                                    </tr>
                                    <tr>
                                        {if $invoice.custom_field4 != null}
                                        <td class="clean left">{$customFieldLabels.invoice_cf4|htmlSafe}:</td>
                                        <td class="clean left">{$invoice.custom_field4|htmlSafe}</td>
                                        {else}
                                        <td class="clean left">&nbsp;</td>
                                        <td class="clean left">&nbsp;</td>
                                        {/if}
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <!-- Customer area -->
                        <td style="text-align: left; width: 40%; ">
                            <table style="table-layout: fixed;vertical-align: bottom;">
                                <tbody>
                                    <!-- Customer fixed fields -->
                                    <tr>
                                        <td class="clean left emph"><b>{$LANG.customerUc}</b></td>
                                        <td class="clean left emph" style="font-weight: bold;">{$customer.name|htmlSafe}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$LANG.addressUc}:</td>
                                        <td class="clean left">
                                            {if $customer.street_address != null}{$customer.street_address|htmlSafe}{/if} {if $customer.city != null }{$customer.city|htmlSafe},{/if} {if $customer.state != null } {$customer.state|htmlSafe},{/if}
                                            {if $customer.zip_code != null } {$customer.zip_code|htmlSafe} {/if} {if $customer.country != null }, {$customer.country|htmlSafe}{/if}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="clean left"></td>
                                        <td class="clean left">{if $customer.street_address2 != null}{$customer.street_address2|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$LANG.email}:</td>
                                        <td class="clean left">{if $customer.email != null }{$customer.email|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$LANG.mobileShort}:</td>
                                        <td class="clean left">{if $customer.mobile_phone != null }{$customer.mobile_phone|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$LANG.phoneUc}:</td>
                                        <td class="clean left">{if $customer.phone != null }{$customer.phone|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$LANG.fax}:</td>
                                        <td class="clean left">{if $customer.fax != null }{$customer.fax|htmlSafe}{/if}</td>
                                    </tr>
                                    <!-- Customer custom fields -->
                                    <tr>
                                        <td class="clean left">{$customFieldLabels.customer_cf1}:</td>
                                        <td class="clean left">{if $customer.custom_field1 != null }{$customer.custom_field1|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$customFieldLabels.customer_cf2}:</td>
                                        <td class="clean left">{if $customer.custom_field2 != null }{$customer.custom_field2|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$customFieldLabels.customer_cf3}:</td>
                                        <td class="clean left">{if $customer.custom_field3 != null }{$customer.custom_field3|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">{$customFieldLabels.customer_cf4}:</td>
                                        <td class="clean left">{if $customer.custom_field4 != null }{$customer.custom_field4|htmlSafe}{/if}</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">&nbsp;</td>
                                        <td class="clean left">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="clean left">&nbsp;</td>
                                        <td class="clean left">&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </header>
        <hr/>
        <!-- Sales Representative and In Attention -->
        <br />
        <br />
        <table class="clean">
            <tbody>
                <tr>
                    <td class="clean" style="width:60%">
                        <span style="font-weight: bold;">{if $invoice.sales_representative != null } {$LANG.salesRepresentative}:</span> {$invoice.sales_representative} {/if}
                        <br />
                        {if $biller.signature != null} {$biller.signature|outHtml} {/if}
                    </td>
                    <td class="clean" style="width:40%">
                        <span style="font-weight: bold;">{if $customer.attention != null } {$LANG.attentionShort}:</span> {$customer.attention|htmlSafe} {/if}
                        <br />
                        {if $customer.notes != null } {$customer.notes|outHtml}{/if}
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Itemization -->
        <br />
        <br />
        <article>
            {if $invoice.type_id == ITEMIZED_INVOICE }
            {include file="$template_path/itemised.tpl"}
            {/if}
            {if $invoice.type_id == TOTAL_INVOICE}
            {include file="$template_path/total.tpl"}
            {/if}
        </article>
        <hr/>
        <!-- invoice footer (details & notes)- start -->
        <div class="footer">
            <!-- Invoice note -->
            {if ($invoice.type_id == 2 && $invoice.note != "") || ($invoice.type_id == 3 && $invoice.note != "") }
            <h1 class="center"><span>{$LANG.notes}</span></h1>
            {/if}                     
            <div class="notices">
                <p>{$invoice.note|outHtml}</p>
            </div>
            <!-- Invoice heading -->
            {if $preference.pref_inv_detail_heading != null}
            <h2 class="center"><span>{$preference.pref_inv_detail_heading|htmlSafe}</span></h2>
            {/if}
            {if $preference.pref_inv_detail_line != null}
            <div class="thankyou">{$preference.pref_inv_detail_line|outHtml}</div>
            {/if}
            <br />
            <!-- Payment method -->
            {if $preference.pref_inv_payment_method != null}
            <h3 class="center"><span>{$preference.pref_inv_payment_method|htmlSafe}</span></h3>
            {/if}
            <div class="notices">
                <!-- Payment details -->
                {if $preference.pref_inv_payment_line1_value != null}
                {$preference.pref_inv_payment_line1_name|htmlSafe}: {$preference.pref_inv_payment_line1_value|htmlSafe}<br />
                {/if}
                {if $preference.pref_inv_payment_line2_value != null}
                {$preference.pref_inv_payment_line2_name|htmlSafe}: {$preference.pref_inv_payment_line2_value|htmlSafe}<br />
                {/if}
                {if $preference.pref_inv_payment_line3_value != null}
                {$preference.pref_inv_payment_line3_name|htmlSafe}: {$preference.pref_inv_payment_line3_value|htmlSafe}<br />
                {/if}
                {if $preference.pref_inv_payment_line4_value != null}
                {$preference.pref_inv_payment_line4_name|htmlSafe}: {$preference.pref_inv_payment_line4_value|htmlSafe}
                {/if}
            </div>
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
            <br />
            <!-- Biller notes & footer -->
            {if $biller.notes != null}
            <div class="bnotes">{$biller.notes|outHtml}</div>
            {/if}
            {if $biller.footer != null}
            <div class="bfooter">{$biller.footer|outHtml}</div>
            {/if}
            <!-- invoice details section - end -->
        </div>
    </body>
</html>
