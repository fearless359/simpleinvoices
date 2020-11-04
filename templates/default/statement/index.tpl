<link rel="shortcut icon" href="images/favicon.ico"/>
<link rel="stylesheet" href="include/jquery/css/main.css">
<div class="center"><h1 class="si_center">{$LANG.statementOfInvoices}</h1></div>
{if $menu}
    <form name="frmpost" method="POST" id="frmpost"
          action="index.php?module=statement&amp;view=index">
        <div class="si_form si_form_search{if !isset($smarty.post.submit)} si_form_search_null{/if}">
            <table>
                <tr>
                    <th>
                        <label for="date1">{$LANG.startDate}:</label>
                    </th>
                    <td>
                        <input type="text" tabindex="10"
                               class="si_input validate[required,custom[date],length[0,10]] date-picker"
                               size="10" name="startDate" id="date1" value='{if isset($startDate)}{$startDate|htmlSafe}{/if}'/>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="date2">{$LANG.endDate}:</label>
                    </th>
                    <td>
                        <input type="text" tabindex="20"
                               class="si_input validate[required,custom[date],length[0,10]] date-picker"
                               size="10" name="endDate" id="date2" value='{if isset($endDate)}{$endDate|htmlSafe}{/if}'/>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="billerId">{$LANG.billerUc}:
                    </th>
                    <td>
                        <select name="billerId" id="billerId" class="si_input" tabindex="30">
                            {if $biller_count != 1}
                                <option value=""></option>
                            {/if}
                            {foreach $billers as $biller}
                                <option {if $biller.id==$billerId} selected {/if} value="{if isset($biller.id)}{$biller.id|htmlSafe}{/if}">
                                    {$biller.name|htmlSafe}
                                </option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="customerId">{$LANG.customerUc}:</label>
                    </th>
                    <td>
                        <select name="customerId" id="customerId" class="si_input" tabindex="40">
                            {if $customerCount != 1}
                                <option value=""></option>
                            {/if}
                            {foreach $customers as $customer}
                                <option {if $customer.id== $customerId} selected {/if} value="{if isset($customer.id)}{$customer.id|htmlSafe}{/if}">
                                    {$customer.name|htmlSafe}
                                </option>
                            {/foreach}
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="filterId">{$LANG.filterByDateRangeUc}:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="filterByDateRange" id="filterId" class="si_input" tabindex="50"
                                {if $filterByDateRange== "yes"} checked {/if} value="yes">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="unpaidId">{$LANG.showOnlyUnpaidInvoices}:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="showOnlyUnpaid" id="unpaidId" class="si_input" tabindex="60"
                                {if $showOnlyUnpaid== "yes"} checked {/if} value="yes">
                    </td>
                </tr>
            </table>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="positive" name="submit" value="statement_report">
                    <img class="button_img" src="images/tick.png" alt=""/>
                    {$LANG.runReport}
                </button>
            </div>
        </div>
    </form>
    {if isset($smarty.post.submit)}
        <div class="si_toolbar si_toolbar_top">
            <a title="{$LANG.printPreview}" target="_blank"
               href="index.php?module=statement&amp;view=export&amp;billerId={$billerId|urlencode}&amp;customerId={$customerId}&amp;startDate={$startDate|urlencode}&amp;endDate={$endDate|urlencode}&amp;showOnlyUnpaid={$showOnlyUnpaid|urlencode}&amp;filterByDateRange={$filterByDateRange|urlencode}&amp;format=print">
                <img src='images/printer.png' class='action' alt=""/>&nbsp;{$LANG.printPreview}
            </a>
            <!-- EXPORT TO PDF -->
            <a title="{$LANG.exportPdf}"
               href="index.php?module=statement&amp;view=export&amp;billerId={$billerId|urlencode}&amp;customerId={$customerId|urlencode}&amp;startDate={$startDate|urlencode}&amp;endDate={$endDate|urlencode}&amp;showOnlyUnpaid={$showOnlyUnpaid|urlencode}&amp;filterByDateRange={$filterByDateRange|urlencode}&amp;format=pdf">
                <img src='images/page_white_acrobat.png' class='action' alt=""/>&nbsp;{$LANG.exportPdf}
            </a>
            <a title="{$LANG.exportUc} {$LANG.exportXlsTooltip} .{$config.exportSpreadsheet} {$LANG.formatTooltip}"
               href="index.php?module=statement&amp;view=export&amp;billerId={$billerId|urlencode}&amp;customerId={$customerId|urlencode}&amp;startDate={$startDate|urlencode}&amp;endDate={$endDate|urlencode}&amp;showOnlyUnpaid={$showOnlyUnpaid|urlencode}&amp;filterByDateRange={$filterByDateRange|urlencode}&amp;format=file&amp;filetype={$config.exportSpreadsheet}">
                <img src='images/page_white_excel.png' class='action' alt=""/>&nbsp;{$LANG.exportAs}.{$config.exportSpreadsheet}
            </a>
            <a title="{$LANG.exportUc} {$LANG.exportDocTooltip} .{$config.exportWordProcessor} {$LANG.formatTooltip}"
               href="index.php?module=statement&amp;view=export&amp;billerId={$billerId|urlencode}&amp;customerId={$customerId|urlencode}&amp;startDate={$startDate|urlencode}&amp;endDate={$endDate|urlencode}&amp;showOnlyUnpaid={$showOnlyUnpaid|urlencode}&amp;filterByDateRange={$filterByDateRange|urlencode}&amp;format=file&amp;filetype={$config.exportWordProcessor}">
                <img src='images/page_white_word.png' class='action' alt=""/>&nbsp;{$LANG.exportAs}.{$config.exportWordProcessor}
            </a>
            <a title="{$LANG.email}"
               href="index.php?module=statement&amp;view=email&amp;stage=1&amp;billerId={$billerId|urlencode}&amp;customerId={$customerId|urlencode}&amp;startDate={$startDate|urlencode}&amp;endDate={$endDate|urlencode}&amp;showOnlyUnpaid={$showOnlyUnpaid|urlencode}&amp;filterByDateRange={$filterByDateRange|urlencode}&amp;format=file">
                <img src='images/mail-message-new.png' class='action' alt=""/>&nbsp;{$LANG.email}
            </a>
        </div>
    {/if}
{/if}
{if isset($smarty.post.submit) || $view == export}
    {if !$menu}
        <hr/>
    {/if}
    <table class="center">
        <tr>
            <th colspan="6"></th>
        </tr>
        <tr>
            <th class="left">{$LANG.billerUc}:</th>
            <td>{if empty($billerDetails.name)}{$LANG.allUc}{else}{$billerDetails.name|htmlSafe}{/if}</td>
            <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <th>{$LANG.totalUc}:</th>
            <td class="si_right">{$statement.total|utilCurrency}</td>
        </tr>
        <tr>
            <th class="left">{$LANG.customerUc}:</th>
            <td>{if empty($customerDetails.name)}{$LANG.allUc}{else}{$customerDetails.name|htmlSafe}{/if}</td>
            <td colspan="2"></td>
            <th>{$LANG.paidUc}:</th>
            <td class="si_right underline">{$statement.paid|utilCurrency}</td>
        </tr>
        <tr>
            <th colspan="5"></th>
            <td class="si_right">{$statement.owing|utilCurrency}</td>
        </tr>
        <tr><th>&nbsp;</th></tr>
        <tr>
            {if $filterByDateRange == "yes"}
                <th colspan="6">{$LANG.statementForThePeriod} {if isset($startDate)}{$startDate|htmlSafe}{/if} {$LANG.to} {if isset($endDate)}{$endDate|htmlSafe}{/if}</th>
            {/if}
        </tr>
        <tr><th>&nbsp;</th></tr>
    </table>
    <div class="si_list">
        <table class="center" style="width:100%;">
            <thead>
            <tr>
                <th>{$LANG.idUc}</th>
                <th>{$LANG.dateUc}</th>
                <th>{$LANG.billerUc}</th>
                <th>{$LANG.customerUc}</th>
                <th class="si_right">{$LANG.totalUc}</th>
                <th class="si_right">{$LANG.paidUc}</th>
                <th class="si_right">{$LANG.owingUc}</th>
            </tr>
            </thead>
            <tbody>
            {foreach $invoices as $invoice}
                {if $invoice@index > 0 && $invoice.preference != $invoices[$invoice@index - 1].preference}
                    <tr>
                        <td><br/></td>
                    </tr>
                {/if}
                <tr>
                    <td class="si_right">
                        {$invoice.preference|htmlSafe}
                        {$invoice.index_id|htmlSafe}
                    </td>
                    <td class="si_center">{$invoice.date|utilDate}</td>
                    <td>{$invoice.biller|htmlSafe}</td>
                    <td>{$invoice.customer|htmlSafe}</td>
                    {if $invoice.status > 0}
                        <td class="si_right">{$invoice.total|utilCurrency}</td>
                        <td class="si_right">{$invoice.paid|utilCurrency}</td>
                        <td class="si_right">{$invoice.owing|utilCurrency}</td>
                    {else}
                        <td class="si_right"><i>{$invoice.total|utilCurrency}</i></td>
                        <td colspan="2">&nbsp;</td>
                    {/if}
                </tr>
            {/foreach}
            </tbody>
            <tfoot>
            <tr>
                <td colspan=3></td>
                <th></th>
                <td class="si_right">{$statement.total|utilCurrency}</td>
                <td class="si_right">{$statement.paid|utilCurrency}</td>
                <td class="si_right">{$statement.owing|utilCurrency}</td>
            </tr>
            </tfoot>
        </table>
    </div>
{/if}
