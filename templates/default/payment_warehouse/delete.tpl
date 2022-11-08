{*
 * Script: delete.tpl
 *    Delete payment record
 *
 * Authors:
 *   Richard Rowley
 *
 * Last edited:
 *    2022-10-21
 *
 * License:
 *   GPL v2 or above
 *
 * Website:
 *  https://simpleinvoices.group
 *}
{if $smarty.get.stage == 1}
    <div class="si_message_warning bold">
        {$LANG.confirmDelete} {$LANG.this} {$LANG.paymentWarehouseUc} {$LANG.record}
    </div>
    <br/>
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 align__text-right bold">{$LANG.customerUc}:&nbsp;</div>
            <div class="cols__5-span-4">{$paymentWarehouse.cname|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 align__text-right bold">{$LANG.balanceUc}:&nbsp;</div>
            <div class="cols__5-span-2">{$paymentWarehouse.balance|utilCurrency}</div>
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
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=payment_warehouse&amp;view=delete&amp;stage=2&amp;id={$paymentWarehouse.id|htmlSafe}">
        <div class="align__text-center">
            <button type="submit" class="positive" name="submit">
                <img class="button_img" src="images/tick.png" alt="{$LANG.yesUc}"/>{$LANG.yesUc}
            </button>
            <button type="submit" class="negative" name="cancel">
                <img class="button_img" src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </button>
        </div>
        <input type="hidden" name="doDelete" value="y"/>
        <input type="hidden" name="id" value="{$paymentWarehouse.id}"/>
    </form>
{else}
    {$display_block}
    {$refresh_redirect}
{/if}
