<div class="si_index si_index_reports">
    {assign var=before value='BEFORE '}
    {if $performExtensionInsertions == true}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'reports' &&
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.statements}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <h2>{$LANG.statements}<a id="statement" href=""></a></h2>
    <div class="si_toolbar">
        <a href="index.php?module=statement&amp;view=index" class="">
            <img src="../../../images/money.png" alt=""/>
            {$LANG.statementOfInvoices}
        </a>
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
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.sales}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <h2>{$LANG.sales}<a id="sales" href=""></a></h2>
    <div class="si_toolbar">
        <a href="index.php?module=reports&amp;view=reportSalesTotal" class="">
            <img src="../../../images/money.png" alt=""/>
            {$LANG.totalSales}
        </a>
        <a href="index.php?module=reports&amp;view=reportSalesByPeriods" class="">
            <img src="../../../images/money.png" alt=""/>
            {$LANG.monthlySalesPerYear}
        </a>
        <a href="index.php?module=reports&amp;view=reportSalesCustomersTotal" class="">
            <img src="../../../images/money.png" alt=""/>
            {$LANG.salesByCustomers}
        </a>
        <a href="index.php?module=reports&amp;view=reportNetIncome" class="">
            <img src="../../../images/money.png" alt=""/>
            <span>{$LANG.netIncomeReport}</span>
        </a>
        <a href="index.php?module=reports&amp;view=reportSalesByRepresentative" class="">
            <img src="../../../images/report_edit.png" alt=""/>
            <span>{$LANG.salesByRepresentative}</span>
        </a>

        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.sales}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
    </div>

    {if $defaults.inventory == $smarty.const.ENABLED}
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $before|cat:$LANG.profit}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
        <h2>{$LANG.profit}</h2>
        <div class="si_toolbar">
            <a href="index.php?module=reports&amp;view=reportInvoiceProfit" class="">
                <img src="../../../images/money.png" alt=""/>
                {$LANG.profitPerInvoice}
            </a>
            {if $performExtensionInsertions == true}
                {section name=idx loop=$extensionInsertionFiles}
                    {if $extensionInsertionFiles[idx].module  == 'reports' &&
                    $extensionInsertionFiles[idx].section == $LANG.debtors}
                        {include file=$extensionInsertionFiles[idx].file}
                    {/if}
                {/section}
            {/if}
        </div>
    {/if}

    {if $performExtensionInsertions == true}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'reports' &&
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.tax}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <h2>{$LANG.tax}</h2>
    <div class="si_toolbar">
        <a href="index.php?module=reports&amp;view=reportTaxTotal" class="">
            <img src="../../../images/money_delete.png" alt=""/>
            {$LANG.totalTaxes}
        </a>
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.tax}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
    </div>

    {if $performExtensionInsertions == true}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'reports' &&
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.products}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <h2>{$LANG.products}</h2>
    <div class="si_toolbar">
        <a href="index.php?module=reports&amp;view=reportProductsSoldTotal" class="">
            <img src="../../../images/cart.png" alt=""/>
            {$LANG.productSales}
        </a>
        <a href="index.php?module=reports&amp;view=reportProductsSoldByCustomer" class="">
            <img src="../../../images/cart.png" alt=""/>
            {$LANG.productsByCustomer}
        </a>
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.products}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
    </div>

    {if $performExtensionInsertions == true}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'reports' &&
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.billerSales}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <h2>{$LANG.billerSales}</h2>
    <div class="si_toolbar">
        <a href="index.php?module=reports&amp;view=reportBillerTotal" class="">
            <img src="../../../images/user_suit.png" alt=""/>
            {$LANG.billerSales}
        </a>
        <a href="index.php?module=reports&amp;view=reportBillerByCustomer" class="">
            <img src="../../../images/user_suit.png" alt=""/>
            {$LANG.billerSalesByCustomerTotals} {* TODO change this - remove total *}
        </a>
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.billerSales}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
    </div>

    {if $performExtensionInsertions == true}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'reports' &&
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.debtors}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <h2>{$LANG.debtors}</h2>
    <div class="si_toolbar">
        <a href="index.php?module=reports&amp;view=reportDebtorsByAmount" class="">
            <img src="../../../images/vcard.png" alt=""/>
            {$LANG.debtorsByAmountOwed}
        </a>
        <a href="index.php?module=reports&amp;view=reportDebtorsByAging" class="">
            <img src="../../../images/vcard.png" alt=""/>
            {$LANG.debtorsByAgingPeriods}
        </a>
        <a href="index.php?module=reports&amp;view=reportDebtorsOwingByCustomer" class="">
            <img src="../../../images/vcard.png" alt=""/>
            {$LANG.totalOwedPerCustomer}
        </a>
        <a href="index.php?module=reports&amp;view=reportDebtorsAgingTotal" class="">
            <img src="../../../images/vcard.png" alt=""/>
            {$LANG.totalByAgingPeriods}
        </a>
        <a href="index.php?module=reports&amp;view=reportPastDue" class="">
            <img src="../../../images/vcard.png" alt=""/>
            {$LANG.pastPueReport}
        </a>
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.debtors}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
    </div>

    {if $defaults.expense == $smarty.const.ENABLED}
        <h2>{$LANG.expensesUc}</h2>
        <div class="si_toolbar">
            <a href="index.php?module=reports&amp;view=reportTaxVsSalesByPeriod" class="">
                <img src="../../../images/money_delete.png" alt=""/>
                {$LANG.monthlyTaxSummaryPerYear}
            </a>
            <a href="index.php?module=reports&amp;view=reportExpenseAccountByPeriod" class="">
                <img src="../../../images/money_delete.png" alt=""/>
                {$LANG.expenseUc} {$LANG.accountsUc} {$LANG.by} {$LANG.periodUc}
            </a>
            <a href="index.php?module=reports&amp;view=reportSummary" class="">
                <img src="../../../images/money_delete.png" alt=""/>
                {$LANG.expenseUc} {$LANG.accountUc} {$LANG.summaryUc}
            </a>
            {if $performExtensionInsertions == true}
                {section name=idx loop=$extensionInsertionFiles}
                    {if $extensionInsertionFiles[idx].module  == 'reports' &&
                    $extensionInsertionFiles[idx].section == $LANG.expensesUc}
                        {include file=$extensionInsertionFiles[idx].file}
                    {/if}
                {/section}
            {/if}
        </div>
    {/if}

    {if $performExtensionInsertions == true}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'reports' &&
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.otherUc}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <h2>{$LANG.otherUc}</h2>
    <div class="si_toolbar">
        <a href="index.php?module=reports&amp;view=reportDatabaseLog" class="">
            <img src="../../../images/database.png" alt=""/>
            {$LANG.databaseLog}
        </a>
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.otherUc}
                    {include file=$extensionInsertionFiles[idx].file}
                {/if}
            {/section}
        {/if}
    </div>
</div>
