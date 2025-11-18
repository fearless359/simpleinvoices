{*
 *  Script: process.tpl
 * 	    Payment add (processing) template
 *
 *  Authors:
 *	    Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 * 	    20210630 by Rich Rowley to convert to grid layout
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group*/
 *}
{if isset($display_block)}
    {$display_block}
    {$refresh_redirect}
{else}
    <form name="frmpost" method="POST" id="pymtPostId" action="index.php?module=payments&amp;view=save">
        <div class="flex__area">
            {if $smarty.get.op === "pay_selected_invoice"}
                <div class="flex__container flex__space-between">
                    <div>
                        <span class="bold margin__right-1">{$invoice.preference|htmlSafe}:</span>
                        <span>{$invoice.index_id|htmlSafe}</span>
                    </div>
                    <div>
                        <span class="bold align__text-right margin__right-1">{$LANG.totalUc}:</span>
                        <span class="align__text-right">{$invoice.total|utilNumber:$invoice.precision:$invoice.locale}</span>
                    </div>
                </div>
                <div class="flex__container flex__space-between">
                    <div>
                        <span class="bold margin__right-1">{$LANG.billerUc}:</span>
                        <span>{$biller.name|htmlSafe}</span>
                    </div>
                    <div>
                        <span class="bold align__text-right margin__right-1">{$LANG.paidUc}:</span>
                        <span class="align__text-right">{$invoice.paid|utilNumber:$invoice.precision:$invoice.locale}</span>
                    </div>
                </div>
                <div class="flex__container flex__space-between">
                    <div>
                        <span class="bold margin__right-1">{$LANG.customerUc}:</span>
                        <span>{$customer.name|htmlSafe}</span>
                    </div>
                    <div>
                        <span class="bold align__text-right margin__right-1">{$LANG.owingUc}:</span>
                        <span class="align__text-right underline">{$invoice.owing|utilNumber:$invoice.precision:$invoice.locale}</span>
                    </div>
                </div>
                <div class="flex__container flex__start">
                    <label for="date1" class="margin__right-1">{$LANG.dateFormatted}:</label>
                    <input type="text" name="ac_date" id="date1" required readonly tabindex="100"
                           class="date-picker" value="{if isset($today)}{$today|htmlSafe}{/if}"/>
                </div>
                <div class="flex__container flex__start">
                    <label for="amountId" class="margin__right-1">{$LANG.amountUc}:
                        <img class="tooltip" title="{$LANG.helpProcessPaymentAutoAmount}"
                             src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                    <input type="text" name="ac_amount" id="amountId" size="25" required tabindex="110"
                           class="checkForWarehousedAmount" data-rule-number="true"
                           data-owing="{$invoice.owing|utilNumber:$invoice.precision:$invoice.locale}"
                           data-warehoused-payment="{$invoice.warehousedPayment|utilNumber:$invoice.precision:$invoice.locale}"
                           value="{if $invoice.warehousedPayment > 0 &&
                           $invoice.warehousedPayment < $invoice.owing}{$invoice.warehousedPayment|utilNumber:$invoice.precision:$invoice.locale}
                            {else}{$invoice.owing|utilNumber:$invoice.precision:$invoice.locale}{/if}"/>
                </div>
                <div class="flex__container flex__start">
                    <label for="pymtTypeId" class="margin__right-1">{$LANG.paymentTypeMethod}:</label>
                    {if !$paymentTypes}
                        <p><em>{$LANG.noPaymentTypes}</em></p>
                    {elseif $invoice.warehousedPayment > 0}
                        {* If warehousePayment then use hidden input field to send values to host and readonly field to display it on the screen. *}
                        {foreach $paymentTypes as $paymentType}
                            {if $paymentType.pt_id == $invoice.warehousedPaymentType ||
                            $paymentType.pt_id == $defaults.payment_type}
                                <input type="hidden" name="ac_payment_type" value="{$paymentType.pt_id|htmlSafe}"/>
                                <input type="text" name="unusedPaymentType" id="pymtTypeId" size="10" tabindex="-1"
                                       value="{$paymentType.pt_description|htmlSafe}" readonly/>
                                {break}
                            {/if}
                        {/foreach}
                    {else}
                        {* If no warehousePayment then use a standard select statement to let the user choose the option. *}
                        <select name="ac_payment_type" id="pymtTypeId" tabindex="130">
                            {foreach $paymentTypes as $paymentType}
                                <option value="{$paymentType.pt_id|htmlSafe}"
                                        {if $paymentType.pt_id == $defaults.payment_type}selected{/if}>{$paymentType.pt_description|htmlSafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </div>
                <div class="flex__container flex__start">
                    <label for="checkNumberId" class="margin__right-1">{$LANG.checkNumberUc}:
                        <img class="tooltip" title="{$LANG.helpCheckNumber}" src="{$helpImagePath}help-small.png"
                             alt="help"/>
                    </label>
                    <input type="text" name="ac_check_number" id="checkNumberId" size="10"
                           {if $invoice.warehousedPayment > 0}value="{$invoice.warehousedCheckNumber}" readonly
                           tabindex="-1"{else} tabindex="140"{/if} />
                </div>
                <input type="hidden" name="locale" id="localeId" value="{$invoice.locale}">
                <input type="hidden" name="currency-code=" id="currencyCodeId" value="{$invoice.currency_code}">
            {else}
                <input type="hidden" name="locale" id="localeId" value="">
                <input type="hidden" name="currency-code=" id="currencyCodeId" value="">
                <div class="flex__container flex__start">
                    <label for="invoiceId" class="margin__right-1">{$LANG.invoiceUc}:</label>
                    <select name="invoice_id" id="invoiceId" class="cols__3-span-8 setWarehousedInfo" required
                            tabindex="100">
                        <option value="" selected></option>
                        {foreach $invoice_all as $inv}
                            {assign txt "`$inv.index_name|htmlSafe` ( `$inv.customer|htmlSafe`, `$LANG.totalUc` `$inv.total|utilNumber:$inv.precision:$inv.locale` : `$LANG.owingUc` `$inv.owing|utilNumber:$inv.precision:$inv.locale`"}
                            {if isset($inv.warehousedPayment) && $inv.warehousedPayment > 0 && $inv.warehousedPayment <= $inv.owing}
                                {$txt = "`$txt`: `$LANG.warehousedUc` `$LANG.limitUc`: `$inv.warehousedPayment|utilNumber:$inv.precision:$inv.locale`"}
                            {/if}
                            {$txt = "`$txt` )"}
                            <option value="{$inv.id|htmlSafe}"
                                    data-customer-id="{$inv.customer_id}"
                                    data-biller-name="{$inv.biller}"
                                    data-customer-name="{$inv.customer}"
                                    data-currency-code="{$inv.currency_code}"
                                    data-locale="{$inv.locale}"
                                    data-warehoused-payment="{$inv.warehousedPayment|utilNumber:$inv.precision:$inv.locale}"
                                    data-warehoused-payment-type="{$inv.warehousedPaymentType}"
                                    data-warehouse-payment-type-desc="{$inv.warehousedPaymentTypeDesc}"
                                    data-warehoused-check-number="{$inv.warehousedCheckNumber}"
                                    data-owing="{$inv.owing|utilNumber:$inv.precision:$inv.locale}">{$txt}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="invPymtInfoFields" style="display:none;">
                    <div class="flex__container flex__start">
                        <div class="bold margin__right-1">{$LANG.billerUc}:</div>
                        <div class="cols__4-span-4" id="biller-name"></div>
                    </div>
                    <div class="flex__container flex__start">
                        <div class="bold margin__right-1">{$LANG.customerUc}:</div>
                        <div class="cols__4-span-4" id="customer-name"></div>
                    </div>
                </div>
                <div class="flex__container flex__start">
                    <label for="date1" class="margin__right-1">{$LANG.dateFormatted}:</label>
                    <input type="text" name="ac_date" id="date1" class="cols__4-span-2 date-picker" tabindex="110"
                           value="{if isset($today)}{$today|htmlSafe}{/if}"/>
                </div>
                <div class="flex__container flex__start">
                    <label for="amountId" class="margin__right-1">{$LANG.amountUc}:
                        <img class="tooltip" title="{$LANG.helpProcessPaymentAutoAmount}"
                             src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                    <input type="text" name="ac_amount" id="amountId" size="25" required tabindex="120"
                           class="cols__4-span-2 checkForWarehousedAmount" data-owing=""
                           data-warehoused-payment="" value=""/>
                </div>
                <div class="flex__container flex__start">
                    {if !$paymentTypes}
                        <p><em>{$LANG.noPaymentTypes}</em></p>
                    {else}
                        {* When screen is rendered, all these fields are hidden or disabled. This changes when an invoice is selected. *}
                        {* If warehousePayment then use hidden input field to send values to host and readonly field to display it on the screen. *}
                        <label for="pymtTypeIdInput" class="margin__right-1"
                               style="display:none">{$LANG.paymentTypeMethod}:</label>
                        <input type="hidden" name="ac_payment_type" id="pymtTypeIdHidden" value="" disabled/>
                        <input type="text" name="unusedPaymentType" id="pymtTypeIdInput" class="cols__4-span-2"
                               size="10" tabindex="-1"
                               style="display:none;" readonly disabled/>
                        {* If no warehousePayment then use a standard select statement to let the user choose the option. *}
                        <label for="pymtTypeId" class="margin__right-1">{$LANG.paymentTypeMethod}:</label>
                        <select name="ac_payment_type" id="pymtTypeId" class="cols__4-span-2" tabindex="130" required>
                            {foreach $paymentTypes as $paymentType}
                                <option value="{$paymentType.pt_id|htmlSafe}"
                                        {if $paymentType.pt_id == $defaults.payment_type}selected{/if}>{$paymentType.pt_description|htmlSafe}</option>
                            {/foreach}
                        </select>
                    {/if}
                </div>
                <div class="flex__container flex__start">
                    <label for="checkNumberId" class="margin__right-1">{$LANG.checkNumberUc}:
                        <img class="tooltip" title="{$LANG.helpCheckNumber}" src="{$helpImagePath}help-small.png"
                             alt="help"/>
                    </label>
                    {* data-tabindex is value used to set tabindex when field in not for warehouse amount *}
                    <input type="text" name="ac_check_number" id="checkNumberId" class="cols__4-span-2" size="10"
                           tabindex="140"
                           data-tabindex="140"/>
                </div>
            {/if}
            <div class="grid__container grid__head-10">
                <label for="ac_notes" class="cols__1-span-2 margin__bottom-1">{$LANG.note}:</label>
                <div class="cols__1-span-10">
                    <input name="ac_notes" id="ac_notes" type="hidden" tabindex="150">
                    <trix-editor input="ac_notes" tabindex="151"></trix-editor>
                </div>
            </div>
            <div class="flex__container">
                <button type="submit" class="positive" name="process_payment" value="{$LANG.save}" tabindex="160">
                    <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
                </button>
                <a href="index.php?module=payments&amp;view=manage" class="button negative" tabindex="170">
                    <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
                </a>
            </div>
            {if $smarty.get.op === "pay_selected_invoice"}
                <input type="hidden" name="invoice_id" id="invoiceId" value="{$invoice.id|htmlSafe}"/>
                <input type="hidden" name="customer_id" id="customerId"
                       value="{if $customer.id}{$customer.id|htmlSafe}{/if}"/>
            {else}
                {*                {* invoice_id already set as the select statement above. *}
                <input type="hidden" name="customer_id" id="customerId" value=""/>
            {/if}
        </div>
    </form>
{/if}
