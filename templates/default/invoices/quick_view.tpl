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
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id|urlencode}&amp;format=print">
        <img src='../../../images/printer.png' class='action' alt="{$LANG.printPreview}"/>&nbsp;{$LANG.printPreview}
    </a>
    {if $smarty.session.role_name != 'customer'}
        <a title="{$LANG.edit} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=invoices&amp;view=edit&amp;id={$invoice.id|urlencode}">
            <img src='../../../images/edit.png' class='action' alt="{$LANG.edit}"/>&nbsp;{$LANG.edit}
        </a>
        <a title="{$LANG.processPaymentFor} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=payments&amp;view=process&amp;id={$invoice.id|urlencode}&amp;op=pay_selected_invoice">
            <img src='../../../images/money_dollar.png' class='action' alt="{$LANG.processPayment}"/>&nbsp;{$LANG.processPayment}
        </a>
    {/if}
    {if $ewayPreCheck == 'true' && $smarty.session.role_name != 'customer'}
        <a title="{$LANG.processPaymentFor} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=payments&amp;view=eway&amp;id={$invoice.id|urlencode}">
            <img src='../../../images/money_dollar.png' class='action' alt="{$LANG.processPaymentViaEway}"/>&nbsp;{$LANG.processPaymentViaEway}
        </a>
    {/if}
    <!-- EXPORT TO PDF -->
    <a title="{$LANG.exportUc} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe} {$LANG.exportPdfTooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=pdf">
        <img src='../../../images/page_white_acrobat.png' class='action' alt="{$LANG.exportPdf}"/>&nbsp;{$LANG.exportPdf}
    </a>
    <a title="{$LANG.exportUc} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe} {$LANG.exportXlsTooltip} .{$config.exportSpreadsheet|htmlSafe} {$LANG.formatTooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$spreadsheet|urlencode}">
        <img src='../../../images/page_white_excel.png' class='action' alt="{$LANG.exportAs} {$spreadsheet|htmlSafe}"/>&nbsp;{$LANG.exportXls}
    </a>
    <a title="{$LANG.exportUc} {$preference.pref_inv_wording} {$invoice.index_id|htmlSafe} {$LANG.exportDocTooltip} .{$config.exportWordProcessor|htmlSafe} {$LANG.formatTooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$wordprocessor|urlencode}">
        <img src='../../../images/page_white_word.png' class='action' alt="{$LANG.exportAs} {$wordprocessor|htmlSafe}"/>&nbsp;{$LANG.exportDoc}
    </a>
    <a title="{$LANG.email} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
       href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={$invoice.id|urlencode}">
        <img src='../../../images/mail-message-new.png' class='action' alt="{$LANG.email}"/>&nbsp;{$LANG.email}
    </a>
    {if $defaults.delete == $smarty.const.ENABLED && $smarty.session.role_name != 'biller' && $smarty.session.role_name != 'customer'}
        <a title="{$LANG.delete} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=invoices&amp;view=delete&amp;stage=1&amp;id={$invoice.id|urlencode}">
            <img src='../../../images/delete.png' class='action' alt="{$LANG.delete}"/>&nbsp;{$LANG.delete}
        </a>
    {/if}
</div>
<!--Actions heading - start-->
<!-- #PDF end -->
{include file="$path/quick_view_invoice_view.tpl"}
{if $invoice.type_id == TOTAL_INVOICE}
<table class='si_invoice_view'>
    <tr class="tr_head">
        <th colspan="6">{$LANG.descriptionUc}</th>
    </tr>
    <tr class="tr_head">
        <td colspan="6">{$invoiceItems[0].description|outHtml}</td>
    </tr>
    <tr class="tr_head">
        <th>{$LANG.salesRepresentative}</th>
        <td colspan="5">{$invoice.sales_representative}</td>
    </tr>
    <tr class="tr_head">
        <td colspan="6"><br/></td>
    </tr>
    <tr class="tr_head">
        <td></td>
        <td></td>
        <td></td>
        <td class="align_right"><b>{$LANG.grossTotal}</b></td>
        <td class="align_right"><b>{$LANG.tax}</b></td>
        <td class="align_right"><b>{$LANG.totalFullUc}</b></td>
    </tr>
    <tr class="tr_head">
        <td></td>
        <td></td>
        <td></td>
        <td class="align_right">{$invoiceItems[0].gross_total|utilCurrency:$locale:$currencyCode}</td>
        <td class="align_right">{$invoiceItems[0].tax_amount|utilCurrency:$locale:$currencyCode}</td>
        <td class="align_right"><u>{$invoiceItems[0].total|utilCurrency:$locale:$currencyCode}</u></td>
    </tr>
    <tr class="tr_head">
        <td colspan="6"><br/><br/></td>
    </tr>
    <tr class="tr_head">
        <td colspan="6">{$preference.pref_inv_detail_heading}</td>
    </tr>
