{* if bill is updated or saved. *}
{if !empty($smarty.post.expense_account_id) }
    {include file="templates/default/expense/save.tpl"}
{else}
    <!--suppress HtmlFormInputWithoutLabel -->
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=expense&amp;view=create">
        <div class="si_form">
            <table class="center" style="width:80%;">
                <tr>
                    <th class="details_screen">{$LANG.expense_accounts}</th>
                    <td>
                        <select name="expense_account_id" class="validate[required]" tabindex="10">
                            <option value=''></option>
                            {foreach $expense_add.expense_accounts as $expense_account}
                                <option value="{if isset($expense_account.id)}{$expense_account.id}{/if}">{$expense_account.name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.amount_uc}</th>
                    <td><input name="amount" class="validate[required]" tabindex="20"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.date_formatted}</th>
                    <td>
                        <input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10" name="date" id="date"
                               value='{$smarty.now|date_format:"%Y-%m-%d"}' tabindex="30"/>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.biller}</th>
                    <td>
                        <select name="biller_id" class="validate[required]">
                            <option value=''></option>
                            {foreach $expense_add.billers as $biller}
                                <option {if isset($biller.id) && $biller.id == $defaults.biller} selected {/if}
                                        value="{if isset($biller.id)}{$biller.id}{/if}" tabindex="40">{$biller.name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.customer}</th>
                    <td>
                        <select name="customer_id">
                            <option value=''></option>
                            {foreach $expense_add.customers as $customer}
                                <option {if isset($customer.id) && $customer.id == $defaults.customer} selected {/if}
                                        value="{if isset($customer.id)}{$customer.id}{/if}" tabindex="50">{$customer.name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoice_uc}</th>
                    <td>
                        <select name="invoice_id" tabindex="60">
                            <option value=''></option>
                            {foreach $expense_add.invoices as $invoice}
                                <option value="{$invoice.id}">{$invoice.index_name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.product_uc}</th>
                    <td>
                        <select name="product_id" tabindex="70">
                            <option value=''></option>
                            {foreach $expense_add.products as $product}
                                <option value="{if isset($product.id)}{$product.id}{/if}">{$product.description}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                    <tr>
                        <th class="details_screen">
                            {$LANG.tax} {if $defaults.tax_per_line_item > 1}{$smarty.section.tax.index+1}{/if}
                        </th>
                        <td>
                            <select id="tax_id[0][{$smarty.section.tax.index}]"
                                    name="tax_id[0][{$smarty.section.tax.index}]" tabindex="8{$smarty.section.tax.index}">
                                <option value=""></option>
                                {foreach $taxes as $tax}
                                    <option {if $tax.tax_id == $defaults.tax AND $smarty.section.tax.index == 0} selected {/if} value="{if isset($tax.tax_id)}{$tax.tax_id}{/if}">{$tax.tax_description}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                {/section}
                <tr>
                    <th class="details_screen">{$LANG.status}</th>
                    <td>
                        <select name="status" tabindex="90">
                            <option value="1" selected>{$LANG.paid}</option>
                            <option value="0">{$LANG.not_paid}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen" colspan="2">{$LANG.notes}</th>
                </tr>
                <tr>
                    <td colspan="2">
                        <input name="note" id="note" {if isset($smarty.post.notes)}value="{$smarty.post.notes|outHtml}"{/if} type="hidden">
                        <trix-editor input="note" tabindex="100"></trix-editor>
                    </td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="110">
                    <img class="button_img" src="../../../images/tick.png" alt=""/>{$LANG.save}
                </button>
                <a href="index.php?module=expense&amp;view=manage" class="negative" tabindex="120">
                    <img src="../../../images/cross.png" alt=""/>
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="create"/>
        <input type="hidden" name="domain_id" value="{if isset($domain_id)}{$domain_id}{/if}"/>
    </form>
{/if}
