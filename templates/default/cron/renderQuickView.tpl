{*
 * Script: renderQuickView.tpl
 *      Quick view of invoice template
 *
 * Authors:
 *      Richard Rowley
 *
 * License:
 *      GPL v3 or above
 *
 * Website:
 *      https://simpleinvoices.group
 *}
<!--Actions heading - start-->
{include file="../invoices/quickViewInvoiceView.tpl"}
{if $invoice.type_id == TOTAL_INVOICE}
    {include file="../invoices/quickViewTotal.tpl"}
{elseif $invoice.type_id == ITEMIZED_INVOICE}
    {include file="../invoices/quickViewItemized.tpl"}

    {foreach $invoiceItems as $invoiceItem }
        {* Set here because it can't be tested in included file *}
        {$even = $invoiceItem@iteration is div by 2}
        {include file="../invoices/quickViewForeachItemized.tpl"}
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
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.salesRepresentative}:</div>
                <div class="cols__3-span-8">{$invoice.sales_representative|htmlSafe}</div>
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
            <div class="cols__1-span-9 bold align__text-right">{$LANG.subtotalUc}:</div>
            <div class="cols__10-span-1 align__text-right {if $invoiceNumberOfTaxes > 1}underline{/if}">
                {$invoice.gross|utilCurrency:$locale:$currencyCode}
            </div>
        </div>
        {foreach $invoice.tax_grouped as $taxg}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-9 bold align__text-right">{$taxg.tax_name|htmlSafe}:</div>
                <div class="cols__10-span-1 align__text-right {if $taxg@last}underline{/if}">{$taxg.tax_amount|utilCurrency:$locale:$currencyCode}</div>
            </div>
        {/foreach}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-9 bold align__text-right">{$LANG.taxTotal}:</div>
            <div class="cols__10-span-1 align__text-right underline_double">
                {$invoice.total_tax|utilCurrency:$locale:$currencyCode}
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-9 bold align__text-right">{$preference.pref_inv_wording|htmlSafe} {$LANG.amountUc}:</div>
            <div class="cols__10-span-1 align__text-right">{$invoice.total|utilCurrency:$locale:$currencyCode}</div>
        </div>
    </div>
{/if}
{* tax section - end *}
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-9 bold align__text-right">{$LANG.totalUc} {$preference.pref_inv_wording|htmlSafe} {$LANG.amountUc}:</div>
        <div class="cols__10-span-1 align__text-right bold">{$invoice.total|utilCurrency:$locale:$currencyCode}</div>
    </div>
</div>
<br/>
<div class="align__text-center">
    <a href="index.php?module=cron&amp;view=edit&amp;id={$cronId}" class="button positive" tabindex="901">
        <img src="images/tick.png" alt="{$LANG.returnToPreviousScreen}"/>{$LANG.returnToPreviousScreen}
    </a>
    <br/>&nbsp;
</div>
