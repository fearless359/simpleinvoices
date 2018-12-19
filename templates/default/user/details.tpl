{*
 *  Script: details.tpl
 *      User detail template
 *
 *  Last edited:
 *      2018-09-26 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *}
{literal}
    <script>
        function setuseridlist() {
            let role = document.getElementById("role_id1");
            let role_idx = role.selectedIndex;
            let role_text = role.options[role_idx].text;
            let orole_val = document.getElementById("origrole1").value ;
            if (role_text === orole_val) return;

            let crole_elem = document.getElementById("currrole1");
            crole_elem.value = role_text;

            let list = document.getElementById("user_id1");
            let newlist = "";
            if (role_text === "customer") {
                let cust = document.getElementById("cust1");
                let cust_value = cust.value;
                let cust_vals = cust_value.split("~");
                for (let i=0; i<cust_vals.length; i++) {
                    let tmp = cust_vals[i].split(" ");
                    newlist += '<option value="' + tmp[0] + '">' + cust_vals[i] + '</option>';
                }
            } else if (role_text === "biller") {
                let billers = document.getElementById("bilr1");
                let billers_value = billers.value;
                let billers_vals = billers_value.split("~");
                for (let i=0; i<billers_vals.length; i++) {
                    let tmp = billers_vals[i].split(" ");
                    newlist += '<option value="' + tmp[0] + '">' + billers_vals[i] + '</option>';
                }
            } else {
                newlist = '<option selected value="0">0 - User</option>';
            }
            list.innerHTML = newlist;
        }
    </script>
{/literal}
<form name="frmpost"
      action="index.php?module=user&amp;view=save&amp;username={$user.username|urlencode}"
      method="post" id="frmpost" onsubmit="return checkForm(this);">
    {if $smarty.get.action== 'view' }
        <div class="si_form si_form_view">
            <table>
                <tr>
                    <th>{$LANG.username}</th>
                    <td>{$user.username|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.password}</th>
                    <td>**********</td>
                </tr>
                <tr>
                    <th>{$LANG.role}</th>
                    <td>{$user.role_name|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.email}</th>
                    <td>{$user.email|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.enabled}</th>
                    <td>{$user.enabled_text|htmlsafe}</td>
                </tr>
                <tr>
                    <th>{$LANG.user_id}</th>
                    <td>{$user_id_desc|htmlsafe}</td>
                </tr>
            </table>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=user&amp;view=details&amp;id={$user.id|urlencode}&amp;action=edit" class="positive">
                <img src="images/famfam/report_edit.png" alt="" />
                {$LANG.edit}
            </a>
            <a href="index.php?module=user&amp;view=manage" class="negative">
                <img src="images/common/cross.png" alt="" />
                {$LANG.cancel}
            </a>
        </div>
    {elseif $smarty.get.action== 'edit' }
        <input type="hidden" name="cust" id="cust1" value="{if isset($cust)}{$cust}{/if}" />
        <input type="hidden" name="bilr" id="bilr1" value="{if isset($bilr)}{$bilr}{/if}" />
        <input type="hidden" name="origrole" id="origrole1" value="{if isset($orig_role_name)}{$orig_role_name}{/if}" />
        <input type="hidden" name="currrole" id="currrole1" value="{if isset($orig_role_name)}{$orig_role_name}{/if}" />
        <input type="hidden" name="origuserid" id="origuserid1" value="{if isset($orig_user_id)}{$orig_user_id}{/if}" />
        <div class="si_form">
            <table>
                <tr>
                    <th>{$LANG.username}
                        <a class="cluetip" href="#" tabindex="910"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_username"
                           title="{$LANG.required_field}">
                            <img src="{$help_image_path}required-small.png" alt="" />
                        </a>
                    </th>
                    <td>
                        <input type="text" name="username" autocomplete="off" tabindex="10"
                               value="{if isset($user.username)}{$user.username|htmlsafe}{/if}" size="35" id="username"
                               pattern="{$username_pattern}" title="See help for details."
                               class="validate[required]" autofocus />
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.new_password}
                        <a class="cluetip" href="#" tabindex="920"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_new_password"
                           title="{$LANG.new_password}">
                            <img src="{$help_image_path}help-small.png" alt="" />
                        </a>
                    </th>
                    <td>
                        <input type="password" name="password" id="password_id" size="20" tabindex="20"
                               pattern="{$pwd_pattern}" title="See help for details."
                               onchange="genConfirmPattern(this,'confirm_pwd_id');" />
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.confirm_password}
                        <a class="cluetip" href="#" tabindex="930"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_confirm_password"
                           title="{$LANG.confirm_password}">
                            <img src="{$help_image_path}help-small.png" alt="" />
                        </a>
                    </th>
                    <td>
                        <input type="password" name="confirm_password" id="confirm_pwd_id"
                               size="20" tabindex="30" pattern="{$pwd_pattern}"
                               title="See help for details"/>
                    </td>
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
                        <input type="text" name="email" autocomplete="off" tabindex="40"
                               value="{if isset($user.email)}{$user.email|htmlsafe}{/if}" size="35" id="email"
                               class="validate[required]" title="See help for details" />
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.role}
                        <a class="cluetip" href="#" tabindex="950"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_user_role"
                           title="{$LANG.role}">
                            <img src="{$help_image_path}help-small.png" alt="" />
                        </a>
                    </th>
                    <td>
                        <select name="role_id" id="role_id1" tabindex="50" onchange="setuseridlist();"
                                title="See help for details">
                            {foreach from=$roles item=role}
                                <option {if $role.id == $user.role_id}selected{/if} value="{if isset($role.id)}{$role.id|htmlsafe}{/if}">
                                    {$role.name|htmlsafe}
                                </option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.user_id}
                        <a class="cluetip" href="#" tabindex="960"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_user_id"
                           title="{$LANG.user_id}">
                            <img src="{$help_image_path}help-small.png" alt="" />
                        </a>
                    </th>
                    <td>
                        <select name="user_id" id="user_id1" tabindex="60" title="See help for details">
                            {if $user.role_name == "customer"}
                                {assign var="ids" value="~"|explode:$cust}
                                {foreach from=$ids item=id}
                                    {assign var="pts" value=" - "|explode:$id}
                                    {assign var="uid" value=$pts[0]-1}
                                    <option {if $user.user_id == trim($pts[0])}selected{/if} value="{if isset($uid)}{$uid|htmlsafe}{/if}">
                                        {if isset($id)}{$id|htmlsafe}{/if}
                                    </option>
                                {/foreach}
                            {elseif $user.role_name == "biller"}
                                {assign var="ids" value="~"|explode:$bilr}
                                {foreach from=$ids item=id}
                                    {assign var="pts" value=" - "|explode:$id}
                                    {assign var="uid" value=$pts[0]-1}
                                    <option {if $user.user_id == trim($pts[0])}selected{/if} value="{if isset($uid)}{$uid|htmlsafe}{/if}">
                                        {if isset($id)}{$id|htmlsafe}{/if}
                                    </option>
                                {/foreach}
                            {else}
                                <option selected value="0">{if isset($user_id_desc)}{$user_id_desc|htmlsafe}{/if}</option>
                            {/if}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.enabled}
                        <a class="cluetip" href="#" tabindex="970"
                           rel="index.php?module=documentation&amp;view=view&amp;page=help_user_enabled"
                           title="{$LANG.enabled} / {$LANG.disabled}">
                            <img src="{$help_image_path}help-small.png" alt="" />
                        </a>
                    </th>
                    <td>{html_options name=enabled options=$enabled_options selected=$user.enabled tabindex=70}</td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="save_user" tabindex="100">
                    <img class="button_img" src="images/common/tick.png" alt="" />
                    {$LANG.save}
                </button>
                <a href="index.php?module=user&amp;view=manage" class="negative" tabindex="110">
                    <img src="images/common/cross.png" alt="" />
                    {$LANG.cancel}
                </a>
            </div>
        </div>
        <input type="hidden" name="op" value="edit_user" />
        <input type="hidden" name="id" value="{if isset($user.id)}{$user.id|htmlsafe}{/if}" />
        <input type="hidden" name="domain_id" value="{if isset($user.domain_id)}{$user.domain_id|htmlsafe}{/if}" />
    {/if}
</form>
