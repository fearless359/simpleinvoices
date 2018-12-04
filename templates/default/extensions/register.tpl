<h2>About to <i>{$action|htmlsafe}</i>: {$name|htmlsafe}</h2>

{* Note that frmpost_Validator() is generated at runtime using the DynamicJs::formValidationBegin() function*}
<form name="frmpost" action="index.php?module=extensions&amp;view=save" method="post" onsubmit="return frmpost_Validator(this);">
    <div class="si_form">
        <table>
            <tr>
                <th>{$LANG.name}</th>
                <td>
                    <input type="text" name="name" readonly="readonly" value="{if isset($name)}{$name|htmlsafe}{/if}"/>
                    <input type="text" size="3" name="id" value="{if isset($id)}{$id|htmlsafe}{/if}" readonly="readonly"/>
                </td>
            </tr>
            <tr>
                <th>{$LANG.description}</th>
                <td>
                    <input type="text" name="description" size="40" value="{if isset($description)}{$description|htmlsafe}{/if}"/>
                </td>
            </tr>
        </table>

        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_extension" value="{$LANG.save}">
                <img class="button_img" src="images/common/tick.png" alt="{$LANG.save}"/>
                {$LANG.save}
            </button>
            <button type="submit" class="negative" name="cancel" value="{$LANG.cancel}">
                <img class="button_img" src="images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </button>
        </div>
    </div>

    {if ($action=="unregister" & $count > 0)}
        <h3>WARNING: All {$count|htmlsafe} extension-specific settings will be deleted!</h3>
    {/if}

    <input name="action" value="{if isset($action)}{$action|htmlsafe}{/if}" type="hidden"/>
</form>
