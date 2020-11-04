<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=product_groups&amp;view=save&amp;name={$smarty.get.name|urlencode}">
    <div class="si_form">
        <table class="center">
            <tr>
                <th class="details_screen"><label for="nameId">{$LANG.nameUc}:</label></th>
                <td>
                    <input type="text" name="name" id="nameId" class="si_input" size="60" readonly
                           value="{if isset($productGroup.name)}{$productGroup.name|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen"><label for="markupId">{$LANG.markupUc}%:</label></th>
                <td>
                    <input type="text" name="markup" id="markupId" class="si_input" size="10" tabindex="10"
                           value="{$productGroup.markup}"/>
                </td>
            </tr>
        </table>
    </div>
    <div class="si_toolbar si_toolbar_form">
        <button type="submit" class="positive" name="save_product_group" value="{$LANG.save}" tabindex="100">
            <img class="button_img" src="images/tick.png" alt=""/>
            {$LANG.save}
        </button>
        <a href="index.php?module=product_groups&amp;view=manage" class="negative" tabindex="110">
            <img src="images/cross.png" alt=""/>
            {$LANG.cancel}
        </a>
    </div>
    <input type="hidden" name="op" value="edit">
</form>
