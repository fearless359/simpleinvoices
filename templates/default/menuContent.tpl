
{*
 *  Script: menuContent.tpl
 *      Content for SI menu template
 *
 *  Last modified:
 *      20250901 by Richard Rowley to support duplicating content in a mobile menu.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
*}
{*<div class="flora">*}
    {if $includeHash == "yes"}
    <div class="{$menuClass}hash">
        <div class="{$menuClass}hash_line"></div>
        <div class="{$menuClass}hash_line"></div>
        <div class="{$menuClass}hash_line"></div>
        {/if}
        <div class="{$menuClass}dropdown">
            {$smarty.capture.hook_tabmenu_start}
            <ul>
                {$smarty.capture.hook_tabmenu_main_start}
                <li><a href="#home" class="{$menuClass}home bold" {if $includeHash == "yes"}onclick="addSubMenuDropdownActiveItem('{$menuClass}', 'home')"{/if}>{$LANG.home}</a></li>
                <li><a href="#money" class="{$menuClass}money bold" {if $includeHash == "yes"}onclick="addSubMenuDropdownActiveItem('{$menuClass}', 'money')"{/if}>{$LANG.money}</a></li>
                <li><a href="#people" class="{$menuClass}people bold" {if $includeHash == "yes"}onclick="addSubMenuDropdownActiveItem('{$menuClass}', 'people')"{/if}>{$LANG.people}</a></li>
                <li><a href="#product" class="{$menuClass}product bold" {if $includeHash == "yes"}onclick="addSubMenuDropdownActiveItem('{$menuClass}', 'product')"{/if}>{$LANG.productsUc}</a></li>
                <!-- SECTION:tabs -->
                {$smarty.capture.hook_tabmenu_main_end}
                <li class="menu__tab_setting"><a href="#setting" class="{$menuClass}setting bold" {if $includeHash == "yes"}onclick="addSubMenuDropdownActiveItem('{$menuClass}', 'setting')"{/if}>{$LANG.settingsUc}</a></li>
            </ul>
        </div>
        {if $includeHash == "yes"}
    </div>
    {/if}
    <!-- SECTION:home -->
    <div id="home" class="{$menuClass}sub_home">
        <div class="{$menuClass}sub_home_dropdown">
            <ul id="homeSubList" class=" fonts__size-1-5" onclick="closeSubMenuDropdown()">
                <li class="bold"><a {if isset($pageActive) && $pageActive=="dashboard"} class="active"{/if}
                            href="index.php?module=index&amp;view=index">{$LANG.dashboard} </a></li>
                <li class="bold"><a {if isset($pageActive) && $pageActive=="report"} class="active"{/if}
                            href="index.php?module=reports&amp;view=index">{$LANG.allReports} </a></li>
            </ul>
        </div>
    </div>
    <!-- SECTION:money -->
    <div id="money" class="{$menuClass}sub_money">
        <div class="{$menuClass}sub_money_dropdown">
            <ul id="moneySubList" class="fonts__size-1-5" onclick="closeSubMenuDropdown()">
                <!-- SECTION:invoices -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="invoice"} class="active"{/if}
                            href="index.php?module=invoices&amp;view=manage">{$LANG.invoicesUc}</a></li>
                {if isset($subPageActive)}
                    {if $subPageActive=="invoiceCreate" }
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                    {elseif $subPageActive=="invoiceEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                    {elseif $subPageActive=="invoiceView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view} </a></li>
                    {/if}
                {/if}
                <!-- SECTION:expense -->
                {if $defaults.expense == $smarty.const.ENABLED}
                    <!-- SECTION:expense_accounts -->
                    <li class="bold"><a {if isset($pageActive) && $pageActive=="expenseAccount"}class="active"
                                        {/if}href="index.php?module=expense_account&amp;view=manage">{$LANG.expenseAccounts}</a>
                    </li>
                    {if isset($subPageActive)}
                        {if $subPageActive=="expenseAccountCreate" }
                            <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                        {elseif $subPageActive=="expenseAccountEdit"}
                            <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                        {elseif $subPageActive=="expenseAccountView"}
                            <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                        {/if}
                    {/if}
                    <!-- SECTION:expenses -->
                    <li class="bold"><a {if isset($pageActive) && $pageActive=="expense"}class="active"
                                        {/if}href="index.php?module=expense&amp;view=manage">{$LANG.expensesUc}</a>
                    </li>
                    {if isset($subPageActive)}
                        {if $subPageActive=="expenseCreate" }
                            <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                        {elseif $subPageActive=="expenseEdit"}
                            <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                        {elseif $subPageActive=="expenseView"}
                            <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                        {elseif $subPageActive=="expenseDelete"}
                            <li><a class="active active_subpage" href="#">{$LANG.delete}</a></li>
                        {/if}
                    {/if}
                {/if}
                <!-- SECTION:payments -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="payment"}class="active"
                                    {/if}href="index.php?module=payments&amp;view=manage">{$LANG.payments}</a></li>
                {if isset($subPageActive)}
                    {if $subPageActive=="paymentProcess"}
                        <li><a class="active active_subpage" href="#">{$LANG.processUc}</a></li>
                    {elseif $subPageActive=="paymentEway"}
                        <li><a class="active active_subpage" href="#">{$LANG.eway}</a></li>
                    {elseif $subPageActive=="paymentFilterInvoice"}
                        <li><a class="active active_subpage"
                               href="#">{$LANG.paymentsFiltered} {$preference.pref_inv_wording|htmlSafe} {$smarty.get.id|htmlSafe}</a>
                        </li>
                    {elseif $subPageActive=="paymentFilterCustomer"}
                        <li><a class="active active_subpage" href="#">{$LANG.paymentsFilteredCustomer}
                                '{$customer.name}'</a></li>
                    {elseif $subPageActive=="paymentDelete"}
                        <li><a class="active active_subpage" href="#">{$LANG.delete}</a></li>
                    {/if}
                {/if}
                <!-- SECTION:payment_warehouse -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="paymentWarehouse"}class="active"
                                    {/if}href="index.php?module=payment_warehouse&amp;view=manage">{$LANG.paymentWarehouseUc}</a>
                </li>
                {if isset($subPageActive)}
                    {if $subPageActive=="create"}
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                    {elseif $subPageActive=="edit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                    {elseif $subPageActive=="view"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                    {elseif $subPageActive=="delete"}
                        <li><a class="active active_subpage" href="#">{$LANG.delete}</a></li>
                    {/if}
                {/if}
                <!-- SECTION:recurrence -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="cron"} class="active"{/if}
                            href="index.php?module=cron&amp;view=manage">{$LANG.recurrenceUc}</a></li>
                {if isset($subPageActive)}
                    {if $subPageActive=="cronCreate"}
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                    {elseif $subPageActive=="cronEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                    {elseif $subPageActive=="cronInvoiceItems"}
                        <li><a class="active active_subpage" href="#">{$LANG.invoiceUc}&nbsp;{$LANG.itemsUc}</a>
                        </li>
                    {elseif isset($subPageActive) && $subPageActive=="cronView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                    {elseif isset($subPageActive) && $subPageActive=="cronRenderInvoice"}
                        <li><a class="active active_subpage" href="#">{$LANG.renderInvoice}</a></li>
                    {/if}
                {/if}
            </ul>
        </div>
    </div>
    <!-- SECTION:people -->
    <div id="people" class="{$menuClass}sub_people">
        <div class="{$menuClass}sub_people_dropdown">
            <ul id="peopleSubList" class="fonts__size-1-5" onclick="closeSubMenuDropdown()">
                <!-- SECTION:billers -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="biller"}class="active"
                                    {/if}href="index.php?module=billers&amp;view=manage">{$LANG.billersUc}</a></li>
                {if isset($subPageActive)}
                    {if $subPageActive=="billerCreate"}
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                    {elseif $subPageActive=="billerEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                    {elseif $subPageActive=="billerView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                    {/if}
                {/if}
                <!-- SECTION:customers -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="customer"}class="active"
                                    {/if}href="index.php?module=customers&amp;view=manage">{$LANG.customersUc}</a>
                </li>
                {if isset($subPageActive)}
                    {if $subPageActive=="customerCreate"}
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                    {elseif $subPageActive=="customerEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                    {elseif $subPageActive=="customerView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                    {/if}
                {/if}
                <!-- SECTION:users -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="user"}class="active"
                                    {/if}href="index.php?module=user&amp;view=manage">{$LANG.users}</a></li>
                {if isset($subPageActive)}
                    {if $subPageActive=="userCreate"}
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                    {elseif $subPageActive=="userEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                    {elseif $subPageActive=="userView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                    {/if}
                {/if}
            </ul>
        </div>
    </div>
    <!-- SECTION:product -->
    <div id="product" class="{$menuClass}sub_product">
        <div class="{$menuClass}sub_product_dropdown">
            <ul id="productSubList" class="fonts__size-1-5" onclick="closeSubMenuDropdown()">
                <!-- SECTION:inventory -->
                {if $defaults.inventory}
                    <li class="bold"><a {if isset($pageActive) && $pageActive=="inventory"}class="active"
                                        {/if}href="index.php?module=inventory&amp;view=manage">{$LANG.inventory}</a>
                    </li>
                    {if isset($subPageActive)}
                        {if $subPageActive=="inventoryCreate"}
                            <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                        {elseif $subPageActive=="inventoryEdit"}
                            <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                        {elseif $subPageActive=="inventoryView"}
                            <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                        {/if}
                    {/if}
                {/if}
                <!-- SECTION:manage_products -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="product"}class="active"
                                    {/if}href="index.php?module=products&amp;view=manage">{$LANG.productsUc}</a>
                </li>
                {if isset($subPageActive)}
                    {if $subPageActive=="productCreate"}
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                    {elseif $subPageActive=="productEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                    {elseif $subPageActive=="productView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                    {/if}
                {/if}
                <!-- SECTION:product_attributes -->
                {if $defaults.product_attributes}
                    <li class="bold"><a {if isset($pageActive) && $pageActive=="productAttribute"}class="active"
                                        {/if}href="index.php?module=product_attribute&amp;view=manage">{$LANG.productAttributes}</a>
                    </li>
                    {if isset($subPageActive)}
                        {if $subPageActive=="productAttributeCreate"}
                            <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                        {elseif $subPageActive=="productAttributeEdit"}
                            <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                        {elseif $subPageActive=="productAttributeView"}
                            <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                        {/if}
                    {/if}
                {/if}
                <!-- SECTION:product_attribute_values -->
                {if $defaults.product_attributes}
                    <li class="bold"><a
                                {if isset($pageActive) && $pageActive=="productAttributeValues"}class="active"
                                {/if}href="index.php?module=product_attribute_values&amp;view=manage">{$LANG.productAttributeValues}</a>
                    </li>
                    {if isset($subPageActive)}
                        {if $subPageActive=="productAttributeValuesCreate"}
                            <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                        {elseif $subPageActive=="productAttributeValuesEdit"}
                            <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                        {elseif $subPageActive=="productAttributeValuesView"}
                            <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                        {/if}
                    {/if}
                {/if}
                <!-- SECTION:product_groups -->
                {if $defaults.product_groups}
                    <li class="bold"><a {if isset($pageActive) && $pageActive=="productGroups"}class="active"
                                        {/if}href="index.php?module=product_groups&amp;view=manage">{$LANG.productGroupsUc}</a>
                    </li>
                    {if isset($subPageActive)}
                        {if $subPageActive=="productGroupsCreate"}
                            <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                        {elseif $subPageActive=="productGroupsEdit"}
                            <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                        {elseif $subPageActive=="productGroupsView"}
                            <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                        {/if}
                    {/if}
                {/if}
            </ul>
        </div>
    </div>
    <!-- SECTION:setting -->
    <div id="setting" class="{$menuClass}sub_setting">
        <div class="{$menuClass}sub_setting_dropdown">
            <ul id="settingSubList" class="fonts__size-1-5" onclick="closeSubMenuDropdown()">
                <!-- SECTION:custom_fields -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="customFields"}class="active"
                                    {/if}href="index.php?module=custom_fields&amp;view=manage">{$LANG.customFieldsUc}</a>
                </li>
                {if isset($subPageActive)}
                    {if $subPageActive=="customFieldsEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                    {elseif $subPageActive=="customFieldsView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                    {/if}
                {/if}
                <!-- SECTION:custom_flags -->
                <li class="bold"><a {if isset($pageActive) && $pageActive == "customFlags"}class="active"
                                    {/if}href="index.php?module=custom_flags&amp;view=manage">{$LANG.customFlagsUc}</a>
                </li>
                {if isset($subPageActive)}
                    {if $subPageActive=="customFlagsEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                    {elseif $subPageActive=="customFlagsView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                    {/if}
                {/if}
                <!-- SECTION:customize -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="settings"}class="active"
                                    {/if}href="index.php?module=options&amp;view=index">{$LANG.customizeUc}</a></li>
                {if isset($subPageActive)}
                    {if $subPageActive=="settingsExtensions"}
                        <li><a class="active active_subpage" href="#">{$LANG.extensionsUc}</a></li>
                    {/if}
                {/if}
                <!-- SECTION:db_backup -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="backup"}class="active"
                                    {/if}href="index.php?module=options&amp;view=backup_database">{$LANG.dbBackup}</a>
                </li>
                <!-- SECTION:invoice_prefs -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="invPrefs"}class="active"
                                    {/if}href="index.php?module=preferences&amp;view=manage">{$LANG.invPrefs}</a>
                </li>
                {if isset($subPageActive)}
                    {if $subPageActive=="invPrefsCreate"}
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                    {elseif $subPageActive=="invPrefsEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                    {elseif $subPageActive=="invPrefsView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                    {/if}
                {/if}
                <!-- SECTION:payment_types -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="pymtTypes"}class="active"
                                    {/if}href="index.php?module=payment_types&amp;view=manage">{$LANG.pymtTypes}</a>
                </li>
                {if isset($subPageActive)}
                    {if $subPageActive=="pymtTypesCreate"}
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                    {elseif $subPageActive=="pymtTypesEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                    {elseif $subPageActive=="pymtTypesView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                    {/if}
                {/if}
                <!-- SECTION:si_defaults -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="siDefaults"}class="active"
                                    {/if}href="index.php?module=system_defaults&amp;view=manage">{$LANG.siDefaults}</a>
                </li>
                {if isset($subPageActive)}
                    {if $subPageActive=="siDefaultsEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                    {/if}
                {/if}
                <!-- SECTION:tax_rates -->
                <li class="bold"><a {if isset($pageActive) && $pageActive=="taxRates"}class="active"
                                    {/if}href="index.php?module=tax_rates&amp;view=manage">{$LANG.taxRates}</a></li>
                {if isset($subPageActive)}
                    {if $subPageActive=="taxRatesCreate"}
                        <li><a class="active active_subpage" href="#">{$LANG.addUc}</a></li>
                    {elseif $subPageActive=="taxRatesEdit"}
                        <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>
                    {elseif $subPageActive=="taxRatesView"}
                        <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>
                    {/if}
                {/if}
            </ul>
        </div>
    </div>
    <!-- SECTION:tabmenu_end -->
    {$smarty.capture.hook_tabmenu_end}
{*</div>*}
