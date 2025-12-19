{*
 *  Script: edit.tpl
 *      System Defaults update template
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      20251202 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      20210701 by Rich Rowley to convert to grid layout.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="form__container">
    <h3 class="align__text-center">{$LANG.edit} {$description|htmlSafe}</h3>
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=system_defaults&amp;view=save">
        <div class="row">
            <div class="col__30">
                <label for="valueId">{$description|htmlSafe}:</label>
            </div>
            <div class="col__70">
                {$value}
            </div>
        </div>
        <div class="align__text-center margin__top-2 margin__bottom-2">
            {if isset($default)}
                <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                    <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
                </button>
            {/if}
            <a href="index.php?module=system_defaults&amp;view=manage" class="button negative">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="name" value="{if isset($default)}{$default|htmlSafe}{/if}">
        <input type="hidden" name="op" value="update_system_defaults"/>
    </form>
</>
