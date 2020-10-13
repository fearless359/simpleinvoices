{* if product_group is saved.*}
{if !empty($smarty.post.name)}
    {include file="templates/default/product_groups/save.tpl"}
{else}
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=product_groups&amp;view=create">
        <div class="si_form">
            <table>
                <tr>
                    <th class="details_screen"><label for="nameId">{$LANG.groupUc} {$LANG.nameUc}:</label>
                        <a class="cluetip" href="#" title="{$LANG.requiredField}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpRequiredField">
                            <img src="{$helpImagePath}required-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="name" id="nameId" class="si_input validate[required]" size="60" tabindex="10"
                               value="{if isset($smarty.post.name)}{$smarty.post.name|htmlSafe}{/if}"/>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen"><label for="markupId">{$LANG.markupUc}:</label>
                        <a class="cluetip" href="#" title="{$LANG.markupUc}" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=helpMarkup">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" class="si_input edit" name="markup" id="markupId" size="10" tabindex="20"
                               value="{if isset($smarty.post.markup)}{$smarty.post.markup|htmlSafe}{/if}"/>
                    </td>
                </tr>
            </table>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_product_group" value="{$LANG.save}" tabindex="100">
                <img class="button_img" src="../../../images/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=product_groups&amp;view=manage" class="negative" tabindex="110">
                <img src="../../../images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
    </form>
{/if}
