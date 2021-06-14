{*
*  Script: email.tpl
*      Send invoice via email page template
*
*  Authors:
*      Justin Kelly, Nicolas Ruflin
*
*  Last edited:
*      2016-08-03
*
*  License:
*      GPL v3 or above
*
*  Website:
*      https://simpleinvoices.group*}
<!--suppress HtmlFormInputWithoutLabel -->
{if $smarty.get.stage == 1 }
    {if $error == 1 }
        <div class="si_message_error"><h2>{$message}</h2></div>
    {/if}
    <div class="si_center">
        <h3>{$LANG.email} {$invoice.index_name|htmlSafe} {$LANG.to} {$LANG.customerUc} {$LANG.asLc} {$LANG.pdf}</h3>
    </div>
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=invoices&amp;view=email&amp;stage=2&amp;id={$smarty.get.id|urlencode}">
        <div class="grid__area">
                <div class="grid__container grid__head-6">
                    <label for="emailFrom" class="cols__1-span-2 bold">
                        {$LANG.emailFrom}:
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpEmailFrom"
                           title="{$LANG.emailFrom} {$LANG.requiredField}">
                            <img src="{$helpImagePath}required-small.png" alt=""/>
                        </a>
                    </label>
                    <div class="cols__3-span-4">
                        <input type="text" name="emailFrom" id="emailFrom" size="50" class="si_input validate[required]"
                               value="{if isset($biller.email)}{$biller.email|htmlSafe}{/if}" tabindex="10"/>
                    </div>
                </div>
                <div class="grid__container grid__head-6">
                    <label for="emailTo" class="cols__1-span-2 bold">{$LANG.emailTo}:
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpEmailTo"
                           title="{$LANG.emailTo} {$LANG.requiredField}">
                            <img src="{$helpImagePath}required-small.png" alt=""/>
                        </a>
                    </label>
                    <div class="cols__3-span-4">
                        <input type="text" name="emailTo" id="emailTo" size="50" class="si _input validate[required]"
                               value="{if isset($customer.email)}{$customer.email|htmlSafe}{/if}" tabindex="20"/>
                    </div>
                </div>
                <div class="grid__container grid__head-6">
                    <label for="emailBcc" class="cols__1-span-2 bold">{$LANG.emailBcc}:
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpEmailBcc"
                           title="{$LANG.emailBcc}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </label>
                    <div class="cols__3-span-4"><input type="text" name="emailBcc" id="emailBcc" class="si_input" size="50"
                               value="{if isset($biller.email)}{$biller.email|htmlSafe}{/if}" tabindex="30"/></div>
                </div>
                <div class="grid__container grid__head-6">
                    <label for="emailSubject" class="cols__1-span-2 bold">{$LANG.subject}:
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpRequiredField"
                           title="{$LANG.subject} {$LANG.requiredField}">
                            <img src="{$helpImagePath}required-small.png" alt=""/>
                        </a>
                    </label>
                    <div class="cols__3-span-4">
                        <input type="text" name="emailSubject" id="emailSubject" size="70" class="si_input validate[required]" tabindex="40"
                               value="{$invoice.index_name|htmlSafe} from {$biller.name|htmlSafe} is attached"/>
                    </div>
                </div>
                <div class="grid__container grid__head-6">
                    <label for="emailNotes" class="cols__1-span-2 bold">{$LANG.message}:</label>
                    <div class="cols__3-span-4">
                        <input name="emailNotes" id="emailNotes" {if isset($biller.signature)}value="{$biller.signature|outHtml}"{/if} type="hidden">
                        <trix-editor input="emailNotes" class="si_input" tabindex="50"></trix-editor>
                    </div>
                </div>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.email}" tabindex="70">
                <img class="button_img" src="images/tick.png" alt=""/>
                {$LANG.email}
            </button>
        </div>
{*        <input type="hidden" name="op" value="insert_customer"/>*}
    </form>
{elseif $smarty.get.stage == 2}
    {include file="templates/default/invoices/save.tpl"}
{/if}
