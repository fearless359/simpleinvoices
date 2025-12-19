{*
 *  Script: create.tpl
 *      Product Attributes add template
 *
 *  Last edited:
 *      20251216 by Rich Rowley to use column size layout for responsiveness and appearence.
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
    {include file="templates/default/product_attribute/save.tpl"}
{else}
    <div class="form__container">
        <form name="frmpost" method="POST" id="frmpost" action="index.php?module=product_attribute&amp;view=create">
            <div class="row">
                <div class="col__20">
                    <label for="nameId">{$LANG.nameUc}:</label>
                </div>
                <div class="col__80">
                    <input type="text" name="name" id="nameId" required size="50"
                           value="{if isset($smarty.post.name)}{$smarty.post.name}{/if}"/>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="typeId">{$LANG.type}:</label>
                </div>
                <div class="col__80">
                    <select name="type_id" id="typeId">
                        {foreach $types as $k => $v}
                            <option value="{if isset($v.id)}{$v.id}{/if}">{$LANG[$v.name]|capitalize}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="enabledId">{$LANG.enabled}:</label>
                </div>
                <div class="col__80">
                    {html_options name=enabled id=enabledId options=$enabled selected=1}
                </div>
            </div>
            <div class="row">
                <div class="col__20">
                    <label for="visibleId">{$LANG.visible}:</label>
                </div>
                <div class="col__80">
                    {html_options name=visible id=visibleId options=$enabled selected=1}
                </div>
            </div>
            <div class="align__text-center margin__top-3 margin__bottom-2">
                <button type="submit" class="positive" name="submit" value="{$LANG.insertProductAttribute}">
                    <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
                </button>
                <a href="index.php?module=product_attribute&amp;view=manage" class="button negative">
                    <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
                </a>
            </div>
            <input type="hidden" name="op" value="create"/>
        </form>
    </div>
{/if}
