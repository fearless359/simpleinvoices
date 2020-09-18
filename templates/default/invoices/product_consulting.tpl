{*
 * Script: consulting.tpl
 * 	 Consulting invoice type template
 *
 * Authors:
 *	 Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 * 	 2018-12-01 by Richard Rowley
 *
 * License:
 *	 GPL v2 or above
 *
 * Website:
 *	https://simpleinvoices.group
 *}
<!--suppress HtmlFormInputWithoutLabel, HtmlUnknownTag -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=invoices&amp;view=save">
    <h3>{$LANG.invoice_uc} {$LANG.inv_consulting}</h3>
    {include file="$path/header.tpl" }
    <tr>
        <td class="details_screen">{$LANG.quantity}</td>
        <td class="details_screen">{$LANG.description_uc}</td>
        <td class="details_screen">{$LANG.price}</td>
    </tr>
    {section name=line start=0 loop=$dynamic_line_items step=1}
        <tr>
            <td><input type="text" name="quantity{$smarty.section.line.index|htmlSafe}" size="5"/></td>
            <td><input type="text" name="description{$smarty.section.line.index|htmlSafe}" size="50"/></td>
            <td><input type="text" name="price{$smarty.section.line.index|htmlSafe}" size="50"/></td>
        </tr>
        <tr class="text{$smarty.section.line.index|htmlSafe} hide">
            <td colspan="3">
        <textarea class="detail" name='notes{$smarty.section.line.index|htmlSafe}' rows="3" cols="80"
                  data-description="{$LANG['description_uc']}"></textarea>
            </td>
        </tr>
    {/section}
    {$customFields.1}
    {$customFields.2}
    {$customFields.3}
    {$customFields.4}
    <tr>
        <td colspan="2" class="details_screen">{$LANG.notes}</td>
    </tr>
    <tr>
        <td colspan="2">
            <input name="note" id="note" type="hidden">
            <trix-editor input="note"></trix-editor>
        </td>
    </tr>
    <tr>
        <td class="details_screen">{$LANG.tax}</td>
        <td>
            <input type="text" name="tax" size="15"/>
            {if !isset($taxes) }
                <p><em>{$LANG.no_taxes}</em></p>
            {else}
                <select name="tax_id">
                    {foreach $taxes as $tax}
                        <option {if $tax.tax_id == $defaults.tax} selected {/if} value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}">{$tax.tax_description|htmlSafe}</option>
                    {/foreach}
                </select>
            {/if}
        </td>
    </tr>
    <tr>
        <td class="details_screen">{$LANG.inv_pref}</td>
        <td><input type="text" name="preference_id"/>
            {if !isset($preferences) }
                <p><em>{$LANG.no_preferences}</em></p>
            {else}
                <select name="preference_id">
                    {foreach $preferences as $preference}
                        <option {if $preference.pref_id == $defaults.preference} selected {/if} value="{if isset($preference.pref_id)}{$preference.pref_id|htmlSafe}{/if}">{$preference.pref_description|htmlSafe}</option>
                    {/foreach}
                </select>
            {/if}
        </td>
    </tr>
    <tr>
        <td>
            <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
                <img src="{$helpImagePath}help-small.png" alt=""/>
                {$LANG.want_more_fields}
            </a>
        </td>
    </tr>
    <hr/>
    <div style="text-align:center;">
        <input type="hidden" name="max_items" value="{if isset($smarty.section.line.index)}{$smarty.section.line.index|htmlSafe}{/if}"/>
        <input type="submit" name="submit" value="{$LANG.save_invoice}"/>
        <input type="hidden" name="type" value="4"/>
    </div>
</form>
