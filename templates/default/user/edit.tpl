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
        function setUserIdList() {
            let role = document.getElementById("role_id1");
            let roleIdx = role.selectedIndex;
            let roleText = role.options[roleIdx].text;
            let origRoleVal = document.getElementById("origRole1").value;
            if (roleText === origRoleVal) return;

            let crigRoleElem = document.getElementById("currRole1");
            crigRoleElem.value = roleText;

            let list = document.getElementById("user_id1");
            let newList = "";
            if (roleText === "customer") {
                let cust = document.getElementById("cust1");
                let custValue = cust.value;
                let custVals = custValue.split("~");
                for (let i = 0; i < custVals.length; i++) {
                    let tmp = custVals[i].split(" ");
                    newList += '<option value="' + tmp[0] + '">' + custVals[i] + '</option>';
                }
            } else if (roleText === "biller") {
                let billers = document.getElementById("bilr1");
                let billersValue = billers.value;
                let billersVals = billersValue.split("~");
                for (let i = 0; i < billersVals.length; i++) {
                    let tmp = billersVals[i].split(" ");
                    newList += '<option value="' + tmp[0] + '">' + billersVals[i] + '</option>';
                }
            } else {
                newList = '<option selected value="0">0 - User</option>';
            }
            list.innerHTML = newList;
        }
    </script>
{/literal}
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=user&amp;view=save&amp;username={$user.username|urlencode}">
    <input type="hidden" name="cust" id="cust1" value="{if isset($cust)}{$cust}{/if}"/>
    <input type="hidden" name="bilr" id="bilr1" value="{if isset($bilr)}{$bilr}{/if}"/>
    <input type="hidden" name="origRole" id="origRole1" value="{if isset($orig_role_name)}{$orig_role_name}{/if}"/>
    <input type="hidden" name="currRole" id="currRole1" value="{if isset($orig_role_name)}{$orig_role_name}{/if}"/>
    <input type="hidden" name="origUserId" id="origUserId1" value="{if isset($orig_user_id)}{$orig_user_id}{/if}"/>
    <div class="si_form">
        <table>
            <tr>
                <th class="details_screen">{$LANG.username}
                    <a class="cluetip" href="#" tabindex="910"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpUsername"
                       title="{$LANG.requiredField}">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name="username" autocomplete="off" class="si_input validate[required]" tabindex="10"
                           value="{if isset($user.username)}{$user.username|htmlSafe}{/if}" size="35" id="username"
                           pattern="{$usernamePattern}" title="See help for details." autofocus
                           {if $smarty.session.role_name == 'biller' || $smarty.session.role_name == 'customer'}readonly{/if}/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.newPassword}
                    <a class="cluetip" href="#" tabindex="920"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpNewPassword"
                       title="{$LANG.newPassword}">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="password" name="password" id="password_id" class="si_input" size="20" tabindex="20"
                           pattern="{$pwd_pattern}" title="See help for details."/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.confirmPassword}
                    <a class="cluetip" href="#" tabindex="930"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpConfirmPassword"
                       title="{$LANG.confirmPassword}">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="password" name="confirm_password" id="confirm_pwd_id" class="si_input" size="20" tabindex="30"
                           pattern="{$pwd_pattern}" title="See help for details"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.email}
                    <a class="cluetip" href="#" tabindex="940"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpEmailAddress"
                       title="{$LANG.requiredField}">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name="email" id="email" class="si_input validate[required]" size="35" tabindex="40"
                           value="{if isset($user.email)}{$user.email|htmlSafe}{/if}"
                           title="See help for details" autocomplete="off"/>
                </td>
            </tr>
            <tr {if $smarty.session.role_name == 'biller' || $smarty.session.role_name == 'customer'}style="display:none;"{/if}>
                <th class="details_screen">{$LANG.role}
                    <a class="cluetip" href="#" tabindex="950"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpUserRole"
                       title="{$LANG.role}">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <select name="role_id" id="role_id1" class="si_input" tabindex="50" onchange="setUserIdList();"
                            title="See help for details">
                        {foreach $roles as $role}
                            <option {if $role.id == $user.role_id}selected{/if} value="{if isset($role.id)}{$role.id|htmlSafe}{/if}">
                                {$role.name|htmlSafe}
                            </option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr {if $smarty.session.role_name == 'biller' || $smarty.session.role_name == 'customer'}style="display:none;"{/if}>
                <th class="details_screen">{$LANG.userId}
                    <a class="cluetip" href="#" tabindex="960"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpUserId"
                       title="{$LANG.userId}">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <select name="user_id" id="user_id1" class="si_input" tabindex="60" title="See help for details"
                            {if $smarty.session.role_name == 'biller' || $smarty.session.role_name == 'customer'}disabled{/if}>
                        {if $user.role_name == "customer"}
                            {assign var="ids" value="~"|explode:$cust}
                            {foreach $ids as $id}
                                {assign var="pts" value=" - "|explode:$id}
                                {assign var="uid" value=$pts[0]-1}
                                <option {if $user.user_id == trim($pts[0])}selected{/if} value="{if isset($uid)}{$uid|htmlSafe}{/if}">
                                    {if isset($id)}{$id|htmlSafe}{/if}
                                </option>
                            {/foreach}
                        {elseif $user.role_name == "biller"}
                            {assign var="ids" value="~"|explode:$bilr}
                            {foreach $ids as $id}
                                {assign var="pts" value=" - "|explode:$id}
                                {assign var="uid" value=$pts[0]-1}
                                <option {if $user.user_id == trim($pts[0])}selected{/if} value="{if isset($uid)}{$uid|htmlSafe}{/if}">
                                    {if isset($id)}{$id|htmlSafe}{/if}
                                </option>
                            {/foreach}
                        {else}
                            <option selected value="0">{if isset($user_id_desc)}{$user_id_desc|htmlSafe}{/if}</option>
                        {/if}
                    </select>
                </td>
            </tr>
            <tr {if $smarty.session.role_name == 'biller' || $smarty.session.role_name == 'customer'}style="display:none;"{/if}>
                <th class="details_screen">{$LANG.enabled}
                    <a class="cluetip" href="#" tabindex="970"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpUserEnabled"
                       title="{$LANG.enabled} / {$LANG.disabled}">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td>{html_options name=enabled class=si_input options=$enabled_options selected=$user.enabled tabindex=70}</td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_user" tabindex="100">
                <img class="button_img" src="images/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=user&amp;view=manage" class="negative" tabindex="110">
                <img src="images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
    </div>
    <input type="hidden" name="op" value="edit"/>
    <input type="hidden" name="id" value="{if isset($user.id)}{$user.id|htmlSafe}{/if}"/>
</form>
