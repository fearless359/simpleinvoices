{* if bill is updated or saved. *}
{if !empty($smarty.post.expense_account_id) && isset($smarty.post.submit) }
    {include file="templates/default/expense/save.tpl"}
{else}
    {* if  name was inserted *}
    {if isset($smarty.post.submit)}
        <div class="validation_alert"><img src="images/common/important.png" alt=""/>
            You must enter a description for the product
        </div>
        <hr/>
    {/if}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=expense&amp;view=add">
        <input type="hidden" name="op" value="add"/>
        <input type="hidden" name="domain_id" value="{if isset($domain_id)}{$domain_id}{/if}"/>
        <table class="left" width="100%">
            <tr>
                <th class="left">{$LANG.amount}</th>
                <td><input name="amount" class="validate[required]"/></td>
            </tr>
            <tr>
                <th class="left">{$LANG.expense_accounts}</th>
                <td>
                    <select name="expense_account_id" class="validate[required]">
                        <option value=''></option>
                        {foreach from=$expense_add.expense_accounts item=expense_account}
                            <option value="{if isset($expense_account.id)}{$expense_account.id}{/if}">{$expense_account.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.date_formatted}</th>
                <td>
                    <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="date" id="date" value='{$smarty.now|date_format:"%Y-%m-%d"}'/>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.biller}</th>
                <td>
                    <select name="biller_id" class="validate[required]">
                        <option value=''></option>
                        {foreach from=$expense_add.billers item=biller}
                            <option {if isset($biller.id) && $biller.id == $defaults.biller} selected {/if} value="{if isset($biller.id)}{$biller.id}{/if}">{$biller.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.customer}</th>
                <td>
                    <select name="customer_id">
                        <option value=''></option>
                        {foreach from=$expense_add.customers item=customer}
                            <option {if isset($customer.id) && $customer.id == $defaults.customer} selected {/if} value="{if isset($customer.id)}{$customer.id}{/if}">{$customer.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.invoice}</th>
                <td>
                    <select name="invoice_id">
                        <option value=''></option>
                        {foreach from=$expense_add.invoices item=invoice}
                            <option value="{$invoice.id}">{$invoice.index_name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.product}</th>
                <td>
                    <select name="product_id">
                        <option value=''></option>
                        {foreach from=$expense_add.products item=product}
                            <option value="{if isset($product.id)}{$product.id}{/if}">{$product.description}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                <tr>
                    <td class="details_screen">
                        {$LANG.tax} {if $defaults.tax_per_line_item > 1}{$smarty.section.tax.index+1}{/if}
                    </td>
                    <td>
                        <select id="tax_id[0][{$smarty.section.tax.index}]"
                                name="tax_id[0][{$smarty.section.tax.index}]">
                            <option value=""></option>
                            {foreach from=$taxes item=tax}
                                <option {if $tax.tax_id == $defaults.tax AND $smarty.section.tax.index == 0} selected {/if} value="{if isset($tax.tax_id)}{$tax.tax_id}{/if}">{$tax.tax_description}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
            {/section}
            <tr>
                <th class="left">{$LANG.status}</th>
                <td>
                    <select name="status">
                        <option value="1" selected>{$LANG.paid}</option>
                        <option value="0">{$LANG.not_paid}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="left" colspan="2">{$LANG.notes}</th>
            </tr>
            <tr>
                <td colspan="2">
                    <!--
                    <textarea class="editor" name='note'>{*if isset($smarty.post.notes)*}{*$smarty.post.notes|unescape*}{*/if*}</textarea>
                    -->
                    <input name="note" id="note" {if isset($smarty.post.notes)}value="{$smarty.post.notes|outhtml}"{/if} type="hidden">
                    <trix-editor input="note"></trix-editor>
                </td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="images/common/tick.png" alt=""/>{$LANG.save}
            </button>
            <a href="index.php?module=expense&amp;view=manage" class="negative">
                <img src="images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
    </form>
{/if}
