<!--Modified code to display apostrophes in text box output 05/02/2008-Gates-->
<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=product_attribute&amp;view=save&amp;id={$smarty.get.id}">
    <table class="center">
        <tr>
            <th class="details_screen">{$LANG.nameUc}:</th>
            <td><input type="text" name="name" class="si_input validate[required]" size="50" tabindex="10"
                       value="{if isset($product_attribute.name)}{$product_attribute.name}{/if}"/></td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.type}:</th>
            <td>
                <select name="type_id" class="si_input" tabindex="20">
                    {foreach $types as $k => $v}
                        <option value="{if isset($v.id)}{$v.id}{/if}" {if $product_attribute.type_id == $v.id} selected {/if}>{$LANG[$v.name]}</option>
                    {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.enabled}:</th>
            <td>
                {html_options name=enabled class=si_input options=$enabled selected=$product_attribute.enabled tabindex=30}
            </td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.visible}:</th>
            <td>
                {html_options name=visible class=si_input options=$enabled selected=$product_attribute.visible tabindex=40}
            </td>
        </tr>
    </table>
    <input type="hidden" name="op" value="edit"/>
    <div class="si_toolbar si_toolbar_form">
        <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="50">
            <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
        </button>
        <a href="index.php?module=product_attribute&amp;view=manage" class="negative" tabindex="60">
            <img src="images/cross.png" alt=""/>
            {$LANG.cancel}
        </a>
    </div>
</form>
