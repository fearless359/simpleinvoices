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
<div class="form__container">
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
            <div class="row">
                <div class="col__20 align__text-left">
                    <span class="bold">{$LANG.notes}:</span>
                </div>
                <div class="col__80 align__text-right">
                    <span class="float__rignt align__right">
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
            <div class="row">
                <div class="col__100">
                    <span class="abbrevNotes">{$invoice.note|truncate:80:"...":true|outHtml}</span>
                    <span class="fullNotes" style="display: none;">{$invoice.note|outHtml}</span>
                </div>
            </div>
        {/if}
        <br/>
        {if !empty($invoice.sales_representative)}
            <div class="row">
                <div class="col__20">
                    <label for="salesRepId">{$LANG.salesRepresentative}:</label>
                </div>
                <div class="col__80">
                    <input type="text" id="salesRepId" value="{$invoice.sales_representative|htmlSafe}" disabled/>
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
            <div class="bold align__text-right">{$LANG.totalUc} {$preference.pref_inv_wording|htmlSafe} {$LANG.amountUc}
                :
            </div>
        </div>
        <div class="col__20">
            <div class="bold float__right">{$invoice.total|utilCurrency:$locale:$currencyCode}</div>
        </div>
    </div>
    <br/>
    <br/>
    <div class="align__text-center">
        <a href="index.php?module=cron&amp;view=edit&amp;id={$cronId}" class="button positive" tabindex="901">
            <img src="images/tick.png" alt="{$LANG.returnToPreviousScreen}"/>{$LANG.returnToPreviousScreen}
        </a>
    </div>
    <br/>
</div>
