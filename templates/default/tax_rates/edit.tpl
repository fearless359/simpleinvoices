<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=tax_rates&amp;view=save&amp;id={$smarty.get.id|urlencode}">
    <div class="si_form">
        <table>
            <tr>
                <th class="details_screen" tabindex="-1">{$LANG.descriptionUc}:</th>
                <td>
                    <input type="text" name="tax_description" class="si_input validate[required]" size="25" tabindex="10"
                           value="{if isset($tax.tax_description)}{$tax.tax_description|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.rateUc}:
                    <a class="cluetip" href="#" title="{$LANG.taxRate}" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpTaxRateSign">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name="tax_percentage" class="si_input validate[required,custom[number]]" tabindex="20"
                           value="{$tax.tax_percentage|utilNumber}" size="10"/>
                    {html_options name=type class=si_input options=$types selected=$tax.type tabindex=21}
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.enabled}:</th>
                <td>
                    <select name="tax_enabled" class="si_input" tabindex="30">
                        <option value="{$smarty.const.ENABLED}" {if isset($tax.tax_enabled) && $tax.tax_enabled == $smarty.const.ENABLED}selected{/if}>{$LANG.enabled}</option>
                        <option value="{$smarty.const.DISABLED}" {if isset($tax.tax_enabled) && $tax.tax_enabled != $smarty.const.ENABLED}selected{/if}>{$LANG.disabled}</option>
                    </select>
                </td>
            </tr>
        </table>

        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_tax_rate" value="{$LANG.saveTaxRate}" tabindex="40">
                <img class="button_img" src="../../../images/tick.png" alt=""/>
                {$LANG.save}
            </button>

            <a href="index.php?module=tax_rates&amp;view=manage" class="negative" tabindex="50">
                <img src="../../../images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>

    </div>
    <input type="hidden" name="op" value="edit"/>
    <input type="hidden" name="orig_description" value="{$orig_description}"/>
</form>

