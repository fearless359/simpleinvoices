{*
 *  Script: edit.tpl
 *      Expense Account edit template
 *
 *  Last edited:
 *      20251209 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20210622 by Rich Rowley to convert to grid layout.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
<div class="form__container">
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=expense_account&amp;view=save&amp;id={$smarty.get.id}">
        <div class="row">
            <div class="col__20">
                <label for="name">{$LANG.nameUc}:
                    <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpExpenseAccounts}"
                         src="{$helpImagePath}required-small.png" alt=""/>
                </label>
            </div>
            <div class="col__80">
                <input type="text" name="name" id="name" required
                       value="{if isset($expense_account.name)}{$expense_account.name}{/if}"/>
            </div>
        </div>
        <div class="align__text-center margin__top-2 margin__bottom-1">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
            </button>
            <a href="index.php?module=expense_account&amp;view=manage" class="button negative">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="edit"/>
        <input type="hidden" name="domain_id"
               value="{if isset($expense_account.domain_id)}{$expense_account.domain_id}{/if}"/>
    </form>
</div>
