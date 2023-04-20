{*
 *  Script: edit.tpl
 *      Expense edit template
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
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=expense&amp;view=save&amp;id={$smarty.get.id|urlEncode}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <label for="expenseAccountId" class="cols__2-span-2 align__text-right margin__right-1">{$LANG.expenseAccounts}:</label>
            <select name="expense_account_id" id="expenseAccountId" class="cols__4-span-5" required
                    autofocus tabindex="10">
                <option value=''></option>
                {foreach $detail.expense_accounts as $expense_account}
                    <option {if $expense_account.id == $expense.ea_id}selected{/if}
                            value="{if isset($expense_account.id)}{$expense_account.id}{/if}">{$expense_account.name}</option>
                {/foreach}
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="date" class="cols__2-span-2 align__text-right margin__right-1">{$LANG.dateFormatted}:</label>
            <input type="text" name="date" id="date" required readonly class="cols__4-span-2 date-picker"
                   value="{$expense.date}" tabindex="20"/>
        </div>
        <input type="hidden" name="locale" id="localeId" value="{$expense.locale}">
        <input type="hidden" name="currency_code" id="currencyCodeId" value="{$expense.currency_code}">
        <div class="grid__container grid__head-10">
            <label for="amountId" class="cols__2-span-2 align__text-right margin__right-1">{$LANG.amountUc}:</label>
            <input name="amount" id="amountId" class="cols__4-span-2 validateNumber" required
                   value="{$expense.amount|utilNumber:$expense.preision:$expense.locale}" tabindex="30"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="billerId" class="cols__2-span-2 align__text-right margin__right-1">{$LANG.billerUc}:</label>
            <select name="biller_id" id="billerId" class="cols__4-span-5" required tabindex="40">
                <option value=''></option>
                {foreach $detail.billers as $biller}
                    <option {if $biller.id == $expense.b_id} selected {/if}
                            value="{if isset($biller.id)}{$biller.id}{/if}">{$biller.name}</option>
                {/foreach}
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="customerId" class="cols__2-span-2 align__text-right margin__right-1">{$LANG.customerUc}:</label>
            <select name="customer_id" id="customerId" class="cols__4-span-5" tabindex="50">
                <option value=''></option>
                {foreach $detail.customers as $customer}
                    <option {if $customer.id == $expense.c_id}selected{/if}
                            value="{if isset($customer.id)}{$customer.id}{/if}">{$customer.name}</option>
                {/foreach}
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="invoiceId" class="cols__2-span-2 align__text-right margin__right-1">{$LANG.invoiceUc}:</label>
            <select name="invoice_id" id="invoiceId" class="cols__4-span-5 expenseInvoiceChange" tabindex="60">
                <option value='' data-locale="{$config.localLocale}" data-currency-code="{$config.localCurrencyCode}"></option>
                {foreach $detail.invoices as $invoice}
                    <option value="{$invoice.id|htmlSafe}" data-locale="{$invoice.locale}"
                            data-currency-code="{$invoice.currency_code}" {if $invoice.id ==  $expense.iv_id}selected{/if}>
                        {$invoice.index_id}&nbsp;&dash;&nbsp;{$invoice.customer}&nbsp;&dash;&nbsp;{$invoice.date}</option>
                {/foreach}
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="productId" class="cols__2-span-2 align__text-right margin__right-1">{$LANG.productUc}:</label>
            <select name="product_id" id="productId" class="cols__4-span-5" tabindex="70">
                <option value=''></option>
                {foreach $detail.products as $product}
                    <option {if $product.id == $expense.p_id}selected{/if}
                            value="{$product.id}">{$product.description}</option>
                {/foreach}
            </select>
        </div>
        {if $defaults.tax_per_line_item > 0}
            <div class="grid__container grid__head-10">
                <label for id="tax_id[0][0]" class="cols__2-span-2 align__text-right margin__right-1">{$LANG.taxesUc}:</label>
                {$begCol=4}
                {section name=tax loop=$defaults.tax_per_line_item}
                    <!--suppress HtmlFormInputWithoutLabel -->
                    <select name="tax_id[0][{$smarty.section.tax.index}]" id="tax_id[0][{$smarty.section.tax.index}]"
                            class="cols__{$begCol}-span-1 {if !$smarty.section.tax.last}margin__right-1{/if}"
                            tabindex="8{$smarty.section.tax.index}">
                        <option value=''></option>
                        {$index = $smarty.section.tax.index}
                        {foreach $taxes as $tax}
                            <option {if !empty($detail.expense_tax) && $tax.tax_id == $detail.expense_tax.$index.tax_id}selected{/if}
                                    value="{$tax.tax_id}">{$tax.tax_description}</option>
                        {/foreach}
                    </select>
                    {$begCol=$begCol+1}
                {/section}
            </div>
        {/if}
        <div class="grid__container grid__head-10">
            <label for="statusId" class="cols__2-span-2 align__text-right margin__right-1">{$LANG.status}:</label>
            <select name="status" id="statusId" class="cols__4-span-2" tabindex="90">
                <option value="{$smarty.const.ENABLED }" {if $expense.status == $smarty.const.ENABLED}selected{/if}>{$LANG.paidUc}</option>
                <option value="{$smarty.const.DISABLED}" {if $expense.status == $smarty.const.DISABLED}selected{/if}>{$LANG.notPaid}</option>
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="notesId" class="cols__2-span-2">{$LANG.notes}</label>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__2-span-8">
                <input name="note" id="notesId" {if isset($expense.note)}value="{$expense.note|outHtml}"{/if} type="hidden">
                <trix-editor input="notesId" tabIndex="100"></trix-editor>
            </div>
        </div>
    </div>
    <br/>
    <div class="align__text-center">
        <button type="submit" class="positive" name="save_product" value="{$LANG.save}" tabindex="110">
            <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
        </button>
        <a href="index.php?module=expense&amp;view=manage" class="button negative" tabindex="120">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="edit"/>
    <input type="hidden" name="domain_id" value="{if isset($expense.domain_id)}{$expense.domain_id}{/if}"/>
</form>
