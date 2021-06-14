<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=product_attribute_values&amp;view=save&amp;id={$smarty.get.id}">
    <table class="center">
        <tr>
            <th class="details_screen">{$LANG.attribute}:</th>
            <td>
                <select name="attribute_id" class="si_input" tabindex="10">
                    {foreach $product_attributes as $product_attribute}
                        <option {if $product_attribute.id == $product_attribute_values.attribute_id}selected{/if}
                                value="{$product_attribute.id}">{$product_attribute.name}</option>
                    {/foreach}
                </select>
            </td>
        <tr>
            <th class="details_screen">{$LANG.value}:</th>
            <td><input type="text" name="value" class="si_input" size="50" tabindex="20"
                       value="{$product_attribute_values.value}"/></td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.status}:</th>
            <td>{html_options name=enabled class=si_input options=$enabled selected=$product_attribute_values.enabled tabindex=30}</td>
        </tr>
    </table>
    <div class="si_toolbar si_toolbar_form">
        <button type="submit" class="positive" name="submit" value="{$LANG.save}" tabindex="40">
            <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
        </button>
        <a href="index.php?module=product_attribute_values&amp;view=manage" class="negative" tabindex="50">
            <img src="images/cross.png" alt=""/>
            {$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="edit"/>
</form>
