<div class="si_defer_display container">
    <div id="si_header">
        {$smarty.capture.hook_topmenu_start}
        {if !empty($smarty.capture.hook_topmenu_section01_replace)}
            {$smarty.capture.hook_topmenu_section01_replace}
        {else}
            <div class="si_wrap">
                <!-- SECTION:help -->
                {$LANG.hello} {$smarty.session.username|htmlSafe} |
                <a href="index.php?module=si_info&amp;view=index">{$LANG.aboutUc}</a> |
                <a href="https://simpleinvoices.group" target="_blank" style="color:white;" title="SimpleInvoices Group">{$LANG.help}</a>
                <!-- SECTION:auth -->
                {if $config.authenticationEnabled == $smarty.const.ENABLED} |
                    {if isset($smarty.session.id)}
                        <a href="index.php?module=auth&amp;view=logout">{$LANG.logout}</a>
                        {if $smarty.session.domain_id != 1} | Domain: {$smarty.session.domain_id} - {$smarty.session.domain_name}{/if}
                    {else}
                        <a href="index.php?module=auth&amp;view=login">{$LANG.login}</a>
                    {/if}
                {/if}
            </div>
        {/if}
        {$smarty.capture.hook_topmenu_end}
    </div>
    <div id="tabmenu" class="flora si_wrap">
        {$smarty.capture.hook_tabmenu_start}
        <ul>
            {$smarty.capture.hook_tabmenu_main_start}
            <li><a href="#home"><span class="bold">{$LANG.home}</span></a></li>
            <li><a href="#money"><span class="bold">{$LANG.money}</span></a></li>
            <li><a href="#people"><span class="bold">{$LANG.people}</span></a></li>
            <li><a href="#product"><span class="bold">{$LANG.productsUc}</span></a></li>
            <!-- SECTION:tabs -->
            {$smarty.capture.hook_tabmenu_main_end}
            <li id="si_tab_settings"><a href="#setting"><span class="bold">{$LANG.settingsUc}</span></a></li>
        </ul>
        <!-- SECTION:home -->
        <div id="home">
            <ul class="subnav">
                <li class="bold"><a {if isset($pageActive) && $pageActive== "dashboard"} class="active"{/if} href="index.php?module=index&amp;view=index">{$LANG.dashboard} </a></li>
                <li class="bold"><a {if isset($pageActive) && $pageActive== "report"} class="active"{/if} href="index.php?module=reports&amp;view=index">{$LANG.allReports} </a></li>
            </ul>
        </div>
        <!-- SECTION:money -->
        <div id="money">
            <ul class="subnav">
                <!-- SECTION:invoices -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "invoice"} class="active"{/if} href="index.php?module=invoices&amp;view=manage">{$LANG.invoicesUc}</a></li>
                {if isset($subPageActive) && $subPageActive == "invoiceCreate" }
                    <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "invoiceEdit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "invoiceView"}
                    <li><a class="active active_subpage" href="#">{$LANG.view} </a></li>{/if}
                <!-- SECTION:expense -->
                {if $defaults.expense == $smarty.const.ENABLED}
                    <!-- SECTION:expense_accounts -->
                    <li class="bold"><a {if isset($pageActive) && $pageActive== "expenseAccount"}class="active" {/if}href="index.php?module=expense_account&amp;view=manage">{$LANG.expenseAccounts}</a></li>
                    {if isset($subPageActive) && $subPageActive == "expenseAccountCreate" }
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "expenseAccountEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "expenseAccountView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                    <!-- SECTION:expenses -->
                    <li class="bold"><a {if isset($pageActive) && $pageActive == "expense"}class="active" {/if}href="index.php?module=expense&amp;view=manage">{$LANG.expensesUc}</a></li>
                    {if isset($subPageActive) && $subPageActive == "expenseCreate" }
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "expenseEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "expenseView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                {/if}
                <!-- SECTION:payments -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "payment"}class="active" {/if}href="index.php?module=payments&amp;view=manage">{$LANG.payments}</a></li>
                {if isset($subPageActive) && $subPageActive == "paymentProcess"}
                    <li><a class="active active_subpage" href="#">{$LANG.processUc}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "paymentEway"}
                    <li><a class="active active_subpage" href="#">{$LANG.eway}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "paymentFilterInvoice"}
                    <li><a class="active active_subpage" href="#">{$LANG.paymentsFiltered} {$preference.pref_inv_wording|htmlSafe} {$smarty.get.id|htmlSafe}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "paymentFilterCustomer"}
                    <li><a class="active active_subpage" href="#">{$LANG.paymentsFilteredCustomer} '{$customer.name}'</a></li>{/if}
                <!-- SECTION:recurrence -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "cron"} class="active"{/if} href="index.php?module=cron&amp;view=manage">{$LANG.recurrence}</a></li>
                {if isset($subPageActive) && $subPageActive == "cronCreate"}
                    <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "cronEdit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "cronView"}
                    <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
            </ul>
        </div>
        <!-- SECTION:people -->
        <div id="people">
            <ul class="subnav">
                <!-- SECTION:billers -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "biller"}class="active" {/if}href="index.php?module=billers&amp;view=manage">{$LANG.billersUc}</a></li>
                {if isset($subPageActive) && $subPageActive == "billerCreate"}
                    <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "billerEdit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "billerView"}
                    <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                <!-- SECTION:customers -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "customer"}class="active" {/if}href="index.php?module=customers&amp;view=manage">{$LANG.customersUc}</a></li>
                {if isset($subPageActive) && $subPageActive == "customerCreate"}
                    <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "customerEdit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "customerView"}
                    <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                <!-- SECTION:users -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "user"}class="active" {/if}href="index.php?module=user&amp;view=manage">{$LANG.users}</a></li>
                {if isset($subPageActive) && $subPageActive == "userCreate"}
                    <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "userEdit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "userView"}
                    <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
            </ul>
        </div>
        <!-- SECTION:product -->
        <div id="product">
            <ul class="subnav">
                <!-- SECTION:inventory -->
                {if $defaults.inventory}
                    <li class="bold"><a {if isset($pageActive) && $pageActive== "inventory"}class="active" {/if}href="index.php?module=inventory&amp;view=manage">{$LANG.inventory}</a></li>
                    {if isset($subPageActive) && $subPageActive == "inventoryCreate"}
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "inventoryEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "inventoryView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                {/if}
                <!-- SECTION:manage_products -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "product"}class="active" {/if}href="index.php?module=products&amp;view=manage">{$LANG.productsUc}</a></li>
                {if isset($subPageActive) && $subPageActive == "productCreate"}
                    <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "productEdit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "productView"}
                    <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                <!-- SECTION:product_attributes -->
                {if $defaults.product_attributes}
                    <li class="bold"><a {if isset($pageActive) && $pageActive== "productAttribute"}class="active" {/if}href="index.php?module=product_attribute&amp;view=manage">{$LANG.productAttributes}</a></li>
                    {if isset($subPageActive) && $subPageActive == "productAttributeCreate"}
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "productAttributeEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "productAttributeView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                {/if}
                <!-- SECTION:product_groups -->
                {if $defaults.product_groups}
                    <li class="bold"><a {if isset($pageActive) && $pageActive== "productGroups"}class="active" {/if}href="index.php?module=product_groups&amp;view=manage">{$LANG.productGroupsUc}</a></li>
                    {if isset($subPageActive) && $subPageActive == "productGroupsCreate"}
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "productGroupsEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "productGroupsView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                {/if}
                <!-- SECTION:product_value -->
                {if $defaults.product_attributes}
                    <li class="bold"><a {if isset($pageActive) && $pageActive== "productValue"}class="active" {/if}href="index.php?module=product_value&amp;view=manage">{$LANG.productValues}</a></li>
                    {if isset($subPageActive) && $subPageActive == "productValueCreate"}
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "productValueEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "productValueView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                {/if}
            </ul>
        </div>
        <!-- SECTION:setting -->
        <div id="setting" style="float:right;">
            <ul class="subnav">
                <!-- SECTION:custom_fields -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "customFields"}class="active" {/if}href="index.php?module=custom_fields&amp;view=manage">{$LANG.customFieldsUc}</a></li>
                {if isset($subPageActive) && $subPageActive == "customFieldsEdit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "customFieldsView"}
                    <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                <!-- SECTION:custom_flags -->
                <li class="bold"><a {if isset($pageActive) && $pageActive == "customFlags"}class="active" {/if}href="index.php?module=custom_flags&amp;view=manage">{$LANG.customFlagsUc}</a></li>
                {if isset($subPageActive) && $subPageActive == "customFlagsEdit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "customFlagsView"}
                    <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                <!-- SECTION:customize -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "settings"}class="active" {/if}href="index.php?module=options&amp;view=index">{$LANG.customizeUc}</a></li>
                {if isset($subPageActive) && $subPageActive == "settingsExtensions"}
                    <li><a class="active active_subpage" href="#">{$LANG.extensionsUc}</a></li>{/if}
                <!-- SECTION:db_backup -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "backup"}class="active" {/if}href="index.php?module=options&amp;view=backup_database">{$LANG.dbBackup}</a></li>
                <!-- SECTION:invoice_prefs -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "invPrefs"}class="active" {/if}href="index.php?module=preferences&amp;view=manage">{$LANG.invPrefs}</a></li>
                {if isset($subPageActive) && $subPageActive == "invPrefsCreate"}
                    <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "invPrefsEdit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "invPrefsView"}
                    <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                <!-- SECTION:payment_types -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "pymtTypes"}class="active" {/if}href="index.php?module=payment_types&amp;view=manage">{$LANG.pymtTypes}</a></li>
                {if isset($subPageActive) && $subPageActive == "pymtTypesCreate"}
                    <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "pymtTypesEdit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "pymtTypesView"}
                    <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                <!-- SECTION:si_defaults -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "siDefaults"}class="active" {/if}href="index.php?module=system_defaults&amp;view=manage">{$LANG.siDefaults}</a></li>
                {if isset($subPageActive) && $subPageActive == "siDefaultsEdit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                <!-- SECTION:tax_rates -->
                <li class="bold"><a {if isset($pageActive) && $pageActive== "taxRates"}class="active" {/if}href="index.php?module=tax_rates&amp;view=manage">{$LANG.taxRates}</a></li>
                {if isset($subPageActive) && $subPageActive == "taxRatesCreate"}
                    <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "taxRatesEdit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "taxRatesView"}
                    <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
            </ul>
        </div>
        <!-- SECTION:tabmenu_end -->
        {$smarty.capture.hook_tabmenu_end}
    </div>
    {literal}
        <script>
            $(document).ready(function () {
                $("div.si_defer_display").removeClass('si_defer_display');
            });
        </script>
    {/literal}
</div>
