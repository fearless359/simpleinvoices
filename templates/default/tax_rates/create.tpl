{*
 *  Script: create.tpl
 *      Tax Rates add template
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
{if !empty($smarty.post.tax_description)}
    {include file="templates/default/tax_rates/save.tpl"}
{else}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=tax_rates&amp;view=create">
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <label for="descId" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.descriptionUc}:</label>
                <input type="text" name="tax_description" id="descId" class="cols__5-span-3" required size="35" tabindex="10"
                       value="{if isset($smarty.post.tax_description)}{$smarty.post.tax_description|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="percentageId" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.rateUc}:
                    <img class="tooltip" title="{$LANG.helpTaxRateSign}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="tax_percentage" id="percentageId" class="cols__5-span-1" size="25" tabindex="20"
                       value="{if isset($smarty.post.tax_percentage)}{$smarty.post.tax_percentage|htmlSafe}{/if}"/>
                {html_options name=type class="cols__6-span-1 margin__left-1" options=$types selected=$types tabindex=30}
                <span class="cols__7-span-2 margin__left-1 margin__top-0-5">{$LANG.ie10For10}</span>
            </div>
            <div class="grid__container grid__head-10">
                <label for="enabledId" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.enabled}:</label>
                <select name="tax_enabled" id="enabledId" class="cols__5-span-1" tabindex="40"
                        value="{if isset($smarty.post.tax_enabled)}{$smarty.post.tax_enabled|htmlSafe}{/if}">
                    <option value="1" selected>{$LANG.enabled}</option>
                    <option value="0">{$LANG.disabled}</option>
                </select>
            </div>

            <div class="align__text-center margin__top-2 margin__bottom-1">
                <button type="submit" class="positive" name="submit" tabindex="50" value="{$LANG.insertTaxRate}">
                    <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
                </button>

                <a href="index.php?module=tax_rates&amp;view=manage" class="button negative">
                    <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="create"/>
    </form>
{/if}
