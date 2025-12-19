{*
 *  Script: edit.tpl
 * 	    Product group update template
 *
 *  Authors:
 *	    Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      20251217 by Rich Rowley to use column size layout for responsiveness and appearence.
 * 	    20210630 by Rich Rowley to convert to grid layout
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group*/
 *}
<div class="form__container">
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=product_groups&amp;view=save&amp;name={$smarty.get.name|urlEncode}">
        <div class="row">
            <div class="col__20">
                <label for="nameId">{$LANG.groupUc} {$LANG.nameUc}:
                    <img class="tooltip" title="{$LANG.requiredField}" src="{$helpImagePath}required-small.png" alt=""/>
                </label>
            </div>
            <div class="col__80">
                <input type="text" name="name" id="nameId" size="60" readonly
                       value="{if isset($productGroup.name)}{$productGroup.name|htmlSafe}{/if}"/>
            </div>
        </div>
        <div class="row">
            <div class="col__20">
                <label for="markupId">{$LANG.markupUc}%:
                    <img class="tooltip" title="{$LANG.helpMarkup}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
            </div>
            <div class="col__80">
                <input type="text" name="markup" id="markupId" size="10" tabindex="10"
                       value="{$productGroup.markup}"/>
            </div>
        </div>
        <div class="align__text-center margin__top-2 margin__bottom-2">
            <button type="submit" class="positive" name="save_product_group" value="{$LANG.save}" tabindex="100">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=product_groups&amp;view=manage" class="button negative" tabindex="110">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="edit">
    </form>
</div>
