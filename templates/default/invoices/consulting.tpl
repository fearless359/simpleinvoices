{*
/*
* Script: consulting.tpl
* 	 Consulting invoice type template
*
* License:
*	 GPL v3 or above
*
* Website:
*	https://simpleinvoices.group
*/
*}
<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=invoices&amp;view=save">
    <h3>{$LANG.invoiceUc} {$LANG.invConsulting}
        <div id="gmail_loading" class="gmailLoader" style="float:right; display: none;">
            <img src="../../../images/gmail-loader.gif" alt="{$LANG.loading} ..."/> {$LANG.loading} ...
        </div>
    </h3>
    {include file="$path/header.tpl" }
    <tr>
        <th class="details_screen">{$LANG.quantity}</th>
        <th class="details_screen">{$LANG.descriptionUc}</th>
        <th class="details_screen">{$LANG.unitPrice}</th>
    </tr>
    {section name=line start=0 loop=$dynamic_line_items step=1}
        <tr>
            <td>
                <input type="text" id="quantity{$smarty.section.line.index|htmlSafe}" class="si_input"
                       {if $smarty.section.line.index == 0}class="validate[required,min[.01],custom[number]]"{/if}
                       name="quantity{$smarty.section.line.index|htmlSafe}" size="5"/>
            </td>
            <td>
                <input type="text" name="description{$smarty.section.line.index|htmlSafe}" class="si_input" size="50"/>
                {if !isset($products) }
                    <p><em>{$LANG.noProducts}</em></p>
                {else}
                    <select name="products{$smarty.section.line.index|htmlSafe}"
                            class="si_input product_change {if $smarty.section.line.index == 0}validate[required]{/if}">
                        <option value=""></option>
                        {foreach $products as $product}
                            <option {if $product.id == $defaults.product} selected {/if} value="{if isset($product.id)}{$product.id|htmlSafe}{/if}">{$product.description|htmlSafe}</option>
                        {/foreach}
                    </select>
                {/if}
            </td>
            <td>
                <input id="unit_price{$smarty.section.line.index|htmlSafe}" name="unit_price{$smarty.section.line.index|htmlSafe}"
                       class="si_input" size="7" value=""/>
            </td>
        </tr>
        <tr class="text{$smarty.section.line.index|htmlSafe} hide">
            <td colspan="3">
                <textarea class="si_input detail" name='description{$smarty.section.line.index|htmlSafe}'
                          rows="3" cols="80" data-description="{$LANG.descriptionUc}"></textarea>
            </td>
        </tr>
    {/section}
    {$customFields.1}
    {$customFields.2}
    {$customFields.3}
    {$customFields.4}
    <tr>
        <th colspan="3" class="details_screen">{$LANG.notes}:</th>
    </tr>
    <tr>
        <td colspan="3">
            <input name="note" id="note" type="hidden">
            <trix-editor input="note" class="si_input"></trix-editor>
        </td>
    </tr>
    <tr>
        <th class="details_screen">{$LANG.tax}:</th>
        <td>
            <input type="text" name="tax" class="si_input" size="15"/>
            {if !isset($taxes) }
                <p><em>{$LANG.noTaxes}</em></p>
            {else}
                <select name="tax_id" class="si_input">
                    {foreach $taxes as $tax}
                        <option {if $tax.tax_id == $defaults.tax}selected{/if}
                                value="{if isset($tax.tax_id)}{$tax.tax_id|htmlSafe}{/if}">{$tax.tax_description|htmlSafe}</option>
                    {/foreach}
                </select>
            {/if}
        </td>
    </tr>
    <tr>
        <th class="details_screen">{$LANG.invPref}:</th>
        <td><input type="text" name="preference_id" class="si_input"/>
            {if !isset($preferences) }
                <p><em>{$LANG.noPreferences}</em></p>
            {else}
                <select name="preference_id">
                    {foreach $preferences as $preference}
                        <option {if $preference.pref_id == $defaults.preference}selected{/if}
                                value="{if isset($preference.pref_id)}{$preference.pref_id|htmlSafe}{/if}">{$preference.pref_description|htmlSafe}</option>
                    {/foreach}
                </select>
            {/if}
        </td>
    </tr>
    <tr>
        <td>
            <a class="cluetip" href="#"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvoiceCustomFields" title="{$LANG.wantMoreFields}">
                <img src="{$helpImagePath}help-small.png" alt=""/>
                {$LANG.wantMoreFields}
            </a>
        </td>
    </tr>
    {* This is NOT an unmatched tag. It is counterpart exists in the parent file that includes this file. *}
    </table>
    <hr/>
    <table class="center">
        <tr>
            <td>
                <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                    <img class="button_img" src="../../../images/tick.png" alt=""/>
                    {$LANG.save}
                </button>
                <input type="hidden" name="max_items" value="{$smarty.section.line.index|htmlSafe}"/>
                <input type="hidden" name="type" value="3"/>
                <a href="index.php?module=invoices&amp;view=manage" class="negative">
                    <img src="../../../images/cross.png" alt=""/>
                    {$LANG.cancel}
                </a>
            </td>
        </tr>
    </table>
</form>
