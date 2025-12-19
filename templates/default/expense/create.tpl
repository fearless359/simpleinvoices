{*
 *  Script: create.tpl
 *      Expense add template
 *
 *  Last edited:
 *      20251210 by Rich Rowley to use column size layout for responsiveness and appearence.
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
    <div class="form__container">
        <form name="frmpost" method="POST" id="frmpost" action="index.php?module=expense&amp;view=create">
            <div class="row">
                <div class="col__20">
                    <label for="expenseAccountId" class="margin__right-1">{$LANG.expenseAccounts}:</label>
                </div>
                <div class="col__80">
                    <select name="expense_account_id" id="expenseAccountId" required autofocus tabindex="10">
                        <option value=''></option>
                        {foreach $expenseAdd.expense_accounts as $expense_account}
                            <option value="{if isset($expense_account.id)}{$expense_account.id}{/if}">{$expense_account.name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="date" class="margin__right-1">{$LANG.dateFormatted}:</label>
                </div>
                <div class="col__80">
                    <input type="text" name="date" id="date" required readonly class="date-picker"
                           value='{$smarty.now|date_format:"%Y-%m-%d"}' tabindex="20"/>
                </div>
            </div>
            <input type="hidden" name="locale" id="localeId" value="{$config.localLocale}">
            <input type="hidden" name="currency_code" id="currencyCodeId" value="{$config.localCurrencyCode}">
            <div class="row">
                <div class="col__20">
                    <label for="amountId" class="margin__right-1">{$LANG.amountUc}:</label>
                </div>
                <div class="col__80">
                    <input type="text" name="amount" id="amountId" class="validateNumber" required tabindex="30"/>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="billerId" class="margin__right-1">{$LANG.billerUc}:</label>
                </div>
                <div class="col__80">
                    <select name="biller_id" id="billerId" required tabindex="40">
                        <option value=''></option>
                        {foreach $expenseAdd.billers as $biller}
                            <option {if isset($biller.id) && $biller.id == $defaults.biller} selected {/if}
                                    value="{if isset($biller.id)}{$biller.id}{/if}">{$biller.name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="customerId" class="margin__right-1">{$LANG.customerUc}:</label>
                </div>
                <div class="col__80">
                    <select name="customer_id" id="customerId" tabindex="50">
                        <option value=''></option>
                        {foreach $expenseAdd.customers as $customer}
                            <option {if isset($customer.id) && $customer.id == $defaults.customer}selected{/if}
                                    value="{if isset($customer.id)}{$customer.id}{/if}">
                                {$customer.name}
                            </option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="invoiceId" class="margin__right-1">{$LANG.invoiceUc}:</label>
                </div>
                <div class="col__80">
                    <select name="invoice_id" id="invoiceId" class="expenseInvoiceChange" tabindex="60">
                        <option value='' data-locale="{$config.localLocale}"
                                data-currency-code="{$config.localCurrencyCode}"
                                data-precision="{$config.localPrecision}"></option>
                        {foreach $expenseAdd.invoices as $invoice}
                            <option value="{$invoice.id}" data-locale="{$invoice.locale}"
                                    data-currency-code="{$invoice.currency_code}" data-precision="{$invoice.precision}"
                                    {if isset($smarty.post.invoice_id) && $smarty.post.invoice_id == $invoice.id}selected{/if}>
                                {$invoice.index_id}&nbsp;&dash;&nbsp;{$invoice.customer}
                                &nbsp;&dash;&nbsp;{$invoice.date}
                            </option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="productId" class="margin__right-1">{$LANG.productUc}:</label>
                </div>
                <div class="col__80">
                    <select name="product_id" id="productId" tabindex="70">
                        <option value=''></option>
                        {foreach $expenseAdd.products as $product}
                            <option value="{$product.id}">{$product.description}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            {if $defaults.tax_per_line_item > 0}
                <div class="row">
                    <div class="col__20">
                        <div class="label">{$LANG.taxesUc}:</div>
                    </div>
                    {$begCol = 4}
                    {section name=tax loop=$defaults.tax_per_line_item}
                        <div class="col__20">
                            <!--suppress HtmlFormInputWithoutLabel -->
                            <select name="tax_id[0][{$smarty.section.tax.index}]"
                                    id="tax_id[0][{$smarty.section.tax.index}]"
                                    tabindex="8{$smarty.section.tax.index}" class="margin__right-1">
                                <option value=""></option>
                                {foreach $taxes as $tax}
                                    <option {if $tax.tax_id == $defaults.tax && $smarty.section.tax.index == 0}selected{/if}
                                            value="{$tax.tax_id}">{$tax.tax_description}</option>
                                {/foreach}
                            </select>
                        </div>
                        {$begCol = $begCol+2}
                    {/section}
                </div>
            {/if}
            <div class="row">
                <div class="col__20">
                    <label for="statusId" class="margin__right-1">{$LANG.status}:</label>
                </div>
                <div class="col__80">
                    <select name="status" id="statusId" tabindex="90">
                        <option value="{$smarty.const.ENABLED }" selected>{$LANG.paidUc}</option>
                        <option value="{$smarty.const.DISABLED}">{$LANG.notPaid}</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col__20 align__text-left">
                    <label for="notesId" class="cols__1-span-2">{$LANG.notes}</label>
                </div>
            </div>
            <div class="row">
                <div class="col__100">
                    <input name="note" id="notesId"
                           {if isset($smarty.post.notes)}value="{$smarty.post.notes|outHtml}"{/if}
                           type="hidden">
                    <trix-editor input="notesId" tabindex="100"></trix-editor>
                </div>
            </div>
            <div class="align__text-center margin__top-2 margin__bottom-1">
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
    </div>
{/if}
