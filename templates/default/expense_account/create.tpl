{*
 *  Script: create.tpl
 *      Expense Account add template
 *
 *  Last edited:
 *      20210622 by Rich Rowley to convert to grid layout.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
{* if bill is updated or saved.*}
{if !empty($smarty.post.name) }
    {include file="templates/default/expense_account/save.tpl"}
{else}
    {* if name was inserted *}
    {if isset($smarty.post.name)}
        <div class="validation_alert">
            <img src="images/important.png" alt=""/> {$LANG.accountNameNeeded}
        </div>
        <hr/>
    {/if}
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=expense_account&amp;view=create">
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <label for="name" class="cols__1-span-3 align__text-right margin__right-1">{$LANG.nameUc}:
                    <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpExpenseAccounts}" src="{$helpImagePath}required-small.png" alt=""/>
                </label>
                <input type="text" name="name" id="name" class="cols__4-span-5" required
                       value="{if isset($smarty.post.name)}{$smarty.post.name}{/if}"/>
            </div>
        </div>
        <div class="align__text-center margin__top-2">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=expense_account&amp;view=manage" class="button negative">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
        <input type="hidden" name="domain_id" value="{if isset($domain_id)}{$domain_id}{/if}"/>
    </form>
{/if}
