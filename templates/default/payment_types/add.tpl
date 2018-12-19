{*
 *  Script: add.tpl
 * 	    Payment type add template
 *
 *  Authors:
 *	    Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 * 	    2018-12-15 by Richard Rowley
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
    <form name="frmpost" method="post" onsubmit="return frmpost_Validator(this)"
          action="index.php?module=payment_types&amp;view=add">
        <div class="si_form">
            <table>
                <tr>
                    <th>Payment type description
                        <a class="cluetip" href="#" title="{$LANG.required_field}"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field">
                            <img src="{$help_image_path}required-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input class="validate[required]" type="text" name="pt_description" size="30"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.enabled}</th>
                    <td>
                        <select name="pt_enabled">
                            <option value="1" selected>{$LANG.enabled}</option>
                            <option value="0">{$LANG.disabled}</option>
                        </select>
                    </td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="insert_preference" value="{$LANG.save}">
                    <img class="button_img" src="images/common/tick.png" alt="{$LANG.save}"/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=payment_types&amp;view=manage" class="negative">
                    <img src="images/common/cross.png" alt="{$LANG.cancel}"/>
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="add"/>
        <input type="hidden" name="domain_id" value="{$domain_id}" />
    </form>
{/if}