{*
 *  Script: view.tpl
 *      Payment warehouse view details template
 *
 *  Author:
 * 	    Richard Rowley
 *
 *  Last Modified:
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="flex__area">
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.customerUc}:</div>
        <div>{$paymentWarehouse.cname|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.balanceUc}:</div>
        <div>{$paymentWarehouse.balance|utilCurrency:$paymentWarehouse.locale:$paymentWarehouse.currency_code}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.paymentType}:</div>
        <div>{$paymentWarehouse.description|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.checkNumberUc}:</div>
        <div>{$paymentWarehouse.check_number|htmlSafe}</div>
    </div>
</div>
<br/>
<div class="align__text-center margin__top-2">
    <a href="index.php?module=payment_warehouse&amp;view=edit&amp;id={$paymentWarehouse.id|htmlSafe}" class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=payment_warehouse&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
<br/>
