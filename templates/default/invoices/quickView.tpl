{*
 * Script: quickView.tpl
 * Quick view of invoice template
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin, Ap.Muthu
 *
 *  Last Modified:
 *      20251207 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      20210615 by Richard Rowley to use grid layout.
 *      20181020 by Richard Rowley
 *
 * License:
 *     GPL v3 or above
 *
 * Website:
 *   https://simpleinvoices.group
 *}
<div class="flex__area">
    <div class="flex__container">
        <a title="{$LANG.printPreviewTooltip} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id|urlEncode}&amp;format=print"
           class="button square" target="_blank">
            <img src='images/printer.png' class='action desktopOnly' alt="{$LANG.printUc}"/>&nbsp;{$LANG.printUc}
        </a>
        {if $smarty.session.role_name != 'customer'}
            <a title="{$LANG.edit} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
               href="index.php?module=invoices&amp;view=edit&amp;id={$invoice.id|urlEncode}"
               class="button square">
                <img src='images/edit.png' class='action desktopOnly' alt="{$LANG.edit}"/>&nbsp;{$LANG.edit}
            </a>
            <a title="{$LANG.processPaymentFor} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
               href="index.php?module=payments&amp;view=process&amp;id={$invoice.id|urlEncode}&amp;op=pay_selected_invoice"
               class="button square">
                <img src='images/money_dollar.png' class='action desktopOnly'
                     alt="{$LANG.processPayment}"/>&nbsp;{$LANG.paymentUc}
            </a>
        {/if}
        {if $ewayPreCheck == 'true' && $smarty.session.role_name != 'customer'}
            <a title="{$LANG.processPaymentFor} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
               href="index.php?module=payments&amp;view=eway&amp;id={$invoice.id|urlEncode}"
               class="button square">
                <img src='images/money_dollar.png' class='action desktopOnly' alt="{$LANG.processPaymentViaEway}"/>&nbsp;{$LANG.eway}
            </a>
        {/if}
        <!-- EXPORT TO PDF -->
        <a title="{$LANG.exportUc} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe} {$LANG.exportPdfTooltip}"
           href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=pdf"
           class="button square">
            <img src='images/page_white_acrobat.png' class='action desktopOnly'
                 alt="{$LANG.exportPdf}"/>&nbsp;{$LANG.pdf}
        </a>
        <a title="{$LANG.exportUc} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe} {$LANG.exportXlsTooltip} .{$config.exportSpreadsheet|htmlSafe} {$LANG.formatTooltip}"
           href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$spreadsheet|urlEncode}"
           class="button square">
            <img src='images/page_white_excel.png' class='action desktopOnly'
                 alt="{$LANG.exportAs} {$spreadsheet|htmlSafe}"/>&nbsp;{$LANG.xlsUc}
        </a>
        <a title="{$LANG.exportUc} {$preference.pref_inv_wording} {$invoice.index_id|htmlSafe} {$LANG.exportDocTooltip} .{$config.exportWordProcessor|htmlSafe} {$LANG.formatTooltip}"
           href="index.php?module=export&amp;view=invoice&amp;id={$invoice.id}&amp;format=file&amp;filetype={$wordprocessor|urlEncode}"
           class="button square">
            <img src='images/page_white_word.png' class='action desktopOnly'
                 alt="{$LANG.exportAs} {$wordprocessor|htmlSafe}"/>&nbsp;{$LANG.docUc}
        </a>
        <a title="{$LANG.email} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
           href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={$invoice.id|urlEncode}"
           class="button square">
            <img src='images/mail-message-new.png' class='action desktopOnly' alt="{$LANG.email}"/>&nbsp;{$LANG.email}
        </a>
        {if $defaults.delete == $smarty.const.ENABLED && $smarty.session.role_name != 'biller' && $smarty.session.role_name != 'customer'}
            <a title="{$LANG.delete} {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}"
               href="index.php?module=invoices&amp;view=delete&amp;stage=1&amp;id={$invoice.id|urlEncode}"
               class="button square">
                <img src='images/delete.png' class='action desktopOnly' alt="{$LANG.delete}"/>&nbsp;{$LANG.delete}
            </a>
        {/if}
        <!-- #PDF end -->
    </div>
