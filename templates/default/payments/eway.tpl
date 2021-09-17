{if $saved == 'true' }
<!--suppress HtmlFormInputWithoutLabel -->
    <meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
<br />
{$LANG.saveEwaySuccess}
<br />
<br />
{/if}
{if $saved == 'check_failed' }
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
<br />
{$LANG.saveEwayCheckFailed}
<br />
<br />
{/if}
{if $saved == 'false' }
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
<br />
{$LANG.saveEwayFailure}
<br />
<br />
{/if}

{if $saved == false}
    {if $smarty.post.op == 'add' AND $smarty.post.invoice_id == ''}
        <div class="validation_alert"><img src="images/important.png" alt="" />
            {$LANG.selectInvoice}</div>
        <hr />
    {/if}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=payments&amp;view=eway">
        <br />
        <table class="center">
            <tr>
                <td class="details_screen">{$LANG.invoiceUc}</td>
                <td>
                    <select name="invoice_id" class="" required>
                        <option value=''></option>
                        {foreach $invoice_all as $invoice}
                        <option value="{if isset($invoice.id)}{$invoice.id|htmlSafe}{/if}" {if $smarty.get.id == $invoice.id} selected {/if} >
                            {$invoice.index_name|htmlSafe}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan=2>
                    <br />
                    {$LANG.warningEway}
                    <br />
                </td>
            </tr>
        </table>
        <br />
        <table class="center" >
            <tr>
                <td>
                    <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                        <img class="button_img" src="images/tick.png" alt="{$LANG.save}" />{$LANG.save}
                    </button>
                    <a href="index.php?module=cron&amp;view=manage" class="button negative">
                        <img src="images/cross.png" alt="{$LANG.cancel}" />{$LANG.cancel}
                    </a>
                </td>
            </tr>
        </table>
        <input type="hidden" name="op" value="create" />
    </form>
{/if}