</table>
{elseif $invoice.type_id == ITEMIZED_INVOICE || $invoice.type_id == CONSULTING_INVOICE}
    <table class="si_invoice_view_items">
    {if $invoice.type_id == ITEMIZED_INVOICE }
        {include file="$path/quick_view_itemized.tpl"}
    {elseif $invoice.type_id == CONSULTING_INVOICE}
        {include file="$path/quick_view_consulting.tpl"}
    {/if}

    {foreach $invoiceItems as $invoiceItem }
        {if $invoice.type_id == ITEMIZED_INVOICE}
            {include file="$path/quick_view_foreach_itemized.tpl"}
        {elseif $invoice.type_id == CONSULTING_INVOICE}
            {include file="$path/quick_view_foreach_consulting.tpl"}
        {/if}
    {/foreach}
    </table>
    {if isset($invoice.note)}
    <table class="si_invoice_view_items">
        <tr class="tr_head_items">
            <th></th>
            <td colspan="5"></td>
        </tr>
        <tr class="tr_head_items">
            <th>{$LANG.notes}:</th>
            <td colspan="4"></td>
            <td class="si_switch">
                <a href='#' class="show_notes"
                   onclick="$('.full_notes').show();$('.hide_notes').show();$('.abbrev_notes').hide();$('.show_notes').hide();">
                    <img src="../../../images/magnifier_zoom_in.png" alt="{$LANG.showDetails}"/>
                </a>
                <a href='#' class="hide_notes si_hide"
                   onclick="$('.full_notes').hide();$('.hide_notes').hide();$('.abbrev_notes').show();$('.show_notes').show();">
                    <img src="../../../images/magnifier_zoom_out.png" alt="{$LANG.hideDetails}"/>
                </a>
            </td>
        </tr>
        <!-- if hide detail click - the stripped note will be displayed -->
        <tr class="abbrev_notes tr_notes">
            <td colspan="6">{$invoice.note|truncate:80:"...":true|outHtml}</td>
        </tr>
        <!-- if show detail click - the full note will be displayed -->
        <tr class="full_notes tr_notes si_hide">
            <td colspan="6" style="white-space:normal;">{$invoice.note|outHtml}</td>
        </tr>
    </table>
    {/if}
    <br/>
    <table class="si_invoice_view_items" style="width:50%;;text-align: left;">
        <tr>
            <th style="width:25%;">{$LANG.salesRepresentative}:</th>
            <td>{$invoice.sales_representative|htmlSafe}</td>
            <td colspan="4">&nbsp;</td>
        </tr>
    </table>
{* end itemized invoice *}
{/if}
    {* tax section - start --------------------- *}
{if $invoiceNumberOfTaxes > 0}
<table class="si_invoice_view_items">
    <tr class="tr_tax">
        <td colspan="4"></td>
        <th class="si_right">{$LANG.subTotal}</th>
        <td class="si_right {if $invoiceNumberOfTaxes > 1}underline{/if}">
            {$invoice.gross|utilCurrency:$locale:$currencyCode}
        </td>
    </tr>
    {foreach $invoice.tax_grouped as $taxg}
        {if $taxg.tax_amount != 0}
            <tr class="tr_tax">
                <td colspan="4"></td>
                <th class="si_right">{$taxg.tax_name|htmlSafe}</th>
                <td class="si_right">{$taxg.tax_amount|utilCurrency:$locale:$currencyCode}</td>
            </tr>
        {/if}
    {/foreach}
    <tr class="tr_tax">
        <td colspan="4"></td>
        <th class="si_right">{$LANG.taxTotal}</th>
        <td class="si_right underline">
            {$invoice.total_tax|utilCurrency:$locale:$currencyCode}
        </td>
    </tr>
    <tr class="tr_total">
        <td colspan="4"></td>
        <th class="si_right">{$preference.pref_inv_wording|htmlSafe} {$LANG.amountUc}</th>
        <td class="si_right">{$invoice.total|utilCurrency:$locale:$currencyCode}</td>
    </tr>
</table>
{/if}
{* tax section - end *}
<div class="si_center">
    <div class="si_invoice_account">
        <h4>{$LANG.financialStatus}</h4>
        <div class="si_invoice_account1">
            <h5>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$invoice.index_id|htmlSafe}</h5>
            <table>
                <tr>
                    <th>{$LANG.totalUc}</th>
                    <th>
                        <a href="index.php?module=payments&amp;view=manage&amp;id={$invoice.id|urlencode}">
                            {$LANG.paidUc}
                        </a>
                    </th>
                    <th>{$LANG.owingUc}</th>
                    <th>{$LANG.age}
                        <a class="cluetip" href="#"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpAge"
                           title="{$LANG.age}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                </tr>
                <tr>
                    <td>{$invoice.total|utilCurrency:$locale:$currencyCode}</td>
                    <td>{$invoice.paid|utilCurrency:$locale:$currencyCode}</td>
                    <td>{$invoice.owing|utilCurrency:$locale:$currencyCode}</td>
                    <td>{$invoiceAge|htmlSafe}</td>
                </tr>
            </table>
        </div>
        <div class="si_invoice_account2">
            <h5>
                <a href="index.php?module=customers&amp;view=view&amp;id={$customer.id|urlencode}">
                    {$LANG.customerAccount}
                </a>
            </h5>
            <table>
                <tr>
                    <th>{$LANG.totalUc}</th>
                    <th>
                        <a href="index.php?module=payments&amp;view=manage&amp;c_id={$customer.id|urlencode}">
                            {$LANG.paidUc}
                        </a>
                    </th>
                    <th>{$LANG.owingUc}</th>
                </tr>
                <tr>
                    <td>{$customerAccount.total|utilCurrency:$locale:$currencyCode}</td>
                    <td>{$customerAccount.paid|utilCurrency:$locale:$currencyCode}</td>
                    <td>{$customerAccount.owing|utilCurrency:$locale:$currencyCode}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
