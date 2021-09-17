{*
*  Script: email.tpl
*      Send invoice via email template
*
*  Authors:
*      Justin Kelly, Nicolas Ruflin
*
*  Last edited:
*      20210630 by Rich Rowley to convert to grid layout.
*
*  License:
*      GPL v3 or above
*
*  Website:
*      https://simpleinvoices.group
*}
{if $smarty.get.stage == 1 }
    {if $error == 1 }
        <div class="si_message_error"><h2>{$message}</h2></div>
    {/if}
    <h3 class="align__text-center margin__bottom-2">{$LANG.email} {$invoice.index_name|htmlSafe} {$LANG.to} {$LANG.customerUc} {$LANG.asLc} {$LANG.pdf}</h3>
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=invoices&amp;view=email&amp;stage=2&amp;id={$smarty.get.id|urlencode}">
        <div class="grid__area">
                <div class="grid__container grid__head-6">
                    <label for="emailFrom" class="cols__1-span-2">
                        {$LANG.emailFrom}:
                        <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpEmailFrom}" src="{$helpImagePath}required-small.png" alt=""/>
                    </label>
                    <div class="cols__3-span-4">
                        <input type="text" name="emailFrom" id="emailFrom" size="50" class="margin__left-0-5" required
                               value="{if isset($biller.email)}{$biller.email|htmlSafe}{/if}" tabindex="10"/>
                    </div>
                </div>
                <div class="grid__container grid__head-6">
                    <label for="emailTo" class="cols__1-span-2">{$LANG.emailTo}:
                        <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpEmailTo}" src="{$helpImagePath}required-small.png" alt=""/>
                    </label>
                    <div class="cols__3-span-4">
                        <input type="text" name="emailTo" id="emailTo" size="50" class="si _input" required
                               value="{if isset($customer.email)}{$customer.email|htmlSafe}{/if}" tabindex="20"/>
                    </div>
                </div>
                <div class="grid__container grid__head-6">
                    <label for="emailBcc" class="cols__1-span-2">{$LANG.emailBcc}:
                        <img class="tooltip" title="{$LANG.helpEmailBcc}" src="{$helpImagePath}help-small.png" alt=""/>
                    </label>
                    <div class="cols__3-span-4"><input type="text" name="emailBcc" id="emailBcc" class="margin__left-0-5" size="50"
                               value="{if isset($biller.email)}{$biller.email|htmlSafe}{/if}" tabindex="30"/></div>
                </div>
                <div class="grid__container grid__head-6">
                    <label for="emailSubject" class="cols__1-span-2">{$LANG.subject}:
                        <img class="tooltip" title="{$LANG.requiredField} {$LANG.helpRequiredField}" src="{$helpImagePath}required-small.png" alt=""/>
                    </label>
                    <div class="cols__3-span-4">
                        <input type="text" name="emailSubject" id="emailSubject" size="70" class="margin__left-0-5" required tabindex="40"
                               value="{$invoice.index_name|htmlSafe} from {$biller.name|htmlSafe} is attached"/>
                    </div>
                </div>
                <div class="grid__container grid__head-6">
                    <label for="emailNotes" class="cols__1-span-2">{$LANG.message}:</label>
                    <div class="cols__3-span-4">
                        <input name="emailNotes" id="emailNotes" {if isset($biller.signature)}value="{$biller.signature|outHtml}"{/if} type="hidden">
                        <trix-editor input="emailNotes" class="margin__left-0-5" tabindex="50"></trix-editor>
                    </div>
                </div>
        </div>
        <div class="align__text-center margin__top-2">
            <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.email}" tabindex="70">
                <img class="button_img" src="images/tick.png" alt="{$LANG.email}"/>{$LANG.email}
            </button>
        </div>
    </form>
{elseif $smarty.get.stage == 2}
    {include file="templates/default/invoices/save.tpl"}
{/if}
