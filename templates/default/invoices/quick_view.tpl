{*
 * Script: quick_view.tpl
 * Quick view of invoice template
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin, Ap.Muthu
 *
 * Last edited:
 *      2018-10-20 by Richard Rowley
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 *   https://simpleinvoices.group
 *}
<div class="si_toolbar si_toolbar_top">
    <a title="{$LANG.printPreviewTooltip} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id|urlencode}&amp;format=print" target="_blank">
        <img src='images/printer.png' class='action' alt="{$LANG.printPreview}"/>&nbsp;{$LANG.printPreview}
    </a>
    {if $smarty.session.role_name != 'customer'}
        <a title="{$LANG.edit} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=invoices&amp;view=edit&amp;id={$invoice.id|urlencode}">
            <img src='images/edit.png' class='action' alt="{$LANG.edit}"/>&nbsp;{$LANG.edit}
        </a>
        <a title="{$LANG.processPaymentFor} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=payments&amp;view=process&amp;id={$invoice.id|urlencode}&amp;op=pay_selected_invoice">
            <img src='images/money_dollar.png' class='action' alt="{$LANG.processPayment}"/>&nbsp;{$LANG.processPayment}
        </a>
    {/if}
    {if $ewayPreCheck == 'true' && $smarty.session.role_name != 'customer'}
        <a title="{$LANG.processPaymentFor} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=payments&amp;view=eway&amp;id={$invoice.id|urlencode}">
            <img src='images/money_dollar.png' class='action' alt="{$LANG.processPaymentViaEway}"/>&nbsp;{$LANG.processPaymentViaEway}
        </a>
    {/if}
    <!-- EXPORT TO PDF -->
    <a title="{$LANG.exportUc} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe} {$LANG.exportPdfTooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=pdf">
        <img src='images/page_white_acrobat.png' class='action' alt="{$LANG.exportPdf}"/>&nbsp;{$LANG.exportPdf}
    </a>
    <a title="{$LANG.exportUc} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe} {$LANG.exportXlsTooltip} .{$config.exportSpreadsheet|htmlSafe} {$LANG.formatTooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$spreadsheet|urlencode}">
        <img src='images/page_white_excel.png' class='action' alt="{$LANG.exportAs} {$spreadsheet|htmlSafe}"/>&nbsp;{$LANG.exportXls}
    </a>
    <a title="{$LANG.exportUc} {$preference.pref_inv_wording} {$invoice.index_id|htmlSafe} {$LANG.exportDocTooltip} .{$config.exportWordProcessor|htmlSafe} {$LANG.formatTooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$wordprocessor|urlencode}">
        <img src='images/page_white_word.png' class='action' alt="{$LANG.exportAs} {$wordprocessor|htmlSafe}"/>&nbsp;{$LANG.exportDoc}
    </a>
    <a title="{$LANG.email} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
       href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={$invoice.id|urlencode}">
        <img src='images/mail-message-new.png' class='action' alt="{$LANG.email}"/>&nbsp;{$LANG.email}
    </a>
    {if $defaults.delete == $smarty.const.ENABLED && $smarty.session.role_name != 'biller' && $smarty.session.role_name != 'customer'}
        <a title="{$LANG.delete} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=invoices&amp;view=delete&amp;stage=1&amp;id={$invoice.id|urlencode}">
            <img src='images/delete.png' class='action' alt="{$LANG.delete}"/>&nbsp;{$LANG.delete}
        </a>
    {/if}
    <!-- #PDF end -->
</div>
<!--Actions heading - start-->
{include file="$path/quick_view_invoice_view.tpl"}
{if $invoice.type_id == TOTAL_INVOICE}
<div class='grid__area'>
    <div>&nbsp;</div>
    <div class="grid__head-6">
        <div class="cols__1-span-6 bold">{$LANG.descriptionUc}</div>
    </div>
    <div class="grid__head-6">
        <div class="cols__1-span-6">{$invoiceItems[0].description|outHtml}</div>
    </div>
    <div>&nbsp;</div>
    <div class="grid__head-6">
        <div class="cols__1-span-2 bold">{$LANG.salesRepresentative}:</div>
        <div class="cols__3-span-4">{$invoice.sales_representative}</div>
    </div>
    <div class="grid__head-6">
        <div>&nbsp;</div>
    </div>
    <div class="grid__head-6">
        <div class="cols__1-span-3"></div>
        <div class="si_right bold">{$LANG.grossTotal}</div>
        <div class="si_right bold">{$LANG.tax}</div>
        <div class="si_right bold">{$LANG.totalFullUc}</div>
    </div>
    <div class="grid__head-6">
        <div class="cols__1-span-3"></div>
        <div class="si_right">{$invoiceItems[0].gross_total|utilCurrency:$locale:$currencyCode}</div>
        <div class="si_right">{$invoiceItems[0].tax_amount|utilCurrency:$locale:$currencyCode}</div>
        <div class="si_right underline">{$invoiceItems[0].total|utilCurrency:$locale:$currencyCode}</div>
    </div>
    <div class="grid__head-6">
        <div>&nbsp;</div>
    </div>
    <div class="grid__head-6">
        <div class="cols__1-span-6 bold">{$preference.pref_inv_detail_heading}</div>
    </div>
