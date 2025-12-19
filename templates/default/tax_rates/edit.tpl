{*
 *  Script: edit.tpl
 *      Tax Rates update template
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      20251209 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      20210701 by Rich Rowley to convert to grid layout.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="form__container">
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=tax_rates&amp;view=save&amp;id={$smarty.get.id|urlEncode}">
        <div class="row">
            <div class="col__20">
                <label for="descId" tabindex="-1">{$LANG.descriptionUc}:</label>
            </div>
            <div class="col__80">
                <input type="text" name="tax_description" id="descId" required size="25" tabindex="10"
                       value="{if isset($tax.tax_description)}{$tax.tax_description|htmlSafe}{/if}"/>
            </div>
        </div>
        <input type="hidden" name="locale" id="localeId" value="{$config.localLocale}">
        <input type="hidden" name="currency_code" id="currencyCodeId" value="{$config.localCurrencyCode}">
        <input type="hidden" name="precision" id="precisionId" value="{$config.localPrecision}">
        <div class="row">
            <div class="col__20">
                <label for="percentageId">{$LANG.rateUc}:
                    <img class="tooltip" title="{$LANG.helpTaxRateSign}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__80">
                <div class="row">
                    <div class="col__25">
                        <input type="text" name="tax_percentage" id="percentageId" tabindex="20" size="10" required
                               class="validateNumber adjustTaxSign" value="{$tax.tax_percentage|utilNumber}"/>
                    </div>
                    <div class="col__25">
                        {html_options name=type class="checkTaxPercentage" id="typeId"
                        options=$types selected=$tax.type tabindex=21}
                    </div>
                    <div class="col__30 align__text-left margin__left-2" style="margin-top: 2rem;">
                        <span>{$LANG.ie10For10}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="enabledId">{$LANG.enabled}:</label>
            </div>
            <div class="col__80">
                <select name="tax_enabled" id="enabledId" tabindex="30">
                    <option value="{$smarty.const.ENABLED}"
                            {if isset($tax.tax_enabled) && $tax.tax_enabled == $smarty.const.ENABLED}selected{/if}>{$LANG.enabled}</option>
                    <option value="{$smarty.const.DISABLED}"
                            {if isset($tax.tax_enabled) && $tax.tax_enabled != $smarty.const.ENABLED}selected{/if}>{$LANG.disabled}</option>
                </select>
            </div>
        </div>
        <div class="align__text-center margin__top-2 margin__bottom-1">
            <button type="submit" class="positive" name="save_tax_rate" value="{$LANG.saveTaxRate}" tabindex="40">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=tax_rates&amp;view=manage" class="button negative" tabindex="50">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="edit"/>
        <input type="hidden" name="orig_description" value="{$orig_description}"/>
    </form>
</div>
