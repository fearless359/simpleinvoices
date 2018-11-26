{*
 *  Script: itemised.tpl
 *      Itemized invoice template
 *
 *  License:
 *      GPL v3 or above
 *
 *  Last modified:
 *      2018-10-06 by Richard Rowley
 *
 *  Website:
 *      https://simpleinvoices.group*/
*}
{* Note that frmpost_Validator() is generated at runtime using the DynamicJs::formValidationBegin() function*}
<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post" onsubmit="return frmpost_Validator(this);">
    <div id="gmail_loading" class="gmailLoader si_hide" style="float:right;">
        <img src="images/common/gmail-loader.gif" alt="{$LANG.loading} ..."/>
        {$LANG.loading} ...
    </div>
    {if $first_run_wizard == true}
        <div class="si_message">
            {$LANG.thank_you} {$LANG.before_starting}
        </div>
        <table class="si_table_toolbar">
            {if empty($billers)}
                <tr>
                    <th>{$LANG.setup_as_biller}</th>
                    <td class="si_toolbar">
                        <a href="index.php?module=billers&amp;view=add" class="positive">
                            <img src="images/common/user_add.png" alt=""/>
                            {$LANG.add_new_biller}
                        </a>
                    </td>
                </tr>
            {/if}
            {if empty($customers)}
                <tr>
                    <th>{$LANG.setup_add_customer}</th>
                    <td class="si_toolbar">
                        <a href="index.php?module=customers&amp;view=add" class="positive">
                            <img src="images/common/vcard_add.png" alt=""/>
                            {$LANG.customer_add}
                        </a>
                    </td>
                </tr>
            {/if}
            {if empty($products)}
                <tr>
                    <th>{$LANG.setup_add_products}</th>
                    <td class="si_toolbar">
                        <a href="index.php?module=products&amp;view=add" class="positive">
                            <img src="images/common/cart_add.png" alt=""/>
                            {$LANG.add_new_product}
                        </a>
                    </td>
                </tr>
            {/if}
            <tr>
                <th>{$LANG.setup_customisation}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=system_defaults&amp;view=manage" class="">
                        <img src="images/common/cog_edit.png" alt=""/>
                        {$LANG.system_preferences}
                    </a>
                </td>
            </tr>
        </table>
    {else}
        <div class="si_invoice_form">
            {include file="$path/header.tpl" }
            <table align="left">
                <tr>
                    <td colspan="3">
                        <table id="itemtable">
                            <tbody id="itemtable-tbody">
                            <tr>
                                <td class="details_screen"></td>
                                <td class="details_screen">{$LANG.quantity}</td>
                                <td class="details_screen">{$LANG.item}</td>
                                {section name=tax_header loop=$defaults.tax_per_line_item }
                                    <td class="details_screen">{$LANG.tax}
                                        {if $defaults.tax_per_line_item > 1}{$smarty.section.tax_header.index+1|htmlsafe}{/if}
                                    </td>
                                {/section}
                                <td class="details_screen">{$LANG.unit_price}</td>
                            </tr>
                            </tbody>
                            {section name=line start=0 loop=$dynamic_line_items step=1}
                                {assign var="lineNumber" value=$smarty.section.line.index }
                                <tbody class="line_item" id="row{$smarty.section.line.index|htmlsafe}">
                                <tr>
                                    <td>
                                        {if $smarty.section.line.index == "0"}
                                            <a href="#" class="trash_link" id="trash_link{$smarty.section.line.index|htmlsafe}"
                                               title="{$LANG.cannot_delete_first_row|htmlsafe}"
                                               data_delete_line_item={$config->confirm->deleteLineItem}>
                                                <img id="trash_image{$smarty.section.line.index|htmlsafe}" src="images/common/blank.gif" height="16px" width="16px"
                                                     title="{$LANG.cannot_delete_first_row}" alt=""/>
                                            </a>
                                        {/if}
                                        {if $smarty.section.line.index != 0}
                                            {* can't delete line 0 *}
                                            <!-- onclick="delete_row({$smarty.section.line.index|htmlsafe});" -->
                                            <a id="trash_link{$smarty.section.line.index|htmlsafe}" class="trash_link" title="{$LANG.delete_row}"
                                               rel="{$smarty.section.line.index|htmlsafe}" href="#" style="display: inline;"
                                               data_delete_line_item={$config->confirm->deleteLineItem}>
                                                <img src="images/common/delete_item.png" alt=""/>
                                            </a>
                                        {/if}
                                    </td>
                                    <td>
                                        <input type="text" {if $smarty.section.line.index == "0"}class="validate[required]"{/if}
                                               name="quantity{$smarty.section.line.index|htmlsafe}"
                                               id="quantity{$smarty.section.line.index|htmlsafe}" size="5"
                                                {if $smarty.get.quantity.$lineNumber}value="{$smarty.get.quantity.$lineNumber}"{/if} />
                                    </td>
                                    <td>
                                        {if $products == null }
                                            <p><em>{$LANG.no_products}</em></p>
                                        {else}
                                            <select id="products{$smarty.section.line.index|htmlsafe}" name="products{$smarty.section.line.index|htmlsafe}"
                                                    rel="{$smarty.section.line.index|htmlsafe}" class="{if $smarty.section.line.index == "0"}validate[required]{/if} product_change"
                                                    data_description="{$LANG.description}">
                                                <option value=""></option>
                                                {foreach from=$products item=product}
                                                    <option value="{if $product.id == $smarty.get.product.$lineNumber} {$smarty.get.product.$lineNumber}" selected {else} {$product.id|htmlsafe}"{/if}" >
                                                    {$product.description|htmlsafe}
                                                    </option>
                                                {/foreach}
                                            </select>
                                        {/if}
                                    </td>
                                    {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                                        {assign var="taxNumber" value=$smarty.section.tax.index}
                                        <td>
                                            <select id="tax_id[{$smarty.section.line.index|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]"
                                                    name="tax_id[{$smarty.section.line.index|htmlsafe}][{$smarty.section.tax.index|htmlsafe}]">
                                                <option value=""></option>
                                                {foreach from=$taxes item=tax}
                                                    <option value="{if $tax.tax_id == $smarty.get.tax.$lineNumber.$taxNumber}{$smarty.get.tax.$lineNumber.$taxNumber}" selected {else} {$tax.tax_id|htmlsafe}"{/if} >
                                                    {$tax.tax_description|htmlsafe}
                                                    </option>
                                                {/foreach}
                                            </select>
                                        </td>
                                    {/section}
                                    <td>
                                        <input id="unit_price{$smarty.section.line.index|htmlsafe}"
                                               name="unit_price{$smarty.section.line.index|htmlsafe}" size="7"
                                               value="{if $smarty.get.unit_price.$lineNumber}{$smarty.get.unit_price.$lineNumber}{/if}"
                                               {if $smarty.section.line.index == "0"}class="validate[required]"{/if} />
                                    </td>
                                </tr>
                                <tr class="note">
                                    <td></td>
                                    <td colspan="4">
                                        <textarea class="detail" name="description{$smarty.section.line.index|htmlsafe}"
                                                  id="description{$smarty.section.line.index|htmlsafe}" rows="3" cols="50"
                                                  data_description="{$LANG['description']}"></textarea>
                                    </td>
                                </tr>
                                </tbody>
                            {/section}
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table class="center" align="left">
                            <tr>
                                <td>
                                    <a href="#" class="add_line_item" data_description="{$LANG.description}">
                                        <img src="images/common/add.png" alt=""/>
                                        {$LANG.add_new_row}
                                    </a>
                                </td>
                                <td>
                                    <a href='#' class="show-note" onclick="javascript: $('.note').show();$('.show-note').hide();">
                                        <img src="images/common/page_white_add.png" title="{$LANG.show_details}" alt=""/>
                                        {$LANG.show_details}
                                    </a>
                                    <a href='#' class="note" onclick="javascript: $('.note').hide();$('.show-note').show();">
                                        <img src="images/common/page_white_delete.png" title="{$LANG.hide_details}" alt=""/>
                                        {$LANG.hide_details}
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                {* $customFields.1 *}
                {$customFields.2}
                {$customFields.3}
                {$customFields.4}
                <tr>
                    <td colspan="1" class="details_screen">{$LANG.notes}</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <textarea class="editor" name="note" rows="5" cols="50">{$smarty.get.note}</textarea>
                    </td>
                </tr>
                <tr>
                    <th>
                        {$LANG.inv_pref}&nbsp;&nbsp;
                    </th>
                    <td class="details_screen">
                        {if $preferences == null }
                            <p><em>{$LANG.no_preferences}</em></p>
                        {else}
                            <select name="preference_id">
                                {foreach from=$preferences item=preference}
                                    <option {if $preference.pref_id == $defaults.preference} selected {/if} value="{$preference.pref_id|htmlsafe}">
                                        {$preference.pref_description|htmlsafe}
                                    </option>
                                {/foreach}
                            </select>
                        {/if}
                    </td>
                    <th>{$LANG.sales_representative}</th>
                    <td>
                        <input id="sales_representative}" name="sales_representative" size="30"
                               value="{$invoice.sales_representative|htmlsafe}" />
                    </td>
                </tr>
                <tr>
                    <td class="">
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_custom_fields"
                           title="{$LANG.want_more_fields}">
                            <img src="{$help_image_path}help-small.png" alt=""/>
                            {$LANG.want_more_fields}
                        </a>
                    </td>
                </tr>
            </table>
            {* These close open tags that are in the header.tpl file *}
            </td>
            </tr>
            <tr>
                <td>
                    <table class="center">
                        <tr>
                            <td>
                                <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.save}">
                                    <img class="button_img" src="images/common/tick.png" alt=""/>
                                    {$LANG.save}
                                </button>
                            </td>
                            <td>
                                <input type="hidden" id="max_items" name="max_items" value="{$smarty.section.line.index|htmlsafe}"/>
                                <input type="hidden" name="type" value="2"/>
                                <a href="index.php?module=invoices&amp;view=manage" class="negative">
                                    <img src="images/common/cross.png" alt=""/>
                                    {$LANG.cancel}
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            {* This closes open table tag in header.tpl *}
            </table>
        </div>
    {/if}
</form>
