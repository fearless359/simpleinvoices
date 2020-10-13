{* if bill is updated or saved.*}
{if !empty($smarty.post.name) }
    {include file="templates/default/expense_account/save.tpl"}
{else}
    {* if name was inserted *}
    {if isset($smarty.post.name)}
        <!--suppress HtmlFormInputWithoutLabel -->
        <div class="validation_alert">
            <img src="images/important.png" alt=""/> {$LANG.accountNameNeeded}
        </div>
        <hr/>
    {/if}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=expense_account&amp;view=create">
        <input type="hidden" name="op" value="create"/>
        <input type="hidden" name="domain_id" value="{if isset($domain_id)}{$domain_id}{/if}"/>
        <br/>
        <table class="center">
            <tr>
                <td class="details_screen">{$LANG.nameUc}
                    <a class="cluetip" href="#" title="{$LANG.requiredField}"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpExpenseAccounts">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                    &nbsp;
                </td>
                <td>
                    <input type="text" name="name" value="{if isset($smarty.post.name)}{$smarty.post.name}{/if}" size="50" id="name"
                           class="validate[required]"/>
                </td>
            </tr>
        </table>
        <br/>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="../../../images/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=expense_account&amp;view=manage" class="negative">
                <img src="../../../images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
    </form>
{/if}
