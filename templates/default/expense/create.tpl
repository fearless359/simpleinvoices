{*
 *  Script: create.tpl
 *      Expense add template
 *
 *  Last edited:
 *      20210621 by Rich Rowley to convert to grid layout.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}

{* if bill is updated or saved. *}
{if !empty($smarty.post.expense_account_id) }
    {include file="templates/default/expense/save.tpl"}
{else}
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=expense&amp;view=create">
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <label for="expenseAccountId" class="cols__2-span-2 align__text-right">{$LANG.expenseAccounts}:&nbsp;</label>
                <select name="expense_account_id" id="expenseAccountId" class="cols__4-span-4" required
                        autofocus tabindex="10">
                    <option value=''></option>
                    {foreach $expenseAdd.expense_accounts as $expense_account}
                        <option value="{if isset($expense_account.id)}{$expense_account.id}{/if}">{$expense_account.name}</option>
                    {/foreach}
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="date" class="cols__2-span-2 align__text-right">{$LANG.dateFormatted}:&nbsp;</label>
                <input type="text" name="date" id="date" required readonly class="cols__4-span-1 date-picker"
                       value='{$smarty.now|date_format:"%Y-%m-%d"}' tabindex="20"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="amountId" class="cols__2-span-2 align__text-right">{$LANG.amountUc}:&nbsp;</label>
                <input name="amount" id="amountId" class="cols__4-span-2" required tabindex="30"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="billerId" class="cols__2-span-2 align__text-right">{$LANG.billerUc}:&nbsp;</label>
                <select name="biller_id" id="billerId" class="cols__4-span-4" required tabindex="40">
                    <option value=''></option>
                    {foreach $expenseAdd.billers as $biller}
                        <option {if isset($biller.id) && $biller.id == $defaults.biller} selected {/if}
                                value="{if isset($biller.id)}{$biller.id}{/if}">{$biller.name}</option>
                    {/foreach}
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="customerId" class="cols__2-span-2 align__text-right">{$LANG.customerUc}:&nbsp;</label>
                <select name="customer_id" id="customerId" class="cols__4-span-4" tabindex="50">
                    <option value=''></option>
                    {foreach $expenseAdd.customers as $customer}
                        <option {if isset($customer.id) && $customer.id == $defaults.customer}selected{/if}
                                value="{if isset($customer.id)}{$customer.id}{/if}">
                            {$customer.name}
                        </option>
                    {/foreach}
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="invoiceId" class="cols__2-span-2 align__text-right">{$LANG.invoiceUc}:&nbsp;</label>
                <select name="invoice_id" id="invoiceId" class="cols__4-span-4" tabindex="60">
                    <option value=''></option>
                    {foreach $expenseAdd.invoices as $invoice}
                        <option value="{$invoice.id}">{$invoice.index_id}&nbsp;&dash;&nbsp;{$invoice.customer}&nbsp;&dash;&nbsp;{$invoice.date}</option>
                    {/foreach}
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="productId" class="cols__2-span-2 align__text-right">{$LANG.productUc}:&nbsp;</label>
                <select name="product_id" id="productId" class="cols__4-span-4" tabindex="70">
                    <option value=''></option>
                    {foreach $expenseAdd.products as $product}
                        <option value="{$product.id}">{$product.description}</option>
                    {/foreach}
                </select>
            </div>
            {if $defaults.tax_per_line_item > 0}
                <div class="grid__container grid__head-10">
                    <div class="cols__2-span-2 bold align__text-right">{$LANG.taxesUc}:&nbsp;</div>
                    {$begCol = 4}
                    {section name=tax loop=$defaults.tax_per_line_item}
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <select name="tax_id[0][{$smarty.section.tax.index}]" id="tax_id[0][{$smarty.section.tax.index}]"
                                class="cols__{$begCol}-span-2 {if !$smarty.section.tax.last}margin__right-1{/if}" tabindex="8{$smarty.section.tax.index}">
                            <option value=""></option>
                            {foreach $taxes as $tax}
                                <option {if $tax.tax_id == $defaults.tax && $smarty.section.tax.index == 0}selected{/if}
                                        value="{$tax.tax_id}">{$tax.tax_description}</option>
                            {/foreach}
                        </select>
                        {$begCol = $begCol+2}
                    {/section}
                </div>
            {/if}
            <div class="grid__container grid__head-10">
                <label for="statusId" class="cols__2-span-2 align__text-right">{$LANG.status}:&nbsp;</label>
                <select name="status" id="statusId" class="cols__4-span-1" tabindex="90">
                    <option value="{$smarty.const.ENABLED }" selected>{$LANG.paidUc}</option>
                    <option value="{$smarty.const.DISABLED}">{$LANG.notPaid}</option>
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="notesId" class="cols__2-span-2">{$LANG.notes}</label>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-8">
                    <input name="note" id="notesId" {if isset($smarty.post.notes)}value="{$smarty.post.notes|outHtml}"{/if} type="hidden">
                    <trix-editor input="notesId" tabindex="100"></trix-editor>
                </div>
            </div>
        </div>
        <div class="align__text-center">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="110">
                <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
            </button>
            <a href="index.php?module=expense&amp;view=manage" class="button negative" tabindex="120">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
        <input type="hidden" name="domain_id" value="{if isset($domain_id)}{$domain_id}{/if}"/>
    </form>
{/if}
