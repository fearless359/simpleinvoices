<!DOCTYPE html>
<html lang="en">
<head>
  <title>SimpleInvoices - Changelog</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="../../../include/jquery/css/main.css">
  <link rel="stylesheet" href="../../../templates/default/css/info.css">
</head>
<body>
  <h1 class="si_center">Change Log</h1>
  <div class="si_toolbar">
    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Return To Previous Screen</a>
  </div>
  <div id="left">
    <ul>
        <li>2020-08-06 - <b>2020.0.00</b>
            <ul>
                <li>New master branch based on PHP 7.4 standard.</li>
            </ul>
        </li>
        <li>2020-08-05 - <b>2019.2.30</b>
            <ul>
                <li>Change PdoDb class to handle information_schema column and table names
                    to force them to be lower case. This is for UNIX like systems that
                    enforce file name case sensitivity.</li>
                <li>Modified all manage.tpl templates the use DataTables to set the
                    deferred render to enhance performance with larger tables.</li>
            </ul>
        </li>
        <li>2020-07-15 - <b>2019.2.29</b>
            <ul>
                <li>Fixed display of status in Pymt Types view screen.</li>
            </ul>
        </li>
        <li>2020-07-13 - <b>2019.2.28</b>
            <ul>
                <li>Use dbStd function to convert amount input on invoice items or total
                    input for Total style invoices to fix issue of amounts not storing
                    correctly in the database.</li>
                <li>Corrected new Total Style invoice handling of more than one tax item
                    for the invoice.</li>
            </ul>
        </li>
        <li>2020-06-24 - <b>2019.2.27</b>
            <ul>
                <li>Modified to correctly generate pdf, xls and doc files for invoices.</li>
                <li>Update vendor libraries to current versions for PHP 7.2x and above.</li>
            </ul>
        </li>
        <li>2020-06-16 - <b>2019.2.26</b>
            <ul>
                <li>Modified code to handle multiple TO and BCC email addresses using a
                    semi-colon, ";", to separate each address.</li>
                <li>Added logic to report invalid email addresses in the FROM, TO and BCC
                    fields as errors to the user rather than throwing an error that never
                    displayed.</li>
            </ul>
        </li>
        <li>2020-06-03 - <b>2019.2.25</b>
            <ul>
                <li>Modified the table lists for all screens to use formatting in javascript
                    rather than php code to enhance performance.</li>
                <li>Added local.currency code field to the config file to support properly
                    formatting currency fields for non-invoice items such as costs for
                    customers, and unit price for products. Note that invoice and payment
                    tables get currency formatting information from the "Inv Prefs" link
                    in the Settings tab.</li>
            </ul>
        </li>
        <li>2020-05-16 - <b>2019.2.24</b>
            <ul>
                <li>Updated the Dutch (nl_NL) language files using changes made by an SI user.</li>
            </ul>
        </li>
        <li>2020-05-06 - <b>2019.2.23</b>
            <ul>
                <li>Cleaned up the total style invoice edit screen input and display logic.</li>
            </ul>
        </li>
        <li>2020-04-01 - <b>2019.2.22</b>
            <ul>
                <li>Fixed logic for adding and deleting invoice item lines, and to
                    fill in the unit price field when a new product is selected.</li>
                <li>Cleaned up the expense handling and display logic.</li>
            </ul>
        </li>
        <li>2020-03-09 - <b>2019.2.21</b>
            <ul>
                <li>Fixed issue of adding tax rate didn't return to correct screen.</li>
            </ul>
        </li>
        <li>2020-02-16 - <b>2019.2.20</b>
            <ul>
                <li>Modified Net Income Report to add customer selection feature.</li>
            </ul>
        </li>
        <li>2020-02-07 - <b>2019.2.19</b>
            <ul>
                <li>Modified getCustomerPastDue() function to add option to include
                    or exclude a specified invoice.</li>
            </ul>
        </li>
        <li>2020-01-26 - <b>2019.2.18</b>
            <ul>
                <li>Fix error of set_aging setting not being read properly from database resulting
                    in no aging information appearing on the invoice table.</li>
            </ul>
        </li>
        <li>2020-01-26 - <b>2019.2.17</b>
            <ul>
                <li>Added domain name to top bar in addition to domain id currently shown
                    when logged in as a user for a domain other than 1.</li>
            </ul>
        </li>
        <li>2020-01-26 - <b>2019.2.16</b>
            <ul>
                <li>Fixed issue with adding a new user.</li>
            </ul>
        </li>
        <li>2020-01-23 - <b>2019.2.15</b>
            <ul>
                <li>Added set_aging field to the si_preferences table to control setting
                    of aging information on invoices. Previously, aging information was
                    updated only if the si_preferences pref_id was 1.</li>
            </ul>
        </li>
        <li>2020-01-14 - <b>2019.2.14</b>
            <ul>
                <li>Fixed Add new row in invoice edit to leave detail description showing or
                    hidden per the state of the other rows.</li>
                <li>Fixed invoice view templates and invoice output templates to correctly
                    display currency system enter using HTML code (aka Pound and Euro)</li>
            </ul>
        </li>
        <li>2019-12-10 - <b>2019.2.13</b>
            <ul>
                <li>To include nebraska invoice template in the standard application.</li>
            </ul>
        </li>
        <li>2019-11-28 - <b>2019.2.12</b>
            <ul>
                <li>Fix the templates/default/cron/add.tpl file domain_id hidden field.</li>
            </ul>
        </li>
        <li>2019-11-25 - <b>2019.2.11</b>
            <ul>
                <li>Set customer default_invoice empty value to 0 so matched field type.</li>
            </ul>
        </li>
        <li>2019-10-15 - <b>2019.2.10</b>
            <ul>
                <li>Add logic to clean up DataTables json file if present.</li>
            </ul>
        </li>
        <li>2019-10-08 - <b>2019.2.9</b>
            <ul>
                <li>Allow equal sign (=) in the config.php value.</li>
            </ul>
        </li>
        <li>2019-10-07 - <b>2019.2.8</b>
            <ul>
                <li>Add pref_id field to the Inv Prefs screens.</li>
            </ul>
        </li>
        <li>2019-10-06 - <b>2019.2.7</b>
            <ul>
                <li>Add email secure parameter to Swift Mailer transport class.</li>
            </ul>
        </li>
        <li>2019-09-29 - <b>2019.2.6</b>
            <ul>
                <li>Add LOG messages to better follow email requests.</li>
                <li>Report thrown exceptions from Email methods.</li>
            </ul>
        </li>
        <li>2019-09-16 - <b>2019.2.5</b>
            <ul>
                <li>Fix prePatch308() result test to allow si_invoice_item_attachments creation if not present.</li>
            </ul>
        </li>
        <li>2019-09-16 - <b>2019.2.4</b>
            <ul>
                <li>Fix setting of no default tax on new product to NULL.</li>
            </ul>
        </li>
        <li>2019-09-13 - <b>2019.2.3</b>
            <ul>
                <li>Modify sql patch 317 to set auto increment on si_payment_types table.</li>
                <li>Update database structure and full definition files to reflect current settings.</li>
            </ul>
        </li>
        <li>2019-09-08 - <b>2019.2.2</b>
            <ul>
                <li>Modify patch management to add si_invoice_item_attachments to database if it doesn't exist.</li>
            </ul>
        </li>
        <li>2019-07-18 - <b>2019.2.1</b>
            <ul>
                <li>Fix issue of Invoice of getAllHavings selection logic.</li>
                <li>Fix date range logic in Sales Report By Period.</li>
            </ul>
        </li>
        <li>2019-07-02 - <b>2019.2.1</b>
            <ul>
                <li>Fix payment to update invoice aging info and remove debug stmt from pdf.</li>
            </ul>
        </li>
        <li>2019-06-15 - <b>2019.2.1</b>
            <ul>
                <li>Modified english email filter to allow multiple emails separated by a semi-colon.</li>
            </ul>
        </li>
        <li>2019-06-13 - <b>2019.2.1</b>
            <ul>
                <li>Changed to format amounts in lists per local.locale setting in the custom.config.php file.</li>
            </ul>
        </li>
        <li>2019-05-31 - <b>2019.2.1</b>
            <ul>
                <li>Fixed logic for cron when both the email biller and customer options are set to yes.</li>
            </ul>
        </li>
        <li>2019-05-21 - <b>2019.2.1</b>
            <ul>
                <li>Fixed to set domain_id for invoice created by recurrence to value from original invoice.</li>
                <li>Fixed setting of updated aging fields that array_merge() missed.</li>
                <li>Remove Invoice::getInvoice() method so new standard method Invoice::getOne() is used.</li>
            </ul>
        </li>
        <li>2019-05-11 - <b>2019.2.1</b>
            <ul>
                <li>Changed to use ZendFramework1 from composer, not library.</li>
            </ul>
        </li>
        <li>2019-04-24 - <b>2019.2.0</b>
            <ul>
                <li>Converted all DB tables to InnoDB using utf8 charset and uft8_unicode_ci collation.</li>
                <li>Add foreign keys to si_invoices.</li>
                <li>Remove foreign key checks in code as they will now occur automatically.</li>
                <li>Modified si_cron delete to also delete associated si_cron_log history records.</li>
                <li>Modified Customer manage page to only show default invoice button if customer is enabled.</li>
                <li>Modified empty database setup - 20190507</li>
            </ul>
        </li>
        <li>2019-03-21 - <b>2019.1.1</b>
            <ul>
                <li>Remove last use of ZendDb ($zendDb), replacing it with PdoDb.</li>
                <li>Use ajax for customer and product tables.</li>
                <li>Update Symphony and related files.</li>
            </ul>
        </li>
        <li>2019-02-21 - <b>2019.1.0</b>
            <ul>
                <li>Major update. Email with PDF attachment replaced with SwiftMailer.</li>
            </ul>
        </li>
        <li>2019-02-17 - <b>2019.0.2</b>
            <ul>
                <li>Correct cron api generation of new invoices copied in total from old invoice.</li>
            </ul>
        </li>
        <li>2019-02-15 - <b>2019.0.1</b>
            <ul>
                <li>Fixed issue where adding item to invoice does not blank the description field.</li>
                <li>Order product list to show enabled products before disabled.</li>
            </ul>
        </li>
        <li>2019-02-11 - <b>2019.0.0</b>
            <ul>
                <li>Renamed namespace_autoload branch to master_2019</li>
            </ul>
        </li>
        <li>2019-02-07 - <b>2018.3.5</b>
            <ul>
                <li>Changed submit button name in tpl files to NOT be 'id'. They are now
                    named 'submit'. The name 'id' should be reserved across the board for
                    an actual field name of 'id' (typically not present or hidden). Otherwise
                    it can trip PdoDb logic that by default builds commands from screen fields.
                    In the case of add templates, this means the value of the submit button
                    was being used for the auto-increment 'id' field.</li>
                <li>Fixed user maintenance logic to use correct domain that was preventing updates.</li>
                <li>Change all occurrences of redirect_redirect to refersh_redirect for consistency.</li>
            </ul>
        </li>
        <li>2019-01-25 - <b>2018.3.4</b>
            <ul>
                <li>Fixed display of customer invoices and invoices owing on Customer view screen.</li>
            </ul>
        </li>
        <li>2019-01-18 - <b>2018.3.3</b>
            <ul>
                <li>Fixed the export button (aka acrobat button) in the invoices list action
                    column. It wasn't displaying update the export option list when selected.</li>
                <li>Fixed the section show hide options on the invoice quick view, itemized entry
                    and details edit screens.</li>
            </ul>
        </li>
        <li>2018-12-30 - <b>2018.3.2</b>
            <ul>
                <li>Modified invoices and payments datatables to use ajax to speed it up.</li>
            </ul>
        </li>
        <li>2018-12-26 - <b>2018.3.1</b>
            <ul>
                <li>Modified to use current version of jquery and jquery-ui files. Using
                    NPM to obtain current version of jquery.</li>
                <li>Replaced Flexigrid tables with Datatables. Datatables version is
                    maintained via NPM. NOTE: The table logic is not yet tuned for performance.
                    This means the process is noticeably slower the more records it has to load.
                    In the future, large tables will be converted to use ajax to load a page of
                    records at a time in much the same way flexigrid tables worked.</li>
                <li>Replaced out of date WYSIWYG rich text editor with the trix-master rich text
                    editor.</li>
                <li>Modified labels in Settings menu to stop wrap around when enhancements
                    displayed "System Preferences" to "SI Defaults", "Invoice Preferences" to
                    "Inv Preferences", "Payment Types" to "Pymt Types", and "Backup Database"
                    to "DB Backup".</li>
                <li>Eliminated dynamic javascript for field validation (validation.php) and
                    replaced with use of jQueryValidationEngine logic throughout.</li>
            </ul>
        </li>
        <li>2018-12-03 - <b>2018.3.0</b>
            <ul>
                <li>Modified templates using a <em>&lt;button ...&gt;</em> tag to cancel so that
                    it uses an anchor tag to exit the page. This makes all maintenance template
                    use the same means to cancel the update/add/delete.</li>
                <li>Cleaned up all PDF and SI errors and warnings issued when E_ALL error
                    mode is set. There is an exceptions for Smarty OPTIONS and CYCLE commands
                    (and possibly others) that attempt to find these functions in the compiled
                    (sysplugins) directory but they are not compiled and are in the plugins
                    directory. This warning is issued when the template using them is first
                    accessed and compiled. Subsequent access does not report an error. Issue
                    reported to Smarty team for hopeful cleanup in a future version.</li>
                <li>Changed to use namespace for class autoloading and add phpunit test support.</li>
                <li>Eliminated "htmlout" template function. Use "outhtml" instead.</li>
                <li>Moved all global functions to classes. Many are in the Util class.</li>
            </ul>
        </li>
        <li>2018-11-12 - <b>2018.2.6</b>
            <ul>
                <li>Added options to the "type" parameter of the Invoices::select_all() method to
                    provide a total owing by itself or combined with the count of invoices. The
                    combined feature is to minimize the data access overhead.</li>
            </ul>
        </li>
        <li>2018-10-27 - <b>2018.2.5</b>
            <ul>
                <li>Fixed issue of default invoice values not populating the new invoice.</li>
            </ul>
        </li>
        <li>2018-10-20 - <b>2018.2.4</b>
            <ul>
                <li>Move the default invoice extension to the main application. This includes
                    adding default_invoice field to the customer file. When the patch is
                    loaded and the module is enabled, the content of custom_field4 will be
                    copied to the new field.</li>
                <li>Moved CustomField.php and BackupDb.php files to the include/class directory.</li>
                <li>Add help information to all the fields on the System Preferences screen. Also
                    modified toggle field to show Enabled/Disabled text rather than 1/0 on the list screen.</li>
                <li>Deleted the text_ui extension as not needed, wasn't working and unmaintained.</li>
            </ul>
        </li>
        <li>2018-10-19 - <b>2018.2.3</b>
            <ul>
                <li>Add sales_representative field to the invoices table and add maintenance
                    support for it. If the inv_custom_field_report extension is enabled, copy
                    the content of the invoice custom_field3 to the new sales_representative
                    field and move the inv_custom_field_report extension to the standard
                    application as Sales by Representative.</li>
                <li>Added Custom Flags button to System Settings screen.</li>
            </ul>
        </li>
        <li>2018-10-17 - <b>2018.2.2</b>
            <ul>
                <li>Added owing to invoices table to fix aging issue.</li>
                <li>Fix inventory sales profit report selection logic.</li>
            </ul>
        </li>
        <li>2018-10-16 - <b>2018.2.1</b>
            <ul>
                <li>Modified templates using obsolete number_formatted function to use the
                    updated siLocal_number function. This fixes display of numbers formatted
                    for the locale set in the custom.config.php file.</li>
            </ul>
        </li>
        <li>2018-10-15 - <b>2018.2.0</b>
            <ul>
                <li>Added interactive calculation of invoice aging. This means:
                    <ol>
                        <li>That when the list of invoices are selected to display in the flexigrid
                            list on the primary invoice screen, the aging information does not have
                            to be calculated thus improving performance.</li>
                        <li>Removal of the Large Dataset system preference option as this change
                            eliminates need for its suppression of aging information calculation.</li>
                    </ol>
                </li>
            </ul>
        </li>
        <li>2018-10-12 - <b>2018.1.3</b>
            <ul>
                <li>Added logic to create and/or update the custom.config.php file. If the file
                    doesn't exist, it will be created by making a copy from the config.php. If
                    it does exist, it will be updated as follows:
                    <ol>
                        <li>If line is in config.php but not custom.config.php, it will be added
                            added to custom.config.php.</li>
                        <li>If line is in custom.config.php but not in config.php, it will be
                            enclosed in a "Possibly deprecated" comment lines.</li>
                        <li>The version name and date will be set to the value in the config.php
                            file.</li>
                    </ol>
                </li>
            </ul>
        </li>
        <li>2018-10-04 - <b>2018.1.2</b>
            <ul>
                <li>2018-10-06 - Minor fix. Modified new install to go to Start Working screen after
                    required biller, customer and product have been set up. Previously went to the
                    list screen of the last of the required items set up (typically Products).</li>
                <li>Moved SQL patch management logic into a new SqlPatchManager class. This change
                    deprecates the sql_patches.php file which is left as an empty file to force it
                    to overload the previous version of the file.</li>
                <li>Used addslashes() to fix issue where single quotes in essential_data.json file
                    sql_statement entries stopped data from being loaded.</li>
            </ul>
        </li>
        <li>2018-10-03 - <b>2018.1.1</b>
            <ul>
                <li>Moved the payments extension into the standard code. This adds a check number field
                    to the payment table. It also changes the logic to return to the invoice management
                    page after a payment is entered.</li>
                <li>Fixed logic supporting department field in the customer table.</li>
                <li>Added last_invoice language index for the default_invoice module.</li>
            </ul>
        </li>
        <li>2018-09-25 - <b>2018.1.0</b>
            <ul>
                <li><b>Production Release</b> User Security extension merged into the standard application.
                    This implements username functionality for logins, password pattern rules maintained in
                    System Preferences and a Session Timeout setting also in System Preferences that allows
                    the minutes a session remains inactive to timeout. It also includes upgrade of the
                    password hash to SHA256 from MD5.</li>
                <li>Documentation extension merged into the standard application to provide custom help
                    message support via the "help" keyword.<br/>
                    Ex:<br/>
                    &lt;a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;help={$cflg.field_help}"
                       title="{$LANG.custom_flags_upper}"&gt;&lt;img src="{$help_image_path}help-small.png" alt="" /&gt;&lt;/a&gt;
                </li>
                <li>Created SystemDefaults class to consolidate function from sql_queries.php.</li>
            </ul>
        </li>
      <li>2018-09-22 - <b>2018.0.0</b>
        <ul>
          <li><b>Production Release</b> Code brought current with PHP 7.2x requirements and all references
                 to the SimpleInvoices forum and wiki have been updated to access the new SimpleInvoices
                 Group website and Fearless359 SimpleInvoices Google+ forum.</li>
          <li>Added a signature field to the si_biller table along with maintenance support to provide
              text that will be automatically included in invoice and statement emails. This was an
              extension and that extension has been removed.</li>
          <li>Moved the Net Income Report extension to be a standard part of the application.</li>
          <li>Moved the Past Due Report extension to be a standard part of the application.</li>
        </ul>
      </li>
      <li>2018-09-12 - <b>2017.4.0</b>
        <ul>
          <li><b>Production Release</b> Removed all deprecated uses of class name for the constructor
                 and updated links for SI documentation to reference the new wiki site.</li>
        </ul>
      </li>
      <li>2018-09-12 - <b>2017.3.0</b>
        <ul>
          <li><b>Production Release</b> Updated to support local.locale setting in the custom.config.php
              (aka config.php) files that use non-english number formats in templates. Also changed <b>Help</b>
              link in the header to reference the new <i>SimpleInvoices.group</i> site which will be updated
              to replace the <i>SimpleInvoices.group</i> site that is no longer available.</li>
        </ul>
      </li>
        <li>2017-12-20 - <b>2017.2.0</b>
            <ul>
                <li><b>Production Release</b> Verified SI working with PHP 7.2</li>
            </ul>
        </li>
      <li>2016-12-27 - <b>2016.1.001</b>
        <ul>
          <li><b>Production Release</b> Verified that this fork of SI will <b>NOT</b> be incorporated into
              the master stream. The reason is that modifications in this stream involve reformatting of code
              which obfuscates changes to the reviewers. Given the amount of work to bring this software
              to the point where it uses the current version of PHP, Zend, Smarty, etc. requires restructuring
              on a large scale, and therefore these changes will be retained.</li>
          <li>Merged Smarty 3 changes into this standard stream.</li>
          <li>Added <b>Information</b> link to the SI banner line between <i>Help</i> and <i>Log out</i>. This
              link displays information about the SI implementation including this change log and other useful
              files stored in the <i>documentation/en-us/general</i> folder.</li>
        </ul>
      </li>
    </ul>
    <ul>
      <li>2016-12-09 - <b>2016.1.beta.1</b>
        <ul>
          <li><b>Update to Smarty v3.1.30:</b> Modifed numerous files to work with updated Smarty version.
          <li><b>Smarty Plugins in extensions.</b> Added ability to specify plugins for extensions in the
              <i>extensions/<b>EXTNAME</b>/include/smarty_plugins</i> directory. Plugin name must be unique
              so it is recommened that the <i><b>EXTNAME</b></i> be part of the name.</li>
        </ul>
      </li>
    </ul>
    <ul>
      <li>2016-08-08 - <b>2016.0.beta.2</b>
        <ul>
          <li><b>General Cleanup</b> Modifed numerous files to correct issues of undefined or uninitialized
              variables and other execution time warnings when <i>strict</i> mode set.
          <li><b>Cron Logic</b> Added delete feature.</li>
          <li><b>Default Invoice</b> extension update to eliminate PHP errors and warnings. Also modified
              to always display detail list with enabled customers first.</li>
          <li><b>Sub-Customers</b> extension enhancements. Changes made are:
            <ul>
              <li>Extension now automatically adds the new <b>parent_customer_id</b> field to the
                  <i>si_customers</i> table when the extension is enabled and the customer information
                  is accessed.</li>
              <li>The credit card information is masked leaving the last four digits showing.</li>
            </ul>
          </li>
          <li>Fixed issue causing standard <i>si_customers</i> to fail when adding new records.</li>
          <li>Added logic to prevent addition of new <i>si_customers</i> record with the same name
              as an existing record.</li>
          <li>Fixed issue with undefined variable issue causing new product records to not post.</li>
        </ul>
      </li>
      <li>2016-07-25 - <b>2016.0.beta.1</b>
        <ul>
          <li>Zend library updated to version 1.12.18
          <p style="padding-left:20px;">
            Removed old library from project. This included removing it as a <b>sub-module</b>
            and adding it as directly supported files. This was necessary as there is no
            ongoing maintenance for <b>Zend Framework 1</b> on Github.
          </p>
          </li>
          <li>Enhancements for extension development
          <p style="padding-left:20px;">
            These changes implement a process that allows files that might be updated by multiple
            extensions to specify only the section that needs to be changed rather than having to
            replicate the entire file. This means there won't be competition between extensions
            having to consider whether or not another extension has been enabled and uses the same
            file. The following files were specifically enhanced to accommodate the likely use in
            multiple extensions:
          </p>
          <ul>
            <li><b>template/default/reports/index.tpl</b>: Modified to contain section identifiers
                at which an extension can specify a menu entry is to be inserted. Use the
                <b>extension/past_due_report/template/default/reports/index.tpl</b> file as an
                example.</li>
            <li><b>templates/default/menu.tpl</b>: Modified to contain section identifiers
                at which an extension can specify a menu entry is to be inserted. Use the
                <b>extension/custom_flags/templates/default/menu.tpl</b> file as an example.</li>
          </ul>
          </li>
          <li>Modified to move popup calendar to move it over a bit.
          <p style="padding-left:20px;">
            If you use a start and end date that are positioned vertically, the popup for
            the start date covers the icon to access the popup for the stop date making it a
            two click process to get to the stop date when no change is made in the start date.
            This change positions the popup to the right so the icons aren't obscured and the
            stop date can be accessed directly.
          </p>
          </li>
          <li>Added <b>Past Due Report</b> extension.
          <p style="padding-left:20px;">
            This change include a report to show customer&#39;s with past due invoices. The
            report can optionally display which invoices are past due for each customer.
            Additionally, this extension adds a <i>smarty template variable: <b>$past_due_amount</b></i>
            for use in the <i>invoice template.</i>
          </p>
          </li>
          <li>Add user definable documentation support. This was added for use by the <b>custom_flags</b>
              extension. This allows the help field text to be accessed from the database and therefore
              can be tailored to field use on each system.</li>
          <li>Change to show user defined field label in help dialog.</li>
          <li>Clean up FAQ document</li>
          <li>General changes to support <b>HTML 5</b>:
            <ul>
              <li>Modified HTML document generation logic to render HTML 5 files. This includes removal
                  of attributes such as, <b>&lt;table align=&quot;center&quot; &gt;</b>.</li>
              <li>Modified all occurrences of <b>&lt;textarea&gt;</b> sections to use <b>class="editor"</b>
                  and eliminate the <b>nowrap</b> attribute.</li>
              <li>Changed all instances of the text, <b>SimpleInvoices</b> to be <b>SimpleInvoices</b>
                  consistent with the logo and name of this applications.</li>
              <li>Remove the <b>class="buttons"</b> values from all <b>&lt;table&gt;</b> tags. The class
                  is commented out in the <b>main.css</b> file.</li>
            </ul>
          </li>
          <li>Changed to minimize maintenance of extension language files. Typically the <b>en_US</b>
              and <b>en_US</b> are both defined in extensions and have the same content. To eliminate
              this doubling of maintenance, the <b>en_US/lang.php</b> file simply includes the content
              of the <b>en_US/lang.php</b> file.</li>
          <li>Formalized the <b>hooks</b> feature so that they will work within extensions. The existing
              <b>custom/hooks.tpl</b> file contains complete documentation on this feature including the
              necessary cautions concerning the drawback of over using them. Basically, extensions should
              be used rather than <b>hooks</b>.</li>
          <li>Fix missing <b>$patchCount</b> and query syntax error in <b>getSystemDefaults()</b>
              method.</li>
          <li>Enhanced <b>install from scratch</b> process to better handle case of no database matching
              the name in the <b>config.php</b> or if present, the <b>custom.config.php</b> file. Also
              cleaned up formatting of screens and added additional information to the screen instructions
              to better assist users with setting <b>SimpleInvoices</b> up.
          </li>
          <li>Added User Security extension.
          <p style="padding-left:20px;">
            This is an important enhancement and is recommended for all users that have internet
            access to their <b>SimpleInvoices</b> system. New features with this enhancement are:
          </p>
          <ul>
            <li>Addition of a <b>username</b> field to the database that will contain the unique ID
                that will be used to log into <b>SimpleInvoices</b>. Initially this is set to
                the <b>email</b> currently used. It can and should be changed by each user when
                they first logon the system. Their <b>email</b> information is retained for
                informational purposes.
            </li>
            <li>Addition of password constraint options maintained in the <b>si_system_defaults</b>
                table. Based on the settings of these fields, a validation pattern is generated
                that will be used to verify user password compliance. The new database fields are:
                <ul>
                  <li><b>password_min_length</b>: Specifies the minimum length user passwords
                      must be. It can be set from <b>6</b> to <b>16</b> and defaults to <b>8</b>
                      when initially enabled.</li>
                  <li><b>password_upper</b>: Specifies if the password should contain at least one
                      upper case character. Set to <b>true</b> when extension is enabled.</li>
                  <li><b>password_lower</b>: Specifies if the password should contain at least one
                      lower case character. Set to <b>true</b> when extension is enabled.</li>
                  <li><b>password_number</b>: Specifies if the password should contain at least one
                      numeric character. Set to <b>true</b> when extension is enabled.</li>
                  <li><b>password_special</b>: Specifies if the password should contain at least one
                      special character. Set to <b>true</b> when extension is enabled.</li>
                </ul>
            </li>
            <li>Enhanced the set up and maintenance of user records for customers and billers.
                This feature already exists and is enhanced by this change to display a dropdown
                list of available settings for the <b>user_id</b> and field help information to
                explain this feature.
            </li>
            <li>Changes to allow your company name to replace the setting in the <b>lang.php</b> file
                as well as specify your company&#39;s logo do display on the logon screen and any other
                place you wish to use it. New fields added to the <i>si_system_defaults</i> table are:
                <ul>
                  <li><b>company_name_item</b>: Company name to replace default value (typically
                      <i>SimpleInvoices</i>. The new value will be used in all places where this language
                      it is currently used.</li>
                  <li><b>company_logo</b>: Name of your logo file. That file must reside in the
                      <i>extensions/user_security/images</i> folder. This will be displayed on the
                      SI logon screen.</li>
                </ul>
            </li>
            <li>Addition of an option to specify in minutes, the time interval for which an inactive session
                will be terminated. Currently, all sessions terminate after 60 minutes of inactivity. This is
                too long for a secured system. The new option allows you to specify a different interval ranging
                from 15 to 999 minutes. New field added to the <i>si_system_defaults</i> table is:
                <ul>
                  <li><b>session_timeout</b>: Number of minutes that a user session will remain alive before
                      being terminated for inactivity. The session expiration interval will be renewed with
                      each screen submission (note that entering data in a field is not considered a submission).</li>
                </ul>
            </li>
            <li>Addition of new image files for help and required help will be used to make them less prominent
                on the user screens. These images are stored in the <b>extensions/user_security/images</b>
                folder and can be replaced by the original images if desired.</li>
          </ul>
          </li>
          <li>Several housekeeping changes:
            <ul>
              <li>Added <b>global</b> statements modified files to eliminate undefined variable warnings
                  for variables defined in other included files.</li>
              <li>Added a test that does nothing to eliminate unused variable warnings for variables
                  that are referenced by other files included following this one. The form of the test
                  is: <b>if ($variable_name) {}</b></li>
              <li>Deleted unused source.</li>
              <li>Excluding HTMLPurifier cache file from git management.</li>
              <li>Provide error log information if email fails.</li>
              <li>Added missing css class to fix display issue on invoice quick view form.</li>
            </ul>
          <li>Modified logic to only display <b>Custom Fields</b> if their associated label is defined.
          <p style="padding-left:20px;">
            Currently custom fields display and function whether they have a label defined of not. This
            change imposes a rule that a label must be defined to activate a custom field cleaning up the
            interface and removes confusion by imposition of this simple requirement.
          </p>
          </li>
          <li>Added new PdoDb class and supporting classes.
          <p style="padding-left:20px;">
            This is a step towards replacement of existing database logic through use of a class that
            will properly format and execute all database requests through a common interface. This
            interface abstracts the underlying database from the application code. The class also supports
            the dynamic build of <b>SQL</b> database maintenance statements, <i>INSERT</i>, <i>UPDATE</i>
            and <i>DELETE</i>, from <b>key</b>/<b>value</b> information defined in the <i>$_POST</i> super
            global. This will allow extensions (or any code) that maintains a database table to perform
            the maintenance request without the need specify table fields to maintain in the <i>PDO</i>
            requests. This also eliminates the need to add table maintenance functions to the
            <i>sql_queries.php</i> file which complicated extension development.
          </p>
          </li>
          <li>Fixed payments to show actual <b>Invoice #</b> rather than the <b>Invoice ID</b>.
          <li>Remove warnings for <b>BROWSE</b> constant redefinition warnings by testing if already defined.</li>
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
          <li>Corrected SQL in report_products_sold_by_customer</li>
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
          <li>Fixed typo temlates to templates in /templates/default/header.tpl on line 57</li>
          <li>Fixed correct location of iehacks.css in the code in include/functions.php line 323</li>
          <li>PHPReports DARWIN OS - $ipsep extended</li>
          <li>PHPReports PHP5 deprecated use of array_push issue fixed</li>
          <li>Invoice PDF and EMail now support spaces in Biller Logo file name</li>
          <li>Invoice PDF EMail now supports spaces in Invoice Preference Name</li>
          <li>Invoice PDF EMail (Boolean variable in config.php) now supports Confirm Reading Receipt to Sender</li>
          <li>screen.css now has th.sortable_rt class - useful for right align for numeric fields - available in the modules/module-name/manage.php files</li>
          <li>General Code cleanup</li>
          <li>Increased limit from 100 to 1000000 billers / clients / inv_prefs - look in modules/invoices/total.php, itemized.php, consulting.php</li>
          <li>Changes in config/config.php made backwards compatible for use with older config.php</li>
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
          <li>Invoices now can be deleted- This is can be enabled via the System Preferences page</li>
          <li>Adding more items to an existing invoice via the edit page it now possible</li>
          <li>New javascript menu included - now works in IEs,FF and Opera</li>
          <li>New language select system - system language can now be changed via the System Preferences page</li>
          <li>All pages move to the smarty templating system</li>
          <li>Updated UI - were slowly moving away from our 37signals style UI to a more unique UI</li>
          <li>PDF system modified - $installation_path no longer required</li>
          <li>Authentication sql table added to the default install - so you now longer have to manually run the login sql to get authentication working</li>
          <li>How total invoices are stored in the DB has changed - now a total invoices gets stored as a product in the products table but set to not visible via the Manage Products page</li>
          <li>System Defaults renamed to System Preferences</li>
          <li>Authentication can now be set via the config.php file - no need to adjust include_auth.php anymore</li>
          <li>Customer add template fixed for if no name entered</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=106">Issue 106</a>Edit tax rates bug fixed</li>
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
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=75">Issue 75</a>Stop browsing to the source .php files</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=90">Issue 90</a>Translation update: save button updates</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=84">Issue 84</a>File cleanup for live grid</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=93">Issue 93</a>Translation: make the redirect pages (ie save.php) translatable</li>
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
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=79">Issue 79</a>Custom field extended to support be in invoices</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=78">Issue 78</a>The ajax info page/alert boxes moved from jQuery thickbox to ibox so as to work with the new open rico live grid</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=81">Issue 81</a>New info page for Custom Fields in voices</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=80">Issue 80</a>New info detailing what custom fields are</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=74">Issue 74</a>Standardised on using English Number system throughout (ie, 2,400.00 instead of 2400)</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=86">Issue 86</a>Live grid modified to work with IT</li>
          <li>Romanian translation added</li>
        </ul>
      </li>
    </ul>
    <ul>
      <li>2007-02-06
        <ul>
          <li>Manage Customer amount owed calculation updated</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=35">Issue 35</a>PDF security issues fixed</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=50">Issue 50</a>Process Payment: Auto populate the amount field with the owed value</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=58">Issue 58</a>Date format woes fixed</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=65">Issue 65</a>Invoice date: make editable</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=72">Issue 72</a>Sort not working correctly - fixed</li>
          <li>Note: If your using authentication please read: <a href="https://simpleinvoices.group/howto" target="_blank">SimpleInvoices Knowledge Base HowTo Page</a> as there have been changes</li>
          <li>SQL Patches
            <ul>
              <li>Adding data to the custom fields table for invoice</li>
              <li>Adding custom fields to the invoices table</li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
    <ul>
      <li>2007-02-02
        <ul>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=73">Issue 73</a>Security: Controller.php doesn't validate the $_GET input</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=71">Issue 71</a>Null Invoice: Consulting style issue</li>
          <li>Manage tables font size reduction</li>
          <li>Known Issue: <a href="http://code.google.com/p/simpleinvoices/issues/detail?id=73">Issue 72</a>Sort not working correctly for numbers</li>
        </ul>
      </li>
    </ul>
    <ul>
      <li>2007-01-25
        <ul>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=68">Issue 68</a>File structure modified</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=69">Issue 69</a>Controller script added and urls system modified</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=70">Issue 70</a>Invoice templates names changed and system modified</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=63">Issue 63</a>MySQL password format changed to MD5</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=63">Issue 66</a>New user interface added</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=64">Issue 64</a>Authentication: Headers already sent drama resolved</li>
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
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=55">Issue 55</a> Custom Fields added to billers, customers, and products</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=30">Issue 30</a> Issue with blank fields and commas being displayed on the invoice has been fixed, now only fields which are not null get displayed and the commas appear in the right places</li>
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
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=30">Issue 43</a> Street address 2 field added to customers and billers</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=30">Issue 32</a> Add amount owed on the invoice print out</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=53">Issue 53</a> MySQL 4 sql file: auto-increment added even though phpMyAdmin doesn't include it in the export (using MySQL 4 comparability)</li>
          <li>Mobile phone field added to customers</li>
          <li>8 sql patches added (number 25 to number 23)</li>
          <li>Known issues with this release:
            <ul>
              <li>PDF Print Preview: The border for the customer and biller details in print_preview.php doesn't show for the telephone fields</li>
              <li>Konqueror: Some of the javascript features don't work in Konqueror</li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
    <ul>
      <li>2006-10-27
        <ul>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=29">Issue 29</a> fixed: Drop down lists now sorted alphabetically</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=31&amp;can=6&amp;q=">Issue 31</a> notes field added to products, billers, and customers</li>
          <li>Customer details page revamped to include a tabbed interface for the notes and invoice listing</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=34&amp;can=2&amp;q=">Issue 34</a> The php include() system has had a cleanup and files can be included in all pages by editing ./include/include_main.php</li>
          <li><a href="http://code.google.com/p/simpleinvoices/issues/detail?id=21&amp;can=2&amp;q=">Issue 21</a> More pages have been made translatable</li>
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
          <li>Index.php - home page renovated - green buttons removed - jQuery accordian menu added</li>
          <li>Issue 25: IE print_preview.ph borders fixed</li>
          <li>Exporting to xls and doc fixed</li>
          <li>German translation added</li>
          <li>Menu text made more easily translatable</li>
          <li>Documentation upgraded to include FAQs</li>
          <li>Creation of a new cache directory to make it easier  to setup SimpleInvoices</li>
        </ul>
      </li>
    </ul>
    <ul>
      <li>2006-10-06
        <ul>
          <li>New invoice template added</li>
          <li>The previous default invoice template 'print_preview.php' has been renamed 'print_preview_basic.php'</li>
          <li>Updating of the invoice templates to resolve Issue 20 - http://code.google.com/p/simpleinvoices/issues/detail?id=20&amp;can=2&amp;q=</li>
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
              <li>CREATE TABLE `si_account_payments` ( `ac_id` INT(10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY , `ac_inv_id` VARCHAR( 10 ) NOT NULL , `ac_amount` DOUBLE( 25, 2 ) NOT NULL , `ac_notes` TEXT NOT NULL , `ac_date` DATETIME NOT NULL );</li>
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
          <li>pdf configs now moved into config/config.php</li>
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
          <li>Database Backup - this now works, so the user can backup the SimpleInvoices database at will
              through SimpleInvoices</li>
          <li>Invoice - Consulting: new invoice type added, this is a cross between and total and an itemized
              invoice. It allows the user to create an invoice with multiple line items and editable item
              descriptions; similar to how a consulting firm creates invoices</li>
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
          <li>Biller logo now a drop down list</li>
          <li>Default invoice template now a drop down list</li>
          <li>System defaults page all option have been enabled</li>
          <li>System defaults actually work :)
            <ul>
              <li>Choosing default biller/customer/tax/preference now make invoice total and itemized default
                  to the selected default</li>
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
          <li>SQL PatchManager (Upgrade Database in the Option menu) has been added, this allows for the upgrade of the SimpleInvoices database from within SimpleInvoices, so the user no longer has to manually run sql scripts when SimpleInvoices is upgraded.</li>
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
                  <li>CREATE TABLE `si_sql_patchmanager` ( `sql_id` INT NOT NULL AUTO_INCREMENT , `sql_patch_ref` VARCHAR( 50 ) NOT NULL , `sql_patch` VARCHAR( 50 ) NOT NULL , `sql_release` VARCHAR( 25 ) NOT NULL , 'sql_statement` TEXT NOT NULL , PRIMARY KEY ( `sql_id` ) );</li>
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
          <li>Invoice Itemized - fix - the unit price was reporting the current unit price when it should of been showing the value of the unit at the time of sale</li>
          <li>Remove unnecessary files</li>
        </ul>
      </li>
    </ul>
    <ul>
      <li>2006-04-28
        <ul>
          <li>Bug fix release
          <li>Tax calculation - fix tax calculation issue as per Jestered email</li>
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
</body>
</html>
