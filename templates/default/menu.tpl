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
        <li><a href="#home"><span>{$LANG.home}</span></a></li>
        <li><a href="#money"><span>{$LANG.money}</span></a></li>
        <li><a href="#people"><span>{$LANG.people}</span></a></li>
        <li><a href="#product"><span>{$LANG.products}</span></a></li>
        <!-- SECTION:tabs -->
        {$smarty.capture.hook_tabmenu_main_end}
        <li id="si_tab_settings"><a href="#setting"><span>{$LANG.settingsUc}</span></a></li>
    </ul>
    <!-- SECTION:home -->
    <div id="home">
        <ul class="subnav">
            <li><a {if isset($pageActive) && $pageActive== "dashboard"} class="active"{/if} href="index.php?module=index&amp;view=index">{$LANG.dashboard} </a></li>
            <li><a {if isset($pageActive) && $pageActive== "report"} class="active"{/if} href="index.php?module=reports&amp;view=index">{$LANG.all_reports} </a></li>
        </ul>
    </div>
    <!-- SECTION:money -->
    <div id="money">
        <ul class="subnav">
            <!-- SECTION:invoices -->
            <li><a {if isset($pageActive) && $pageActive== "invoice"} class="active"{/if} href="index.php?module=invoices&amp;view=manage">{$LANG.invoices_uc}</a></li>
            {if isset($subPageActive) && $subPageActive == "invoice_edit"}
                <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "invoice_view"}
                <li><a class="active active_subpage" href="#">{$LANG.quick_view} </a></li>{/if}
            <!-- SECTION:expense -->
            {if $defaults.expense == $smarty.const.ENABLED}
                <!-- SECTION:expense_accounts -->
                <li><a {if isset($pageActive) && $pageActive== "expense_account"}class="active" {/if}href="index.php?module=expense_account&amp;view=manage">{$LANG.expense_accounts}</a></li>
                {if isset($pageActive) && $pageActive == "expense_account"}
                    {if isset($subPageActive) && $subPageActive == "edit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "view"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "create" }
                        <li><a class="active active_subpage" href="#">{$LANG.add }</a></li>{/if}
                {/if}
                <!-- SECTION:expenses -->
                <li><a {if isset($pageActive) && $pageActive == "expense"}class="active" {/if}href="index.php?module=expense&amp;view=manage">{$LANG.expenses_uc}</a></li>
                {if isset($pageActive) && $pageActive == "expense"}
                    {if isset($subPageActive) && $subPageActive == "edit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "view"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                    {if isset($subPageActive) && $subPageActive == "add" }
                        <li><a class="active active_subpage" href="#">{$LANG.add }</a></li>{/if}
                {/if}
            {/if}
            <!-- SECTION:payments -->
            <li><a {if isset($pageActive) && $pageActive== "payment"}class="active" {/if}href="index.php?module=payments&amp;view=manage">{$LANG.payments}</a></li>
            {if isset($subPageActive) && $subPageActive == "payment_process"}
                <li><a class="active active_subpage" href="#">{$LANG.process}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "payment_eway"}
                <li><a class="active active_subpage" href="#">{$LANG.eway}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "payment_filter_invoice"}
                <li><a class="active active_subpage" href="#">{$LANG.payments_filtered} {$preference.pref_inv_wording|htmlSafe} {$smarty.get.id|htmlSafe}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "payment_filter_customer"}
                <li><a class="active active_subpage" href="#">{$LANG.payments_filtered_customer} '{$customer.name}'</a></li>{/if}
            <!-- SECTION:recurrence -->
            <li><a {if isset($pageActive) && $pageActive== "cron"} class="active"{/if} href="index.php?module=cron&amp;view=manage">{$LANG.recurrence}</a></li>
            {if isset($subPageActive) && $subPageActive == "cron_add"}
                <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "cron_edit"}
                <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "cron_view"}
                <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
        </ul>
    </div>
    <!-- SECTION:people -->
    <div id="people">
        <ul class="subnav">
            <!-- SECTION:billers -->
            <li><a {if isset($pageActive) && $pageActive== "biller"}class="active" {/if}href="index.php?module=billers&amp;view=manage">{$LANG.billers}</a></li>
            {if isset($subPageActive) && $subPageActive == "biller_add"}
                <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "biller_view"}
                <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "biller_edit"}
                <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
            <!-- SECTION:customers -->
            <li><a {if isset($pageActive) && $pageActive== "customer"}class="active" {/if}href="index.php?module=customers&amp;view=manage">{$LANG.customers}</a></li>
            {if isset($subPageActive) && $subPageActive == "customer_add"}
                <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "customer_view"}
                <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "customer_edit"}
                <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
            <!-- SECTION:users -->
            <li><a {if isset($pageActive) && $pageActive== "user"}class="active" {/if}href="index.php?module=user&amp;view=manage">{$LANG.users}</a></li>
            {if isset($subPageActive) && $subPageActive == "user_add"}
                <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "user_view"}
                <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "user_edit"}
                <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
        </ul>
    </div>
    <!-- SECTION:product -->
    <div id="product">
        <ul class="subnav">
            <!-- SECTION:manage_products -->
            <li><a {if isset($pageActive) && $pageActive== "product_manage"}class="active" {/if}href="index.php?module=products&amp;view=manage">{$LANG.products}</a></li>
            {if isset($subPageActive) && $subPageActive == "product_view"}
                <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "product_edit"}
                <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
            <!-- SECTION:add_product -->
            <li><a {if isset($pageActive) && $pageActive== "product_add"}class="active" {/if}href="index.php?module=products&amp;view=add">{$LANG.add_product}</a></li>
            {if $defaults.inventory == $smarty.const.ENABLED}
                <li><a {if isset($pageActive) && $pageActive== "inventory"}class="active" {/if}href="index.php?module=inventory&amp;view=manage">{$LANG.inventory}</a></li>
                {if isset($subPageActive) && $subPageActive == "inventory_view"}
                    <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "inventory_edit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "inventory_add"}
                    <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
            {/if}
            <!-- SECTION:product_attributes -->
            {if $defaults.product_attributes}
                <li><a {if isset($pageActive) && $pageActive== "product_attribute_manage"}class="active" {/if}href="index.php?module=product_attribute&amp;view=manage">{$LANG.product_attributes}</a></li>
                {if isset($subPageActive) && $subPageActive == "product_attributes_view"}
                    <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "product_attributes_edit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "product_attributes_add"}
                    <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
                <li><a {if isset($pageActive) && $pageActive== "product_value_manage"}class="active" {/if}href="index.php?module=product_value&amp;view=manage">{$LANG.product_values}</a></li>
                {if isset($subPageActive) && $subPageActive == "product_value_view"}
                    <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "product_value_edit"}
                    <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
                {if isset($subPageActive) && $subPageActive == "product_value_add"}
                    <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
            {/if}
        </ul>
    </div>
    <!-- SECTION:setting -->
    <div id="setting" style="float:right;">
        <ul class="subnav">
            <!-- SECTION:custom_flags -->
            <li><a {if isset($pageActive) && $pageActive == "custom_flags"}class="active" {/if}href="index.php?module=custom_flags&amp;view=manage">{$LANG.custom_flags_uc}</a></li>
            {if isset($subPageActive) && $subPageActive == "custom_flags_view"}
                <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "custom_flags_edit"}
                <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
            <!-- SECTION:custom_fields -->
            <li><a {if isset($pageActive) && $pageActive== "custom_field"}class="active" {/if}href="index.php?module=custom_fields&amp;view=manage">{$LANG.custom_fields_uc}</a></li>
            {if isset($subPageActive) && $subPageActive == "custom_fields_view"}
                <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "custom_fields_edit"}
                <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
            <!-- SECTION:customizeSettings -->
            <li><a {if isset($pageActive) && $pageActive== "setting"}class="active" {/if}href="index.php?module=options&amp;view=index">{$LANG.customizeSettings}</a></li>
            {if isset($subPageActive) && $subPageActive == "setting_extensions"}
                <li><a class="active active_subpage" href="#">{$LANG.extensionsUc}</a></li>{/if}
            <!-- SECTION:db_backup -->
            <li><a {if isset($pageActive) && $pageActive== "backup"}class="active" {/if}href="index.php?module=options&amp;view=backup_database">{$LANG.db_backup}</a></li>
            <!-- SECTION:invoice_prefs -->
            <li><a {if isset($pageActive) && $pageActive== "preference"}class="active" {/if}href="index.php?module=preferences&amp;view=manage">{$LANG.inv_prefs}</a></li>
            {if isset($subPageActive) && $subPageActive == "preferences_add"}
                <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "preferences_view"}
                <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "preferences_edit"}
                <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
            <!-- SECTION:payment_types -->
            <li><a {if isset($pageActive) && $pageActive== "payment_type"}class="active" {/if}href="index.php?module=payment_types&amp;view=manage">{$LANG.pymt_types}</a></li>
            {if isset($subPageActive) && $subPageActive == "payment_types_add"}
                <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "payment_types_view"}
                <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "payment_types_edit"}
                <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
            <!-- SECTION:si_defaults -->
            <li><a {if isset($pageActive) && $pageActive== "system_default"}class="active" {/if}href="index.php?module=system_defaults&amp;view=manage">{$LANG.si_defaults}</a></li>
            <!-- SECTION:tax_rates -->
            <li><a {if isset($pageActive) && $pageActive== "tax_rate"}class="active" {/if}href="index.php?module=tax_rates&amp;view=manage">{$LANG.tax_rates}</a></li>
            {if isset($subPageActive) && $subPageActive == "tax_rates_add"}
                <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "tax_rates_view"}
                <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
            {if isset($subPageActive) && $subPageActive == "tax_rates_edit"}
                <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
        </ul>
    </div>
    <!-- SECTION:tabmenu_end -->
    {$smarty.capture.hook_tabmenu_end}
</div>
