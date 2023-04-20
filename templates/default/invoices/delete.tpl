{*
 * Script: quickView.tpl
 *    Quick view of invoice template
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *    2007-07-18
 *
 * License:
 *   GPL v2 or above
 *
 * Website:
 *  https://simpleinvoices.group *}

{if $smarty.get.stage == 1 }
    <br/>
    {if $invoicePaid == 0}
        <div class="si_message_warning">
            {$LANG.confirmDelete}
            {$preference.pref_inv_wording|htmlSafe}
            {$invoice.index_id|htmlSafe}
        </div>
        <form name="frmpost" method="POST" id="frmpost"
              action="index.php?module=invoices&amp;view=delete&amp;stage=2&amp;id={$smarty.get.id|urlEncode}">
            <div class="align__text-center">
                <button type="submit" class="positive" name="submit">
                    <img class="button_img" src="images/tick.png" alt="{$LANG.yesUc}"/>{$LANG.yesUc}
                </button>
                <a href="index.php?module=invoices&amp;view=manage" class="button negative">
                    <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
                </a>
            </div>
            <input type="hidden" name="doDelete" value="y"/>
        </form>
    {else}
        <span class="welcome">
            {$preference.pref_inv_wording|htmlSafe} {$invoice.index_id|htmlSafe}
            {$LANG.deleteHasPayments1} {$preference.pref_currency_sign}
            {$invoicePaid|utilNumber} {$LANG.deleteHasPayments2}
        </span>
        <br/>
        {* LANG_TODO: Add help section here!! *}
        <br/>
    {/if}
{elseif $smarty.get.stage == 2 }
    {$display_block}
    {$refresh_redirect}
{/if}
