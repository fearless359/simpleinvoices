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
    <a title="{$LANG.print_preview_tooltip} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id|urlencode}&amp;format=print">
        <img src='../../../images/printer.png' class='action' alt="{$LANG.print_preview}"/>&nbsp;{$LANG.print_preview}
    </a>
    <a title="{$LANG.edit} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
       href="index.php?module=invoices&amp;view=edit&amp;id={$invoice.id|urlencode}">
        <img src='../../../images/edit.png' class='action' alt="{$LANG.edit}"/>&nbsp;{$LANG.edit}
    </a>
    <a title="{$LANG.process_payment_for} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
       href="index.php?module=payments&amp;view=process&amp;id={$invoice.id|urlencode}&amp;op=pay_selected_invoice">
        <img src='../../../images/money_dollar.png' class='action' alt="{$LANG.process_payment}"/>&nbsp;{$LANG.process_payment}
    </a>
    {if $ewayPreCheck == 'true'}
        <a title="{$LANG.process_payment_for} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=payments&amp;view=eway&amp;id={$invoice.id|urlencode}">
            <img src='../../../images/money_dollar.png' class='action' alt="{$LANG.process_payment_via_eway}"/>&nbsp;{$LANG.process_payment_via_eway}
        </a>
    {/if}
    <!-- EXPORT TO PDF -->
    <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe} {$LANG.export_pdf_tooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=pdf">
        <img src='../../../images/page_white_acrobat.png' class='action' alt="{$LANG.export_pdf}"/>&nbsp;{$LANG.export_pdf}
    </a>
    <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe} {$LANG.export_xls_tooltip} .{$config.exportSpreadsheet|htmlSafe} {$LANG.format_tooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$spreadsheet|urlencode}">
        <img src='../../../images/page_white_excel.png' class='action' alt="{$LANG.export_as} {$spreadsheet|htmlSafe}"/>&nbsp;{$LANG.export_as}.{$spreadsheet|htmlSafe}
    </a>
    <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording} {$invoice.index_id|htmlSafe} {$LANG.export_doc_tooltip} .{$config.exportWordProcessor|htmlSafe} {$LANG.format_tooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$wordprocessor|urlencode}">
        <img src='../../../images/page_white_word.png' class='action' alt="{$LANG.export_as} {$wordprocessor|htmlSafe}"/>&nbsp;{$LANG.export_as}.{$wordprocessor|htmlSafe}
    </a>
    <a title="{$LANG.email} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
       href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={$invoice.id|urlencode}">
        <img src='../../../images/mail-message-new.png' class='action' alt="{$LANG.email}"/>&nbsp;{$LANG.email}
    </a>
    {if $defaults.delete == '1'}
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
        <th colspan="6">{$LANG.description_uc}</th>
    </tr>
    <tr class="tr_head">
        <td colspan="6">{$invoiceItems[0].description|outHtml}</td>
    </tr>
    <tr class="tr_head">
        <th>{$LANG.sales_representative}</th>
        <td colspan="5">{$invoice.sales_representative}</td>
    </tr>
    <tr class="tr_head">
        <td colspan="6"><br/></td>
    </tr>
    <tr class="tr_head">
        <td></td>
        <td></td>
        <td></td>
        <td class="align_right"><b>{$LANG.gross_total}</b></td>
        <td class="align_right"><b>{$LANG.tax}</b></td>
        <td class="align_right"><b>{$LANG.total_uc}</b></td>
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

    {foreach from=$invoiceItems item=invoiceItem }
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
                    <img src="../../../images/magnifier_zoom_in.png" alt="{$LANG.show_details}"/>
                </a>
                <a href='#' class="hide_notes si_hide"
                   onclick="$('.full_notes').hide();$('.hide_notes').hide();$('.abbrev_notes').show();$('.show_notes').show();">
                    <img src="../../../images/magnifier_zoom_out.png" alt="{$LANG.hide_details}"/>
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
            <th style="width:25%;">{$LANG.sales_representative}:</th>
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
        <th class="si_right">{$LANG.sub_total}</th>
        <td class="si_right" {if $invoiceNumberOfTaxes > 1}style="text-decoration:underline;"{/if}>
            {$invoice.gross|utilCurrency:$locale:$currencyCode}
        </td>
    </tr>
    {foreach from=$invoice.tax_grouped item=taxg}
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
        <th class="si_right">{$LANG.tax_total}</th>
        <td class="si_right" style="text-decoration:underline;">
            {$invoice.total_tax|utilCurrency:$locale:$currencyCode}
        </td>
    </tr>
    <tr class="tr_total">
        <td colspan="4"></td>
        <th class="si_right">{$preference.pref_inv_wording|htmlSafe} {$LANG.amount_uc}</th>
        <td class="si_right">{$invoice.total|utilCurrency:$locale:$currencyCode}</td>
    </tr>
</table>
{/if}
{* tax section - end *}
<div class="si_center">
    <div class="si_invoice_account">
        <h4>{$LANG.financial_status}</h4>
        <div class="si_invoice_account1">
            <h5>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$invoice.index_id|htmlSafe}</h5>
            <table>
                <tr>
                    <th>{$LANG.total}</th>
                    <th>
                        <a href="index.php?module=payments&amp;view=manage&amp;id={$invoice.id|urlencode}">
                            {$LANG.paid}
                        </a>
                    </th>
                    <th>{$LANG.owing_uc}</th>
                    <th>{$LANG.age}
                        <a class="cluetip" href="#"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_age"
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
                <a href="index.php?module=customers&amp;view=details&amp;id={$customer.id|urlencode}&amp;action=view">
                    {$LANG.customer_account}
                </a>
            </h5>
            <table>
                <tr>
                    <th>{$LANG.total}</th>
                    <th>
                        <a href="index.php?module=payments&amp;view=manage&amp;c_id={$customer.id|urlencode}">
                            {$LANG.paid}
                        </a>
                    </th>
                    <th>{$LANG.owing_uc}</th>
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
