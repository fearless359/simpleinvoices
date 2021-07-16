{*
 *  Script: edit.tpl
 *      Tax Rates update template
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
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=tax_rates&amp;view=save&amp;id={$smarty.get.id|urlencode}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <label for="descId" class="cols__3-span-2" tabindex="-1">{$LANG.descriptionUc}:</label>
            <input type="text" name="tax_description" id="descId" class="cols__5-span-5 validate[required]" size="25" tabindex="10"
                   value="{if isset($tax.tax_description)}{$tax.tax_description|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="percentageId" class="cols__3-span-2">{$LANG.rateUc}:
                <a class="cluetip" href="#" title="{$LANG.taxRate}" tabindex="-1"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpTaxRateSign">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </label>
            <input type="text" name="tax_percentage" id="percentageId"
                   class="cols__5-span-2 validate[required,custom[number]]" tabindex="20"
                   value="{$tax.tax_percentage|utilNumber}" size="10"/>
            {html_options name=type class="cols__7-span-1 magrin__left-1" options=$types selected=$tax.type tabindex=21}
            <span class="cols__8-span-2 margin__left-1">{$LANG.ie10For10}</span>
        </div>
        <div class="grid__container grid__head-10">
            <label for="enabledId" class="cols__3-span-2">{$LANG.enabled}:</label>
            <select name="tax_enabled" id="enabledId" class="cols__5-span-1" tabindex="30">
                <option value="{$smarty.const.ENABLED}" {if isset($tax.tax_enabled) && $tax.tax_enabled == $smarty.const.ENABLED}selected{/if}>{$LANG.enabled}</option>
                <option value="{$smarty.const.DISABLED}" {if isset($tax.tax_enabled) && $tax.tax_enabled != $smarty.const.ENABLED}selected{/if}>{$LANG.disabled}</option>
            </select>
        </div>

        <div class="align__text-center margin__top-3 margin__right-1">
            <button type="submit" class="positive" name="save_tax_rate" value="{$LANG.saveTaxRate}" tabindex="40">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>

            <a href="index.php?module=tax_rates&amp;view=manage" class="button negative" tabindex="50">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>

    </div>
    <input type="hidden" name="op" value="edit"/>
    <input type="hidden" name="orig_description" value="{$orig_description}"/>
</form>

