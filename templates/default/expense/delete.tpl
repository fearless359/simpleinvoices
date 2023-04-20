{*
 * Script: delete.tpl
 *    Delete expense record
 *
 * Authors:
 *   Richard Rowley
 *
 * Last edited:
 *    2023-04-17
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *  https://simpleinvoices.group
 *}

{if $smarty.get.stage == 1}
    <div class="si_message_warning bold">
        {$LANG.confirmDelete} {$LANG.expense} {$payment.id|htmlSafe}
    </div>
    <br/>
    {if isset($adjustMessage)}
        <h3 class="align__text-center bold">{$adjustMessage}</h3>
        <br/>
    {/if}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=expense&amp;view=delete&amp;stage=2&amp;id={$expense.eid|htmlSafe}">
        <div class="align__text-center">
            <button type="submit" class="positive" name="submit">
                <img class="button_img" src="images/tick.png" alt="{$LANG.yesUc}"/>{$LANG.yesUc}
            </button>
            <button type="submit" class="negative" name="cancel">
                <img class="button_img" src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </button>
        </div>
        <input type="hidden" name="doDelete" value="y"/>
        <input type="hidden" name="ac_inv_id" value="{$payment.ac_inv_id}"/>
    </form>
{else}
    {$display_block}
    {$refresh_redirect}
{/if}
