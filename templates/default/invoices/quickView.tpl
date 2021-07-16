{*
 * Script: quickView.tpl
 * Quick view of invoice template
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin, Ap.Muthu
 *
 * Last edited:
 *      2021-06-15 by Richard Rowley to use grid layout.
 *      2018-10-20 by Richard Rowley
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 *   https://simpleinvoices.group
 *}
<div class="align__text-center margin__bottom-2">
    <a title="{$LANG.printPreviewTooltip} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id|urlencode}&amp;format=print"
       class="button square" target="_blank">
        <img src='images/printer.png' class='action' alt="{$LANG.printUc}"/>&nbsp;{$LANG.printUc}
    </a>
    {if $smarty.session.role_name != 'customer'}
        <a title="{$LANG.edit} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=invoices&amp;view=edit&amp;id={$invoice.id|urlencode}"
           class="button square">
            <img src='images/edit.png' class='action' alt="{$LANG.edit}"/>&nbsp;{$LANG.edit}
        </a>
        <a title="{$LANG.processPaymentFor} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=payments&amp;view=process&amp;id={$invoice.id|urlencode}&amp;op=pay_selected_invoice"
           class="button square">
            <img src='images/money_dollar.png' class='action' alt="{$LANG.processPayment}"/>&nbsp;{$LANG.paymentUc}
        </a>
    {/if}
    {if $ewayPreCheck == 'true' && $smarty.session.role_name != 'customer'}
        <a title="{$LANG.processPaymentFor} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=payments&amp;view=eway&amp;id={$invoice.id|urlencode}"
           class="button square">
            <img src='images/money_dollar.png' class='action' alt="{$LANG.processPaymentViaEway}"/>&nbsp;{$LANG.eway}
        </a>
    {/if}
    <!-- EXPORT TO PDF -->
    <a title="{$LANG.exportUc} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe} {$LANG.exportPdfTooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=pdf"
       class="button square">
        <img src='images/page_white_acrobat.png' class='action' alt="{$LANG.exportPdf}"/>&nbsp;{$LANG.pdf}
    </a>
    <a title="{$LANG.exportUc} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe} {$LANG.exportXlsTooltip} .{$config.exportSpreadsheet|htmlSafe} {$LANG.formatTooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$spreadsheet|urlencode}"
       class="button square">
        <img src='images/page_white_excel.png' class='action' alt="{$LANG.exportAs} {$spreadsheet|htmlSafe}"/>&nbsp;{$LANG.xlsUc}
    </a>
    <a title="{$LANG.exportUc} {$preference.pref_inv_wording} {$invoice.index_id|htmlSafe} {$LANG.exportDocTooltip} .{$config.exportWordProcessor|htmlSafe} {$LANG.formatTooltip}"
       href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$wordprocessor|urlencode}"
       class="button square">
        <img src='images/page_white_word.png' class='action' alt="{$LANG.exportAs} {$wordprocessor|htmlSafe}"/>&nbsp;{$LANG.docUc}
    </a>
    <a title="{$LANG.email} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
       href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={$invoice.id|urlencode}"
       class="button square">
        <img src='images/mail-message-new.png' class='action' alt="{$LANG.email}"/>&nbsp;{$LANG.email}
    </a>
    {if $defaults.delete == $smarty.const.ENABLED && $smarty.session.role_name != 'biller' && $smarty.session.role_name != 'customer'}
        <a title="{$LANG.delete} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=invoices&amp;view=delete&amp;stage=1&amp;id={$invoice.id|urlencode}"
           class="button square">
            <img src='images/delete.png' class='action' alt="{$LANG.delete}"/>&nbsp;{$LANG.delete}
        </a>
    {/if}
    <!-- #PDF end -->
