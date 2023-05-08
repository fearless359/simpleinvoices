{*<div class="pad__left-2 pad__bottom-3">*}
{*<div class="grid__area">*}
<div class="grid__area">
    {assign var=before value='BEFORE '}
    {if $performExtensionInsertions == true}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'reports' &&
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.statements}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <div class="grid__report-menu-areas">
        <div class="grid__report-lbl">
            <h3>{$LANG.statements}:</h3>
        </div>
        {$gridReportItem = 1}
        {if in_array('reportStatement', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportStatement&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/money.png" alt=""/>{$LANG.statementOfInvoices}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.statements}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
    </div>
    {if $performExtensionInsertions == true}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'reports' &&
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.salesUc}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}
    <hr class="margin__top-2 margin__bottom-2"/>

    <div class="grid__report-menu-areas">
        <div class="grid__report-lbl">
            <h3>{$LANG.salesUc}:</h3>
        </div>
        {$gridReportItem = 1}
        {if in_array('reportSalesTotal', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportSalesTotal&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/money.png" alt=""/>{$LANG.totalSales}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if in_array('reportSalesByPeriods', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportSalesByPeriods&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/money.png" alt=""/>{$LANG.monthlySalesPerYear}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if in_array('reportSalesCustomersTotal', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportSalesCustomersTotal&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/money.png" alt=""/>{$LANG.salesByCustomers}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if in_array('reportNetIncome', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportNetIncome&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/money.png" alt=""/>{$LANG.netIncomeReport}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if in_array('reportSalesByRepresentative', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportSalesByRepresentative&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/money.png" alt=""/>{$LANG.salesByRepresentative}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.salesUc}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
    </div>
    <hr class="margin__top-2 margin__bottom-2"/>

    {if $defaults.inventory == $smarty.const.ENABLED}
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $before|cat:$LANG.profitUc}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
        <div class="grid__report-menu-areas">
            <div class="grid__report-lbl">
                <h3>{$LANG.profitUc}:</h3>
            </div>
            {$gridReportItem = 1}
            {if in_array('reportInvoiceProfit', $reports)}
                <div class="grid__report-itm-{$gridReportItem}">
                    <a href="index.php?module=reports&amp;view=reportInvoiceProfit&amp;showAllReports={$showAllReports}" class="button square">
                        <img src="images/money.png" alt=""/>{$LANG.profitPerInvoice}
                    </a>
                </div>
                {$gridReportItem = $gridReportItem + 1}
            {/if}
            {if $performExtensionInsertions == true}
                {section name=idx loop=$extensionInsertionFiles}
                    {if $extensionInsertionFiles[idx].module  == 'reports' &&
                    $extensionInsertionFiles[idx].section == $LANG.debtors}
                        {include file=$extensionInsertionFiles[idx].file}
                    {/if}
                {/section}
            {/if}
        </div>
        <hr class="margin__top-2 margin__bottom-2"/>
    {/if}

    {if $performExtensionInsertions == true}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'reports' &&
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.tax}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <div class="grid__report-menu-areas">
        <div class="grid__report-lbl">
            <h3>{$LANG.tax}:</h3>
        </div>
        {$gridReportItem = 1}
        {if in_array('reportTaxTotal', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportTaxTotal&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/money_delete.png" alt=""/>{$LANG.totalTaxes}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.tax}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
    </div>
    <hr class="margin__top-2 margin__bottom-2"/>

    {if $performExtensionInsertions == true}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'reports' &&
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.productsUc}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <div class="grid__report-menu-areas">
        <div class="grid__report-lbl">
            <h3>{$LANG.productsUc}:</h3>
        </div>
        {$gridReportItem = 1}
        {if in_array('reportProductsSoldTotal', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportProductsSoldTotal&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/cart.png" alt=""/>{$LANG.productSales}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if in_array('reportProductsSoldByCustomer', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportProductsSoldByCustomer&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/cart.png" alt=""/>{$LANG.productsByCustomer}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.productsUc}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
    </div>
    <hr class="margin__top-2 margin__bottom-2"/>

    {if $performExtensionInsertions == true}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'reports' &&
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.billerSales}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <div class="grid__report-menu-areas">
        <div class="grid__report-lbl">
            <h3>{$LANG.billerSales}:</h3>
        </div>
        {$gridReportItem = 1}
        {if in_array('reportBillerTotal', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportBillerTotal&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/user_suit.png" alt=""/>{$LANG.billerSales}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if in_array('reportBillerByCustomer', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportBillerByCustomer&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/user_suit.png" alt=""/>{$LANG.billerSalesByCustomer}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.billerSales}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
    </div>
    <hr class="margin__top-2 margin__bottom-2"/>

    {if $performExtensionInsertions == true}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'reports' &&
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.debtors}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <div class="grid__report-menu-areas">
        <div class="grid__report-lbl margin__top-2">
            <h3>{$LANG.debtors}:</h3>
        </div>
        {$gridReportItem = 1}
        {if in_array('reportDebtorsByAmount', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportDebtorsByAmount&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/vcard.png" alt=""/>{$LANG.debtorsByAmountOwed}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if in_array('reportDebtorsByAging', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportDebtorsByAging&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/vcard.png" alt=""/>{$LANG.debtorsByAgingPeriods}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if in_array('reportDebtorsOwingByCustomer', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportDebtorsOwingByCustomer&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/vcard.png" alt=""/>{$LANG.totalOwedPerCustomer}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if in_array('reportDebtorsAgingTotal', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportDebtorsAgingTotal&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/vcard.png" alt=""/>{$LANG.totalByAgingPeriods}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if in_array('reportPastDue', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportPastDue&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/vcard.png" alt=""/>{$LANG.pastPueReport}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.debtors}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
    </div>
    <hr class="margin__top-2 margin__bottom-2"/>

    {if $defaults.expense == $smarty.const.ENABLED}
        <div class="grid__report-menu-areas">
            <div class="grid__report-lbl">
                <h3>{$LANG.expensesUc}:</h3>
            </div>
            {$gridReportItem = 1}
            {if in_array('reportTaxVsSalesByPeriod', $reports)}
                <div class="grid__report-itm-{$gridReportItem}">
                    <a href="index.php?module=reports&amp;view=reportTaxVsSalesByPeriod&amp;showAllReports={$showAllReports}" class="button square">
                        <img src="images/money_delete.png" alt=""/>{$LANG.monthlyTaxSummaryPerYear}
                    </a>
                </div>
                {$gridReportItem = $gridReportItem + 1}
            {/if}
            {if in_array('reportExpenseAccountByPeriod', $reports)}
                <div class="grid__report-itm-{$gridReportItem}">
                    <a href="index.php?module=reports&amp;view=reportExpenseAccountByPeriod&amp;showAllReports={$showAllReports}" class="button square">
                        <img src="images/money_delete.png" alt=""/>{$LANG.expenseUc} {$LANG.accountsUc} {$LANG.by} {$LANG.periodUc}
                    </a>
                </div>
                {$gridReportItem = $gridReportItem + 1}
            {/if}
            {if in_array('reportExpenseSummary', $reports)}
                <div class="grid__report-itm-{$gridReportItem}">
                    <a href="index.php?module=reports&amp;view=reportExpenseSummary&amp;showAllReports={$showAllReports}" class="button square">
                        <img src="images/money_delete.png" alt=""/>{$LANG.expenseUc} {$LANG.accountUc} {$LANG.summaryUc}
                    </a>
                </div>
                {$gridReportItem = $gridReportItem + 1}
            {/if}
            {if $performExtensionInsertions == true}
                {section name=idx loop=$extensionInsertionFiles}
                    {if $extensionInsertionFiles[idx].module  == 'reports' &&
                    $extensionInsertionFiles[idx].section == $LANG.expensesUc}
                        {include file=$extensionInsertionFiles[idx].file}
                    {/if}
                {/section}
            {/if}
        </div>
        <hr class="margin__top-2 margin__bottom-2"/>
    {/if}

    {if $performExtensionInsertions == true}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'reports' &&
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.otherUc}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <div class="grid__report-menu-areas">
        <div class="grid__report-lbl">
            <h3>{$LANG.otherUc}:</h3>
        </div>
        {$gridReportItem = 1}
        {if in_array('reportDatabaseLog', $reports)}
            <div class="grid__report-itm-{$gridReportItem}">
                <a href="index.php?module=reports&amp;view=reportDatabaseLog&amp;showAllReports={$showAllReports}" class="button square">
                    <img src="images/database.png" alt=""/>{$LANG.databaseLog}
                </a>
            </div>
            {$gridReportItem = $gridReportItem + 1}
        {/if}
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.otherUc}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
    </div>
    <hr class="margin__top-2 margin__bottom-2"/>
</div>
