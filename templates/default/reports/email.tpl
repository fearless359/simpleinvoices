{*
 * Script: email.tpl
 *    Send invoice via email page template
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin
 *
 * Last edited:
 *    2016-11-28 by Rich Rowley to add signature field.
 *    2007-07-18
 *
 * License:
 *   GPL v2 or above
 *
 * Website:
 *  https://simpleinvoices.group *}
{if $smarty.get.stage == 1 }
    <!--suppress HtmlFormInputWithoutLabel -->
    {if $error == 1}
        <div class="si_message_error"><h2>{if isset($message)}{$message}{/if}</h2></div>
    {/if}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=reports&amp;view=email&amp;stage=2{foreach $params as $key => $val}&amp;{$key}={$val}{/foreach}&amp;format=pdf">
        <div class="si_center">
            <h3>{$LANG.email} {$LANG.pdf} {$LANG.to} {$LANG.specified} &quot;{$LANG.emailTo}&quot; {$LANG.recipients}</h3>
        </div>
        <div class="si_form"></div>
        <table class="center">
            <tr>
                <th class="left details_screen">{$LANG.emailFrom}:
                    <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpEmailFrom"
                       title="{$LANG.emailFrom}">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name="emailFrom" size="50" class="si_input validate[required]" tabindex="10" autofocus
                           value="{if isset($biller.email)}{$biller.email|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="left details_screen">{$LANG.emailTo}:
                    <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpEmailTo"
                       title="{$LANG.emailTo}">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name="emailTo" size="50" class="si_input validate[required]" tabindex="20"
                           value="{if isset($customer.email)}{$customer.email|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="left details_screen">{$LANG.emailBcc}:
                    <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpEmailBcc"
                       title="{$LANG.emailBcc}">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                    </a>
                </th>
                <td><input type="text" name="emailBcc" class="si_input" size="50" tabindex="30"
                           value="{if isset($biller.email)}{$biller.email|htmlSafe}{/if}"/></td>
            </tr>
            <tr>
                <th class="left details_screen">{$LANG.subject}:
                    <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpRequiredField"
                       title="{$LANG.subject} {$LANG.requiredField}">
                        <img src="{$helpImagePath}required-small.png" alt=""/>
                    </a>
                </th>
                <td>
                    <input type="text" name="emailSubject" size="70" class="si_input validate[required]" tabindex="40"
                           value="{if isset($subject)}{$subject|htmlSafe}{/if}"/>
                </td>
            </tr>
            <tr>
                <th class="left details_screen">{$LANG.message}</th>
                <td>
                    <input name="emailNotes" id="email_notes" {if isset($biller.signature)}value="{$biller.signature|outHtml}"{/if} type="hidden">
                    <trix-editor input="email_notes" class="si_input" tabindex="50"></trix-editor>
                </td>
            </tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="{$LANG.email}" tabindex="60">
                <img class="button_img" src="../../../images/tick.png" alt=""/>
                {$LANG.email}
            </button>
        </div>
    </form>
{elseif $smarty.get.stage == 2}
    {$refresh_redirect}
    {$display_block}
{/if}
