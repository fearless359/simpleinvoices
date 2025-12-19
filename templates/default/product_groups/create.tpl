{*
 *  Script: create.tpl
 *      Product Groups add template
 *
 *  Last edited:
 *      20251217 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 * 	    20210630 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
{if !empty($smarty.post.name)}
    {include file="templates/default/product_groups/save.tpl"}
{else}
    <div class="form__container">
        <form name="frmpost" method="POST" id="frmpost" action="index.php?module=product_groups&amp;view=create">
            <div class="row">
                <div class="col__20">
                    <label for="nameId" class="margin__right-1">{$LANG.groupUc} {$LANG.nameUc}:
                        <img class="tooltip" title="{$LANG.requiredField}" src="{$helpImagePath}required-small.png"
                             alt=""/>
                    </label>
                </div>
                <div class="col__80">
                    <input type="text" name="name" id="nameId" required size="60" tabindex="10"
                           value="{if isset($smarty.post.name)}{$smarty.post.name|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="markupId" class="margin__right-1">{$LANG.markupUc}%:
                        <img class="tooltip" title="{$LANG.helpMarkup}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__80">
                    <input type="text" name="markup" id="markupId" size="10" tabindex="20"
                           value="{if isset($smarty.post.markup)}{$smarty.post.markup|htmlSafe}{/if}"/>
                </div>
            </div>
            <div class="align__text-center margin__top-3 margin__bottom-2">
                <button type="submit" class="positive" name="save_product_group" value="{$LANG.save}" tabindex="100">
                    <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
                </button>
                <a href="index.php?module=product_groups&amp;view=manage" class="button negative" tabindex="110">
                    <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
                </a>
            </div>
            <input type="hidden" name="op" value="create"/>
        </form>
    </div>
{/if}
