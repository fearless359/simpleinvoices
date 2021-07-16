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
                <div class="cols__2-span-1 bold">{$invoice.preference|htmlSafe}:</div>
                <div class="cols__3-span-4">{$invoice.index_id|htmlSafe}</div>
                <div class="cols__7-span-1 bold">{$LANG.totalUc}:</div>
                <div class="cols__8-span-1 align__text-right">{$invoice.total|utilNumber:2}</div>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-1 bold">{$LANG.billerUc}:</div>
                <div class="cols__3-span-4">{$biller.name|htmlSafe}</div>
                <div class="cols__7-span-1 bold">{$LANG.paidUc}:</div>
                <div class="cols__8-span-1 align__text-right">{$invoice.paid|utilNumber}</div>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-1 bold">{$LANG.customerUc}:</div>
                <div class="cols__3-span-4">{$customer.name|htmlSafe}</div>
                <div class="cols__7-span-1 bold">{$LANG.owingUc}:</div>
                <div class="cols__8-span-1 align__text-right underline">{$invoice.owing|utilNumber}</div>
            </div>
            <div class="grid__container grid__head-10">
                <label for="amountId" class="cols__2-span-2">{$LANG.amountUc}:
                    <a class="cluetip" href="#"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpProcessPaymentAutoAmount"
                       title="{$LANG.processPaymentAutoAmount}">
                        <img src="{$helpImagePath}help-small.png" alt="help"/>
                    </a>
                </label>
                <input type="text" name="ac_amount" id="amountId" size="25"
                       class="cols__4-span-3 validate[required,custom[number]]" value="{$invoice.owing|utilNumber}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="date1" class="cols__2-span-2">{$LANG.dateFormatted}:</label>
                <input type="text" name="ac_date" id="date1"
                       class="cols__4-span-3 validate[required,custom[date],length[0,10]] date-picker"
                       value="{if isset($today)}{$today|htmlSafe}{/if}"/>
            </div>
        {elseif $smarty.get.op === "pay_invoice"}
            <div class="grid__container grid__head-10">
                <label for="invoiceId" class="cols__2-span-1">{$LANG.invoiceUc}:</label>
                <select name="invoice_id" id="invoiceId" class="cols__3-span-8 validate[required]">
                    <option value=''></option>
                    {foreach $invoice_all as $invoice}
                        <option value="{if isset($invoice.id)}{$invoice.id|htmlSafe}{/if}">
                            {$invoice.index_name|htmlSafe}
                            (
                            {$invoice.biller|htmlSafe},
                            {$invoice.customer|htmlSafe},
                            {$LANG.totalUc} {$invoice.total|utilNumber} :
                            {$LANG.owingUc} {$invoice.owing|utilNumber}
                            )
                        </option>
                    {/foreach}
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="amountId" class="cols__2-span-2">{$LANG.amountUc}:</label>
                <input type="text" name="ac_amount" id="amountId" size="25" class="cols__4-span-2"/>
                <label for="date1" class="cols__7-span-2">{$LANG.dateFormatted}:</label>
                <input type="text" name="ac_date" id="date1" class="cols__9-span-2 date-picker"
                       value="{if isset($today)}{$today|htmlSafe}{/if}"/>
            </div>
        {/if}
        <div class="grid__container grid__head-10">
            <label for="pymt_type" class="cols__2-span-2">{$LANG.paymentTypeMethod}:</label>
            {if !$paymentTypes}
                <p><em>{$LANG.noPaymentTypes}</em></p>
            {else}
                <select name="ac_payment_type" id="pymt_type" class="cols__4-span-2">
                    {foreach $paymentTypes as $paymentType}
                        <option value="{if isset($paymentType.pt_id)}{$paymentType.pt_id|htmlSafe}{/if}"
                                {if $paymentType.pt_id==$defaults.payment_type}selected{/if}>
                            {$paymentType.pt_description|htmlSafe}
                        </option>
                    {/foreach}
                </select>
            {/if}
            <label for="chk_num" class="cols__7-span-1">{$LANG.checkNumber}:
                <a class="cluetip" href="#" title="{$LANG.checkNumber}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=helpCheckNumber">
                    <img src="{$helpImagePath}help-small.png" alt="help"/>
                </a>
            </label>
            <input type="text" name="ac_check_number" id="chk_num" class="cols__8-span-2" size="10"/>
            {literal}
                <script>
                    $(function () {
                        $('#frmpost').submit(function () {
                            let pymt_type_selected = $('#pymt_type_option:selected');
                            let pymt_type = pymt_type_selected.text().toLowerCase();
                            if (pymt_type === 'check') {
                                let chkNumId = $('#chk_num');
                                let ckNumber = chkNumId.val().toUpperCase();
                                if (ckNumber !== 'N/A' && !(/^[1-9][0-9]* *$/).test(ckNumber)) {
                                    alert('Enter a valid Check Number, "N/A" or change the Payment Type.');
                                    chkNumId.focus();
                                    return false;
                                }
                                chkNumId.val(ckNumber);
                            }
                        });
                    });
                </script>
            {/literal}
        </div>
        <div class="grid__container grid__head-10">
            <label for="ac_notes" class="cols__2-span-2">{$LANG.note}:</label>
            <div class="cols__2-span-9">
                <input name="ac_notes" id="ac_notes" type="hidden">
                <trix-editor input="ac_notes"></trix-editor>
            </div>
        </div>
        <div class="align__text-center">
            <button type="submit" class="positive" name="process_payment" value="{$LANG.save}">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=payments&amp;view=manage" class="button negative">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        {if $smarty.get.op == 'pay_selected_invoice'}
            <input type="hidden" name="invoice_id" value="{if isset($invoice.id)}{$invoice.id|htmlSafe}{/if}"/>
        {/if}
    </div>
</form>
