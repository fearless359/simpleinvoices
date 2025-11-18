{*
 *  Script: view.tpl
 *      Tax Rates details template
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      20210701 by Rich Rowley to convert to grid layout.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="flex__area">
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.descriptionUc}:</div>
        <div>{$tax.tax_description|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.rateUc}:
            <img class="tooltip" title="{$LANG.helpTaxRateSign}" src="{$helpImagePath}help-small.png" alt=""/>
        </div>
        <div>{$tax.tax_percentage|utilNumber} {$tax.type|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.enabled}:</div>
        <div>{$tax.enabled_text|htmlSafe}</div>
    </div>
</div>
<div class="align__text-center margin__top-3 margin__bottom-2">
    <a href="index.php?module=tax_rates&amp;view=edit&amp;id={$tax.tax_id|urlEncode}" class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=tax_rates&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt=""/>{$LANG.cancel}
    </a>
</div>

