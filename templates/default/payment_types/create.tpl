{*
 *  Script: create.tpl
 * 	    Payment type add template
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
{if !empty($smarty.post.pt_description)}
    {include file="templates/default/payment_types/save.tpl"}
{else}
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=payment_types&amp;view=create">
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <label for="descriptionId" class="cols__2-span-3">{$LANG.paymentTypeDescription}:
                    <img class="tooltip" title="{$LANG.requiredField}" src="{$helpImagePath}required-small.png" alt=""/>
                </label>
                <input type="text" name="pt_description" id="descriptionId" class="cols__5-span-5" required size="30" tabindex="10"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="enabledId" class="cols__2-span-3">{$LANG.enabled}:</label>
                <select name="pt_enabled" id="enabledId" class="cols__5-span-1" tabindex="20">
                    <option value="1" selected>{$LANG.enabled}</option>
                    <option value="0">{$LANG.disabled}</option>
                </select>
            </div>
        </div>
        <div class="align__text-center">
            <button type="submit" class="positive" name="insert_preference" value="{$LANG.save}" tabindex="30">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=payment_types&amp;view=manage" class="button negative" tabindex="40">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
        <input type="hidden" name="domain_id" value="{$domain_id}"/>
    </form>
{/if}
