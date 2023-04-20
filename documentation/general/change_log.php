<!DOCTYPE html>
<html lang="en">
<head>
    <title>SimpleInvoices - Change Log</title>
    <meta charset="UTF-8"/>
    <link rel="shortcut icon" href="../../images/favicon.ico"/>
    <link rel="stylesheet" href="../../css/main.css">
</head>
<body>
<div class="container">
    <h1 class="align__text-center margin__top-0-75">Change Log</h1>
    <div class="align__text-center margin__top-0-75">
        <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">
            <button>Return To Previous Screen</button>
        </a>
    </div>
    <br/>
    <div id="left">
        <ul>
            <li>2023-04-20 - <strong>2023.0.1</strong>
                <ul>
                    <li>Fixed multiple issues emanating from merge of globalization logic with 2023 version.</li>
                </ul>
            </li>
            <li>2023-04-20 - <strong>2023.0.0</strong>
                <ul>
                    <li>Updated to run on PHP 8.1 and above. This is a requirement.</li>
                    <li>Added template function for International Dates. See Template Date Formatting topic on
                        SimpleInvoice.group forum.</li>
                </ul>
            </li>
            <li>2023-04-20 - <strong>2020.4.10</strong>
                <ul>
                    <li>Use globalization for client side number formatting and verification.</li>
                    <li>Check for payment owing before allowing payment screen display.</li>
                    <li>Add logic to get inventory cost from product.</li>
                    <li>Added logic to delete expense records.</li>
                </ul>
            </li>
            <li>2023-03-10 - <strong>2020.4.8</strong>
                <ul>
                    <li>Fixed invoice line item deletion failure to remove associated taxes.</li>
                </ul>
            </li>
            <li>2023-02-07 - <strong>2020.4.7</strong>
                <ul>
                    <li>Updated to use HTMLPurifier library maintained via composer.</li>
                    <li>Update composer vendor files to current versions.</li>
                </ul>
            </li>
            <li>2022-12-20 - <strong>2020.4.6</strong>
                <ul>
                    <li>Added need to verify db user's access to server in &quot;Initial setup&quot; message.</li>
                </ul>
            </li>
            <li>2022-11-18 - <strong>2020.4.5</strong>
                <ul>
                    <li>Modified system defaults get functions to force int type value returned when appropriate.</li>
                </ul>
            </li>
            <li>2022-11-10 - <strong>2020.4.4</strong>
                <ul>
                    <li>Add invoice display days SI Defaults option for invoice display screen.</li>
                </ul>
            </li>
            <li>2022-11-08 - <strong>2020.4.3</strong>
                <ul>
                    <li>Add payment warehouse feature to SI.</li>
                    <li>Fix issue with update from 2019.2 to 2020.x</li>
                    <li>Update files for structure and full database update.</li>
                    <li>Replace jquery alert dialogs with Alertify.js alerts.</li>
                    <li>Enhance add payments to fill fields based on payment selection.</li>
                    <li>Allow payments to be deleted based on delete days option in SI Defaults.</li>
                    <li>Changed to use node jquery-ui library for ongoing maintenance.</li>
                </ul>
            </li>
            <li>2022-09-30 - <strong>2020.4.2</strong>
                <ul>
                    <li>Make invoice_item_id a key field in the si_invoice_item_tax table.</li>
                    <li>Add logic to support new invoice items for next new, cron generated,
                        itemized invoice.</li>
                </ul>
            </li>
            <li>2022-08-29 - <strong>2020.4.1</strong>
                <ul>
                    <li>Updated node library files.</li>
                    <li>Fixed logic for user screen change of user role to properly set list.</li>
                    <li>Miscellaneous clean up of function parameter declarations and comment syntax.
                        No functional change.</li>
                </ul>
            </li>
            <li>2022-08-10 - <strong>2020.4.0</strong>
                <ul>
                    <li>Updated to the latest version of Smarty template engine.</li>
                    <li>Correct logic that set invoice unity price when product changes.</li>
                </ul>
            </li>
            <li>2022-07-22 - <strong>2020.3.22</strong>
                <ul>
                    <li>Update trix library to resolve security issues.</li>
                </ul>
            </li>
            <li>2022-07-19 - <strong>2020.3.21</strong>
                <ul>
                    <li>Add Jewish holidays to holiday logos. See <strong>How To ...</strong> topic for use.</li>
                </ul>
            </li>
            <li>2022-07-16 - <strong>2020.3.20</strong>
                <ul>
                    <li>Fix issue with PDF invoice export not showing logo by:
                        <ol>
                            <li>Make logo a relative path rather than absolute.</li>
                            <li>Make holiday_logo function a UTIL function and set export logo via it.</li>
                            <li>Update mPDF version to most current.</li>
                            <li>Modify all invoice templates to use logo url directly from export.</li>
                            <li>Modify payment print logic to generate holiday logo for template.</li>
                            <li>Make smarty plugin, function.holiday_logo.php deprecated and made to do nothing.</li>
                        </ol>
                    </li>
                </ul>
            </li>
            <li>2022-04-20 - <strong>2020.3.19</strong>
                <ul>
                    <li>Update to version 1.13.1 of jquery-ui with Smoothness theme.</li>
                </ul>
            </li>
            <li>2022-04-16 - <strong>2020.3.18</strong>
                <ul>
                    <li>Fix test for template directory existing to use the relative path.</li>
                    <li>Add <strong>dueAmount</strong> to variables available for use on the template.
                        This is the amount due for the client whose invoice is being generated, less
                        the amount due for the actual invoice being reported.</li>
                </ul>
            </li>
            <li>2022-04-07 - <strong>2020.3.17</strong>
                <ul>
                    <li>Logic to detect multiple payments to the same invoice fixed.</li>
                </ul>
            </li>
            <li>2022-04-06 - <strong>2020.3.16</strong>
                <ul>
                    <li>Update npm and composer libraries.</li>
                </ul>
            </li>
            <li>2022-03-24 - <strong>2020.3.15</strong>
                <ul>
                    <li>Changes from redcullin were added to the master branch. They address conversion of
                        data in the json tables for DataTables to properly handle UTF-8 data from the
                        database.</li>
                </ul>
            </li>
            <li>2022-02-20 - <strong>2020.3.14</strong>
                <ul>
                    <li>Fixed issue with upgrade from master_2019.2 to master_2020 not working correctly.
                        It was not detecting that updates from the 319 patch were needed and kept trying
                        to install the essential data.</li>
                </ul>
            </li>
            <li>2021-11-04 - <strong>2020.3.13</strong>
                <ul>
                    <li>Composer vendor libraries update.</li>
                </ul>
            </li>
            <li>2021-11-02 - <strong>2020.3.12</strong>
                <ul>
                    <li>Modified default template to render foreign currency sign correctly
                        on all fields.</li>
                    <li>Add non-blank space to print_if_not_empty function following colon
                        on label field. This preserves space if right justifying the text.</li>
                </ul>
            </li>
            <li>2021-10-16 - <strong>2020.3.11</strong>
                <ul>
                    <li>Fixed logic the adds new items to the invoice edit item list.</li>
                </ul>
            </li>
            <li>2021-10-14 - <strong>2020.3.10</strong>
                <ul>
                    <li>Updated composer and node library files.</li>
                </ul>
            </li>
            <li>2021-10-11 - <strong>2020.3.09</strong>
                <ul>
                    <li>Fix issue with null parent id in customer record not processing on view screen.</li>
                    <li>Move about version information to the "About SimpleInvoices" screen.</li>
                </ul>
            </li>
            <li>2021-10-05 - <strong>2020.3.08</strong>
                <ul>
                    <li>Fix issue with "Filters" not working on invoice management page.</li>
                </ul>
            </li>
            <li>2021-09-30 - <strong>2020.3.07</strong>
                <ul>
                    <li>Add option to display department field on customer manage screen. If not set,
                        the customer's mobile phone will be displayed unless empty in which case the
                        phone field will be displayed.</li>
                </ul>
            </li>
            <li>2021-09-29 - <strong>2020.3.06</strong>
                <ul>
                    <li>Update node modules in particular DataTables to install a security update.</li>
                </ul>
            </li>
            <li>2021-09-23 - <strong>2020.3.05</strong>
                <ul>
                    <li>Update field label alignment in forms. Right justify for better appearance
                        and understandability.</li>
                </ul>
            </li>
            <li>2021-09-17 - <strong>2020.3.04</strong>
                <ul>
                    <li>Modified to use jQuery validate npm module for form validation. This
                        replaces the jQuery Validation Engine which is no longer updated save
                        for user submitted fixes.</li>
                    <li>Modified to use ToolTipster npm module for display of field help.
                        Primary advantage is that you hover help icon to view help and the
                        disappears automatically when you move it off the icon. This replaces
                        the cluetip library previously used.</li>
                    <li>Added PLACEHOLDERS array to $LANG file to provide field entry hints. Values
                        such as "country", should be customized to $LANG files other than en_US. Note
                        that these are just hints, they to not impose when the user enters.</li>
                </ul>
            </li>
            <li>2021-08-13 - <strong>2020.3.03</strong>
                <ul>
                    <li>Fix issue with multiple, semicolon separated email addresses.</li>
                </ul>
            </li>
            <li>2021-08-09 - <strong>2020.3.02</strong>
                <ul>
                    <li>Fixed issue with TOTAL STYLE invoice delete.</li>
                    <li>Changes to use new PHP features such as null coalescing operator, unhandled error THROWS,
                        etc.
                    </li>
                    <li>Changes all occurrences of die to exit just to be consistent.</li>
                    <li>Fixed customer credit card handling logic to both set and clear the field.</li>
                </ul>
            </li>
            <li>2021-07-08 - <strong>2020.3.01</strong>
                <ul>
                    <li>Removed unused items, emailhost, emailpassword, emailusername, pdfbottommargin,
                        pdfleftmargin, pdfpapersize, pdfrightmargin, pdfscreensize, pdftopmargin, spreadsheet and
                        wordprocessor, from the si_system_defaults table and associated setup files.
                    </li>
                    <li>Removed "company_name" from si_system_defaults table and from <em>LANG</em> files. Also
                        removed "helpCompanyName" from <em>LANG</em> files. The only reference to this was in
                        the <em>templates/default/header.tpl</em> file for the &lt;title&gt; tag. This
                        tag now references the "company_name_item" field from the si_system_defaults table.
                    </li>
                    <li>Cleaned up display of description fields on invoices and tax functionality on Total
                        style invoices.
                    </li>
                    <li>Cleaned up use of spelling for "itemized"/"itemised" settling on "itemized".</li>
                    <li>Eliminated all references to consulting and consulting language elements.</li>
                    <li>Modified create, edit, view and delete screens to use grid layout. Changes made
                        for: billers, cron, custom_field, custom_flags, customers, expense, system_defaults
                    </li>
                    <li>Added cell-border property to all DataTables table class list.</li>
                    <li>Modified customers view screen to use DataTables in the <strong>Customer Invoice Listing</strong>
                        tab and changed tab heading to <strong>Customer Invoices</strong>. Also removed the
                        <strong>Unpaid Invoices</strong> tab as the <strong>Customer Invoices</strong> table
                        contains them, and they can be sorted to the top.
                    </li>
                    <li>Fixed issue of expense edit duplicating si_expense_item_tax records.</li>
                    <li>All reports converted to use grid layout in selection criteria form.</li>
                    <li>Moved Statement Of Invoices report to the standard reports category.</li>
                </ul>
            </li>
            <li>2021-06-14 - <strong>2020.3.00</strong>
                <ul>
                    <li>Changed all invoices screens except manage, to use grid layout in place
                        of tables.
                    </li>
                    <li>Changed throughout to reference Product Attribute Values instead of
                        Product Values to better reflect the content being managed.
                    </li>
                    <li>Renamed si_products_values table to si_products_attributes_values.</li>
                    <li>Modified generation of product attributes for invoice items on the edit
                        invoice screen and display of selected values on the quick_view screen.
                    </li>
                    <li>Removed Consulting style invoice as upon further inspection found it was
                        a non-functional type of the Itemized style invoice.
                    </li>
                    <li>Modified new setup menu to use grid layout and new verbiage.</li>
                    <li>Shortened text in invoice print, payment, PDF, etc. buttons to make smaller.</li>
                </ul>
            </li>
            <li>2021-05-25 - <strong>2020.2.00</strong>
                <ul>
                    <li>Converted main.css file into multiple scss files. Includes
                        logic to build a compressed version of the main.css file
                        for improved load times.
                    </li>
                    <li>Updated to use grid layout for quick_view invoice. Includes
                        display enhancements for invoice item tables.
                    </li>
                    <li>Added Consulting type invoice to the types available when adding
                        a new invoice. Logic was always there, just wasn't an option
                        to switch to it.
                    </li>
                    <li>Updated npm and composer libraries for current versions.</li>
                    <li>Added responsive settings to all DataTables implemented on
                        manage screens.
                    </li>
                    <li>Updated reports to have standard background formatting for the
                        run report criteria sections.
                    </li>
                </ul>
            </li>
            <li>2021-05-10 - <strong>2020.1.00</strong>
                <ul>
                    <li>Css updates to move to a responsive layout. Tabs and body of screens updated.</li>
                    <li>Updates made to use responsive DataTables.</li>
                    <li>Additional changes to implement DataTables via Ajax in files using DataTables.</li>
                    <li>Vendor and node libraries updated.</li>
                    <li>Login screen updated for new effects.</li>
                    <li>Modified smarty functions empty and null to handle array parameters.</li>
                    <li>Smarty function merge address updated to support street parameter for output.</li>
                </ul>
            </li>
            <li>2021-04-28 - <strong>2020.0.21</strong>
                <ul>
                    <li>Updated smarty functions not empty vs not null to explain the difference.</li>
                </ul>
            </li>
            <li>2021-04-28 - <strong>2020.0.20</strong>
                <ul>
                    <li>Modified to stop display of menu tabs before screen is rendered.</li>
                    <li>Modified remaining management lists to use ajax to get list data.</li>
                </ul>
            </li>
            <li>2021-04-27 - <strong>2020.0.19</strong>
                <ul>
                    <li>Remove standard library duplicate template invoice plugins.</li>
                </ul>
            </li>
            <li>2021-04-22 - <strong>2020.0.18</strong>
                <ul>
                    <li>Change the Customer::managerTableInfo() function to specify return type.</li>
                    <li>Change http references to https in lang.php files.</li>
                </ul>
            </li>
            <li>2021-04-22 - <strong>2020.0.17</strong>
                <ul>
                    <li>Fix si_system_defaults management screen to display the selected value.</li>
                </ul>
            </li>
            <li>2021-04-13 - <strong>2020.0.16</strong>
                <ul>
                    <li>Add system defaults option, &quot;Invoice Description Open&quot; for state when
                        entering the new and edit invoice screens.
                    </li>
                </ul>
            </li>
            <li>2021-03-26 - <strong>2020.0.15</strong>
                <ul>
                    <li>Remove all language specific directories under <strong>documentation</strong> leaving
                        only a <strong>general</strong> directory. The information in the deleted files exists
                        in the topics in the <em>SimpleInvoices.group</em> forum.
                    </li>
                    <li>Clean up non-HTML5 code in files in the <strong>extras</strong> directory and deleted
                        the <strong>invoice_grouped</strong> example as out of date and example is actually
                        incorporated as the Product Groups options in SI. Also standardized name of
                        <strong>default_templates</strong> directory to <strong>defaultTemplates</strong>.
                    </li>
                    <li>Change to "subTotal" and "login" langauge items to be single words, <strong>Subtotal</strong>
                        and <strong>Login</strong>.
                    </li>
                    <li>Added generic font-family name to css usages.</li>
                    <li>Added <strong>Cancel</strong> buttons to <em>products</em> and <em>tax_rates</em> view screens.</li>
                </ul>
            </li>
            <li>2021-03-20 - <strong>2020.0.14</strong>
                <ul>
                    <li>Update node libraries</li>
                </ul>
            </li>
            <li>2021-03-19 - <strong>2020.0.13</strong>
                <ul>
                    <li>Check for error return from parse_ini_file while loading custom.config.php</li>
                </ul>
            </li>
            <li>2021-01-28 - <strong>2020.0.12</strong>
                <ul>
                    <li>Fix default payment type empty test.</li>
                    <li>Add support to build si_index record for new si_preferences (aka Inv Prefs) records.</li>
                    <li>Fix issue of field names not displaying on system_defaults edit screens.</li>
                    <li>Alter order of invoices on the manage list screen to sort by index_id within date. This
                        allows invoices in various numbering schemes to show up.
                    </li>
                </ul>
            </li>
            <li>2020-12-23 - <strong>2020.0.11</strong>
                <ul>
                    <li>Standardize date display format for yyyy-mm-dd in payment & expense view
                        and invoice quick view screens.
                    </li>
                </ul>
            </li>
            <li>2020-12-17 - <strong>2020.0.10</strong>
                <ul>
                    <li>Fix customer create template for parent id field default value.</li>
                    <li>Update libraries to current versions for datatables security issue.</li>
                </ul>
            </li>
            <li>2020-12-04 - <strong>2020.0.09</strong>
                <ul>
                    <li>Fix phpunit tests to pass full run.</li>
                </ul>
            </li>
            <li>2020-12-03 - <strong>2020.0.08</strong>
                <ul>
                    <li>Fix setting for system_defaults "delete" record to 0 rather than N.</li>
                    <li>Add additional phpunit tests.</li>
                </ul>
            </li>
            <li>2020-12-01 - <strong>2020.0.07</strong>
                <ul>
                    <li>Add updated nebraska invoice template and new cleverit template.</li>
                    <li>Add additional phpunit tests.</li>
                </ul>
            </li>
            <li>2020-11-09 - <strong>2020.0.06</strong>
                <ul>
                    <li>Remove duplicate config.ini parsing logic to allow .ini file
                        values to be enclosed within double quotes so special characters
                        can be used.
                    </li>
                    <li>Update vendor libraries to current versions</li>
                    <li>Update phpunit tests</li>
                    <li>Fixed smarty_function_do_tr naming issue that caused product custom
                        fields error in invoice templates.
                    </li>
                </ul>
            </li>
            <li>2020-11-04 - <strong>2020.0.05</strong>
                <ul>
                    <li>Update formatting of Log output.</li>
                    <li>Get shell scripts in lang directory working.</li>
                </ul>
            </li>
            <li>2020-11-03 - <strong>2020.0.04</strong>
                <ul>
                    <li>Set correct path for tpl images.</li>
                </ul>
            </li>
            <li>2020-10-26 - <strong>2020.0.03</strong>
                <ul>
                    <li>Fix cron api issues.</li>
                    <li>Remove unused product_consulting.tpl, add_invoice_item.tpl and
                        add_invoice_item.php.
                    </li>
                </ul>
            </li>
            <li>2020-10-22 - <strong>2020.0.02</strong>
                <ul>
                    <li>Fix quantity on invoice items not allowing a fraction (ie: 1.5)</li>
                </ul>
            </li>
            <li>2020-10-16 - <strong>2020.0.01</strong>
                <ul>
                    <li>Add extension check for report template path.</li>
                    <li>Fix issue with emailing invoice pdfs.</li>
                </ul>
            </li>
            <li>2020-10-15 - <strong>2020.0.00</strong>
                <ul>
                    <li>New master branch based on PHP 7.4 standard.</li>
                    <li>Removed mini and measurement extensions. Mini didn't work and measurement
                        is a template type, not an extension.
                    </li>
                    <li>Incorporated the sub-customer and invoice-grouped (aka product-groups) into
                        the standard program. See options in SI Defaults.
                    </li>
                    <li>Removed flexigrid images and moved used images to the images directory.</li>
                    <li>Converted config.php and custom.config.php file to config.ini and custom.config.ini
                        files respectively.
                    </li>
                    <li>Added program to perform one time conversion of the custom.config.php file on
                        existing installations into the new custom.config.ini format file.
                    </li>
                    <li>Updated all reports to use new framework that provides print, export and
                        email options.
                    </li>
                </ul>
            </li>
            <li>2020-08-05 - <strong>2019.2.30</strong>
                <ul>
                    <li>Change PdoDb class to handle information_schema column and table names
                        to force them to be lower case. This is for UNIX like systems that
                        enforce file name case sensitivity.
                    </li>
                    <li>Modified all manage.tpl templates the use DataTables to set the
                        deferred render to enhance performance with larger tables.
                    </li>
                </ul>
            </li>
            <li>2020-07-15 - <strong>2019.2.29</strong>
                <ul>
                    <li>Fixed display of status in Pymt Types view screen.</li>
                </ul>
            </li>
            <li>2020-07-13 - <strong>2019.2.28</strong>
                <ul>
                    <li>Use dbStd function to convert amount input on invoice items or total
                        input for Total style invoices to fix issue of amounts not storing
                        correctly in the database.
                    </li>
                    <li>Corrected new Total Style invoice handling of more than one tax item
                        for the invoice.
                    </li>
                </ul>
            </li>
            <li>2020-06-24 - <strong>2019.2.27</strong>
                <ul>
                    <li>Modified to correctly generate pdf, xls and doc files for invoices.</li>
                    <li>Update vendor libraries to current versions for PHP 7.2x and above.</li>
                </ul>
            </li>
            <li>2020-06-16 - <strong>2019.2.26</strong>
                <ul>
                    <li>Modified code to handle multiple TO and BCC email addresses using a
                        semicolon, ";", to separate each address.
                    </li>
                    <li>Added logic to report invalid email addresses in the FROM, TO and BCC
                        fields as errors to the user rather than throwing an error that never
                        displayed.
                    </li>
                </ul>
            </li>
            <li>2020-06-03 - <strong>2019.2.25</strong>
                <ul>
                    <li>Modified the table lists for all screens to use formatting in javascript
                        rather than php code to enhance performance.
                    </li>
                    <li>Added "local.currency" code field to the config file to support properly
                        formatting currency fields for non-invoice items such as costs for
                        customers, and unit price for products. Note that invoice and payment
                        tables get currency formatting information from the "Inv Prefs" link
                        in the Settings tab.
                    </li>
                </ul>
            </li>
            <li>2020-05-16 - <strong>2019.2.24</strong>
                <ul>
                    <li>Updated the Dutch (nl_NL) language files using changes made by an SI user.</li>
                </ul>
            </li>
            <li>2020-05-06 - <strong>2019.2.23</strong>
                <ul>
                    <li>Cleaned up the total style invoice edit screen input and display logic.</li>
                </ul>
            </li>
            <li>2020-04-01 - <strong>2019.2.22</strong>
                <ul>
                    <li>Fixed logic for adding and deleting invoice item lines, and to
                        fill in the unit price field when a new product is selected.
                    </li>
                    <li>Cleaned up the expense handling and display logic.</li>
                </ul>
            </li>
            <li>2020-03-09 - <strong>2019.2.21</strong>
                <ul>
                    <li>Fixed issue of adding tax rate didn't return to correct screen.</li>
                </ul>
            </li>
            <li>2020-02-16 - <strong>2019.2.20</strong>
                <ul>
                    <li>Modified Net Income Report to add customer selection feature.</li>
                </ul>
            </li>
            <li>2020-02-07 - <strong>2019.2.19</strong>
                <ul>
                    <li>Modified getCustomerPastDue() function to add option to include
                        or exclude a specified invoice.
                    </li>
                </ul>
            </li>
            <li>2020-01-26 - <strong>2019.2.18</strong>
                <ul>
                    <li>Fix error of set_aging setting not being read properly from database resulting
                        in no aging information appearing on the invoice table.
                    </li>
                </ul>
            </li>
            <li>2020-01-26 - <strong>2019.2.17</strong>
                <ul>
                    <li>Added domain name to top bar in addition to domain id currently shown
                        when logged in as a user for a domain other than 1.
                    </li>
                </ul>
            </li>
            <li>2020-01-26 - <strong>2019.2.16</strong>
                <ul>
                    <li>Fixed issue with adding a new user.</li>
                </ul>
            </li>
            <li>2020-01-23 - <strong>2019.2.15</strong>
                <ul>
                    <li>Added set_aging field to the si_preferences table to control setting
                        of aging information on invoices. Previously, aging information was
                        updated only if the si_preferences pref_id was 1.
                    </li>
                </ul>
            </li>
            <li>2020-01-14 - <strong>2019.2.14</strong>
                <ul>
                    <li>Fixed Add new row in invoice edit to leave detail description showing or
                        hidden per the state of the other rows.
                    </li>
                    <li>Fixed invoice view templates and invoice output templates to correctly
                        display currency system enter using HTML code (aka Pound and Euro)
                    </li>
                </ul>
            </li>
            <li>2019-12-10 - <strong>2019.2.13</strong>
                <ul>
                    <li>To include nebraska invoice template in the standard application.</li>
                </ul>
            </li>
            <li>2019-11-28 - <strong>2019.2.12</strong>
                <ul>
                    <li>Fix the templates/default/cron/add.tpl file domain_id hidden field.</li>
                </ul>
            </li>
            <li>2019-11-25 - <strong>2019.2.11</strong>
                <ul>
                    <li>Set customer default_invoice empty value to 0 so matched field type.</li>
                </ul>
            </li>
            <li>2019-10-15 - <strong>2019.2.10</strong>
                <ul>
                    <li>Add logic to clean up DataTables json file if present.</li>
                </ul>
            </li>
            <li>2019-10-08 - <strong>2019.2.9</strong>
                <ul>
                    <li>Allow equal sign (=) in the config.ini value.</li>
                </ul>
            </li>
            <li>2019-10-07 - <strong>2019.2.8</strong>
                <ul>
                    <li>Add pref_id field to the Inv Prefs screens.</li>
                </ul>
            </li>
            <li>2019-10-06 - <strong>2019.2.7</strong>
                <ul>
                    <li>Add email secure parameter to Swift Mailer transport class.</li>
                </ul>
            </li>
            <li>2019-09-29 - <strong>2019.2.6</strong>
                <ul>
                    <li>Add LOG messages to better follow email requests.</li>
                    <li>Report thrown exceptions from Email methods.</li>
                </ul>
            </li>
            <li>2019-09-16 - <strong>2019.2.5</strong>
                <ul>
                    <li>Fix prePatch308() result test to allow si_invoice_item_attachments creation if not present.</li>
                </ul>
            </li>
            <li>2019-09-16 - <strong>2019.2.4</strong>
                <ul>
                    <li>Fix setting of no default tax on new product to NULL.</li>
                </ul>
            </li>
            <li>2019-09-13 - <strong>2019.2.3</strong>
                <ul>
                    <li>Modify sql patch 317 to set auto increment on si_payment_types table.</li>
                    <li>Update database structure and full definition files to reflect current settings.</li>
                </ul>
            </li>
            <li>2019-09-08 - <strong>2019.2.2</strong>
                <ul>
                    <li>Modify patch management to add si_invoice_item_attachments to database if it doesn't exist.</li>
                </ul>
            </li>
            <li>2019-07-18 - <strong>2019.2.1</strong>
                <ul>
                    <li>Fix issue of Invoice of getAllHavings selection logic.</li>
                    <li>Fix date range logic in Sales Report By Period.</li>
                </ul>
            </li>
            <li>2019-07-02 - <strong>2019.2.1</strong>
                <ul>
                    <li>Fix payment to update invoice aging info and remove debug stmt from pdf.</li>
                </ul>
            </li>
            <li>2019-06-15 - <strong>2019.2.1</strong>
                <ul>
                    <li>Modified english email filter to allow multiple emails separated by a semicolon.</li>
                </ul>
            </li>
            <li>2019-06-13 - <strong>2019.2.1</strong>
                <ul>
                    <li>Changed to format amounts in lists per localLocale setting in the custom.config.ini file.</li>
                </ul>
            </li>
            <li>2019-05-31 - <strong>2019.2.1</strong>
                <ul>
                    <li>Fixed logic for cron when both the email biller and customer options are set to yes.</li>
                </ul>
            </li>
            <li>2019-05-21 - <strong>2019.2.1</strong>
                <ul>
                    <li>Fixed to set domain_id for invoice created by recurrence to value from original invoice.</li>
                    <li>Fixed setting of updated aging fields that array_merge() missed.</li>
                    <li>Remove Invoice::getInvoice() method so new standard method Invoice::getOne() is used.</li>
                </ul>
            </li>
            <li>2019-05-11 - <strong>2019.2.1</strong>
                <ul>
                    <li>Changed to use ZendFramework1 from composer, not library.</li>
                </ul>
            </li>
            <li>2019-04-24 - <strong>2019.2.0</strong>
                <ul>
                    <li>Converted all DB tables to InnoDB using utf8 charset and uft8_unicode_ci collation.</li>
                    <li>Add foreign keys to si_invoices.</li>
                    <li>Remove foreign key checks in code as they will now occur automatically.</li>
                    <li>Modified si_cron delete to also delete associated si_cron_log history records.</li>
                    <li>Modified Customer manage page to only show default invoice button if customer is enabled.</li>
                    <li>Modified empty database setup - 20190507</li>
                </ul>
            </li>
            <li>2019-03-21 - <strong>2019.1.1</strong>
                <ul>
                    <li>Remove last use of ZendDb ($zendDb), replacing it with PdoDb.</li>
                    <li>Use ajax for customer and product tables.</li>
                    <li>Update Symphony and related files.</li>
                </ul>
            </li>
            <li>2019-02-21 - <strong>2019.1.0</strong>
                <ul>
                    <li>Major update. Email with PDF attachment replaced with SwiftMailer.</li>
                </ul>
            </li>
            <li>2019-02-17 - <strong>2019.0.2</strong>
                <ul>
                    <li>Correct cron api generation of new invoices copied in total from old invoice.</li>
                </ul>
            </li>
            <li>2019-02-15 - <strong>2019.0.1</strong>
                <ul>
                    <li>Fixed issue where adding item to invoice does not blank the description field.</li>
                    <li>Order product list to show enabled products before disabled.</li>
                </ul>
            </li>
            <li>2019-02-11 - <strong>2019.0.0</strong>
                <ul>
                    <li>Renamed namespace_autoload branch to master_2019</li>
                </ul>
            </li>
            <li>2019-02-07 - <strong>2018.3.5</strong>
                <ul>
                    <li>Changed submit button name in tpl files to NOT be 'id'. They are now
                        named 'submit'. The name 'id' should be reserved across the board for
                        an actual field name of 'id' (typically not present or hidden). Otherwise,
                        it can trip PdoDb logic that by default builds commands from screen fields.
                        In the case of add templates, this means the value of the submit button
                        was being used for the auto-increment 'id' field.
                    </li>
                    <li>Fixed user maintenance logic to use correct domain that was preventing updates.</li>
                    <li>Change all occurrences of redirect_redirect to refresh_redirect for consistency.</li>
                </ul>
            </li>
            <li>2019-01-25 - <strong>2018.3.4</strong>
                <ul>
                    <li>Fixed display of customer invoices and invoices owing on Customer view screen.</li>
                </ul>
            </li>
            <li>2019-01-18 - <strong>2018.3.3</strong>
                <ul>
                    <li>Fixed the export button (aka acrobat button) in the invoices list action
                        column. It wasn't displaying update the export option list when selected.
                    </li>
                    <li>Fixed the section show hide options on the invoice quick view, itemized entry
                        and details edit screens.
                    </li>
                </ul>
            </li>
            <li>2018-12-30 - <strong>2018.3.2</strong>
                <ul>
                    <li>Modified invoices and payments datatables to use ajax to speed it up.</li>
                </ul>
            </li>
            <li>2018-12-26 - <strong>2018.3.1</strong>
                <ul>
                    <li>Modified to use current version of jquery and jquery-ui files. Using
                        NPM to obtain current version of jquery.
                    </li>
                    <li>Replaced Flexigrid tables with Datatables. Datatables version is
                        maintained via NPM. NOTE: The table logic is not yet tuned for performance.
                        This means the process is noticeably slower the more records it has to load.
                        In the future, large tables will be converted to use ajax to load a page of
                        records at a time in much the same way flexigrid tables worked.
                    </li>
                    <li>Replaced out of date WYSIWYG rich text editor with the trix-master rich text
                        editor.
                    </li>
                    <li>Modified labels in Settings menu to stop wrap around when enhancements
                        displayed "System Preferences" to "SI Defaults", "Invoice Preferences" to
                        "Inv Preferences", "Payment Types" to "Pymt Types", and "Backup Database"
                        to "DB Backup".
                    </li>
                    <li>Eliminated dynamic javascript for field validation (validation.php) and
                        replaced with use of jQueryValidationEngine logic throughout.
                    </li>
                </ul>
            </li>
            <li>2018-12-03 - <strong>2018.3.0</strong>
                <ul>
                    <li>Modified templates using a <em>&lt;button ...&gt;</em> tag to cancel so that
                        it uses an anchor tag to exit the page. This makes all maintenance template
                        use the same means to cancel the update/add/delete.
                    </li>
                    <li>Cleaned up all PDF and SI errors and warnings issued when E_ALL error
                        mode is set. There is an exceptions for Smarty OPTIONS and CYCLE commands
                        (and possibly others) that attempt to find these functions in the compiled
                        (sysplugins) directory, but they are not compiled and are in the plugins
                        directory. This warning is issued when the template using them is first
                        accessed and compiled. Subsequent access does not report an error. Issue
                        reported to Smarty team for hopeful cleanup in a future version.
                    </li>
                    <li>Changed to use namespace for class autoloading and add phpunit test support.</li>
                    <li>Eliminated "htmlout" template function. Use "outHtml" instead.</li>
                    <li>Moved all global functions to classes. Many are in the Util class.</li>
                </ul>
            </li>
            <li>2018-11-12 - <strong>2018.2.6</strong>
                <ul>
                    <li>Added options to the "type" parameter of the Invoices::select_all() method to
                        provide a total owing by itself or combined with the count of invoices. The
                        combined feature is to minimize the data access overhead.
                    </li>
                </ul>
            </li>
            <li>2018-10-27 - <strong>2018.2.5</strong>
                <ul>
                    <li>Fixed issue of default invoice values not populating the new invoice.</li>
                </ul>
            </li>
            <li>2018-10-20 - <strong>2018.2.4</strong>
                <ul>
                    <li>Move the default invoice extension to the main application. This includes
                        adding default_invoice field to the customer file. When the patch is
                        loaded and the module is enabled, the content of custom_field4 will be
                        copied to the new field.
                    </li>
                    <li>Moved CustomField.php and BackupDb.php files to the include/class directory.</li>
                    <li>Add help information to all the fields on the System Preferences screen. Also
                        modified toggle field to show Enabled/Disabled text rather than 1/0 on the list screen.
                    </li>
                    <li>Deleted the text_ui extension as not needed, wasn't working and unmaintained.</li>
                </ul>
            </li>
            <li>2018-10-19 - <strong>2018.2.3</strong>
                <ul>
                    <li>Add sales_representative field to the invoices table and add maintenance
                        support for it. If the inv_custom_field_report extension is enabled, copy
                        the content of the invoice custom_field3 to the new sales_representative
                        field and move the inv_custom_field_report extension to the standard
                        application as Sales by Representative.
                    </li>
                    <li>Added Custom Flags button to System Settings screen.</li>
                </ul>
            </li>
            <li>2018-10-17 - <strong>2018.2.2</strong>
                <ul>
                    <li>Added owing to "invoices" table to fix aging issue.</li>
                    <li>Fix inventory sales profit report selection logic.</li>
                </ul>
            </li>
            <li>2018-10-16 - <strong>2018.2.1</strong>
                <ul>
                    <li>Modified templates using obsolete number_formatted function to use the
                        updated siLocal_number function. This change fixes display of numbers formatted
                        for the locale set in the custom.config.ini file.
                    </li>
                </ul>
            </li>
            <li>2018-10-15 - <strong>2018.2.0</strong>
                <ul>
                    <li>Added interactive calculation of invoice aging. This means:
                        <ol>
                            <li>That when the list of invoices are selected to display in the flexigrid
                                list on the primary invoice screen, the aging information does not have
                                to be calculated thus improving performance.
                            </li>
                            <li>Removal of the Large Dataset system preference option as this change
                                eliminates need for its suppression of aging information calculation.
                            </li>
                        </ol>
                    </li>
                </ul>
            </li>
            <li>2018-10-12 - <strong>2018.1.3</strong>
                <ul>
                    <li>Added logic to create and/or update the custom.config.ini file. If the file
                        doesn't exist, it will be created by making a copy from the config.ini. If
                        it does exist, it will be updated as follows:
                        <ol>
                            <li>If line is in config.ini but not custom.config.ini, it will be added
                                to custom.config.ini.
                            </li>
                            <li>If line is in custom.config.ini but not in config.ini, it will be
                                enclosed in a "Possibly deprecated" comment lines.
                            </li>
                            <li>The version name and date will be set to the value in the config.ini
                                file.
                            </li>
                        </ol>
                    </li>
                </ul>
            </li>
            <li>2018-10-04 - <strong>2018.1.2</strong>
                <ul>
                    <li>2018-10-06 - Minor fix. Modified new installations to go to Start Working screen after
                        required biller, customer and product have been set up. Previously went to the
                        list screen of the last of the required items set up (typically Products).
                    </li>
                    <li>Moved SQL patch management logic into a new SqlPatchManager class. This change
                        deprecates the sql_patches.php file which is left as an empty file to force it
                        to overload the previous version of the file.
                    </li>
                    <li>Used addslashes() to fix issue where single quotes in essential_data.json file
                        sql_statement entries stopped data from being loaded.
                    </li>
                </ul>
            </li>
            <li>2018-10-03 - <strong>2018.1.1</strong>
                <ul>
                    <li>Moved the payments extension into the standard code. This adds a check number field
                        to the payment table. It also changes the logic to return to the invoice management
                        page after a payment is entered.
                    </li>
                    <li>Fixed logic supporting department field in the customer table.</li>
                    <li>Added last_invoice language index for the default_invoice module.</li>
                </ul>
            </li>
            <li>2018-09-25 - <strong>2018.1.0</strong>
                <ul>
                    <li><strong>Production Release</strong> User Security extension merged into the standard application.
                                                            This implements username functionality for logins, password
                                                            pattern rules maintained in System Preferences and a Session
                                                            Timeout setting also in System Preferences that allows the
                                                            minutes a session remains inactive to timeout. It also includes
                                                            upgrade of the password hash to SHA256 from MD5.
                    </li>
                    <li>Documentation extension merged into the standard application to provide custom help
                        message support via the "help" keyword.<br/>
                        Ex:<br/>
                        &lt;a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;help={$cflg.field_help}"
                        title="{$LANG.customFlagsUc}"&gt;&lt;img src="{$helpImagePath}help-small.png" alt="" /&gt;&lt;/a&gt;
                    </li>
                    <li>Created SystemDefaults class to consolidate function from sql_queries.php.</li>
                </ul>
            </li>
            <li>2018-09-22 - <strong>2018.0.0</strong>
                <ul>
                    <li><strong>Production Release</strong> Code brought current with PHP 7.2x requirements and all references
                                                            to the SimpleInvoices forum and wiki have been updated to access the
                                                            new SimpleInvoices Group website and Fearless359 SimpleInvoices Google+ forum.
                    </li>
                    <li>Added a signature field to the si_biller table along with maintenance support to provide
                        text that will be automatically included in invoice and statement emails. This was an
                        extension and that extension has been removed.
                    </li>
                    <li>Moved the Net Income Report extension to be a standard part of the application.</li>
                    <li>Moved the Past Due Report extension to be a standard part of the application.</li>
                </ul>
            </li>
            <li>2018-09-12 - <strong>2017.4.0</strong>
                <ul>
                    <li><strong>Production Release</strong> Removed all deprecated uses of class name for the constructor
                                                            and updated links for SI documentation to reference the new wiki site.
                    </li>
                </ul>
            </li>
            <li>2018-09-12 - <strong>2017.3.0</strong>
                <ul>
                    <li><strong>Production Release</strong> Updated to support localLocale setting in the custom.config.ini
                                                            (aka config.ini) files that use non-english number formats in templates. Also
                                                            changed <strong>Help</strong> link in the header to reference the new
                        <em>SimpleInvoices.group</em> site which will be updated to replace the
                        <em>SimpleInvoices.group</em> site that is no longer available.
                    </li>
                </ul>
            </li>
            <li>2017-12-20 - <strong>2017.2.0</strong>
                <ul>
                    <li><strong>Production Release</strong> Verified SI working with PHP 7.2</li>
                </ul>
            </li>
            <li>2016-12-27 - <strong>2016.1.001</strong>
                <ul>
                    <li><strong>Production Release</strong> Verified that this fork of SI will <strong>NOT</strong> be incorporated into
                                                            the master stream. The reason is that modifications in this stream involve reformatting of code
                                                            which obfuscates changes to the reviewers. Given the amount of work to bring this software
                                                            to the point where it uses the current version of PHP, Zend, Smarty, etc. requires restructuring
                                                            on a large scale, and therefore these changes will be retained.
                    </li>
                    <li>Merged Smarty 3 changes into this standard stream.</li>
                    <li>Added <strong>Information</strong> link to the SI banner line between <em>Help</em> and <em>Log out</em>. This
                        link displays information about the SI implementation including this change log and other useful
                        files stored in the <em>documentation/en-us/general</em> folder.
                    </li>
                </ul>
            </li>
        </ul>
        <ul>
            <li>2016-12-09 - <strong>2016.1.beta.1</strong>
                <ul>
                    <li><strong>Update to Smarty v3.1.30:</strong> Modifed numerous files to work with updated Smarty version.
                    <li><strong>Smarty Plugins in extensions.</strong> Added ability to specify plugins for extensions in the
                        <em>extensions/<strong>EXTNAME</strong>/include/smarty_plugins</em> directory. Plugin name must be unique,
                        so it is recommended that the <em><strong>EXTNAME</strong></em> be part of the name.
                    </li>
                </ul>
            </li>
        </ul>
        <ul>
            <li>2016-08-08 - <strong>2016.0.beta.2</strong>
                <ul>
                    <li><strong>General Cleanup</strong> Modifed numerous files to correct issues of undefined or uninitialized
                                                         variables and other execution time warnings when <em>strict</em> mode set.
                    <li><strong>Cron Logic</strong> Added delete feature.</li>
                    <li><strong>Default Invoice</strong> extension update to eliminate PHP errors and warnings. Also modified
                                                         to always display detail list with enabled customers first.
                    </li>
                    <li><strong>Sub-Customers</strong> extension enhancements. Changes made are:
                        <ul>
                            <li>Extension now automatically adds the new <strong>parent_customer_id</strong> field to the
                                <em>si_customers</em> table when the extension is enabled and the customer information
                                is accessed.
                            </li>
                            <li>The credit card information is masked leaving the last four digits showing.</li>
                        </ul>
                    </li>
                    <li>Fixed issue causing standard <em>si_customers</em> to fail when adding new records.</li>
                    <li>Added logic to prevent addition of new <em>si_customers</em> record with the same name
                        as an existing record.
                    </li>
                    <li>Fixed issue with undefined variable issue causing new product records to not post.</li>
                </ul>
            </li>
            <li>2016-07-25 - <strong>2016.0.beta.1</strong>
                <ul>
                    <li>Zend library updated to version 1.12.18
                        <ul class="li__type-none">
                            <li>Removed old library from project. This included removing it as a <strong>sub-module</strong>
                                and adding it as directly supported files. This was necessary as there is no
                                ongoing maintenance for <strong>Zend Framework 1</strong> on GitHub.
                            </li>
                        </ul>
                    </li>
                    <li>Enhancements for extension development
                        <ul class="li__type-none">
                            <li>These changes implement a process that allows files that might be updated by multiple
                                extensions to specify only the section that needs to be changed rather than having to
                                replicate the entire file. This means there won't be competition between extensions
                                having to consider whether another extension has been enabled and uses the same
                                file. The following files were specifically enhanced to accommodate the likely use in
                                multiple extensions:
                            </li>
                            <li>
                                <ul>
                                    <li><strong>template/default/reports/index.tpl</strong>: Modified to contain section identifiers
                                                                                           at which an extension can specify a menu entry is to be inserted. Use the
                                        <strong>extension/past_due_report/template/default/reports/index.tpl</strong> file as an example.
                                    </li>
                                    <li><strong>templates/default/menu.tpl</strong>: Modified to contain section identifiers
                                                                                   at which an extension can specify a menu entry is to be inserted. Use the
                                        <strong>extension/custom_flags/templates/default/menu.tpl</strong> file as an example.
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>Modified to move popup calendar to move it over a bit.
                        <ul class="li__type-none">
                            <li>If you use a start and end date that are positioned vertically, the popup for
                                the start date covers the icon to access the popup for the stop date making it a
                                two click process to get to the stop date when no change is made in the start date.
                                This change positions the popup to the right so the icons aren't obscured and the
                                stop date can be accessed directly.
                            </li>
                        </ul>
                    </li>
                    <li>Added <strong>Past Due Report</strong> extension.
                        <ul class="li__type-none">
                            <li>This change include a report to show customer&#39;s with past due invoices. The
                                report can optionally display which invoices are past due for each customer.
                                Additionally, this extension adds a <em>smarty template variable: <strong>$past_due_amount</strong></em>
                                for use in the <em>invoice template.</em></li>
                        </ul>
                    </li>
                    <li>Add user definable documentation support. This was added for use by the <strong>custom_flags</strong>
                        extension. This allows the help field text to be accessed from the database and therefore
                        can be tailored to field use on each system.
                    </li>
                    <li>Change to show user defined field label in help dialog.</li>
                    <li>Clean up FAQ document</li>
                    <li>General changes to support <strong>HTML5</strong>:
                        <ul>
                            <li>Modified HTML document generation logic to render HTML5 files. This includes removal
                                of attributes such as, <strong>&lt;table align=&quot;center&quot; &gt;</strong>.
                            </li>
                            <li>Modified all occurrences of <strong>&lt;textarea&gt;</strong> sections to use <strong>class="editor"</strong>
                                and eliminate the <strong>nowrap</strong> attribute.
                            </li>
                            <li>Changed all instances of the text, <strong>SimpleInvoices</strong> to be <strong>SimpleInvoices</strong>
                                consistent with the logo and name of this applications.
                            </li>
                            <li>Remove the <strong>class="buttons"</strong> values from all <strong>&lt;table&gt;</strong> tags. The class
                                is commented out in the <strong>main.css</strong> file.
                            </li>
                        </ul>
                    </li>
                    <li>Changed to minimize maintenance of extension language files. Typically the <strong>en_US</strong>
                        and <strong>en_US</strong> are both defined in extensions and have the same content. To eliminate
                        this doubling of maintenance, the <strong>en_US/lang.php</strong> file simply includes the content
                        of the <strong>en_US/lang.php</strong> file.
                    </li>
                    <li>Formalized the <strong>hooks</strong> feature so that they will work within extensions. The existing
                        <strong>custom/hooks.tpl</strong> file contains complete documentation on this feature including the
                        necessary cautions concerning the drawback of over using them. Basically, extensions should
                        be used rather than <strong>hooks</strong>.
                    </li>
                    <li>Fix missing <strong>$patchCount</strong> and query syntax error in <strong>getSystemDefaults()</strong>
                        method.
                    </li>
                    <li>Enhanced <strong>install from scratch</strong> process to better handle case of no database matching
                        the name in the <strong>config.ini</strong> or if present, the <strong>custom.config.ini</strong> file. Also
                        cleaned up formatting of screens and added additional information to the screen instructions
                        to better assist users with setting <strong>SimpleInvoices</strong> up.
                    </li>
                    <li>Added User Security extension.
                        <ul class="li__type-none">
                            <li>This is an important enhancement and is recommended for all users that have internet
                                access to their <strong>SimpleInvoices</strong> system. New features with this enhancement are:
                                <ul>
                                    <li>Addition of a <strong>username</strong> field to the database that will contain the unique ID
                                        that will be used to log into <strong>SimpleInvoices</strong>. Initially this is set to
                                        the <strong>email</strong> currently used. It can and should be changed by each user when
                                        they first log on to the system. Their <strong>email</strong> information is retained for
                                        informational purposes.
                                    </li>
                                    <li>Addition of password constraint options maintained in the <strong>si_system_defaults</strong>
                                        table. Based on the settings of these fields, a validation pattern is generated
                                        that will be used to verify user password compliance. The new database fields are:
                                        <ul class="li__type-disc">
                                            <li><strong>password_min_length</strong>: Specifies the minimum length user passwords
                                                                                    must be. It can be set from <strong>6</strong>
                                                                                    to <strong>16</strong> and defaults to <strong>8</strong>
                                                                                    when initially enabled.
                                            </li>
                                            <li><strong>password_upper</strong>: Specifies if the password should contain at least one
                                                                               upper case character. Set to <strong>true</strong> when
                                                                               extension is enabled.
                                            </li>
                                            <li><strong>password_lower</strong>: Specifies if the password should contain at least one
                                                                               lower case character. Set to <strong>true</strong> when
                                                                               extension is enabled.
                                            </li>
                                            <li><strong>password_number</strong>: Specifies if the password should contain at least one
                                                                                numeric character. Set to <strong>true</strong> when
                                                                                extension is enabled.
                                            </li>
                                            <li><strong>password_special</strong>: Specifies if the password should contain at least one
                                                                                 special character. Set to <strong>true</strong> when
                                                                                 extension is enabled.
                                            </li>
                                        </ul>
                                    </li>
                                    <li>Enhanced the setup and maintenance of user records for customers and billers.
                                        This feature already exists and is enhanced by this change to display a dropdown
                                        list of available settings for the <strong>user_id</strong> and field help information to
                                        explain this feature.
                                    </li>
                                    <li>Changes to allow your company name to replace the setting in the <strong>lang.php</strong> file
                                        as well as specify your company&#39;s logo do display on the logon screen and any other
                                        place you wish to use it. New fields added to the <em>si_system_defaults</em> table are:
                                        <ul class="li__type-disc">
                                            <li><strong>company_name_item</strong>: Company name to replace default value (typically
                                                <em>SimpleInvoices</em>. The new value will be used in all places where this language
                                                                                  it is currently used.
                                            </li>
                                            <li><strong>company_logo</strong>: Name of your logo file. That file must reside in the
                                                <em>extensions/user_security/images</em> folder. This will be displayed on the
                                                                             SI logon screen.
                                            </li>
                                        </ul>
                                    </li>
                                    <li>Addition of an option to specify in minutes, the time interval for which an inactive session
                                        will be terminated. Currently, all sessions terminate after 60 minutes of inactivity. This is
                                        too long for a secured system. The new option allows you to specify a different interval ranging
                                        from 15 to 999 minutes. New field added to the <em>si_system_defaults</em> table is:
                                        <ul class="li__type-disc">
                                            <li><strong>session_timeout</strong>: Number of minutes that a user session will remain alive before
                                                                                being terminated for inactivity. The session expiration interval will be renewed with
                                                                                each screen submission (note that entering data in a field is not considered a submission).
                                            </li>
                                        </ul>
                                    </li>
                                    <li>Addition of new image files for help and required help will be used to make them less prominent
                                        on the user screens. These images are stored in the <strong>extensions/user_security/images</strong>
                                        folder and can be replaced by the original images if desired.
                                    </li>
                                </ul>
                            </li>
                            <li>Several housekeeping changes:
                                <ul>
                                    <li>Added <strong>global</strong> statements modified files to eliminate undefined variable warnings
                                        for variables defined in other included files.
                                    </li>
                                    <li>Added a test that does nothing to eliminate unused variable warnings for variables
                                        that are referenced by other files included following this one. The form of the test
                                        is: <strong>if ($variable_name) {}</strong></li>
                                    <li>Deleted unused source.</li>
                                    <li>Excluding HTMLPurifier cache file from git management.</li>
                                    <li>Provide error log information if email fails.</li>
                                    <li>Added missing css class to fix display issue on invoice quick view form.</li>
                                </ul>
                            <li>Modified logic to only display <strong>Custom Fields</strong> if their associated label is defined.
                                <ul class="li__type-none">
                                    Currently custom fields display and function whether they have a label defined of not. This
                                    change imposes a rule that a label must be defined to activate a custom field cleaning up the
                                    interface and removes confusion by imposition of this simple requirement.
                                </ul>
                            </li>
                            <li>Added new PdoDb class and supporting classes.
                                <ul class="li__type-none">
                                    <li>
                                        This is a step towards replacement of existing database logic through use of a class that
                                        will properly format and execute all database requests through a common interface. This
                                        interface abstracts the underlying database from the application code. The class also supports
                                        the dynamic build of <strong>SQL</strong> database maintenance statements, <em>INSERT</em>, <em>UPDATE</em>
                                        and <em>DELETE</em>, from <strong>key</strong>/<strong>value</strong> information defined in the <em>$_POST</em> super
                                        global. This will allow extensions (or any code) that maintains a database table to perform
                                        the maintenance request without the need specify table fields to maintain in the <em>PDO</em>
                                        requests. This also eliminates the need to add table maintenance functions to the
                                        <em>sql_queries.php</em> file which complicated extension development.
                                    </li>
                                </ul>
                            </li>
                            <li>Fixed payments to show actual <strong>Invoice #</strong> rather than the <strong>Invoice ID</strong>.</li>
                            <li>Remove warnings for <strong>BROWSE</strong> constant redefinition warnings by testing if already defined.</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2008-05-12
                        <ul>
                            <li>templates\default\preferences\manage.tpl // Apostrophe display fixes// -Dimante</li>
                            <li>templates\default\billers\manage.tpl // Apostrophe display fixes// -Dimante</li>
                            <li>templates\default\billers\details.tpl // Apostrophe display fixes// -Dimante</li>
                            <li>templates\default\payment_types\manage.tpl // Apostrophe display fixes// -Dimante</li>
                            <li>templates\default\payment_types\details.tpl // Apostrophe display fixes// -Dimante</li>
                            <li>templates\default\customers\manage.tpl // Apostrophe display fixes// -Dimante</li>
                            <li>templates\default\customers\details.tpl // Apostrophe display fixes// -Dimante</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2008-05-11
                        <ul>
                            <li>modules\payment_types\save.php // Added domain_id to insert SQL // -Dimante</li>
                            <li>modules\sql_queries.php // Added domain_id to insert SQL for Add Biller // -Dimante</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2008-05-05
                        <ul>
                            <li>modules/preferences/save.php // Added domain_id to parameters // -Dimante</li>
                            <li>templates/default/preferences/details.tpl // Added coding to prevent \' in text rendering for lines with apostrophes. // -Dimante</li>
                            <li>Renamed database_backup.tpl to database_backup.tpl so that the backup function would work. // -Dimante</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2008-04-xx
                        <ul>
                            <li>Fixed minor code error in /templates/default/preferences/details.tpl - Dimante</li>
                            <li>Greek translation added</li>
                            <li>Albanian translation added</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>NR +1 2008-02
                        <ul>
                            <li>Favicon.ico added</li>
                            <li>Port removed from the PDF url code as HTTP_HOST was already returning the port</li>
                            <li>Quick view page - Customer accounts section now calculating correctly</li>
                            <li>Slovak translation added</li>
                            <li>Latvian translation added</li>
                            <li>Reports on WAMP problem fixed - https://simpleinvoices.group/forum/topic-449.html</li>
                            <li>DB Upgrade SQL Patcher extra text output error and html syntax errors fixed</li>
                            <li>Enabled Gross Total in Export formats too.</li>
                            <li>Norwegian, Slovenian, Danish lang.php corrected by removing leading hex EF BB BF in file - now authentication works for them too.</li>
                            <li>Quantity Format in invoice quick view set to number_format:2</li>
                            <li>System Default Invoice Preference now gets saved correctly</li>
                            <li>Zero Owing Fully Paid Invoices now have 0 Age and blank Aging in Manage Invoices page</li>
                            <li>Enabled Gross Total in all invoices with local template variable: gross_total = total - total_tax</li>
                            <li>Migrated PDF to library and a new pdfmaker.php that takes only Invoice ID in URL</li>
                            <li>Removed Type ID from URLs in manage invoices</li>
                            <li>Introduced user_group table patch</li>
                            <li>domain_id and group_id field changes in users table and dropping of old defaults and auth_challenges table</li>
                            <li>Migrated PHPReports to library/phpreports and modified the existing reports to use it</li>
                            <li>Corrected SQL in reportProductsSoldByCustomer</li>
                            <li>Corrected Spanish translation file for correct encoding and double quote typo</li>
                            <li>Fixed email.php with new location of pdf cache folder - ./cache - no need for out symlink</li>
                            <li>Fix for undefined $title in invoice output templates and html syntax fixes</li>
                            <li>Right side border lines now display correctly in print preview of invoices</li>
                            <li>Mobile Phone of Biller and Customer now prints and displays correctly</li>
                            <li>Removed duplicates in all lang files (insert_biller, insert_customer, insert_product)</li>
                            <li>Synced all language files alphabetically with new variables to that in en-gb with 0 attribute and same as in stable version</li>
                            <li>Patch 141: sql_patchmanager.sql_patch_ref field changed to INT</li>
                            <li>Some more NULL replacements in various save.php files</li>
                            <li>Some more TB_PREFIX replacements in various php files</li>
                            <li>Ported the DB Upgrade click fix (when all upgrades are over)</li>
                            <li>Ported the INSERT NULL (primary key) and TB_PREFIX from NextRelease into sql_patches, sql_queries and the MySQL Dumps - Postgres Dump need to be updated</li>
                            <li>Alphabetically arranged lang/en-gb/lang.php and removed unused and duplicated system_prefs element</li>
                            <li>Uploaded missing header_bg.gif and g_close.gif files in /modules/include/js - this was causing GET errors in the Apache Log files</li>
                            <li>PDF cache is the main smarty cache folder itself - no more symlinks - hence removed the /include/pdf/temp and /include/pdf/out symlinks - the /include/pdf/cache symlink was removed earlier</li>
                            <li>Fixed typo templates to templates in /templates/default/header.tpl on line 57</li>
                            <li>Fixed correct location of iehacks.css in the code in include/functions.php line 323</li>
                            <li>PHPReports DARWIN OS - $ipsep extended</li>
                            <li>PHPReports PHP5 deprecated use of array_push issue fixed</li>
                            <li>Invoice PDF and EMail now support spaces in Biller Logo file name</li>
                            <li>Invoice PDF EMail now supports spaces in Invoice Preference Name</li>
                            <li>Invoice PDF EMail (Boolean variable in config.ini) now supports Confirm Reading Receipt to Sender</li>
                            <li>screen.css now has th.sortable_rt class - useful for right align for numeric fields - available in the modules/module-name/manage.php files</li>
                            <li>General Code cleanup</li>
                            <li>Increased limit from 100 to 1000000 billers / clients / inv_prefs - look in modules/invoices/total.php, itemized.php, consulting.php</li>
                            <li>Changes in config/config.ini made backwards compatible for use with older config.ini</li>
                            <li>Invoice PDF EMail now supports SecureSMTP and user configurable SMTP Port</li>
                            <li>Updated PHPMailer to v2.10 Beta 1 for PHP5</li>
                            <li>lang/en-gb/lang.php capitalised correctly</li>
                            <li>Default Customer now shows up correctly on all add new invoice pages</li>
                            <li>Editing invoice with custom date format issue fixed</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2007-11-16
                        <ul>
                            <li>Security patch to fix issue with the login system - could be bypassed by disabling javascript support</li>
                            <li>Documentation and docs.php updates</li>
                            <li>Default language on upgrade from older version, refer: https://simpleinvoices.group/forum/topic-359.html</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2007-10-02 aka AFL Grand Final celebration release
                        <ul>
                            <li>PHP5 and above only - SimpleInvoices will no longer work on PHP4 servers</li>
                            <li>Invoices now can be deleted - This is can be enabled via the System Preferences page</li>
                            <li>Adding more items to an existing invoice via the edit page it now possible</li>
                            <li>New javascript menu included - now works in IEs,FF and Opera</li>
                            <li>New language select system - system language can now be changed via the System Preferences page</li>
                            <li>All pages move to the smarty templating system</li>
                            <li>Updated UI - were slowly moving away from our 37signals style UI to a more unique UI</li>
                            <li>PDF system modified - $installation_path no longer required</li>
                            <li>Authentication sql table added to the default install - so you now longer have to manually run the login sql to get authentication working</li>
                            <li>How total invoices are stored in the DB has changed - now a total invoices gets stored as a product in the products table but set to not visible via the Manage Products page</li>
                            <li>System Defaults renamed to System Preferences</li>
                            <li>Authentication can now be set via the config.ini file - no need to adjust include_auth.php anymore</li>
                            <li>Customer add template fixed for if no name entered</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=106">Issue 106</a>Edit tax rates bug fixed</li>
                            <li>Report: Debtors owing by customer sql fixed</li>
                            <li>SQL patch to alter tax rate to 3 decimal places</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2007-08-29
                        <ul>
                            <li>Security patch release: fix md5 javascript login error</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2007-05-25
                        <ul>
                            <li>Error in export template fixed</li>
                            <li>Turn logging off by default</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2007-05-23
                        <ul>
                            <li>Email function added, you can now email an invoice as PDF in SimpleInvoices</li>
                            <li>Moved to smarty for templating system</li>
                            <li>Major rewrite (refactoring) of backend code by aplsyia to simplify and make sane</li>
                            <li>Replace the javascript validation with a purely php validation system</li>
                            <li>Table prefix configuration option added</li>
                            <li>Corrected &lt;? ... ?&gt; Syntax to &lt;?php ... ?&gt; Syntax</li>
                            <li>Lots of small optimizations</li>
                            <li>Introduced XHTML header</li>
                            <li>Czech translation added</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=75">Issue 75</a> Stop browsing to the source .php files</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=90">Issue 90</a> Translation update: save button updates</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=84">Issue 84</a> File cleanup for live grid</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=93">Issue 93</a> Translation: make the redirect pages (ie save.php) translatable</li>
                            <li>Old invoice templates (old_lco and old_original) retired, sorry Gaelyne, we moved to a new template system and these didn't make it</li>
                            <li>ibox replaced with greybox as the ajax alert box of choice</li>
                            <li>PDF name now 'Wording for invoice'.'Invoice ID'.pdf (ie. Invoice12.pdf or Estimate8.pdf)</li>
                            <li>Moved a lot of the templates to smarty - still an ongoing project</li>
                            <li>Language file cleanup - duplicates and old style variables removed</li>
                            <li>Logo directory moved from images/logos to templates/invoices/logos</li>
                            <li>css update by lionel</li>
                            <li>MySQL connect - pretty info messages instead of errors all over the page</li>
                            <li>SQL Patches: system changes - manage screen made all nice and if are patches to be applied you have to apply them before using SimpleInvoices</li>
                            <li>System defaults: moved to a new db format to be sane</li>
                            <li>Documentation system altered</li>
                            <li>Reports: If report runs OK you no longer see the did you get an OOPS error message</li>
                            <li>SQL Patches
                                <ul>
                                    <li>Patch 37: reset default invoice template to 'default' due to new invoice template</li>
                                    <li>Patch 38 &amp; 39: Alter custom field table - field length now 255 for field label and name</li>
                                    <li>Patch 40 - 116: Alter database fields</li>
                                    <li>Patch 63: Introduce new system_defaults table</li>
                                    <li>Patch 116 - 122: Patches to convert defaults from the old table to the new si_system_defaults table</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2007-03-05
                        <ul>
                            <li>New live grid ajax (Live Grid Plus + OpenRico) used to manage the display of all the table info in the 'manage' pages. The old tablesorter and filtering has been removed.</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=79">Issue 79</a>Custom field extended to support be in invoices</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=78">Issue 78</a>The ajax info page/alert boxes moved from jQuery thickbox to ibox to work with the new open rico live grid</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=81">Issue 81</a>New info page for Custom Fields in voices</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=80">Issue 80</a>New info detailing what custom fields are</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=74">Issue 74</a>Standardised on using English Number system throughout (ie, 2,400.00 instead of 2400)</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=86">Issue 86</a>Live grid modified to work with IT</li>
                            <li>Romanian translation added</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2007-02-06
                        <ul>
                            <li>Manage Customer amount owed calculation updated</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=35">Issue 35</a>PDF security issues fixed</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=50">Issue 50</a>Process Payment: Auto populate the amount field with the owed value</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=58">Issue 58</a>Date format woes fixed</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=65">Issue 65</a>Invoice date: make editable</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=72">Issue 72</a>Sort not working correctly - fixed</li>
                            <li>Note: If you're using authentication please read: <a href="https://simpleinvoices.group/howto" target="_blank">SimpleInvoices Knowledge Base HowTo Page</a> as there have been changes</li>
                            <li>SQL Patches
                                <ul>
                                    <li>Adding data to the "custom fields" table for invoice</li>
                                    <li>Adding custom fields to the invoices table</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2007-02-02
                        <ul>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=73">Issue 73</a>Security: Controller.php doesn't validate the $_GET input</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=71">Issue 71</a>Null Invoice: Consulting style issue</li>
                            <li>Manage tables font size reduction</li>
                            <li>Known Issue: <a href="https://code.google.com/p/simpleinvoices/issues/detail?id=73">Issue 72</a>Sort not working correctly for numbers</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2007-01-25
                        <ul>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=68">Issue 68</a>File structure modified</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=69">Issue 69</a>Controller script added and urls system modified</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=70">Issue 70</a>Invoice templates names changed and system modified</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=63">Issue 63</a>MySQL password format changed to MD5</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=63">Issue 66</a>New user interface added</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=64">Issue 64</a>Authentication: Headers already sent drama resolved</li>
                            <li>Manage Invoices: some unneeded filters removed</li>
                            <li>Jquery greybox replace with Jquery thickbox as the ajax popup window javascript</li>
                            <li>Menus structure modification - Manage then Add</li>
                            <li>Numbers - all formatted to 2 decimal places</li>
                            <li>SQL Patches
                                <ul>
                                    <li>UPDATE `si_custom_fields` SET `cf_custom_field` = 'product_cf4' WHERE `si_custom_fields`.`cf_id` =12 LIMIT 1 ;</li>
                                    <li>UPDATE `si_system_preferences` SET `def_inv_template` = 'default' WHERE `def_id` =1 LIMIT 1;</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-12-11
                        <ul>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=55">Issue 55</a> Custom Fields added to billers, customers, and products</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=30">Issue 30</a> Issue with blank fields and commas being displayed on the invoice has been fixed, now only fields which are not null get displayed and the
                                                                                                                    commas
                                                                                                                    appear in the right places
                            </li>
                            <li>Invoice templates updated to enable custom fields and moved to a more css base</li>
                            <li>Language files updated</li>
                            <li>More of SimpleInvoices made translatable</li>
                            <li>Manage Custom Fields page added</li>
                            <li>Quick View: updated with show/hide buttons to toggle the amount of info displayed on screen, and alter for the Custom Fields</li>
                            <li>Customer details page tab feature revamped to include an updated style</li>
                            <li>Index page updated, icons added and layout cleaned up</li>
                            <li>Index page: warning added for MySQL4, IE, Konqueror, and Safari users</li>
                            <li>Index page: warning added to notified if database patches are required to be run</li>
                            <li>Database Upgrade Manager: updated to display the number of patches that need to be displayed</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=30">Issue 43</a> Street address 2 field added to customers and billers</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=30">Issue 32</a> Add amount owed on the invoice print out</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=53">Issue 53</a> MySQL 4 sql file: auto-increment added even though phpMyAdmin doesn't include it in the export (using MySQL 4 comparability)</li>
                            <li>Mobile phone field added to customers</li>
                            <li>8 sql patches added (number 25 to number 23)</li>
                            <li>Known issues with this release:
                                <ul>
                                    <li>PDF Print Preview: The border for the customer and biller details in print_preview.php doesn't show for the telephone fields</li>
                                    <li>Konqueror: Some javascript features don't work in Konqueror</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-10-27
                        <ul>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=29">Issue 29</a> fixed: Drop down lists now sorted alphabetically</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=31&amp;can=6&amp;q=">Issue 31</a> notes field added to products, billers, and customers</li>
                            <li>Customer details page revamped to include a tabbed interface for the notes and invoice listing</li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=34&amp;can=2&amp;q=">Issue 34</a> The php include() system has had a cleanup and files can be included in all pages by editing ./include/include_main.php
                            </li>
                            <li><a href="https://code.google.com/p/simpleinvoices/issues/detail?id=21&amp;can=2&amp;q=">Issue 21</a> More pages have been made translatable</li>
                            <li>SQL Patches
                                <ul>
                                    <li>ALTER TABLE `si_customers` ADD `c_notes` TEXT NULL AFTER `c_email`</li>
                                    <li>ALTER TABLE `si_biller` ADD `b_notes` TEXT NULL AFTER `b_co_footer`</li>
                                    <li>ALTER TABLE `si_products` ADD `prod_notes` TEXT NOT NULL AFTER `prod_unit_price`</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-10-14
                        <ul>
                            <li>Index.php - home page renovated - green buttons removed - jQuery accordion menu added</li>
                            <li>Issue 25: IE print_preview.ph borders fixed</li>
                            <li>Exporting to xls and doc fixed</li>
                            <li>German translation added</li>
                            <li>Menu text made more easily translatable</li>
                            <li>Documentation upgraded to include FAQs</li>
                            <li>Creation of a new cache directory to make it easier to set up SimpleInvoices</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-10-06
                        <ul>
                            <li>New invoice template added</li>
                            <li>The previous default invoice template 'print_preview.php' has been renamed 'print_preview_basic.php'</li>
                            <li>Updating of the invoice templates to resolve Issue 20 - https://code.google.com/p/simpleinvoices/issues/detail?id=20&amp;can=2&amp;q=</li>
                            <li>Italian tax rate - ITA of 20% added</li>
                            <li>German tax rate - MWSt (DE) of 16% added</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-09-28
                        <ul>
                            <li>Invoices are now editable!!</li>
                            <li>Invoice - consulting now includes an optional notes section - similar to the other 2 invoice styles</li>
                            <li>Authentication work started</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-09-25
                        <ul>
                            <li>Adding TinyMCE support for all the text box fields - a javascript html editor</li>
                            <li>Minor reports updated</li>
                            <li>Options menu now points to index.php instead of the nonexistent options.php</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-09-18
                        <ul>
                            <li>Aging column add to the Mange Invoices page</li>
                            <li>Age field added to the print preview page</li>
                            <li>Debtors reports section added</li>
                            <li>Report "Debtors_by_amount" added</li>
                            <li>Report "Debtors by Aging periods" added</li>
                            <li>Report "Total owed per customer" added</li>
                            <li>Report "Total by Aging periods" added</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-09-13
                        <ul>
                            <li>Process payments modified to include auto-complete in the invoice id field as well as the details box being auto updated with the selected invoices information</li>
                            <li>Validation updated for the process payment page and other small changes to cover various other pages</li>
                            <li>Print Preview - small bug fix to make the click through to the various customer account screens work correctly</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-09-09
                        <ul>
                            <li>Payment menu added</li>
                            <li>Manage Payments page added</li>
                            <li>Process Payment page modify to be able to select an invoice</li>
                            <li>Process Payment page modify to be able to select from a calendar the date of the payment</li>
                            <li>Process Payment page modify to be able to select Payment Type</li>
                            <li>Manage Payment Types page added</li>
                            <li>Insert Payment Type page added</li>
                            <li>System Defaults modified to be able to select default payment type</li>
                            <li>Print Preview modified to be able to 'click through' to the Manage Payments filtered by invoice or customer</li>
                            <li>The 'Manage' pages have been modified to include a header and be able to create a New Item button</li>
                            <li><a href="https://mirror2.cvsdude.com/trac/simpleinvoices/simpleinvoices/ticket/45">Ticket 45</a>PDF printing issues fixed</li>
                            <li><a href="https://mirror2.cvsdude.com/trac/simpleinvoices/simpleinvoices/ticket/23">Ticket 23</a>'Add' button it the Manage pages done</li>
                            <li>Client Accounts
                                <a href="https://mirror2.cvsdude.com/trac/simpleinvoices/simpleinvoices/wiki/Client_Accounts">Stage 2</a>
                                :
                                <a href="https://mirror2.cvsdude.com/trac/simpleinvoices/simpleinvoices/ticket/41">implemented</a></li>
                            <li>SQL Patches
                                <ul>
                                    <li>CREATE TABLE `si_payment_types` (`pt_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,`pt_description` VARCHAR( 250 ) NOT NULL ,`pt_enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1');</li>
                                    <li>INSERT INTO `si_payment_types` ( `pt_id` , `pt_description` ) VALUES (NULL , 'Cash'), (NULL , 'Credit Card');</li>
                                    <li>ALTER TABLE `si_account_payments` ADD `ac_payment_type` INT( 10 ) NOT NULL DEFAULT '1';</li>
                                    <li>ALTER TABLE `si_system_preferences` ADD `def_payment_type` VARCHAR( 25 ) NOT NULL DEFAULT '1' ;</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-08-25
                        <ul>
                            <li>Client Accounts added:
                                <a href="https://mirror2.cvsdude.com/trac/simpleinvoices/simpleinvoices/wiki/Client_Accounts">Stage 1</a> :
                                <a href="https://mirror2.cvsdude.com/trac/simpleinvoices/simpleinvoices/ticket/40">complete</a>
                                <ul>
                                    <li>In Manage Invoice/Manage Customer and Customer Details pages the fields "Total","Paid","owing" have been added to reflect the customers account summary</li>
                                    <li>A Process Payment feature has been added in Manage Invoice and Quick View to allow the recording of payments by clients for invoice</li>
                                </ul>
                            </li>
                            <li><a href="https://mirror2.cvsdude.com/trac/simpleinvoices/simpleinvoices/ticket/25">Ticket 25:</a>Blank Biller/Customer bug fixed</li>
                            <li><a href="https://mirror2.cvsdude.com/trac/simpleinvoices/simpleinvoices/ticket/39">Ticket 39:</a>A tax_id field has been added to the items table to aid reporting</li>
                            <li>5 new themes added</li>
                            <li>SQL Patches
                                <ul>
                                    <li>ALTER TABLE `si_biller` ADD `b_enabled` varchar(1) NOT NULL default '1'</li>
                                    <li>ALTER TABLE `si_invoice_items` CHANGE `inv_it_quantity` `inv_it_quantity` FLOAT NOT NULL DEFAULT '0'</li>
                                    <li>CREATE TABLE `si_account_payments` ( `ac_id` INT(10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , `ac_inv_id` VARCHAR( 10 ) NOT NULL , `ac_amount` DOUBLE( 25, 2 ) NOT NULL , `ac_notes` TEXT NOT NULL , `ac_date` DATETIME
                                        NOT
                                        NULL );
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-08-15
                        <ul>
                            <li>New menu system that works in all major browsers</li>
                            <li>Billers,customers,products,tax rates and preferences can now be enabled/disabled</li>
                            <li>SQL Patches to add enabled/disabled
                                <ul>
                                    <li>ALTER TABLE si_biller ADD b_enabled varchar(1) NOT NULL default '1'</li>
                                    <li>ALTER TABLE si_customers ADD c_enabled varchar(1) NOT NULL default '1'</li>
                                    <li>ALTER TABLE si_preferences ADD pref_enabled varchar(1) NOT NULL default '1'</li>
                                    <li>ALTER TABLE si_products ADD prod_enabled varchar(1) NOT NULL default '1'</li>
                                    <li>ALTER TABLE si_tax ADD tax_enabled varchar(1) NOT NULL default '1'</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-07-28
                        <ul>
                            <li>Fix minor issues with previous release - nifty corners location and UFT-8</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-07-27
                        <ul>
                            <li>Manage pages now sortable and filterable</li>
                            <li>Translation framework added</li>
                            <li>Portuguese translation added</li>
                            <li>Reports added</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-07-20
                        <ul>
                            <li>Live search aka search as you type added into the 'Manage' screens</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-06-16
                        <ul>
                            <li>The manage invoices page had the actions pdf,xls,doc changed to icons</li>
                            <li>pdf configs now moved into config/config.ini</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-06-15
                        <ul>
                            <li>The 'Manage' pages received some css love</li>
                            <li>The manage invoices page had the actions pdf,xls,doc added</li>
                            <li>The quick view page had the Export to PDF changed from a button to a link</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-06-10
                        <ul>
                            <li>Export to PDF now works!!! :)</li>
                            <li>Export to Excel/Word/OPenDocument format now works!!! :)</li>
                            <li>Invoice Itemized now has an optional invoice note feature</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-05-31
                        <ul>
                            <li>Database Backup - this now works, so the user can back up the SimpleInvoices database at will
                                through SimpleInvoices
                            </li>
                            <li>Invoice - Consulting: new invoice type added, this is a cross between and total and an itemized
                                invoice. It allows the user to create an invoice with multiple line items and editable item
                                descriptions; similar to how a consulting firm creates invoices
                            </li>
                            <li>SQL Patches
                                <ul>
                                    <li>INSERT INTO si_invoice_type ( inv_ty_id , inv_ty_description ) VALUES (3, 'Consulting')</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-05-27
                        <ul>
                            <li>Biller logo now a drop-down list</li>
                            <li>Default invoice template now a drop-down list</li>
                            <li>System defaults page all option have been enabled</li>
                            <li>System defaults actually work :)
                                <ul>
                                    <li>Choosing default biller/customer/tax/preference now make invoice total and itemized default
                                        to the selected default
                                    </li>
                                </ul>
                            </li>
                            <li>Print_preview_slick.php modified to work with multi line invoices</li>
                            <li>Ajax text modifed</li>
                            <li>Raymond's php/mysql sanity patches applied</li>
                            <li>Tax description and default invoice template fields increased to 50 characters</li>
                            <li>SQL Patches
                                <ul>
                                    <li>ALTER TABLE si_tax CHANGE tax_description tax_description VARCHAR( 50 ) DEFAULT NULL</li>
                                    <li>ALTER TABLE si_system_preferences CHANGE def_inv_template def_inv_template VARCHAR( 50 ) DEFAULT NULL</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-05-19
                        <ul>
                            <li>Remove the original lightbox 'ajax alert windows' and add ParticleTrees lightbox ajax alerts</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-05-15
                        <ul>
                            <li>Fix a bug where Internet Explorer prints out code that was commented out</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-05-14
                        <ul>
                            <li>Multiple line items now supported in the Itemized Invoice</li>
                            <li>License page added to the About Menu</li>
                            <li>Credits page added to the About Menu</li>
                            <li>FAQs page added to the Instruction Menu</li>
                            <li>Logo support added</li>
                            <li>/logo directory created -</li>
                            <li>Invoice template theme support added</li>
                            <li>/invoice_templates directory created. Print_preview.php has been moved to this directory</li>
                            <li>2 new fields have been added to the biller, biller logo file and invoice footer</li>
                            <li>A new invoice template contributed by Dave Holden called print_preview_slick.php has been added to the /invoice_templates directory</li>
                            <li>print_view_manual.php has been renamed to print_quick_view.php</li>
                            <li>Add lightbox text popups</li>
                            <li>Add input validation</li>
                            <li>SQL PatchManager (Upgrade Database in the Option menu) has been added, this allows for the upgrade of the SimpleInvoices database from within SimpleInvoices, so the user no longer has to manually run sql scripts when
                                SimpleInvoices is upgraded.
                            </li>
                            <li>SQL Patches
                                <ul>
                                    <li>#update invoice no details to have a default currency sign of $
                                        <ul>
                                            <li>UPDATE `si_preferences` SET `pref_currency_sign` = '$', `pref_inv_detail_heading` = NULL WHERE `pref_id` =2 LIMIT 1 ;</li>
                                        </ul>
                                    </li>
                                    <li>Add a row into the defaults table to handle the default number of line items
                                        <ul>
                                            <li>ALTER TABLE `si_system_preferences` ADD `def_number_line_items` INT( 25 ) NOT NULL ;</li>
                                        </ul>
                                    </li>
                                    <li>Set the default number of line items to 5
                                        <ul>
                                            <li>UPDATE `si_system_preferences` SET `def_number_line_items` = '5' WHERE `def_id` =1 LIMIT 1 ;</li>
                                        </ul>
                                    </li>
                                    <li>Create the sql patch manager table
                                        <ul>
                                            <li>CREATE TABLE `si_sql_patchmanager` ( `sql_id` INT NOT NULL AUTO_INCREMENT , `sql_patch_ref` VARCHAR( 50 ) NOT NULL , `sql_patch` VARCHAR( 50 ) NOT NULL , `sql_release` VARCHAR( 25 ) NOT NULL , 'sql_statement`
                                                TEXT NOT NULL , PRIMARY KEY ( `sql_id` ) );
                                            </li>
                                        </ul>
                                    </li>
                                    <li>Add logo and invoice footer support to biller
                                        <ul>
                                            <li>ALTER TABLE `si_biller` ADD `b_co_logo` VARCHAR( 50 ) ,ADD `b_co_footer` TEXT;</li>
                                        </ul>
                                    </li>
                                    <li>Add default invoice template option
                                        <ul>
                                            <li>ALTER TABLE `si_system_preferences` ADD `def_inv_template` VARCHAR( 25 ) DEFAULT 'print_preview.php' NOT NULL ;</li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-05-03
                        <ul>
                            <li>SQL file fix - SimpleInvoiceDatabase-MySQL4_0.sql added to enable the installation of the SimpleInvoices database in MySQL 4.0. SimpleInvoicesDatabase.sql works with MySQL 4.1 and above</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-04-30
                        <ul>
                            <li>Tax calculation - 0% tax rate is now allowed - a Tax Rate with 0% has to be setup</li>
                            <li>Tax Rate: a 'No Tax' rate has been added to the 2 .sql files</li>
                            <li>Insert_action.php - change 5 seconds to 2 and alter text</li>
                            <li>Add Instructions into the Options menu - this links to the ReadMe.html file</li>
                            <li>Add About sub-menu in the Options menu - which reads from the ChangeLog.html and RoadMap.html files</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-04-29
                        <ul>
                            <li>Invoice Itemized - fix - the unit price was reporting the current unit price when it should have been showing the value of the unit at the time of sale</li>
                            <li>Remove unnecessary files</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-04-28
                        <ul>
                            <li>Bug fix release
                            <li>Tax calculation - fix tax calculation issue as per Jestered's email</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-04-26
                        <ul>
                            <li>Bug fix release</li>
                            <li>Invoice - duplicate fields bug fixed</li>
                            <li>Invoice - Not redirecting to Quick View issue fixed</li>
                            <li>Menus - menus now working in Opera and IE</li>
                        </ul>
                    </li>
                </ul>
                <ul>
                    <li>2006-04-25
                        <ul>
                            <li>Initial release</li>
                        </ul>
                    </li>
                </ul>
    </div>
</div>
</body>
</html>
