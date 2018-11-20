<h2>About to <i>{$action|htmlsafe}</i>: {$name|htmlsafe}</h2>

{* Note that frmpost_Validator() is generated at runtime using the jsFormValidationBegin() function*}
<form name="frmpost" action="index.php?module=extensions&amp;view=save" method="post" onsubmit="return frmpost_Validator(this);">
    <div class="si_form">
        <table>
            <tr>
                <th>{$LANG.name}</th>
                <td>
                    <input type="text" name="name" readonly="readonly" value="{$name|htmlsafe}"/>
                    <input type="text" size="3" name="id" value="{$id|htmlsafe}" readonly="readonly"/>
                </td>
            </tr>
            <tr>
                <th>{$LANG.description}</th>
                <td>
                    <input type="text" name="description" size="40" value="{$description|htmlsafe}"/>
                </td>
            </tr>
        </table>

        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_extension" value="{$LANG.save}">
                <img class="button_img" src="images/common/tick.png" alt=""/>
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

    <input name="action" value="{$action|htmlsafe}" type="hidden"/>
</form>
