<h2 class="si_center">About to <i>{$action|htmlsafe}</i>: {$name|htmlsafe}</h2>

<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=extensions&amp;view=save">
    <div class="si_form">
        <table>
            <tr>
                <th>{$LANG.name}</th>
                <td>
                    <input type="text" name="name" readonly value="{if isset($name)}{$name|htmlsafe}{/if}"/>
                    <input type="text" name="id" readonly size="3" value="{if isset($id)}{$id|htmlsafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th>{$LANG.description}</th>
                <td>
                    <input type="text" name="description" readonly value="{if isset($description)}{$description|htmlsafe}{/if}"/>
                </td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_extension" value="{$LANG.save}">
                <img class="button_img" src="images/common/tick.png" alt="{$LANG.save}"/>
                {$LANG.save}
            </button>
            <a href="index.php?module=extensions&amp;view=manage" class="negative">
                <img src="images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
        </div>
    </div>
    {if ($action=="unregister" & $count) > 0}
        <h3 class="si_message_warning">WARNING: All {$count|htmlsafe} extension-specific settings will be deleted!</h3>
    {/if}
    <input type="hidden" name="action" value="{if isset($action)}{$action|htmlsafe}{/if}"/>
</form>
