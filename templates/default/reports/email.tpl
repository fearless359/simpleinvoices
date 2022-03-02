{*
 *  Script: email.tpl
 *      Send invoice via email template
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      20210701 by Rich Rowley to convert to grid layout.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
{if $smarty.get.stage == 1 }
    {if $error == 1}
        <div class="si_message_error"><h2>{if isset($message)}{$message}{/if}</h2></div>
    {/if}
    <h3 class="align__text-center margin__bottom-2">{$LANG.email} {$LANG.pdf} {$LANG.to} {$LANG.specified} &quot;{$LANG.emailTo}&quot; {$LANG.recipients}</h3>
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=email&amp;stage=2{foreach $params as $key=>$val}&amp;{$key}={$val}{/foreach}&amp;format=pdf">
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <label for="emailFromId" class="cols__2-span-3 align__text-right margin__right-1">{$LANG.emailFrom}:
                    <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpEmailFrom}" src="{$helpImagePath}required-small.png" alt=""/>
                </label>
                <input type="text" name="emailFrom" id="emailFromId" class="cols__5-span-5" required size="50" tabindex="10" autofocus
                       value="{if isset($biller.email)}{$biller.email|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="emailToId" class="cols__2-span-3 align__text-right margin__right-1">{$LANG.emailTo}:
                    <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpEmailTo}" src="{$helpImagePath}required-small.png" alt=""/>
                </label>
                <input type="text" name="emailTo" id="emailToId" class="cols__5-span-5" required size="50" tabindex="20"
                       value="{if isset($customer.email)}{$customer.email|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="emailBccId" class="cols__2-span-3 align__text-right margin__right-1">{$LANG.emailBcc}:
                    <img class="tooltip" title="{$LANG.helpEmailBcc}" src="{$helpImagePath}help-small.png" alt=""/>
                </label>
                <input type="text" name="emailBcc" id="emailBccId" class="cols__5-span-5" size="50" tabindex="30"
                       value="{if isset($biller.email)}{$biller.email|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="emailSubjectTo" class="cols__2-span-3 align__text-right margin__right-1">{$LANG.subject}:
                    <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpEmailSubject}" src="{$helpImagePath}required-small.png" alt=""/>
                </label>
                <input type="text" name="emailSubject" id="emailSubjectTo" class="cols__5-span-5" required size="70" tabindex="40"
                       value="{if isset($subject)}{$subject|htmlSafe}{/if}"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="emailNotesId" class="cols__2-span-3">{$LANG.message}</label>
            </div>
            <div class="grid__container grid__head-10">
                <div class="cols__2-span-8">
                    <input name="emailNotes" id="emailNotesId" {if isset($biller.signature)}value="{$biller.signature|outHtml}"{/if} type="hidden">
                    <trix-editor input="emailNotesId" tabindex="50"></trix-editor>
                </div>
            </div>
        </div>
        <div class="align__text-center margin__top-2 margin__bottom-2">
            <button type="submit" class="positive" name="submit" value="{$LANG.email}" tabindex="60">
                <img class="button_img" src="images/tick.png" alt="{$LANG.email}"/>{$LANG.email}
            </button>
            <a href="index.php?module=reports&amp;view=index" class="button negative">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
    </form>
{elseif $smarty.get.stage == 2}
    {$refresh_redirect}
    {$display_block}
{/if}
