<!--suppress HtmlFormInputWithoutLabel -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=reports&amp;view=reportSummary">
    <table class="center">
        <tr>
            <th>{$LANG.startDate}</th>
            <td><input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10"
                       name="start_date" id="date1" value='{if isset($start_date)}{$start_date}{/if}'/>
            </td>
            <td>
                &nbsp;
                &nbsp;
                &nbsp;
            </td>
            <th>{$LANG.endDate}</th>
            <td><input type="text" class="validate[required,custom[date],length[0,10]] date-picker" size="10"
                       name="end_date" id="date1" value='{if isset($end_date)}{$end_date}{/if}'/>
            </td>
        </tr>
    </table>
    <br/>
    <table class="center">
        <tr>
            <td>
                <button type="submit" class="positive" name="submit" value="{$LANG.insertBiller}">
                    <img class="button_img" src="../../../images/tick.png" alt=""/>
                    {$LANG.runReport}
                </button>

            </td>
        </tr>
    </table>
</form>
<div id="top">
    <h3>{$LANG.expenseUc} {$LANG.account} {$LANG.summary} {$LANG.for} {$LANG.the} {$LANG.period}
        {if isset($start_date)}{$start_date}{/if} to {if isset($end_date)}{$end_date}{/if}
    </h3>
</div>

<table class="center">
    <tr>
        <td class="details_screen">
            <b>{$LANG.accountUc}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.amountUc}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.tax}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.totalUc}</b>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.status}</b>
        </td>
    </tr>
    {foreach $accounts as $account}
        <tr>
            <td class="details_screen">
                {$account.account}
            </td>
            <td>
                &nbsp;
                &nbsp;
            </td>
            <td class="details_screen">
                {$account.expense|utilNumber}
            </td>
            <td>
                &nbsp;
                &nbsp;
            </td>
            <td class="details_screen">
                {if $account.tax != ""}
                    {$account.tax|utilNumber}
                {/if}
            </td>
            <td>
                &nbsp;
                &nbsp;
            </td>
            <td class="details_screen">
                {if $account.total != ""}
                    {$account.total|utilNumber}
                {else}
                    {$account.expense|utilNumber}
                {/if}
            </td>
            <td>
                &nbsp;
                &nbsp;
            </td>
            <td class="details_screen">
                {$account.status_wording}
            </td>
        </tr>
    {/foreach}
</table>

<div id="top"><h3>{$LANG.invoiceUc}/{$LANG.quoteUc} {$LANG.summary} {$LANG.for} {$LANG.the} {$LANG.period}
        {if isset($start_date)}{$start_date}{/if} {$LANG.to} {if isset($end_date)}{$end_date}{/if}</h3></div>

<table class="center">
    <tr>
        <td class="details_screen">
            <b>{$LANG.id}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.biller}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.customer}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.amountUc}</b>
        </td>
    </tr>
    {section name=invoice loop=$invoices}
        {if $invoices[invoice].preference != $invoices[invoice][index_prev].preference AND $smarty.section.invoice.index != 0}
            <tr>
                <td><br/></td>
            </tr>
        {/if}
        <tr>
            <td class="details_screen">{if isset($index)}{$index}{/if}
                {$invoices[invoice].preference}
                {$invoices[invoice].index_id}
            </td>
            <td>
                &nbsp;
                &nbsp;
            </td>
            <td class="details_screen">
                {$invoices[invoice].biller}
            </td>
            <td>
                &nbsp;
                &nbsp;
            </td>
            <td class="details_screen">
                {$invoices[invoice].customer}
            </td>
            <td>
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
                &nbsp;
            </td>
            <td class="details_screen">
                {$invoices[invoice].total|utilNumber}
            </td>
        </tr>
    {/section}
</table>


<div id="top">
    <h3>
        {$LANG.paymentUc} {$LANG.summary} {$LANG.for} {$LANG.the} {$LANG.period}
        {if isset($start_date)}{$start_date}{/if} to {if isset($end_date)}{$end_date}{/if}
    </h3>
</div>

<table class="center">
    <tr>
        <td class="details_screen">
            <b>{$LANG.id}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.biller}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.customer}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.type}</b>
        </td>
        <td>
            &nbsp;
            &nbsp;
        </td>
        <td class="details_screen">
            <b>{$LANG.amountUc}</b>
        </td>
    </tr>
    {foreach $payments as $payment}
        <tr>
            <td class="details_screen">
                {$payment.id}
            </td>
            <td></td>
            <td class="details_screen">
                {$payment.bname}
            </td>
            <td></td>
            <td class="details_screen">
                {$payment.cname}
            </td>
            <td></td>
            <td class="details_screen">
                {$payment.type}
            </td>
            <td>
                &nbsp;
                &nbsp;
            </td>
            <td class="details_screen">
                {$payment.ac_amount|utilNumber}
            </td>
        </tr>
    {/foreach}
</table>
    
