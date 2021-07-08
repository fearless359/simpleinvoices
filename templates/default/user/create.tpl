{*
 *  Script: create.tpl
 *      User add template
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin, Soif, Rich Rowley
 *
 *  Last edited:
 * 	    20210701 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
{if isset($smarty.post.username) && isset($smarty.post.submit)}
    {include file="templates/default/user/save.tpl"}
{else}
    <h3 class="align__text-center margin__bottom-2">{$LANG.addNewUser}</h3>
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=user&amp;view=create">
        <input type="hidden" name="role_id" value="0"/>
        <input type="hidden" name="enabled" value="{$smarty.const.DISABLED}"/>
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <label for="username" class="cols__3-span-2">{$LANG.username}:
                    <a class="cluetip" href="#" tabindex="-1" title="{$LANG.username}"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpUsername">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="username" id="username" class="cols__5-span-5 validate[required]" size="35" tabindex="10"
                       pattern="{$usernamePattern}" title="See help for details." autocomplete="off" autofocus/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="passwordId" class="cols__3-span-2">{$LANG.newPassword}:
                    <a class="cluetip" href="#" tabindex="-1" title="{$LANG.newPassword}"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpNewPassword">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                </label>
                <input type="password" name="password" id="passwordId" class="cols__5-span-5 validate[required]" size="20"
                       pattern="{$pwd_pattern}" title="See help for details." tabindex="20"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="passwordConfirmId" class="cols__3-span-2">{$LANG.confirmPassword}:
                    <a class="cluetip" href="#" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpConfirmPassword"
                       title="{$LANG.confirmPassword}">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                </label>
                <input type="password" name="confirm_password" id="passwordConfirmId" size="20" tabindex="30"
                       class="cols__5-span-5 validate[required]" pattern="{$pwd_pattern}" title="See help for details"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="email" class="cols__3-span-2">{$LANG.email}:
                    <a class="cluetip" href="#" tabindex="-1"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpEmailAddress"
                       title="{$LANG.requiredField}">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                </label>
                <input type="text" name="email" id="email" class="cols__5-span-5 validate[required]" size="35" tabindex="40"
                       title="See help for details"/>
            </div>
        </div>
        <div class="align__text-center margin__top-3 margin__bottom-2">
            <button type="submit" class="positive" name="submit" value="Insert User" tabindex="50">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=user&view=manage" class="button negative" tabindex=60>
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
    </form>
{/if}
