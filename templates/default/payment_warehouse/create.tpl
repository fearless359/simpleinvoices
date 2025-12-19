{*
 *  Script: create.tpl
 * 	    Payment warehousee add template
 *
 *  Authors:
 *	    Richard Rowley
 *
 *  Last Modified:
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group*/
 *}
{if !empty($smarty.post.customer)}
    {include file="templates/default/payment_warehouse/save.tpl"}
{elseif $customerCount == 0}
    <h3 class="si_message_error">{$LANG.allUc} {$LANG.customers} {$LANG.have} {$LANG.warehouse} {$LANG.records}.
        {$LANG.useUc} {$LANG.edit}/{$LANG.delete} {$LANG.options} {$LANG.to} {$LANG.maintain} {$LANG.them}!</h3>
{else}
    <div class="form__container">
        <form name="frmpost" method="POST" id="frmpost" action="index.php?module=payment_warehouse&amp;view=create">
            <div class="row">
                <div class="col__20">
                    <label for="customerId">{$LANG.customerUc}:</label>
                </div>
                <div class="col__80">
                    <select name="customer" id="customerId" class="changePaymentWarehouseCustomer" required
                            tabindex="20">
                        <option value="" data-locale="{$config.localLocale}"
                                data-currency-code="{$config.localCurrencyCode}" selected></option>
                        {foreach $customers as $customer}
                            <option value="{$customer.id}" data-locale="{$customer.locale}"
                                    data-currency-code="{$customer.currency_code}">{$customer.name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <input type="hidden" name="locale" id="localeId" value="{$config.localLocale}">
            <input type="hidden" name="currency_code" id="currencyCodeId" value="{$config.localCurrencyCode}">
            <div class="row">
                <div class="col__20">
                    <label for="balanceId">{$LANG.balanceUc}:</label>
                </div>
                <div class="col__80">
                    <input type="text" name="balance" id="balanceId" class="validateNumber" required size="20"
                           tabindex="30"/>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="pymtTypeId">{$LANG.paymentType}:</label>
                </div>
                <div class="col__80">
                    <select name="payment_type" id="pymtTypeId" required tabindex="40">
                        <option value="" selected></option>
                        {foreach $paymentTypes as $paymentType}
                            <option value="{$paymentType.pt_id}">{$paymentType.pt_description}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="checkNumberId">{$LANG.checkNumberUc}:</label>
                </div>
                <div class="col__80">
                    <input type="text" name="check_number" id="checkNumberId" class="validateCheckNumber" size="20"
                           tabindex="50"/>
                </div>
            </div>
            <br/>
            <div class="align__text-center margin__top-2">
                <button type="submit" class="positive" name="savePaymentWarehouse" value="{$LANG.save}" tabindex="100">
                    <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
                </button>
                <a href="index.php?module=payment_warehouse&amp;view=manage" class="button negative" tabindex="110">
                    <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
                </a>
            </div>
            <input type="hidden" name="op" value="create"/>
            <br/>
        </form>
    </div>
{/if}
