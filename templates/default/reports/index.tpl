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

    <div class="flex__container flex__start">
        <h3>{$LANG.statements}:</h3>
        {if in_array('reportStatement', $reports)}
            <a href="index.php?module=reports&amp;view=reportStatement&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/money.png" alt="" class="desktopOnly"/>{$LANG.statementOfInvoices}
            </a>
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
            {if $extensionInsertionFiles[idx].module  == 'reports' &
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.salesUc}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <hr class="margin__top-2 margin__bottom-2"/>

    <div class="flex__container flex__start">
        <h3>{$LANG.salesUc}:</h3>
        {if in_array('reportSalesTotal', $reports)}
            <a href="index.php?module=reports&amp;view=reportSalesTotal&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/money.png" alt="" class="desktopOnly"/>{$LANG.totalSales}
            </a>
        {/if}
        {if in_array('reportSalesByPeriods', $reports)}
            <a href="index.php?module=reports&amp;view=reportSalesByPeriods&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/money.png" alt="" class="desktopOnly"/>{$LANG.monthlySalesPerYear}
            </a>
        {/if}
        {if in_array('reportSalesCustomersTotal', $reports)}
            <a href="index.php?module=reports&amp;view=reportSalesCustomersTotal&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/money.png" alt="" class="desktopOnly"/>{$LANG.salesByCustomers}
            </a>
        {/if}
        {if in_array('reportNetIncome', $reports)}
            <a href="index.php?module=reports&amp;view=reportNetIncome&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/money.png" alt="" class="desktopOnly"/>{$LANG.netIncomeReport}
            </a>
        {/if}
        {if in_array('reportSalesByRepresentative', $reports)}
            <a href="index.php?module=reports&amp;view=reportSalesByRepresentative&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/money.png" alt="" class="desktopOnly"/>{$LANG.salesByRepresentative}
            </a>
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
        <div class="flex__container flex__start">
            <h3>{$LANG.profitUc}:</h3>
            {if in_array('reportInvoiceProfit', $reports)}
                <a href="index.php?module=reports&amp;view=reportInvoiceProfit&amp;showAllReports={$showAllReports}"
                   class="button square">
                    <img src="images/money.png" alt="" class="desktopOnly"/>{$LANG.profitPerInvoice}
                </a>
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

    <div class="flex__container flex__start">
        <h3>{$LANG.tax}:</h3>
        {if in_array('reportTaxTotal', $reports)}
            <a href="index.php?module=reports&amp;view=reportTaxTotal&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/money_delete.png" alt="" class="desktopOnly"/>{$LANG.totalTaxes}
            </a>
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

    <div class="flex__container flex__start">
        <h3>{$LANG.productsUc}:</h3>
        {if in_array('reportProductsSoldTotal', $reports)}
            <a href="index.php?module=reports&amp;view=reportProductsSoldTotal&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/cart.png" alt="" class="desktopOnly"/>{$LANG.productSales}
            </a>
        {/if}
        {if in_array('reportProductsSoldByCustomer', $reports)}
            <a href="index.php?module=reports&amp;view=reportProductsSoldByCustomer&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/cart.png" alt="" class="desktopOnly"/>{$LANG.productsByCustomer}
            </a>
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

    <div class="flex__container flex__start">
        <h3>{$LANG.billerSales}:</h3>
        {if in_array('reportBillerTotal', $reports)}
            <a href="index.php?module=reports&amp;view=reportBillerTotal&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/user_suit.png" alt="" class="desktopOnly"/>{$LANG.billerSales}
            </a>
        {/if}
        {if in_array('reportBillerByCustomer', $reports)}
            <a href="index.php?module=reports&amp;view=reportBillerByCustomer&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/user_suit.png" alt="" class="desktopOnly"/>{$LANG.billerSalesByCustomer}
            </a>
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

    <div class="flex__container flex__start">
        <h3>{$LANG.debtors}:</h3>
        {if in_array('reportDebtorsByAmount', $reports)}
            <a href="index.php?module=reports&amp;view=reportDebtorsByAmount&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/vcard.png" alt="" class="desktopOnly"/>{$LANG.debtorsByAmountOwed}
            </a>
        {/if}
        {if in_array('reportDebtorsByAging', $reports)}
            <a href="index.php?module=reports&amp;view=reportDebtorsByAging&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/vcard.png" alt="" class="desktopOnly"/>{$LANG.debtorsByAgingPeriods}
            </a>
        {/if}
        {if in_array('reportDebtorsOwingByCustomer', $reports)}
            <a href="index.php?module=reports&amp;view=reportDebtorsOwingByCustomer&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/vcard.png" alt="" class="desktopOnly"/>{$LANG.totalOwedPerCustomer}
            </a>
        {/if}
        {if in_array('reportDebtorsAgingTotal', $reports)}
            <a href="index.php?module=reports&amp;view=reportDebtorsAgingTotal&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/vcard.png" alt="" class="desktopOnly"/>{$LANG.totalByAgingPeriods}
            </a>
        {/if}
        {if in_array('reportPastDue', $reports)}
            <a href="index.php?module=reports&amp;view=reportPastDue&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/vcard.png" alt="" class="desktopOnly"/>{$LANG.pastPueReport}
            </a>
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
        <div class="flex__container flex__start">
            <h3>{$LANG.expensesUc}:</h3>
            {if in_array('reportTaxVsSalesByPeriod', $reports)}
                <a href="index.php?module=reports&amp;view=reportTaxVsSalesByPeriod&amp;showAllReports={$showAllReports}"
                   class="button square">
                    <img src="images/money_delete.png" alt="" class="desktopOnly"/>{$LANG.monthlyTaxSummaryPerYear}
                </a>
            {/if}
            {if in_array('reportExpenseAccountByPeriod', $reports)}
                <a href="index.php?module=reports&amp;view=reportExpenseAccountByPeriod&amp;showAllReports={$showAllReports}"
                   class="button square">
                    <img src="images/money_delete.png" alt=""
                         class="desktopOnly"/>{$LANG.expenseUc} {$LANG.accountsUc} {$LANG.by} {$LANG.periodUc}
                </a>
            {/if}
            {if in_array('reportExpenseSummary', $reports)}
                <a href="index.php?module=reports&amp;view=reportExpenseSummary&amp;showAllReports={$showAllReports}"
                   class="button square">
                    <img src="images/money_delete.png" alt="" class="desktopOnly"/>{$LANG.expenseUc} {$LANG.accountUc} {$LANG.summaryUc}
                </a>
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

    <div class="flex__container flex__start">
        <h3>{$LANG.otherUc}:</h3>
        {if in_array('reportDatabaseLog', $reports)}
            <a href="index.php?module=reports&amp;view=reportDatabaseLog&amp;showAllReports={$showAllReports}"
               class="button square">
                <img src="images/database.png" alt="" class="desktopOnly"/>{$LANG.databaseLog}
            </a>
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
