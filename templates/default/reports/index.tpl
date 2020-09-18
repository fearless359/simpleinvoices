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
            {$LANG.statement_of_invoices}
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
        <a href="index.php?module=reports&amp;view=report_sales_total" class="">
            <img src="../../../images/money.png" alt=""/>
            {$LANG.total_sales}
        </a>
        <a href="index.php?module=reports&amp;view=report_sales_by_periods" class="">
            <img src="../../../images/money.png" alt=""/>
            {$LANG.monthly_sales_per_year}
        </a>
        <a href="index.php?module=reports&amp;view=report_sales_customers_total" class="">
            <img src="../../../images/money.png" alt=""/>
            {$LANG.sales_by_customers}
        </a>
        <a href="index.php?module=reports&amp;view=report_net_income" class="">
            <img src="../../../images/money.png" alt=""/>
            <span>{$LANG.net_income_report}</span>
        </a>
        <a href="index.php?module=reports&amp;view=report_sales_by_representative" class="">
            <img src="../../../images/report_edit.png" alt=""/>
            <span>{$LANG.sales_by_representative}</span>
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
            <a href="index.php?module=reports&amp;view=report_invoice_profit" class="">
                <img src="../../../images/money.png" alt=""/>
                {$LANG.profit_per_invoice}
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
        <a href="index.php?module=reports&amp;view=report_tax_total" class="">
            <img src="../../../images/money_delete.png" alt=""/>
            {$LANG.total_taxes}
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
        <a href="index.php?module=reports&amp;view=report_products_sold_total" class="">
            <img src="../../../images/cart.png" alt=""/>
            {$LANG.product_sales}
        </a>
        <a href="index.php?module=reports&amp;view=report_products_sold_by_customer" class="">
            <img src="../../../images/cart.png" alt=""/>
            {$LANG.products_by_customer}
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
            $extensionInsertionFiles[idx].section == $before|cat:$LANG.biller_sales}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}

    <h2>{$LANG.biller_sales}</h2>
    <div class="si_toolbar">
        <a href="index.php?module=reports&amp;view=report_biller_total" class="">
            <img src="../../../images/user_suit.png" alt=""/>
            {$LANG.biller_sales}
        </a>
        <a href="index.php?module=reports&amp;view=report_biller_by_customer" class="">
            <img src="../../../images/user_suit.png" alt=""/>
            {$LANG.biller_sales_by_customer_totals} {* TODO change this - remove total *}
        </a>
        {if $performExtensionInsertions == true}
            {section name=idx loop=$extensionInsertionFiles}
                {if $extensionInsertionFiles[idx].module  == 'reports' &&
                $extensionInsertionFiles[idx].section == $LANG.biller_sales}
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
        <a href="index.php?module=reports&amp;view=report_debtors_by_amount" class="">
            <img src="../../../images/vcard.png" alt=""/>
            {$LANG.debtors_by_amount_owed}
        </a>
        <a href="index.php?module=reports&amp;view=report_debtors_by_aging" class="">
            <img src="../../../images/vcard.png" alt=""/>
            {$LANG.debtors_by_aging_periods}
        </a>
        <a href="index.php?module=reports&amp;view=report_debtors_owing_by_customer" class="">
            <img src="../../../images/vcard.png" alt=""/>
            {$LANG.total_owed_per_customer}
        </a>
        <a href="index.php?module=reports&amp;view=report_debtors_aging_total" class="">
            <img src="../../../images/vcard.png" alt=""/>
            {$LANG.total_by_aging_periods}
        </a>
        <a href="index.php?module=reports&amp;view=report_past_due" class="">
            <img src="../../../images/vcard.png" alt=""/>
            {$LANG.past_due_report}
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
        <h2>{$LANG.expenses_uc}</h2>
        <div class="si_toolbar">
            <a href="index.php?module=reports&amp;view=report_tax_vs_sales_by_period" class="">
                <img src="../../../images/money_delete.png" alt=""/>
                {$LANG.monthly_tax_summary_per_year}
            </a>
            <a href="index.php?module=reports&amp;view=report_expense_account_by_period" class="">
                <img src="../../../images/money_delete.png" alt=""/>
                {$LANG.expense_uc} {$LANG.accounts_uc} {$LANG.by} {$LANG.period_uc}
            </a>
            <a href="index.php?module=reports&amp;view=report_summary" class="">
                <img src="../../../images/money_delete.png" alt=""/>
                {$LANG.expense_uc} {$LANG.account_uc} {$LANG.summary_uc}
            </a>
            {if $performExtensionInsertions == true}
                {section name=idx loop=$extensionInsertionFiles}
                    {if $extensionInsertionFiles[idx].module  == 'reports' &&
                    $extensionInsertionFiles[idx].section == $LANG.expenses_uc}
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
        <a href="index.php?module=reports&amp;view=report_database_log" class="">
            <img src="../../../images/database.png" alt=""/>
            {$LANG.database_log}
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
