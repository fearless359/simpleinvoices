<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=expense_account&amp;view=save&amp;id={$smarty.get.id}">
    <br/>
    <table class="center">
        <tr>
            <th class="left">{$LANG.nameUc}:</th>
            <td>
                <input type="text" name="name" size="50" value="{if isset($expense_account.name)}{$expense_account.name}{/if}" id="name" class="validate[required]"/>
            </td>
        </tr>
    </table>
    <br/>
    <div class="si_toolbar si_toolbar_form">
        <button type="submit" class="positive" name="submit" value="{$LANG.save}">
            <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
        </button>
        <a href="index.php?module=expense_account&amp;view=manage" class="negative">
            <img src="images/cross.png" alt=""/>
            {$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="edit"/>
    <input type="hidden" name="domain_id" value="{if isset($expense_account.domain_id)}{$expense_account.domain_id}{/if}"/>
</form>
