<!--suppress HtmlFormInputWithoutLabel -->
<h2 class="si_center">{$LANG.aboutUc} {$LANG.to} <i>{$action|htmlSafe}</i>: {$name|htmlSafe}</h2>

<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=extensions&amp;view=save">
    <div class="si_form">
        <table>
            <tr>
                <th>{$LANG.nameUc}</th>
                <td>
                    <input type="text" name="name" readonly value="{if isset($name)}{$name|htmlSafe}{/if}"/>
                    <input type="text" name="id" readonly size="3" value="{if isset($id)}{$id|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th>{$LANG.descriptionUc}</th>
                <td>
                    <input type="text" name="description" readonly value="{if isset($description)}{$description|htmlSafe}{/if}"/>
                </td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_extension" value="{$LANG.save}">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>
                {$LANG.save}
            </button>
            <a href="index.php?module=extensions&amp;view=manage" class="negative">
                <img src="images/cross.png" alt="" />
                {$LANG.cancel}
            </a>
        </div>
    </div>
    {if ($action=="unregister" & $count) > 0}
        <h3 class="si_message_warning">{$LANG.warningUcAll}: {$LANG.allUc} {$count|htmlSafe} {$LANG.extensionSpecificSettings}!</h3>
    {/if}
    <input type="hidden" name="action" value="{if isset($action)}{$action|htmlSafe}{/if}"/>
</form>
