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
<form name="frmpost" method="POST" id="frmpost" action="index.php?module=payments&amp;view=save">
    <div class="grid__area">
        {if $smarty.get.op === "pay_selected_invoice"}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-3 bold align__text-right margin__right-1">{$invoice.preference|htmlSafe}:</div>
                <div class="cols__4-span-1">{$invoice.index_id|htmlSafe}</div>
                <div class="cols__8-span-1 bold align__text-right">{$LANG.totalUc}:</div>
                <div class="cols__9-span-1 align__text-right">{$invoice.total|utilNumber:2}</div>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.billerUc}:</div>
                <div class="cols__4-span-4">{$biller.name|htmlSafe}</div>
                <div class="cols__8-span-1 bold align__text-right">{$LANG.paidUc}:</div>
                <div class="cols__9-span-1 align__text-right">{$invoice.paid|utilNumber}</div>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.customerUc}:</div>
                <div class="cols__4-span-4">{$customer.name|htmlSafe}</div>
                <div class="cols__8-span-1 bold align__text-right">{$LANG.owingUc}:</div>
                <div class="cols__9-span-1 align__text-right underline">{$invoice.owing|utilNumber}</div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="date1" class="cols__1-span-3 align__text-right margin__right-1">{$LANG.dateFormatted}:</label>
                <input type="text" name="ac_date" id="date1" required readonly tabindex="100"
                       class="cols__4-span-1 date-picker" value="{if isset($today)}{$today|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="amountId" class="cols__1-span-3 align__text-right margin__right-1">{$LANG.amountUc}:
                    <img class="tooltip" title="{$LANG.helpProcessPaymentAutoAmount}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="ac_amount" id="amountId" size="25" required tabindex="110"
                       class="cols__4-span-2 validate-number checkForWarehousedAmount"
                       data-owing="{$invoice.owing|utilNumber}" data-currency-code="{$invoice.currency_code}"
                       data-locale="{$invoice.locale}" data-warehoused-payment="{$invoice.warehousedPayment|utilNumber}"
                       value="{if $invoice.warehousedPayment > 0 &&
                                  $invoice.warehousedPayment < $invoice.owing}{$invoice.warehousedPayment|utilNumber}{else}{$invoice.owing|utilNumber}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="pymtTypeId" class="cols__1-span-3 align__text-right margin__right-1">{$LANG.paymentTypeMethod}:</label>
                {if !$paymentTypes}
                    <p><em>{$LANG.noPaymentTypes}</em></p>
                {elseif $invoice.warehousedPayment > 0}
                    {* If warehousePayment then use hidden input field to send values to host and readonly field to display it on the screen. *}
                    {foreach $paymentTypes as $paymentType}
                        {if $paymentType.pt_id == $invoice.warehousedPaymentType ||
                            $paymentType.pt_id == $defaults.payment_type}
                            <input type="hidden" name="ac_payment_type" value="{$paymentType.pt_id|htmlSafe}"/>
                            <input type="text" name="unusedPaymentType" id="pymtTypeId" class="cols__4-span-2" size="10" tabindex="-1"
                                   value="{$paymentType.pt_description|htmlSafe}" readonly />
                            {break}
                        {/if}
                    {/foreach}
                {else}
                    {* If no warehousePayment then use a standard select statement to let the user choose the option. *}
                    <select name="ac_payment_type" id="pymtTypeId" class="cols__4-span-2" tabindex="130">
                        {foreach $paymentTypes as $paymentType}
                            <option value="{$paymentType.pt_id|htmlSafe}"
                                    {if $paymentType.pt_id == $defaults.payment_type}selected{/if}>{$paymentType.pt_description|htmlSafe}</option>
                        {/foreach}
                    </select>
                {/if}
            </div>
            <div class="grid__container grid__head-10">
                <label for="checkNumberId" class="cols__1-span-3 align__text-right margin__right-1">{$LANG.checkNumberUc}:
                    <img class="tooltip" title="{$LANG.helpCheckNumber}" src="{$helpImagePath}help-small.png" alt="help"/>
                </label>
                <input type="text" name="ac_check_number" id="checkNumberId" class="cols__4-span-2" size="10"
                       {if $invoice.warehousedPayment > 0}value="{$invoice.warehousedCheckNumber}" readonly tabindex="-1"{else} tabindex="140"{/if} />
            </div>
        {else}
            <div class="grid__container grid__head-10">
                <label for="invoiceId" class="cols__2-span-1 align__text-right margin__right-1">{$LANG.invoiceUc}:</label>
                <select name="invoice_id" id="invoiceId" class="cols__3-span-8 setWarehousedInfo" required tabindex="100">
                    <option value="" selected></option>
                    {foreach $invoice_all as $inv}
                        {assign txt "`$inv.index_name|htmlSafe` ( `$inv.customer|htmlSafe`, `$LANG.totalUc` `$inv.total|utilNumber` : `$LANG.owingUc` `$inv.owing|utilNumber`"}
                        {if $inv.warehousedPayment > 0 && $inv.warhousedPayment <= $inv.owing}
                            {$txt = "`$txt`: `$LANG.warehousedUc` `$LANG.limitUc`: `$inv.warehousedPayment|utilNumber`"}
                        {/if}
                        {$txt = "`$txt` )"}
                        <option value="{$inv.id|htmlSafe}"
                                data-customer-id="{$inv.customer_id}"
                                data-biller-name="{$inv.biller}"
                                data-customer-name="{$inv.customer}"
                                data-currency-code="{$inv.currency_code}"
                                data-locale="{$inv.locale}"
                                data-warehoused-payment="{$inv.warehousedPayment}"
                                data-warehoused-payment-type="{$inv.warehousedPaymentType}"
                                data-warehouse-payment-type-desc="{$inv.warehousedPaymentTypeDesc}"
                                data-warehoused-check-number="{$inv.warehousedCheckNumber}"
                                data-owing="{$inv.owing|utilNumber}">{$txt}</option>
                    {/foreach}
                </select>
            </div>
            <div class="invPymtInfoFields" style="display:none;">
                <div class="grid__container grid__head-10">
                    <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.billerUc}:</div>
                    <div class="cols__4-span-4" id="biller-name"></div>
                </div>
                <div class="grid__container grid__head-10">
                    <div class="cols__1-span-3 bold align__text-right margin__right-1">{$LANG.customerUc}:</div>
                    <div class="cols__4-span-4" id="customer-name"></div>
                </div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="date1" class="cols__1-span-3 align__text-right margin__right-1">{$LANG.dateFormatted}:</label>
                <input type="text" name="ac_date" id="date1" class="cols__4-span-2 date-picker" tabindex="110"
                       value="{if isset($today)}{$today|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="amountId" class="cols__1-span-3 align__text-right margin__right-1">{$LANG.amountUc}:
                    <img class="tooltip" title="{$LANG.helpProcessPaymentAutoAmount}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="ac_amount" id="amountId" size="25" required tabindex="120"
                       class="cols__4-span-2 validate-number checkForWarehousedAmount"
                       data-owing="" data-currency-code="{$invoice.currency_code}"
                       data-locale="{$invoice.locale}" data-warehoused-payment="{$invoice.warehousedPayment|utilNumber}"
                       value="" />
            </div>
            <div class="grid__container grid__head-10">
                {if !$paymentTypes}
                    <p><em>{$LANG.noPaymentTypes}</em></p>
                {else}
                    {* When screen is rendered, all these fields are hidden or disabled. This changes when an invoice is selected. *}
                    {* If warehousePayment then use hidden input field to send values to host and readonly field to display it on the screen. *}
                    <label for="pymtTypeIdInput" class="cols__1-span-3 align__text-right margin__right-1" style="display:none">{$LANG.paymentTypeMethod}:</label>
                    <input type="hidden" name="ac_payment_type" id="pymtTypeIdHidden" value="" disabled/>
                    <input type="text" name="unusedPaymentType" id="pymtTypeIdInput" class="cols__4-span-2" size="10" tabindex="-1"
                           style="display:none;" readonly disabled/>

                    {* If no warehousePayment then use a standard select statement to let the user choose the option. *}
                    <label for="pymtTypeId" class="cols__1-span-3 align__text-right margin__right-1">{$LANG.paymentTypeMethod}:</label>
                    <select name="ac_payment_type" id="pymtTypeId" class="cols__4-span-2" tabindex="130" required>
                        {foreach $paymentTypes as $paymentType}
                            <option value="{$paymentType.pt_id|htmlSafe}"
                                    {if $paymentType.pt_id == $defaults.payment_type}selected{/if}>{$paymentType.pt_description|htmlSafe}</option>
                        {/foreach}
                    </select>
                {/if}
            </div>
            <div class="grid__container grid__head-10">
                <label for="checkNumberId" class="cols__1-span-3 align__text-right margin__right-1">{$LANG.checkNumberUc}:
                    <img class="tooltip" title="{$LANG.helpCheckNumber}" src="{$helpImagePath}help-small.png" alt="help"/>
                </label>
                {* data-tabindex is value used to set tabindex when field in not for warehouse amount *}
                <input type="text" name="ac_check_number" id="checkNumberId" class="cols__4-span-2" size="10" tabindex="140"
                       data-tabindex="140"/>
            </div>
        {/if}
        <div class="grid__container grid__head-10">
            <label for="ac_notes" class="cols__2-span-2 margin__bottom-1">{$LANG.note}:</label>
            <div class="cols__2-span-8">
                <input name="ac_notes" id="ac_notes" type="hidden" tabindex="150">
                <trix-editor input="ac_notes" tabindex="151"></trix-editor>
            </div>
        </div>
        <div class="align__text-center">
            <button type="submit" class="positive" name="process_payment" value="{$LANG.save}" tabindex="160">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=payments&amp;view=manage" class="button negative" tabindex="170">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        {if $smarty.get.op === "pay_selected_invoice"}
            <input type="hidden" name="invoice_id" id="invoiceId" value="{$invoice.id|htmlSafe}"/>
        {/if}
        <input type="hidden" name="customer_id" id="customerId" value="{if $customer.id}{$customer.id|htmlSafe}{/if}"/>
    </div>
</form>
