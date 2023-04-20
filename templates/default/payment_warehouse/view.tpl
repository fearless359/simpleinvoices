{*
 *  Script: view.tpl
 *      Payment warehouse view details template
 *
 *  Author:
 * 	    Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__3-span-2 align__text-right bold">{$LANG.customerUc}:&nbsp;</div>
        <div class="cols__5-span-4">{$paymentWarehouse.cname|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__3-span-2 align__text-right bold">{$LANG.balanceUc}:&nbsp;</div>
        <div class="cols__5-span-2">{$paymentWarehouse.balance|utilCurrency:$paymentWarehouse.locale:$paymentWarehouse.currency_code}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__3-span-2 align__text-right bold">{$LANG.paymentType}:&nbsp;</div>
        <div class="cols__5-span-2">{$paymentWarehouse.description|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__3-span-2 align__text-right bold">{$LANG.checkNumberUc}:&nbsp;</div>
        <div class="cols__5-span-2">{$paymentWarehouse.check_number|htmlSafe}</div>
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
