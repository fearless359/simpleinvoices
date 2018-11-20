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
    <a title="{$LANG.print_preview_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id|urlencode}&amp;format=print">
        <img src='images/common/printer.png' class='action'/>&nbsp;{$LANG.print_preview}
    </a>
    <a title="{$LANG.edit} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}"
       href="index.php?module=invoices&amp;view=details&amp;id={$invoice.id|urlencode}&amp;action=view">
        <img src='images/common/edit.png' class='action'/>&nbsp;{$LANG.edit}
    </a>
    <a title="{$LANG.process_payment_for} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}"
       href="index.php?module=payments&amp;view=process&amp;id={$invoice.id|urlencode}&amp;op=pay_selected_invoice">
        <img src='images/common/money_dollar.png' class='action'/>&nbsp;{$LANG.process_payment}
    </a>
    {if $eway_pre_check == 'true'}
        <a title="{$LANG.process_payment_for} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}"
           href="index.php?module=payments&amp;view=eway&amp;id={$invoice.id|urlencode}">
            <img src='images/common/money_dollar.png' class='action'/>&nbsp;{$LANG.process_payment_via_eway}
        </a>
    {/if}
    <!-- EXPORT TO PDF -->
    <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe} {$LANG.export_pdf_tooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=pdf">
        <img src='images/common/page_white_acrobat.png' class='action'/>&nbsp;{$LANG.export_pdf}
    </a>
    <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe} {$LANG.export_xls_tooltip} .{$config->export->spreadsheet|htmlsafe} {$LANG.format_tooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$spreadsheet|urlencode}">
        <img src='images/common/page_white_excel.png' class='action'/>&nbsp;{$LANG.export_as}.{$spreadsheet|htmlsafe}
    </a>
    <a title="{$LANG.export_tooltip} {$preference.pref_inv_wording} {$invoice.index_id|htmlsafe} {$LANG.export_doc_tooltip} .{$config->export->wordprocessor|htmlsafe} {$LANG.format_tooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$wordprocessor|urlencode}">
        <img src='images/common/page_white_word.png' class='action'/>&nbsp;{$LANG.export_as}.{$wordprocessor|htmlsafe}
    </a>
    <a title="{$LANG.email} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}"
       href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={$invoice.id|urlencode}">
        <img src='images/common/mail-message-new.png' class='action'/>&nbsp;{$LANG.email}
    </a>
    {if $defaults.delete == '1'}
        <a title="{$LANG.delete} {$preference.pref_inv_wording|htmlsafe} {$invoice.index_id|htmlsafe}"
           href="index.php?module=invoices&amp;view=delete&amp;stage=1&amp;id={$invoice.id|urlencode}">
            <img src='images/common/delete.png' class='action'/>&nbsp;{$LANG.delete}
        </a>
    {/if}
