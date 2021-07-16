{*
 *  Script: view.tpl
 *      Tax Rates details template
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      20210701 by Rich Rowley to convert to grid layout.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__5-span-1 bold">{$LANG.descriptionUc}:</div>
        <div class="cols__6-span-5 margin__left-1">{$tax.tax_description|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__5-span-1 bold">{$LANG.rateUc}:
            <a class="cluetip" href="#" title="{$LANG.taxRate}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpTaxRateSign">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__6-span-2 margin__left-1">{$tax.tax_percentage|utilNumber} {$tax.type|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__5-span-1 bold">{$LANG.enabled}:</div>
        <div class="cols__6-span-1 margin__left-1">{$tax.enabled_text|htmlSafe}</div>
    </div>
</div>
<div class="align__text-center margin__top-3 margin__bottom-2">
    <a href="index.php?module=tax_rates&amp;view=edit&amp;id={$tax.tax_id|urlencode}" class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>

    <a href="index.php?module=tax_rates&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt=""/>{$LANG.cancel}
    </a>
</div>

