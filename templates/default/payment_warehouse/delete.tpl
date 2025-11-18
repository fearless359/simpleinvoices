{*
 * Script: delete.tpl
 *    Delete payment record
 *
 * Authors:
 *   Richard Rowley
 *
 *  Last Modified:
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      2022-10-21
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
    <div class="flex__area">
        <div class="flex__container flex__start">
            <div class="bold margin__right-1">{$LANG.customerUc}</div>
            <div>{$paymentWarehouse.cname|htmlSafe}</div>
        </div>
        <div class="flex__container flex__start">
            <div class="bold margin__right-1">{$LANG.balanceUc}:</div>
            <div>{$paymentWarehouse.balance|utilCurrency}</div>
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
