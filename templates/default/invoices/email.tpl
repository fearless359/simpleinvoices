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
{if $smarty.get.stage == 1 }
    {if $error == 1 }
        <!--suppress HtmlFormInputWithoutLabel -->
        <div class="si_message_error"><h2>{$message}</h2></div>
    {/if}
    <div class="si_center">
        <h3>{$LANG.email} {$invoice.index_name|htmlSafe} {$LANG.to} {$LANG.customer} {$LANG.asLc} {$LANG.pdf}</h3>
    </div>
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=invoices&amp;view=email&amp;stage=2&amp;id={$smarty.get.id|urlencode}">
        <div class="si_form">
            <table>
                <tr>
                    <th>{$LANG.email_from}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_from"
                           title="{$LANG.email_from} {$LANG.required_field}">
                            <img src="{$helpImagePath}required-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="email_from" size="50" value="{if isset($biller.email)}{$biller.email|htmlSafe}{/if}" tabindex="10"
                               class="validate[required]"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.email_to}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_to"
                           title="{$LANG.email_to} {$LANG.required_field}">
                            <img src="{$helpImagePath}required-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="email_to" size="50" value="{if isset($customer.email)}{$customer.email|htmlSafe}{/if}" tabindex="20"
                               class="validate[required]"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.email_bcc}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_email_bcc"
                           title="{$LANG.email_bcc}">
                            <img src="{$helpImagePath}help-small.png" alt=""/>
                        </a>
                    </th>
                    <td><input type="text" name="email_bcc" size="50" value="{if isset($biller.email)}{$biller.email|htmlSafe}{/if}" tabindex="30"/></td>
                </tr>
                <tr>
                    <th>{$LANG.subject}
                        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
                           title="{$LANG.subject} {$LANG.required_field}">
                            <img src="{$helpImagePath}required-small.png" alt=""/>
                        </a>
                    </th>
                    <td>
                        <input type="text" name="email_subject" size="70" class="validate[required]" tabindex="40"
                               value="{$invoice.index_name|htmlSafe} from {$biller.name|htmlSafe} is attached"/>
                    </td>
                </tr>
                <tr>
                    <th>{$LANG.message}</th>
                    <td>
                        <input name="email_notes" id="email_notes" {if isset($biller.signature)}value="{$biller.signature|outHtml}"{/if} type="hidden">
                        <trix-editor input="email_notes" tabindex="50"></trix-editor>
                    </td>
                </tr>
                <!--  TODO: Eventual use for adding additional attachments
                <tr>
                    <th>{*$LANG.attachments*}</th>
                    <td><input type="file" name="attachments[]" accept=".pdf|.txt|.doc|.docx|image/*" tabindex="60" /></td>
                </tr>
                -->
            </table>
        </div>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.email}" tabindex="70">
                <img class="button_img" src="../../../images/tick.png" alt=""/>
                {$LANG.email}
            </button>
        </div>
        <input type="hidden" name="op" value="insert_customer"/>
    </form>
{elseif $smarty.get.stage == 2}
    {include file="templates/default/invoices/save.tpl"}
{/if}
