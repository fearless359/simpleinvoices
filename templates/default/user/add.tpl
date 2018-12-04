{*
 * Script: add.tpl
 *   User add template
 *
 * Authors:
 *  Justin Kelly, Nicolas Ruflin, Soif, Rich Rowley
 *
 * Last edited:
 *    2016-08-10
 *
 * License:
 *  GPL v3 or above
 *}
{if isset($smarty.post.username) && isset($smarty.post.submit)}
    {include file="templates/default/user/save.tpl"}
{else}
    <div class="si_center si_help_div">
        <h4><strong>{$LANG.add_new_user}</strong></h4>
    </div>
    <hr/>
    <form name="frmpost" action="index.php?module=user&amp;view=add" method="post" id="frmpost">
        <input type="hidden" name="role_id" value="0"/>
        <input type="hidden" name="enabled" value="{$smarty.const.DISABLED}"/>
        <div class="si_form">
            <table>
                <tr>
                    <th>{$LANG.username}
                        <a class="cluetip" href="#" tabindex="910"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_username"
                           title="{$LANG.username}">
                            <img src="{$help_image_path}required-small.png" alt="" />
                        </a>
                    </th>
                    <td>
                        <input type="text" name="username" size="35" id="username" tabindex="10"
                               pattern="{$username_pattern}" title="See help for details."
                               autocomplete="off" class="validate[required]" autofocus />
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.new_password}
                        <a class="cluetip" href="#" tabindex="920"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_new_password"
                           title="{$LANG.new_password}">
                            <img src="{$help_image_path}required-small.png" alt="" />
                        </a>
                    </th>
                    <td><input type="password" name="password" size="20" pattern="{$pwd_pattern}"
                               title="See help for details." tabindex="20"
                               class="validate[required]"
                               onchange="genConfirmPattern(this,'confirm_pwd_id');"/></td>
                </tr>
                <tr>
                    <th>{$LANG.confirm_password}
                        <a class="cluetip" href="#" tabindex="930"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_confirm_password"
                           title="{$LANG.confirm_password}">
                            <img src="{$help_image_path}required-small.png" alt="" />
                        </a>
                    </th>
                    <td><input type="password" name="confirm_password" size="20" tabindex="30"
                               class="validate[required]" pattern="{$pwd_pattern}"
                               title="See help for details" /></td>
                </tr>
                <tr>
                    <th>{$LANG.email}
                        <a class="cluetip" href="#" tabindex="940"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_email_address"
                           title="{$LANG.required_field}">
                            <img src="{$help_image_path}required-small.png" alt="" />
                        </a>
                    </th>
                    <td>
                        <input type="text" name="email" size="35" id="email" tabindex="40"
                               class="validate[required]" title="See help for details" />
                    </td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" value="Insert User">
                    <img class="button_img" src="images/common/tick.png" alt="" tabindex="100" />
                    {$LANG.save}
                </button>
                <a href="index.php?module=user&view=manage" class="negative" tabindex="110">
                    <img src="images/common/cross.png" alt="" />
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="insert_user" />
    </form>
{/if}
