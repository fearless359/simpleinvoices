{*
 *  Script: edit.tpl
 * 	    Payment type update template
 *
 *  Authors:
 *	    Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
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
    <div class="flex__area">
        <div class="flex__container flex__start">
            <label for="descriptionId" class=margin__right-1">{$LANG.paymentTypeDescription}:
                <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpPaymentTypes}"
                     src="{$helpImagePath}required-small.png" alt="(required)"/>
            </label>
            <input type="text" name="pt_description" id="descriptionId" required size="30"
                   value="{$paymentType.pt_description|htmlSafe|htmlSafe}" tabindex="10"/>
        </div>
        <div class="flex__container flex__start">
            <label for="enabledId" class="margin__right-1">{$LANG.status}:</label>
            {html_options name=pt_enabled id=enabledId options=$enabled selected=$paymentType.pt_enabled tabindex=20}
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
