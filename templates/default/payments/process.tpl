<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=payments&amp;view=save">
    <div class="si_form">
        <table>
            {if $smarty.get.op === "pay_selected_invoice"}
                <tr>
                    <th class="details_screen">{$invoice.preference|htmlSafe}:</th>
                    <td>{$invoice.index_id|htmlSafe}</td>
                    <th class="details_screen">{$LANG.totalUc}:</th>
                    <td>{$invoice.total|utilNumber:2}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.billerUc}:</th>
                    <td>{$biller.name|htmlSafe}</td>
                    <th class="details_screen">{$LANG.paidUc}:</th>
                    <td>{$invoice.paid|utilNumber}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.customerUc}:</th>
                    <td>{$customer.name|htmlSafe}</td>
                    <th class="details_screen">{$LANG.owingUc}:</th>
                    <td style="text-decoration: underline;">{$invoice.owing|utilNumber}</td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.amountUc}:</th>
                    <td colspan="5">
                        <input type="text" name="ac_amount" size="25" class="validate[required,custom[number]]"
                               value="{$invoice.owing|utilNumber}"/>
                        <a class="cluetip" href="#"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpProcessPaymentAutoAmount"
                           title="{$LANG.processPaymentAutoAmount}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.dateFormatted}:</th>
                    <td colspan="5">
                        <input type="text" name="ac_date" id="date1"
                               class="validate[required,custom[date],length[0,10]] date-picker"
                               value="{if isset($today)}{$today|htmlSafe}{/if}"/>
                    </td>
                </tr>
            {elseif $smarty.get.op === "pay_invoice"}
                <tr>
                    <th class="details_screen">{$LANG.invoiceUc}:</th>
                    <td colspan="3">
                        <select name="invoice_id" class="si_input validate[required]">
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
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.amountUc}:</th>
                    <td colspan="3"><input type="text" name="ac_amount" size="25" class="si_input"/></td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.dateFormatted}:</th>
                    <td colspan="3">
                        <input type="text" class="si_input date-picker" name="ac_date" id="date1"
                               value="{if isset($today)}{$today|htmlSafe}{/if}"/>
                    </td>
                </tr>
            {/if}
            <tr>
                <th class="details_screen">{$LANG.paymentTypeMethod}:</th>
                <td>
                    {if !$paymentTypes}
                        <p><em>{$LANG.noPaymentTypes}</em></p>
                    {else}
                        <select name="ac_payment_type" class="si_input" id="pymt_type">
                            {foreach $paymentTypes as $paymentType}
                                <option value="{if isset($paymentType.pt_id)}{$paymentType.pt_id|htmlSafe}{/if}"
                                        {if $paymentType.pt_id==$defaults.payment_type}selected{/if}>
                                    {$paymentType.pt_description|htmlSafe}
                                </option>
                            {/foreach}
                        </select>
                    {/if}
                </td>
                <th class="details_screen">{$LANG.checkNumber}:</th>
                <td>
                    <input type="text" name="ac_check_number" class="si_input" id="chk_num" size="10"/>
                    {literal}
                        <script>
                            $(function(){
                                $('#frmpost').submit(function(){
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
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.note}:</th>
                <td colspan="3">
                    <input name="ac_notes" id="ac_notes" type="hidden">
                    <trix-editor input="si_input ac_notes"></trix-editor>
                </td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="process_payment" value="{$LANG.save}">
                <img class="button_img" src="images/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=payments&amp;view=manage" class="negative">
                <img src="images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
        {if $smarty.get.op == 'pay_selected_invoice'}
            <input type="hidden" name="invoice_id" value="{if isset($invoice.id)}{$invoice.id|htmlSafe}{/if}"/>
        {/if}
    </div>
</form>
