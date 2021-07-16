{*
 *  Script: register.tpl
 *      Register and extension template
 *
 *  Last edited:
 *      20210629 by Rich Rowley to convert to grid layout.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
<h2 class="align__text-center">{$LANG.aboutUc} {$LANG.to} <i>{$action|htmlSafe}</i>: {$name|htmlSafe}</h2>
<form name="frmpost" method="POST" id="frmpost" action="index.php?module=extensions&amp;view=save">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <label for="nameId" class="cols__3-span-2">{$LANG.nameUc}:</label>
            <input type="text" name="name" id="nameId" class="cols__5-span-2" readonly
                   value="{if isset($name)}{$name|htmlSafe}{/if}"/>
            <!--suppress HtmlFormInputWithoutLabel -->
            <input type="text" name="id" class="cols__7-span-1" readonly size="3"
                   value="{if isset($id)}{$id|htmlSafe}{/if}"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="descriptionId" class="cols__3-span-2">{$LANG.descriptionUc}:</label>
            <input type="text" name="description" id="descriptionId" class="cols__5-span-5" readonly
                   value="{if isset($description)}{$description|htmlSafe}{/if}"/>
        </div>
    </div>
    <div class="align__text-center">
        <button type="submit" class="positive" name="save_extension" value="{$LANG.save}">
            <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
        </button>
        <a href="index.php?module=extensions&amp;view=manage" class="button negative">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>
    {if ($action=="unregister" & $count) > 0}
        <h3 class="si_message_warning">{$LANG.warningUcAll}: {$LANG.allUc} {$count|htmlSafe} {$LANG.extensionSpecificSettings}!</h3>
    {/if}
    <input type="hidden" name="action" value="{if isset($action)}{$action|htmlSafe}{/if}"/>
</form>
