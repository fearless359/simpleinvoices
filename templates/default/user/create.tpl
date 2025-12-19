{*
 *  Script: create.tpl
 *      User add template
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin, Soif, Rich Rowley
 *
 *  Last edited:
 *      20251215 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 * 	    20210701 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="form__container">
    {if isset($smarty.post.username) && isset($smarty.post.submit)}
        {include file="templates/default/user/save.tpl"}
    {else}
        <div class="row">
            <div class="col__100 margin__bottom-2">
                <h3 class="align__text-center">{$LANG.addNewUser}</h3>
            </div>
        </div>
        <form name="frmpost" method="POST" id="frmpost" action="index.php?module=user&amp;view=create">
            <div class="row">
                <div class="col__25">
                    <label for="username">{$LANG.username}:
                        <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpUsername}"
                             src="{$helpImagePath}required-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="text" name="username" id="username" required size="35" tabindex="10"
                           pattern="{$usernamePattern}" placeholder="{$PLACEHOLDERS["name"]}"
                           title="See help for details." autocomplete="off" autofocus/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="passwordId">{$LANG.newPassword}:
                        <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpNewPassword}"
                             src="{$helpImagePath}required-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="password" name="password" id="passwordId" class="cols__4-span-5" required size="20"
                           pattern="{$pwd_pattern}" title="See help for details." tabindex="20"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="passwordConfirmId">{$LANG.confirmPassword}:
                        <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpConfirmPassword}"
                             src="{$helpImagePath}required-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="password" name="confirm_password" id="passwordConfirmId" size="20" tabindex="30"
                           required pattern="{$pwd_pattern}" title="See help for details"/>
                </div>
            </div>
            <div class="row">
                <div class="col__25">
                    <label for="email">{$LANG.email}:
                        <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpEmailAddress}"
                             src="{$helpImagePath}required-small.png" alt=""/>
                    </label>
                </div>
                <div class="col__75">
                    <input type="email" name="email" id="email" required size="35" tabindex="40"
                           title="See help for details" placeholder="{$PLACEHOLDERS.email}"/>
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
            <input type="hidden" name="role_id" value="0"/>
            <input type="hidden" name="enabled" value="{$smarty.const.DISABLED}"/>
        </form>
    {/if}
</div>