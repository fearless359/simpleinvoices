{*
 *  Script: create.tpl
 *      Tax Rates add template
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
{if !empty($smarty.post.tax_description)}
    {include file="templates/default/tax_rates/save.tpl"}
{else}
    <div class="form__container">
        <form name="frmpost" method="POST" id="frmpost"
              action="index.php?module=tax_rates&amp;view=create">
            <div class="row">
                <div class="col__20">
                    <label for="descId" class="margin__right-1">{$LANG.descriptionUc}:</label>
                </div>
                <div class="col__80">
                    <input type="text" name="tax_description" id="descId" required size="35" tabindex="10"
                           value="{if isset($smarty.post.tax_description)}{$smarty.post.tax_description|htmlSafe}{/if}"/>
                </div>
            </div>
            <input type="hidden" name="locale" id="localeId" value="{$config.localLocale}">
            <input type="hidden" name="currency_code" id="currencyCodeId" value="{$config.localCurrencyCode}">
            <input type="hidden" name="precision" id="precisionId" value="{$config.localPrecision}">
            <div class="row">
                <div class="col__20">
                    <label for="percentageId" class="margin__right-1">{$LANG.rateUc}:
                        <img class="tooltip" title="{$LANG.helpTaxRateSign}" src="{$helpImagePath}help-small.png"
                             alt=""/>
                    </label>
                </div>
                <div class="col__80">
                    <div class="row">
                        <div class="col__25">
                            <input type="text" name="tax_percentage" id="percentageId" size="25" tabindex="20"
                                   class="validateNumber adjustTaxSign"
                                   value="{if isset($smarty.post.tax_percentage)}{$smarty.post.tax_percentage|htmlSafe}{/if}"/>
                        </div>
                        <div class="col__25">
                            {html_options name=type class="checkTaxPercentage" id="typeId" options=$types tabindex=30}
                        </div>
                        <div class="col__30 align__text-left margin__left-2" style="margin-top: 2rem;">
                            <span>{$LANG.ie10For10}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="enabledId" class="margin__right-1">{$LANG.enabled}:</label>
                </div>
                <div class="col__25">
                    <select name="tax_enabled" id="enabledId" tabindex="40">
                        <option value="1" selected>{$LANG.enabled}</option>
                        <option value="0">{$LANG.disabled}</option>
                    </select>
                </div>
            </div>
            <div class="align__text-center margin__top-2 margin__bottom-1">
                <button type="submit" class="positive" name="submit" tabindex="50"
                        value="{$LANG.insertTaxRate}">
                    <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
                </button>
                <a href="index.php?module=tax_rates&amp;view=manage" class="button negative">
                    <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
                </a>
            </div>
            <input type="hidden" name="op" value="create"/>
        </form>
    </div>
{/if}
