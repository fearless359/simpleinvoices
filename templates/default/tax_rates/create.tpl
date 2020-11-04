{if !empty($smarty.post.tax_description)}
    {include file="templates/default/tax_rates/save.tpl"}
{else}
    {* if  name was inserted *}
    <!--suppress HtmlFormInputWithoutLabel -->
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=tax_rates&amp;view=create">
        <div class="si_form">
            <table>
                <tr>
                    <th>{$LANG.descriptionUc}</th>
                    <td>
                        <input type="text" class="validate[required]" name="tax_description" size="35" tabindex="10"
                               value="{if isset($smarty.post.tax_description)}{$smarty.post.tax_description|htmlSafe}{/if}"/>
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <th>{$LANG.rateUc}
                        <a class="cluetip" href="#"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpTaxRateSign">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="tax_percentage" size="25" tabindex="20"
                               value="{if isset($smarty.post.tax_percentage)}{$smarty.post.tax_percentage|htmlSafe}{/if}"/>
                        {html_options name=type class=si_input options=$types selected=$types tabindex=30}
                    </td>
                    <td>{$LANG.ie10For10}</td>
                </tr>
                <tr>
                    <th>{$LANG.enabled}</th>
                    <td>
                        <select name="tax_enabled" tabindex="40"
                                value="{if isset($smarty.post.tax_enabled)}{$smarty.post.tax_enabled|htmlSafe}{/if}">
                            <option value="1" selected>{$LANG.enabled}</option>
                            <option value="0">{$LANG.disabled}</option>
                        </select>
                    </td>
                </tr>

            </table>

            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" tabindex="50" value="{$LANG.insertTaxRate}">
                    <img class="button_img" src="images/tick.png" alt=""/>
                    {$LANG.save}
                </button>

                <a href="index.php?module=tax_rates&amp;view=manage" class="negative">
                    <img src="images/cross.png" alt=""/>
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="create"/>
    </form>
{/if}