</div>
<br/>
<div class="form__container">
    <!--Actions heading - start-->
    {include file="$path/quickViewInvoiceView.tpl"}
    {if $invoice.type_id == TOTAL_INVOICE}
        {include file="$path/quickViewTotal.tpl"}
    {elseif $invoice.type_id == ITEMIZED_INVOICE}
        {include file="$path/quickViewItemized.tpl"}

        {foreach $invoiceItems as $invoiceItem }
{*            Set here because it can't be tested in included file *}
            {$even = $invoiceItem@iteration is div by 2}
            {include file="$path/quickViewForeachItemized.tpl"}
        {/foreach}
        {if !empty($invoice.note)}
            <br/>
            <div class="row">
                <div class="col__20">
                    <span class="label">{$LANG.notes}:</span>
                </div>
                <div class="col__75">
                    <span class="abbrevNotes inputText">{$invoice.note|truncate:80:"...":true|outHtml}</span>
                    <span class="fullNotes inputText" style="display: none;">{$invoice.note|outHtml}</span>
                </div>
                <div class="col__5">
                    <span class="showHide">
                        <a href='#' class="showNotes"
                           onclick="$('.fullNotes').show();$('.abbrevNotes').hide();$('.hideNotes').show();$('.showNotes').hide();">
                            <img src="images/magnifier_zoom_in.png" alt="{$LANG.showDetails}"/>
                        </a>
                        <a href='#' class="hideNotes" style="display:none;"
                           onclick="$('.fullNotes').hide();$('.abbrevNotes').show();$('.hideNotes').hide();$('.showNotes').show();">
                            <img src="images/magnifier_zoom_out.png" alt="{$LANG.hideDetails}"/>
                        </a>
                    </span>
                </div>
            </div>
        {/if}
        <br/>
        {if !empty($invoice.sales_representative)}
            <div class="row">
                <div class="col__20">
                    <span class="label">{$LANG.salesRepresentative}:</span>
                </div>
                <div class="col__75">
                    <span class="inputText">{$invoice.sales_representative|htmlSafe}</span>
                </div>
            </div>
        {/if}
        {* end itemized invoice *}
    {/if}
    {$customFields.1}
    {$customFields.2}
    {$customFields.3}
    {$customFields.4}
    {* tax section - start --------------------- *}
    {if $invoiceNumberOfTaxes > 0}
        <div class="row">
            <div class="col__80">
                <div class="bold align__text-right">{$LANG.subtotalUc}:</div>
            </div>
            <div class="col__20">
                <div class="align__text-right {if $invoiceNumberOfTaxes > 1}underline{/if}">
                    {$invoice.gross|utilCurrency:$locale:$currencyCode}
                </div>
            </div>
        </div>
        {foreach $invoice.tax_grouped as $taxg}
            <div class="row">
                <div class="col__80">
                    <div class="bold align__text-right">{$taxg.tax_name|htmlSafe}:</div>
                </div>
                <div class="col__20">
                    <div class="align__text-right {if $taxg@last}underline{/if}">
                        {$taxg.tax_amount|utilCurrency:$locale:$currencyCode}
                    </div>
                </div>
            </div>
        {/foreach}
        <div class="row">
            <div class="col__80">
                <div class="bold align__text-right">{$LANG.taxTotal}:</div>
            </div>
            <div class="col__20">
                <div class="align__text-right underline_double">
                    {$invoice.total_tax|utilCurrency:$locale:$currencyCode}
                </div>
            </div>
        </div>
    {/if}
    {* tax section - end *}
    <div class="row">
        <div class="col__80">
            <span class="labelTotal">{$LANG.totalUc}&nbsp;{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.amountUc}:</span>
        </div>
        <div class="col__20">
            <span class="labelTotal">{$invoice.total|utilCurrency:$locale:$currencyCode}</span>
        </div>
    </div>
    <br/>
    <div class="grid__area-totals">
        <h4>{$LANG.financialStatus}</h4>
        <div class="grid__area-totals-financial">
            <div class="grid__area-totals-financial-area">
                <h5>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$invoice.index_id|htmlSafe}</h5>
                <div class="grid__area-totals-financial-invoice-box1">
                    <div class="bold align__text-right">{$LANG.totalUc}</div>
                    <div class="bold align__text-right">
                        <a href="index.php?module=payments&amp;view=manage&amp;id={$invoice.id|urlEncode}">
                            {$LANG.paidUc}
                        </a>
                    </div>
                    <div class="bold align__text-right">{$LANG.owingUc}</div>
                    <div class="bold align__text-right">{$LANG.age}
                        <img class="tooltip" title="{$LANG.helpAge}" src="{$helpImagePath}help-small.png" alt=""/>
                    </div>
                    <div class="align__text-right">{$invoice.total|utilCurrency:$locale:$currencyCode}</div>
                    <div class="align__text-right">{$invoice.paid|utilCurrency:$locale:$currencyCode}</div>
                    <div class="align__text-right">{$invoice.owing|utilCurrency:$locale:$currencyCode}</div>
                    <div class="align__text-right">{$invoiceAge|htmlSafe}</div>
                </div>
            </div>
            <div class="grid__area-totals-financial-area">
                <h5>
                    <a href="index.php?module=customers&amp;view=view&amp;id={$customer.id|urlEncode}">
                        {$LANG.customerAccount}
                    </a>
                </h5>
                <div class="grid__area-totals-financial-invoice-box2">
                    <div class="bold align__text-right">{$LANG.totalUc}</div>
                    <div class="bold align__text-right">
                        <a href="index.php?module=payments&amp;view=manage&amp;c_id={$customer.id|urlEncode}">
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
</div>