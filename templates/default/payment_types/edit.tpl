{*
 *  Script: details.tpl
 *      Payment type details template
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group *}
<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=payment_types&amp;view=save&amp;id={$smarty.get.id|htmlSafe}">
    <div class="si_form">
        <table>
            <tr>
                <th class="details_screen">{$LANG.descriptionUc}:
                    <a class="cluetip cluetip-clicked" href="#" title="Payment Type Description" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpRequiredField">
                        <img src="{$helpImagePath}required-small.png" alt="(required)"/>
                    </a>
                </th>
                <td>
                    <input type="text" class="si_input validate[required]" name="pt_description" size="30"
                           value="{$paymentType.pt_description|htmlSafe|htmlSafe}" tabindex="10"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.status}:</th>
                <td>{html_options name=pt_enabled class=si_input options=$enabled selected=$paymentType.pt_enabled tabindex=20}</td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_payment_type" value="{$LANG.save}" tabindex="30">
                <img class="button_img" src="../../../images/tick.png" alt="{$LANG.save}"/>
                {$LANG.save}
            </button>
            <a href="index.php?module=payment_types&amp;view=manage" class="negative" tabindex="40">
                <img src="../../../images/cross.png" alt="{$LANG.cancel}"/>
                {$LANG.cancel}
            </a>
        </div>
    </div>
    <input type="hidden" name="op" value="edit">
</form>
