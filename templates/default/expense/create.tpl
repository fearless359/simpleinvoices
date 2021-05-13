{* if bill is updated or saved. *}
{if !empty($smarty.post.expense_account_id) }
    {include file="templates/default/expense/save.tpl"}
{else}
    <!--suppress HtmlFormInputWithoutLabel -->
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=expense&amp;view=create">
        <div class="si_form">
            <table class="center">
                <tr>
                    <th class="details_screen">{$LANG.expenseAccounts}:</th>
                    <td>
                        <select name="expense_account_id" class="si_input validate[required]" tabindex="10">
                            <option value=''></option>
                            {foreach $expenseAdd.expense_accounts as $expense_account}
                                <option value="{if isset($expense_account.id)}{$expense_account.id}{/if}">{$expense_account.name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.amountUc}:</th>
                    <td><input name="amount" class="validate[required]" tabindex="20"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.dateFormatted}:</th>
                    <td>
                        <input type="text" name="date" id="date" class="si_input validate[required,custom[date],length[0,10]] date-picker" size="10"
                               value='{$smarty.now|date_format:"%Y-%m-%d"}' tabindex="30"/>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.billerUc}:</th>
                    <td>
                        <select name="biller_id" class="si_input validate[required]" tabindex="40">
                            <option value=''></option>
                            {foreach $expenseAdd.billers as $biller}
                                <option {if isset($biller.id) && $biller.id == $defaults.biller} selected {/if}
                                        value="{if isset($biller.id)}{$biller.id}{/if}" tabindex="40">{$biller.name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.customerUc}:</th>
                    <td>
                        <select name="customer_id" class="si_input" tabindex="50">
                            <option value=''></option>
                            {foreach $expenseAdd.customers as $customer}
                                <option {if isset($customer.id) && $customer.id == $defaults.customer} selected {/if}
                                        value="{if isset($customer.id)}{$customer.id}{/if}" tabindex="50">{$customer.name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.invoiceUc}:</th>
                    <td>
                        <select name="invoice_id" class="si_input" tabindex="60">
                            <option value=''></option>
                            {foreach $expenseAdd.invoices as $invoice}
                                <option value="{$invoice.id}">{$invoice.index_name}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.productUc}:</th>
                    <td>
                        <select name="product_id" class="si_input" tabindex="70">
                            <option value=''></option>
                            {foreach $expenseAdd.products as $product}
                                <option value="{if isset($product.id)}{$product.id}{/if}">{$product.description}</option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                    <tr>
                        <th class="details_screen">
                            {$LANG.tax} {if $defaults.tax_per_line_item > 1}{$smarty.section.tax.index+1}{/if}:
                        </th>
                        <td>
                            <select name="tax_id[0][{$smarty.section.tax.index}]" id="tax_id[0][{$smarty.section.tax.index}]"
                                    class="si_input" tabindex="8{$smarty.section.tax.index}">
                                <option value=""></option>
                                {foreach $taxes as $tax}
                                    <option {if $tax.tax_id == $defaults.tax AND $smarty.section.tax.index == 0} selected {/if} value="{if isset($tax.tax_id)}{$tax.tax_id}{/if}">{$tax.tax_description}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                {/section}
                <tr>
                    <th class="details_screen">{$LANG.status}:</th>
                    <td>
                        <select name="status" class="si_input" tabindex="90">
                            <option value="1" selected>{$LANG.paidUc}</option>
                            <option value="0">{$LANG.notPaid}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen" colspan="2">{$LANG.notes}</th>
                </tr>
                <tr>
                    <td colspan="2">
                        <input name="note" id="note" {if isset($smarty.post.notes)}value="{$smarty.post.notes|outHtml}"{/if} type="hidden">
                        <trix-editor input="note" class="si_input" tabindex="100"></trix-editor>
                    </td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="110">
                    <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
                </button>
                <a href="index.php?module=expense&amp;view=manage" class="negative" tabindex="120">
                    <img src="images/cross.png" alt=""/>
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="create"/>
        <input type="hidden" name="domain_id" value="{if isset($domain_id)}{$domain_id}{/if}"/>
    </form>
{/if}
