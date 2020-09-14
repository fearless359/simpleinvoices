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
        <h3><strong>{$LANG.add_new_user}</strong></h3>
    </div>
    <hr/>
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=user&amp;view=create">
        <input type="hidden" name="role_id" value="0"/>
        <input type="hidden" name="enabled" value="{$smarty.const.DISABLED}"/>
        <div class="si_form">
            <table>
                <tr>
                    <th class="details_screen">{$LANG.username}
                        <a class="cluetip" href="#" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_username"
                           title="{$LANG.username}">
                            <img src="{$helpImagePath}required-small.png" alt="" />
                        </a>
                    </th>
                    <td>
                        <input type="text" name="username" class="si_input validate[required]" size="35" id="username" tabindex="10"
                               pattern="{$usernamePattern}" title="See help for details."
                               autocomplete="off" autofocus />
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.new_password}
                        <a class="cluetip" href="#" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_new_password"
                           title="{$LANG.new_password}">
                            <img src="{$helpImagePath}required-small.png" alt="" />
                        </a>
                    </th>
                    <td>
                        <input type="password" name="password" class="si_input validate[required]" size="20" pattern="{$pwd_pattern}"
                               title="See help for details." tabindex="20"/>
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.confirm_password}
                        <a class="cluetip" href="#" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_confirm_password"
                           title="{$LANG.confirm_password}">
                            <img src="{$helpImagePath}required-small.png" alt="" />
                        </a>
                    </th>
                    <td>
                        <input type="password" name="confirm_password" class="si_input validate[required]" size="20" tabindex="30"
                               pattern="{$pwd_pattern}" title="See help for details" />
                    </td>
                </tr>
                <tr>
                    <th class="details_screen">{$LANG.email}
                        <a class="cluetip" href="#" tabindex="-1"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_email_address"
                           title="{$LANG.required_field}">
                            <img src="{$helpImagePath}required-small.png" alt="" />
                        </a>
                    </th>
                    <td>
                        <input type="text" id="email" name="email" class="si_input validate[required]" size="35" tabindex="40"
                               title="See help for details" />
                    </td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" value="Insert User" tabindex="50">
                    <img class="button_img" src="../../../images/tick.png" alt=""/>
                    {$LANG.save}
                </button>
                <a href="index.php?module=user&view=manage" class="negative" tabindex=60>
                    <img src="../../../images/cross.png" alt="" />
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="create" />
    </form>
{/if}