</div>
<!--Actions heading - start-->
<!-- #PDF end -->
{include file="$path/quick_view_invoice_view.tpl"}
{if $invoice.type_id == TOTAL_INVOICE}
<table class='si_invoice_view'>
    <tr class="tr_head">
        <th colspan="6">{$LANG.description}</th>
    </tr>
    <tr class="tr_head">
        <td colspan="6">{$invoiceItems[0].description|outhtml}</td>
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
        <td style="text-align:right"><b>{$LANG.gross_total}</b></td>
        <td style="text-align:right"><b>{$LANG.tax}</b></td>
        <td style="text-align:right"><b>{$LANG.total_uppercase}</b></td>
    </tr>
    <tr class="tr_head">
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align:right">{$preference.pref_currency_sign|htmlsafe}{$invoiceItems[0].gross_total|siLocal_number}</td>
        <td style="text-align:right">{$preference.pref_currency_sign|htmlsafe}{$invoiceItems[0].tax_amount|siLocal_number}</td>
        <td style="text-align:right"><u>{$preference.pref_currency_sign|htmlsafe}{$invoiceItems[0].total|siLocal_number}</u></td>
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
    {if $invoice.note != null}
    <table class="si_invoice_view_items">
        <tr class="tr_head_items">
            <th></th>
            <td colspan="5"></td>
        </tr>
        <tr class="tr_head_items">
            <th>{$LANG.notes}:</th>
            <td colspan="4"></td>
            <td class="si_switch">
                {if ($invoice.note|count_characters:true > 25)}
                    <a href='#' class="show-notes"
                       onclick="$('.notes').show();$('.si_notes_hide').show();$('.show-notes').hide();">
                        <img src="images/common/magnifier_zoom_in.png" title="{$LANG.show_details}"/>
                    </a>
                    <a href='#' class="notes si_hide"
                       onclick="$('.notes').hide();$('.si_notes_hide').hide();$('.show-notes').show();">
                        <img src="images/common/magnifier_zoom_out.png" title="{$LANG.hide_details}"/>
                    </a>
                {/if}
            </td>
        </tr>
        <!-- if hide detail click - the stripped note will be displayed -->
        <tr class="show-notes tr_notes">
            <td colspan="6">{$invoice.note|truncate:25:"...":true|outhtml}</td>
        </tr>
        <!-- if show detail click - the full note will be displayed -->
        <tr class="notes tr_notes si_notes_hide">
            <td colspan="6" style="white-space:normal;">{$invoice.note|outhtml}</td>
        </tr>
    </table>
    {/if}
    <table class="si_invoice_view_items" width="50%" style="text-align: left;">
        <tr>
            <th width="25%">{$LANG.sales_representative}:</th>
            <td>{$invoice.sales_representative|htmlsafe}</td>
            <td colspan="4">&nbsp;</td>
        </tr>
    </table>
{* end itemized invoice *}
{/if}
    {* tax section - start --------------------- *}
{if $invoice_number_of_taxes > 0}
<table class="si_invoice_view_items">
    <tr class="tr_tax">
        <td colspan="4"></td>
        <th class="si_right">{$LANG.sub_total}</th>
        <td class="si_right" {if $invoice_number_of_taxes > 1}style="text-decoration:underline;"{/if}>
            {$preference.pref_currency_sign}{$invoice.gross|siLocal_number}
        </td>
    </tr>
    {if $invoice_number_of_taxes > 1 }
        {foreach from=$invoice.tax_grouped item=taxg}
            {if $taxg.tax_amount != 0}
                <tr class="tr_tax">
                    <td colspan="4"></td>
                    <th class="si_right">{$taxg.tax_name|htmlsafe}</th>
                    <td class="si_right">{$preference.pref_currency_sign}{$taxg.tax_amount|siLocal_number}</td>
                </tr>
            {/if}
        {/foreach}
        <tr class="tr_tax">
            <td colspan="4"></td>
            <th class="si_right">{$LANG.tax_total}</th>
            <td class="si_right" style="text-decoration:underline;">
                {$preference.pref_currency_sign}{$invoice.total_tax|siLocal_number}
            </td>
        </tr>
        <tr class="tr_total">
            <td colspan="4"></td>
            <th class="si_right">{$preference.pref_inv_wording|htmlsafe} {$LANG.amount}</th>
            <td class="si_right">{$preference.pref_currency_sign}{$invoice.total|siLocal_number}</td>
        </tr>
    {/if}
</table>
{/if}
{* tax section - end *}
<div class="si_center">
    <div class="si_invoice_account">
        <h4>{$LANG.financial_status}</h4>
        <div class="si_invoice_account1">
            <h5>{$preference.pref_inv_wording|htmlsafe}&nbsp;{$invoice.index_id|htmlsafe}</h5>
            <table>
                <tr>
                    <th>{$LANG.total}</th>
                    <th>
                        <a href="index.php?module=payments&amp;view=manage&amp;id={$invoice.id|urlencode}">
                            {$LANG.paid}
                        </a>
                    </th>
                    <th>{$LANG.owing}</th>
                    <th>{$LANG.age}
                        <a class="cluetip" href="#"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_age"
                           title="{$LANG.age}"><img
                                    src="{$help_image_path}help-small.png" alt=""/>
                        </a>
                    </th>
                </tr>
                <tr>
                    <td>{$preference.pref_currency_sign}{$invoice.total|siLocal_number}</td>
                    <td>{$preference.pref_currency_sign}{$invoice.paid|siLocal_number}</td>
                    <td>{$preference.pref_currency_sign}{$invoice.owing|siLocal_number}</td>
                    <td>{$invoice_age|htmlsafe}</td>
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
                    <th>{$LANG.owing}</th>
                </tr>
                <tr>
                    <td>{$preference.pref_currency_sign}{$customerAccount.total|siLocal_number}</td>
                    <td>{$preference.pref_currency_sign}{$customerAccount.paid|siLocal_number}</td>
                    <td>{$preference.pref_currency_sign}{$customerAccount.owing|siLocal_number}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