</div>
{elseif $invoice.type_id == ITEMIZED_INVOICE || $invoice.type_id == CONSULTING_INVOICE}
    {if $invoice.type_id == ITEMIZED_INVOICE }
        {include file="$path/quick_view_itemized.tpl"}
    {elseif $invoice.type_id == CONSULTING_INVOICE}
        {include file="$path/quick_view_consulting.tpl"}
    {/if}

    {foreach $invoiceItems as $invoiceItem }
        {* Set here because it can't be tested in included file *}
        {$even = $invoiceItem@iteration is div by 2}
        {if $invoice.type_id == ITEMIZED_INVOICE}
            {include file="$path/quick_view_foreach_itemized.tpl"}
        {elseif $invoice.type_id == CONSULTING_INVOICE}
            {include file="$path/quick_view_foreach_consulting.tpl"}
        {/if}
    {/foreach}
    {if !empty($invoice.note)}
    <br/>
    <div class="grid__area">
        <div class="grid__head-10">
            <div class="bold">{$LANG.notes}:</div>
            <div class="cols__9-span-2 si_right">
                <a href='#' class="show_notes"
                   onclick="$('.full_notes').show();$('.hide_notes').show();$('.abbrev_notes').hide();$('.show_notes').hide();">
                    <img src="images/magnifier_zoom_in.png" alt="{$LANG.showDetails}"/>
                </a>
                <a href='#' class="hide_notes si_hide"
                   onclick="$('.full_notes').hide();$('.hide_notes').hide();$('.abbrev_notes').show();$('.show_notes').show();">
                    <img src="images/magnifier_zoom_out.png" alt="{$LANG.hideDetails}"/>
                </a>
            </div>
            <div class="cols__1-span-10 abbrev_notes tr_notes">
                {$invoice.note|truncate:80:"...":true|outHtml}
            </div>
            <div class="cols__1-span-10 full_notes tr_notes si_hide">
                {$invoice.note|outHtml}
            </div>
        </div>
    </div>
    {/if}
    <br/>
    {if !empty($invoice.sales_representative)}
        <div class="grid__area">
            <div class="grid__area">
                <div class="grid__head-10">
                    <div class="cols__1-span-2 bold">{$LANG.salesRepresentative}:</div>
                    <div class="cols__3-span-8">{$invoice.sales_representative|htmlSafe}</div>
                </div>
            </div>
        </div>
    {/if}
    {* end itemized invoice *}
{/if}
    {* tax section - start --------------------- *}
{if $invoiceNumberOfTaxes > 0}
    <div class="grid__area">
        <div class="grid__head-10">
            <div class="cols__1-span-8 bold si_right">{$LANG.subtotalUc}:</div>
            <div class="cols__9-span-2 si_right {if $invoiceNumberOfTaxes > 1}underline{/if}">
                {$invoice.gross|utilCurrency:$locale:$currencyCode}
            </div>
        </div>
        {foreach $invoice.tax_grouped as $taxg}
            <div class="grid__head-10">
                <div class="cols__1-span-8 bold si_right">{$taxg.tax_name|htmlSafe}:</div>
                <div class="cols__9-span-2 si_right {if $taxg@last}underline{/if}">{$taxg.tax_amount|utilCurrency:$locale:$currencyCode}</div>
            </div>
        {/foreach}
        <div class="grid__head-10">
            <div class="cols__1-span-8 bold si_right">{$LANG.taxTotal}:</div>
            <div class="cols__9-span-2 si_right double_underline">
                {$invoice.total_tax|utilCurrency:$locale:$currencyCode}
            </div>
        </div>
        <div class="grid__head-10">
            <div class="cols__1-span-8 bold si_right">{$preference.pref_inv_wording|htmlSafe} {$LANG.amountUc}:</div>
            <div class="cols__9-span-2 si_right">{$invoice.total|utilCurrency:$locale:$currencyCode}</div>
        </div>
    </div>
{/if}
{* tax section - end *}
<div class="si_center">
    <div class="grid__area-totals">
        <h4>{$LANG.financialStatus}</h4>
        <div class="grid__area-totals--financial">
            <div class="grid__area-totals--financial--area">
                <h5>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$invoice.index_id|htmlSafe}</h5>
                <div class="grid__area-totals--financial--invoice-box1">
                    <div class="bold si_right">{$LANG.totalUc}</div>
                    <div class="bold si_right">
                        <a href="index.php?module=payments&amp;view=manage&amp;id={$invoice.id|urlencode}">
                            {$LANG.paidUc}
                        </a>
                    </div>
                    <div class="bold si_right">{$LANG.owingUc}</div>
                    <div class="bold si_right">{$LANG.age}
                        <a class="cluetip" href="#"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpAge"
                           title="{$LANG.age}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </div>
                    <div class="si_right">{$invoice.total|utilCurrency:$locale:$currencyCode}</div>
                    <div class="si_right">{$invoice.paid|utilCurrency:$locale:$currencyCode}</div>
                    <div class="si_right">{$invoice.owing|utilCurrency:$locale:$currencyCode}</div>
                    <div class="si_right">{$invoiceAge|htmlSafe}</div>
                </div>
            </div>
            <div class="grid__area-totals--financial--area">
                <h5>
                    <a href="index.php?module=customers&amp;view=view&amp;id={$customer.id|urlencode}">
                        {$LANG.customerAccount}
                    </a>
                </h5>
                <div class="grid__area-totals--financial--invoice-box2">
                    <div class="bold si_right">{$LANG.totalUc}</div>
                    <div class="bold si_right">
                        <a href="index.php?module=payments&amp;view=manage&amp;c_id={$customer.id|urlencode}">
                            {$LANG.paidUc}
                        </a>
                    </div>
                    <div class="bold si_right">{$LANG.owingUc}</div>
                    <div class="si_right">{$customerAccount.total|utilCurrency:$locale:$currencyCode}</div>
                    <div class="si_right">{$customerAccount.paid|utilCurrency:$locale:$currencyCode}</div>
                    <div class="si_right">{$customerAccount.owing|utilCurrency:$locale:$currencyCode}</div>
                </div>
            </div>
        </div>
    </div>
</div>
