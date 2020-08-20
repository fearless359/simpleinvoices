<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=tax_rates&amp;view=save&amp;id={$smarty.get.id|urlencode}">
    {if $smarty.get.action === 'view' }
        <div class="si_form si_form_view">
            <table>
                <tr>
                    <th>{$LANG.description}</th>
                    <td>{$tax.tax_description|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.rate}
                        <a class="cluetip" href="#" title="{$LANG.tax_rate}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_tax_rate_sign">
                            <img src="{$helpImagePath}help-small.png"/>
                        </a>
                    </th>
                    <td>
                        {$tax.tax_percentage|siLocal_number} {$tax.type|htmlsafe}
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.enabled}</th>
                    <td>{$tax.enabled_text|htmlsafe}</td>
                </tr>
            </table>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=tax_rates&amp;view=details&amp;id={$tax.tax_id|urlencode}&amp;action=edit" class="positive">
                <img src="../../../images/report_edit.png" alt=""/>
                {$LANG.edit}
            </a>
        </div>
    {elseif $smarty.get.action === 'edit'}
        <div class="si_form">
            <table>
                <tr>
                    <th>{$LANG.description}</th>
                    <td>
                        <input type="text" name="tax_description" class="validate[required]" size="25"
                               value="{if isset($tax.tax_description)}{$tax.tax_description|htmlsafe}{/if}"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.rate}
                        <a class="cluetip" href="#" title="{$LANG.tax_rate}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_tax_rate_sign">
                            <img src="{$helpImagePath}help-small.png"/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="tax_percentage" class="validate[required,custom[number]]" size="10"
                               value="{$tax.tax_percentage|siLocal_number}" size="10"/>
                        {html_options name=type options=$types selected=$tax.type}
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.enabled} </th>
                    <td>
                        <select name="tax_enabled">
                            <option value="{$smarty.const.ENABLED}" {if isset($tax.tax_enabled) && $tax.tax_enabled == $smarty.const.ENABLED}selected{/if}>{$LANG.enabled}</option>
                            <option value="{$smarty.const.DISABLED}" {if isset($tax.tax_enabled) && $tax.tax_enabled != $smarty.const.ENABLED}selected{/if}>{$LANG.disabled}</option>
                        </select>
                    </td>
                </tr>
            </table>

            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="save_tax_rate" value="{$LANG.save_tax_rate}">
                    <img class="button_img" src="../../../images/tick.png" alt=""/>
                    {$LANG.save}
                </button>

                <a href="index.php?module=tax_rates&amp;view=manage" class="negative">
                    <img src="../../../images/cross.png" alt=""/>
                    {$LANG.cancel}
                </a>
            </div>

        </div>
        <input type="hidden" name="op" value="edit"/>
        <input type="hidden" name="orig_description" value="{$orig_description}" />
    {/if}
</form>

