{*
 *  Script: edit.tpl
 * 	    Payment type update template
 *
 *  Authors:
 *	    Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 * 	    20210630 by Rich Rowley to convert to grid layout
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group*/
 *}
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=payment_types&amp;view=save&amp;id={$smarty.get.id|htmlSafe}">
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <label for="descriptionId" class="cols__3-span-2">{$LANG.descriptionUc}:
                <img class="tooltip" title="{$LANG.requiredField}" src="{$helpImagePath}required-small.png" alt="(required)"/>
            </label>
            <input type="text" name="pt_description" id="descriptionId" class="cols__5-span-5" required size="30"
                   value="{$paymentType.pt_description|htmlSafe|htmlSafe}" tabindex="10"/>
        </div>
        <div class="grid__container grid__head-10">
            <label for="enabledId" class="cols__3-span-2">{$LANG.status}:</label>
            {html_options name=pt_enabled id=enabledId class=cols__5-span-1 options=$enabled selected=$paymentType.pt_enabled tabindex=20}
        </div>
    </div>
    <div class="align__text-center">
        <button type="submit" class="positive" name="save_payment_type" value="{$LANG.save}" tabindex="30">
            <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
        </button>
        <a href="index.php?module=payment_types&amp;view=manage" class="button negative" tabindex="40">
            <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="edit">
</form>
