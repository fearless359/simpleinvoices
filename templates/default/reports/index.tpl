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
        <div class="grid__report-itm-1">
            <a href="index.php?module=reports&amp;view=reportStatement" class="button square">
                <img src="images/money.png" alt=""/>{$LANG.statementOfInvoices}
            </a>
        </div>
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
        <div class="grid__report-lbl margin__top-2">
            <h3>{$LANG.salesUc}:</h3>
        </div>
        <div class="grid__report-itm-1">
            <a href="index.php?module=reports&amp;view=reportSalesTotal" class="button square">
                <img src="images/money.png" alt=""/>{$LANG.totalSales}
            </a>
        </div>
        <div class="grid__report-itm-2">
            <a href="index.php?module=reports&amp;view=reportSalesByPeriods" class="button square">
                <img src="images/money.png" alt=""/>{$LANG.monthlySalesPerYear}
            </a>
        </div>
        <div class="grid__report-itm-3">
            <a href="index.php?module=reports&amp;view=reportSalesCustomersTotal" class="button square">
                <img src="images/money.png" alt=""/>{$LANG.salesByCustomers}
            </a>
        </div>
        <div class="grid__report-itm-4">
            <a href="index.php?module=reports&amp;view=reportNetIncome" class="button square">
                <img src="images/money.png" alt=""/>{$LANG.netIncomeReport}
            </a>
        </div>
        <div class="grid__report-itm-5">
            <a href="index.php?module=reports&amp;view=reportSalesByRepresentative" class="button square">
                <img src="images/money.png" alt=""/>{$LANG.salesByRepresentative}
            </a>
        </div>

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
            <div class="grid__report-itm-1">
                <a href="index.php?module=reports&amp;view=reportInvoiceProfit" class="button square">
                    <img src="images/money.png" alt=""/>{$LANG.profitPerInvoice}
                </a>
            </div>
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
        <div class="grid__report-itm-1">
            <a href="index.php?module=reports&amp;view=reportTaxTotal" class="button square">
                <img src="images/money_delete.png" alt=""/>{$LANG.totalTaxes}
            </a>
        </div>
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
        <div class="grid__report-itm-1">
            <a href="index.php?module=reports&amp;view=reportProductsSoldTotal" class="button square">
                <img src="images/cart.png" alt=""/>{$LANG.productSales}
            </a>
        </div>
        <div class="grid__report-itm-2">
            <a href="index.php?module=reports&amp;view=reportProductsSoldByCustomer" class="button square">
                <img src="images/cart.png" alt=""/>{$LANG.productsByCustomer}
            </a>
        </div>
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
        <div class="grid__report-itm-1">
            <a href="index.php?module=reports&amp;view=reportBillerTotal" class="button square">
                <img src="images/user_suit.png" alt=""/>{$LANG.billerSales}
            </a>
        </div>
        <div class="grid__report-itm-2">
            <a href="index.php?module=reports&amp;view=reportBillerByCustomer" class="button square">
                <img src="images/user_suit.png" alt=""/>{$LANG.billerSalesByCustomer}
            </a>
        </div>
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
        <div class="grid__report-itm-1">
            <a href="index.php?module=reports&amp;view=reportDebtorsByAmount" class="button square">
                <img src="images/vcard.png" alt=""/>{$LANG.debtorsByAmountOwed}
            </a>
        </div>
        <div class="grid__report-itm-2">
            <a href="index.php?module=reports&amp;view=reportDebtorsByAging" class="button square">
                <img src="images/vcard.png" alt=""/>{$LANG.debtorsByAgingPeriods}
            </a>
        </div>
        <div class="grid__report-itm-3">
            <a href="index.php?module=reports&amp;view=reportDebtorsOwingByCustomer" class="button square">
                <img src="images/vcard.png" alt=""/>{$LANG.totalOwedPerCustomer}
            </a>
        </div>
        <div class="grid__report-itm-4">
            <a href="index.php?module=reports&amp;view=reportDebtorsAgingTotal" class="button square">
                <img src="images/vcard.png" alt=""/>{$LANG.totalByAgingPeriods}
            </a>
        </div>
        <div class="grid__report-itm-5">
            <a href="index.php?module=reports&amp;view=reportPastDue" class="button square">
                <img src="images/vcard.png" alt=""/>{$LANG.pastPueReport}
            </a>
        </div>
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
            <div class="grid__report-itm-1">
                <a href="index.php?module=reports&amp;view=reportTaxVsSalesByPeriod" class="button square">
                    <img src="images/money_delete.png" alt=""/>{$LANG.monthlyTaxSummaryPerYear}
                </a>
            </div>
            <div class="grid__report-itm-2">
                <a href="index.php?module=reports&amp;view=reportExpenseAccountByPeriod" class="button square">
                    <img src="images/money_delete.png" alt=""/>{$LANG.expenseUc} {$LANG.accountsUc} {$LANG.by} {$LANG.periodUc}
                </a>
            </div>
            <div class="grid__report-itm-3">
                <a href="index.php?module=reports&amp;view=reportExpenseSummary" class="button square">
                    <img src="images/money_delete.png" alt=""/>{$LANG.expenseUc} {$LANG.accountUc} {$LANG.summaryUc}
                </a>
            </div>
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
        <div class="grid__report-itm-1">
            <a href="index.php?module=reports&amp;view=reportDatabaseLog" class="button square">
                <img src="images/database.png" alt=""/>{$LANG.databaseLog}
            </a>
        </div>
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
