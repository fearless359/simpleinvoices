{* if  name was inserted *}
<form name="frmpost" method="POST" action="index.php?module=tax_rates&amp;view=add">
    <div class="si_form">
        <table>
            <tr>
                <th>{$LANG.description}</th>
                <td>
                    <input type="text" class="validate[required]" name="tax_description" size="35" tabindex="10"
                           value="{if isset($smarty.post.tax_description)}{$smarty.post.tax_description|htmlsafe}{/if}"/>
                </td>
                <td></td>
            </tr>
            <tr>
                <th>{$LANG.rate}
                    <a class="cluetip" href="#"
                       rel="index.php?module=documentation&amp;view=view&amp;page=help_tax_rate_sign">
                        <img src="{$help_image_path}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name="tax_percentage" size="25" tabindex="20"
                           value="{if isset($smarty.post.tax_percentage)}{$smarty.post.tax_percentage|htmlsafe}{/if}"/>
                    {html_options name=type options=$types selected=$types tabindex=30}
                </td>
                <td>{$LANG.ie_10_for_10}</td>
            </tr>
            <tr>
                <th>{$LANG.enabled}</th>
                <td>
                    <select name="tax_enabled" tabindex="40"
                            value="{if isset($smarty.post.tax_enabled)}{$smarty.post.tax_enabled|htmlsafe}{/if}">
                        <option value="1" selected>{$LANG.enabled}</option>
                        <option value="0">{$LANG.disabled}</option>
                    </select>
                </td>
            </tr>

        </table>

        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" tabindex="50" value="{$LANG.insert_tax_rate}">
                <img class="button_img" src="images/common/tick.png" alt=""/>
                {$LANG.save}
            </button>

            <a href="index.php?module=tax_rates&amp;view=manage" class="negative">
                <img src="images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
    </div>
    <input type="hidden" name="op" value="add"/>
</form>