</div>
<!--Actions heading - start-->
{include file="$path/quickViewInvoiceView.tpl"}
{if $invoice.type_id == TOTAL_INVOICE}
    {include file="$path/quickViewTotal.tpl"}
{elseif $invoice.type_id == ITEMIZED_INVOICE}
    {include file="$path/quickViewItemized.tpl"}

    {foreach $invoiceItems as $invoiceItem }
        {* Set here because it can't be tested in included file *}
        {$even = $invoiceItem@iteration is div by 2}
        {include file="$path/quickViewForeachItemized.tpl"}
    {/foreach}
    {if !empty($invoice.note)}
        <br/>
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <div class="bold">{$LANG.notes}:</div>
                <div class="cols__9-span-2 align__text-right">
                    <a href='#' class="showNotes"
                       onclick="$('.fullNotes').show();$('.abbrevNotes').hide();$('.hideNotes').show();$('.showNotes').hide();">
                        <img src="images/magnifier_zoom_in.png" alt="{$LANG.showDetails}"/>
                    </a>
                    <a href='#' class="hideNotes" style="display:none;"
                       onclick="$('.fullNotes').hide();$('.abbrevNotes').show();$('.hideNotes').hide();$('.showNotes').show();">
                        <img src="images/magnifier_zoom_out.png" alt="{$LANG.hideDetails}"/>
                    </a>
                </div>
                <div class="cols__1-span-10">
                    <span class="abbrevNotes">{$invoice.note|truncate:80:"...":true|outHtml}</span>
                    <span class="fullNotes" style="display: none;">{$invoice.note|outHtml}</span>
                </div>
            </div>
        </div>
    {/if}
    <br/>
    {if !empty($invoice.sales_representative)}
        <div class="grid__area">
            <div class="grid__area">
                <div class="grid__container grid__head-10">
                    <div class="cols__1-span-2 bold">{$LANG.salesRepresentative}:</div>
                    <div class="cols__3-span-8">{$invoice.sales_representative|htmlSafe}</div>
                </div>
            </div>
        </div>
    {/if}
    {* end itemized invoice *}
{/if}
<div class="grid__area">
{$customFields.1}
{$customFields.2}
{$customFields.3}
{$customFields.4}
<div>&nbsp;</div>
</div>

{* tax section - start --------------------- *}
{if $invoiceNumberOfTaxes > 0}
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-8 bold align__text-right">{$LANG.subtotalUc}:</div>
            <div class="cols__9-span-2 align__text-right {if $invoiceNumberOfTaxes > 1}underline{/if}">
                {$invoice.gross|utilCurrency:$locale:$currencyCode}
            </div>
        </div>
        {foreach $invoice.tax_grouped as $taxg}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-8 bold align__text-right">{$taxg.tax_name|htmlSafe}:</div>
                <div class="cols__9-span-2 align__text-right {if $taxg@last}underline{/if}">{$taxg.tax_amount|utilCurrency:$locale:$currencyCode}</div>
            </div>
        {/foreach}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-8 bold align__text-right">{$LANG.taxTotal}:</div>
            <div class="cols__9-span-2 align__text-right underline_double">
                {$invoice.total_tax|utilCurrency:$locale:$currencyCode}
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-8 bold align__text-right">{$preference.pref_inv_wording|htmlSafe} {$LANG.amountUc}:</div>
            <div class="cols__9-span-2 align__text-right">{$invoice.total|utilCurrency:$locale:$currencyCode}</div>
        </div>
    </div>
{/if}
{* tax section - end *}
<br/>
<div class="grid__area-totals">
    <h4>{$LANG.financialStatus}</h4>
    <div class="grid__area-totals--financial">
        <div class="grid__area-totals--financial--area">
            <h5>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$invoice.index_id|htmlSafe}</h5>
            <div class="grid__area-totals--financial--invoice-box1">
                <div class="bold align__text-right">{$LANG.totalUc}</div>
                <div class="bold align__text-right">
                    <a href="index.php?module=payments&amp;view=manage&amp;id={$invoice.id|urlencode}">
                        {$LANG.paidUc}
                    </a>
                </div>
                <div class="bold align__text-right">{$LANG.owingUc}</div>
                <div class="bold align__text-right">{$LANG.age}
                    <a class="cluetip" href="#"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpAge"
                       title="{$LANG.age}">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </div>
                <div class="align__text-right">{$invoice.total|utilCurrency:$locale:$currencyCode}</div>
                <div class="align__text-right">{$invoice.paid|utilCurrency:$locale:$currencyCode}</div>
                <div class="align__text-right">{$invoice.owing|utilCurrency:$locale:$currencyCode}</div>
                <div class="align__text-right">{$invoiceAge|htmlSafe}</div>
            </div>
        </div>
        <div class="grid__area-totals--financial--area">
            <h5>
                <a href="index.php?module=customers&amp;view=view&amp;id={$customer.id|urlencode}">
                    {$LANG.customerAccount}
                </a>
            </h5>
            <div class="grid__area-totals--financial--invoice-box2">
                <div class="bold align__text-right">{$LANG.totalUc}</div>
                <div class="bold align__text-right">
                    <a href="index.php?module=payments&amp;view=manage&amp;c_id={$customer.id|urlencode}">
                        {$LANG.paidUc}
                    </a>
                </div>
                <div class="bold align__text-right">{$LANG.owingUc}</div>
                <div class="align__text-right">{$customerAccount.total|utilCurrency:$locale:$currencyCode}</div>
                <div class="align__text-right">{$customerAccount.paid|utilCurrency:$locale:$currencyCode}</div>
                <div class="align__text-right">{$customerAccount.owing|utilCurrency:$locale:$currencyCode}</div>
            </div>
        </div>
    </div>
</div>
