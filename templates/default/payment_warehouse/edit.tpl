{*
 *  Script: edit.tpl
 * 	    Payment warehouse update template
 *
 *  Authors:
 *	    Richard Rowley
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group*/
 *}
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=payment_warehouse&amp;view=save&amp;id={$smarty.get.id|htmlSafe}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 align__text-right bold">{$LANG.customerUc}:&nbsp;</div>
            <div class="cols__5-span-4">{$paymentWarehouse.cname|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 align__text-right bold">{$LANG.lastPaymentId}:&nbsp;</div>
            <div class="cols__5-span-4">{$paymentWarehouse.last_payment_id|utilNumberTrim:0}</div>
        </div>
        <input type="hidden" name="locale" id="localeId" value="{$paymentWarehouse.locale}">
        <input type="hidden" name="currency_code" id="currencyCodeId" value="{$paymentWarehouse.currency_code}">
        <input type="hidden" name="precision" id="precisionId" value="{$paymentWarehouse.precision}">
        <div class="grid__container grid__head-10">
            <label for="balanceId" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.balanceUc}:</label>
            <input type="text" name="balance" id="balanceId" class="cols__5-span-2 validateNumber"
                   required size="20" tabindex="30"
                   {if $paymentWarehouse.balance}value="{$paymentWarehouse.balance|utilNumber:$paymentWarehouse.precision:$paymentWarehouse.locale}"{/if}/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="pymtTypeId" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.paymentType}:</label>
            <select name="payment_type" id="pymtTypeId" class="cols__5-span-2" required tabindex="40">
                {foreach $paymentTypes as $paymentType}
                    <option value="{$paymentType.pt_id}" {if $paymentType.pt_id == $paymentWarehouse.payment_type}selected{/if}>{$paymentType.pt_description}</option>"
                {/foreach}
            </select>
        </div>
        <div class="grid__container grid__head-10">
            <label for="checkNumberId" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.checkNumberUc}:</label>
            <input type="text" name="check_number" id="checkNumberId" class="cols__5-span-2 validateCheckNumber" size="20" tabindex="50"
                   value="{$paymentWarehouse.check_number}"/>
        </div>
    </div>
    <br/>
    <div class="align__text-center">
        <button type="submit" class="positive" name="savePaymentWarehouse" value="{$LANG.save}" tabindex="100">
            <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
        </button>
        <a href="index.php?module=payment_warehouse&amp;view=manage" class="button negative" tabindex="110">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="edit">
    <br/>
</form>
