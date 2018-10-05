<?php
/**
 * User: Richard Rowley
 * Date: 10/4/2018
 * Time: 8:16 AM
 */

class SqlPatchManager
{
    // Note that array is zero based but patch ref is 1 based.
    private static $patchLines = array();
    private static $patchCount = 0;

    /**
     * Add an entry to the $patchLines array.
     * @param int $num Number of patch, must match what is expected next.
     * @param array $patch entry to add to $patchLines.
     * @throws PdoDbException if the $num parameter is out of sequence.
     */
    private static function makePatch($num, $patch)
    {
        static $last = 0;
        $last++;

        if ($last != $num) {
            throw new PdoDbException("self::makePatch - Patch #{$num} is out of sequence.");
        }

        self::$patchLines[] = array(
            'name' => $patch['name'],
            'patch' => $patch['patch'],
            'date' => $patch['date']
        );

        self::$patchCount = $num;
    }

    /**
     * Greatest sql_patch_ref value in the sql_patchmanager table.
     * @return int max patch ref value.
     */
    public static function lastPatchApplied()
    {
        global $pdoDb_admin;

        try {
            $pdoDb_admin->setSelectList(array('sql_patch_ref'));
            $pdoDb_admin->setOrderBy(array('sql_patch_ref', 'D'));
            $pdoDb_admin->setLimit(1);
            $rows = $pdoDb_admin->request("SELECT", "sql_patchmanager");
        } catch (PdoDbException $pde) {
            return 0;
        }
        // Returns number of patches applied
        return $rows[0]['sql_patch_ref'];
    }

    /**
     * @return int Count of patches
     * @throws PdoDbException
     */
    public static function numberOfUnappliedPatches()
    {
        // Initialize patch data if not already done
        if (self::$patchCount == 0) {
            self::loadPatches();
        }

        return self::$patchCount - self::lastPatchApplied();
    }

    /**
     * Assign database patches up to date message in smarty "page" variable.
     */
    public static function donePatchesMessage()
    {
        global $smarty;
        $page_info = array(
            'message' => "The database patches are up to date. You can continue working with SimpleInvoices",
            'html' => '<div class="si_toolbar si_toolbar_form"><a href="index.php">HOME</a></div>',
            'refresh' => 3);
        $smarty->assign("page", $page_info);
    }

    /**
     * @param $id
     * @param $patch
     * @return array
     * @throws PdoDbException
     */
    private static function runSqlPatch($id, $patch) {
        global $pdoDb_admin;

        $escaped_id = htmlsafe($id);
        $patch_name = htmlsafe($patch['name']);

        $smarty_row = array();
        try {
            $pdoDb_admin->setSelectAll(true);
            $pdoDb_admin->addSimpleWhere('sql_patch_ref', $id);
            $rows = $pdoDb_admin->request('SELECT', 'sql_patchmanager');
            if (!empty($rows)) {
                // forget about the patch as it has already been run!!
                $smarty_row['text'] = "Skipping SQL patch $escaped_id, $patch_name as it <i>has</i> already been applied";
                $smarty_row['result'] = "skip";
            } else {
                // patch hasn't been run, so run it
                $pdoDb_admin->query($patch['patch']);

                $smarty_row['text'] = "SQL patch $escaped_id, $patch_name <i>has</i> been applied to the database";
                $smarty_row['result'] = "done";

                // now update the ".TB_PREFIX."sql_patchmanager table
                $pdoDb_admin->setFauxPost(array(
                    'sql_patch_ref' => $id,
                    'sql_patch' => $patch['name'],
                    'sql_release' => $patch['date'],
                    'sql_statement' => $patch['patch']
                ));
            }

            if ($pdoDb_admin->request('INSERT', 'sql_patchmanager') == 0) {
                throw new PdoDbException( "SqlPatchManager::runSqlPatch() = Unable to insert into sql_patchmanager.");
            }
        } catch (PdoDbException $pde) {
            throw new PdoDbException("SqlPatchManager::runSqlPatch() - " . $pde->getMessage());
        }

        if ($id == 126) {
            self::patch126();
        }
        return $smarty_row;
    }

    /**
     * Run the unapplied patches.
     */
    public static function runPatches()
    {
        global $pdoDb_admin, $smarty;

        // Initialize patch data if not already done
        if (self::$patchCount == 0) {
            self::loadPatches();
        }

        $rows = $pdoDb_admin->request('SHOW TABLES', 'sql_patchmanager');
        $page_info = array();
        $init_patch_table = true;
        if (count($rows) == 1) {
            $pdoDb_admin->begin();

            $i = 0;
            $error = false;
            $page_info['html'] = '';
            foreach(self::$patchLines as $patch) {
                $i++;
                try {
                    $page_info['rows'][$i] = self::runSqlPatch($i, $patch);
                } catch (PdoDbException $pde) {
                    error_log($pde->getMessage());
                    $page_info['html'] .= "<div class=\"si_message_error\">Unable to process patch #{$i}. Error: " . $pde->getMessage() . "</div>";
                    $error = true;
                    break;
                }
            }

            if ($error) {
                $pdoDb_admin->rollback();
            } else {
                $init_patch_table = false;
                $pdoDb_admin->commit();
                $page_info['message'] = "The database patches have now been applied. You can now start working with SimpleInvoices";
                $page_info['html'] .= "<div class='si_toolbar si_toolbar_form'><a href='index.php'>HOME</a></div>";
                $page_info['refresh'] = 5;
            }
        }

        if ($init_patch_table) {
            $page_info['html'] .= "Step 1 - This is the first time Database Updates has been run";
            $page_info['html'] .= self::initializeSqlPatchTable();
            $page_info['html'] .= "<br />Now that the Database upgrade table has been initialized, click the following button ".
                                        "to return to the Database Upgrade Manager page to run the remaining patches." .
                                  "<div class='si_toolbar si_toolbar_form'><a href='index.php?module=options&amp;view=database_sqlpatches'>Continue</a></div>.";
        }

        $smarty->assign("page", $page_info);
    }

    /**
     * List all patches and their status.
     * @throws PdoDbException
     */
    public static function listPatches()
    {
        global $smarty;
        // Initialize patch data if not already done
        if (self::$patchCount == 0) {
            self::loadPatches();
        }

        $page_info = array();
        $page_info['message'] = "Your version of SimpleInvoices can now be upgraded. With this new release there are database patches that need to be applied";
        $page_info['html'] = '<div class="si_message_install">' .
                               'The list below describes which patches have and have not been applied to the database. ' .
                               'If there are patches that have not been applied, run the Update database by clicking update.' .
                             '</div>'.
                             '<div class="si_message_warning">Warning: Please backup your database before upgrading!</div>' .
                             '<div class="si_toolbar si_toolbar_form">' .
                             '  <a href="index.php?case=run" class=""><img src="images/common/tick.png" alt="" />Update</a>' .
                             '</div>';
        $i = 0;
        foreach(self::$patchLines as $patch) {
            $i++;
            $patch_name = htmlsafe($patch['name']);
            $patch_date = htmlsafe($patch['date']);
            if (self::checkIfSqlPatchApplied($i)) {
                $page_info['rows'][$i]['text'] = "SQL patch $i, $patch_name <i>has</i> already been applied in release $patch_date";
                $page_info['rows'][$i]['result'] = 'skip';
            } else {
                $page_info['rows'][$i]['text'] = "SQL patch $i, $patch_name <span style='color:red !important;'><b>has not</b> been applied to the database</span>";
                $page_info['rows'][$i]['result'] = 'todo';
            }
        }

        $smarty->assign("page", $page_info);
    }

    /**
     * Get all patches.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     * @throws PdoDbException
     */
    public static function sqlPatches() {
        global $pdoDb_admin;
        $pdoDb_admin->addToWhere(new WhereItem(false, "sql_patch"    , "<>", "", false, "OR"));
        $pdoDb_admin->addToWhere(new WhereItem(false, "sql_release"  , "<>", "", false, "OR"));
        $pdoDb_admin->addToWhere(new WhereItem(false, "sql_statement", "<>", "", false));
        $pdoDb_admin->setOrderBy(array(array("sql_release","A"), array("sql_patch_ref","A")));

        $rows = $pdoDb_admin->request("SELECT", "sql_patchmanager");
        return $rows;
    }

    /**
     * Check to see if patch is in database (aka applied).
     * @param $patchRef
     * @return bool true if applied, false if not.
     */
    private static function checkIfSqlPatchApplied($patchRef)
    {
        global $pdoDb_admin;

        if ($patchRef == 0) return true; // start patch always applied
        
        try {
            $pdoDb_admin->addSimpleWhere('sql_patch_ref', $patchRef);
            $rows = $pdoDb_admin->request('SELECT', 'sql_patchmanager');
        } catch (PdoDbException $pde) {
            return false;
        }
        return !empty($rows);
    }

    /**
     * Create the sql_patchmanager table and save initial record in it.
     * @return string
     * @throws PdoDbException if error occurs performing create or table insert.
     */
    private static function initializeSqlPatchTable()
    {
        global $pdoDb_admin;

        $pdoDb_admin->addTableColumns("sql_id"       , "INT"         , "NOT NULL AUTO_INCREMENT PRIMARY KEY");
        $pdoDb_admin->addTableColumns("sql_patch_ref", "VARCHAR( 50)", "NOT NULL");
        $pdoDb_admin->addTableColumns("sql_patch"    , "VARCHAR(255)", "NOT NULL");
        $pdoDb_admin->addTableColumns("sql_release"  , "VARCHAR( 25)", "NOT NULL");
        $pdoDb_admin->addTableColumns("sql_statement", "TEXT"        , "NOT NULL");
        $pdoDb_admin->addTableEngine("MYISAM");
        if (!$pdoDb_admin->request("CREATE TABLE", "sql_patchmanager")) {
            throw new PdoDbException("SqlPatchManager::initializeSqlPatchTable() - Unable to create sql_patchmanager table.");
        }

        $log = "<b>Step 2</b> - The SQL patch table has been created<br />";

        $lastCommand = $pdoDb_admin->getLastCommand();
        $pdoDb_admin->setFauxPost(array(
                'sql_patch_ref' => '1',
                'sql_patch' => 'Create " . TB_PREFIX . "sql_patchmanger table',
                'sql_release' => '20060514',
                'sql_statement' => $lastCommand
        ));
        if ($pdoDb_admin->request('INSERT', 'sql_patchmanager') == 0) {
            throw new PdoDbException("SqlPatchManager::initializeSqlPatchTable() - Unable to save create sql_patchmanager record in table.");
        }

        $log .= "<b>Step 3</b> - The SQL patch has been inserted into the SQL patch table<br />";

        return $log;
    }

    /**
     * Special handling for patch #126
     * @throws PdoDbException
     */
    private static function patch126()
    {
        global $pdoDb_admin;

        $pdoDb_admin->addSimpleWhere('product_id', 0);
        $rows = $pdoDb_admin->request('SELECT', 'invoice_items');
        foreach ($rows as $row) {
            $pdoDb_admin->setSelectList(array (
                'description' => $row['description'],
                'unit_price' => $row['gross_total'],
                'enabled' => DISABLED,
                'visible' => DISABLED));
            $id = $pdoDb_admin->request('INSERT', 'invoice_items');

            $pdoDb_admin->setFauxPost(array('product_id' => $id, 'unit_price' => $row['gross_total']));
            $pdoDb_admin->addSimpleWhere('id', $row['id']);
            if (!$pdoDb_admin->request('UPDATE', 'invoice_items')) {
                throw new PdoDbException("'SqlPatchManager::patch126() - Update invoice_items error for " .
                    "id[{$row['id']}] product_id[$id] unit_price[{$row['gross_total']}]");
            }
        }
    }

    /**
     * Load all patches to be processed.
     * @throws PdoDbException
     */
    private static function loadPatches()
    {
        global $config, $LANG, $language, $pdoDb_admin;

        // @formatter:off
        $domain_id = domain_id::get();

        $defaults = array();
        if (self::lastPatchApplied() < 124) {
            // System defaults conversion patch. Defaults query and DEFAULT NUMBER OF LINE ITEMS
            $pdoDb_admin->setSelectAll(true);
            $rows = $pdoDb_admin->request('SELECT', 'defaults');
            $defaults = (empty($rows) ? array() : $rows[0]);
        }

        $patch = array(
            'name' => "Create sql_patchmanager table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "sql_patchmanager` (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                                          sql_patch_ref VARCHAR( 50 ) NOT NULL,
                                                                          sql_patch VARCHAR( 255 ) NOT NULL ,
                                                                          sql_release VARCHAR( 25 ) NOT NULL,
                                                                          sql_statement TEXT NOT NULL)
                                                                          ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
            'date' => "20060514"
        );
        self::makePatch('1', $patch);

        $patch = array(
            'name' => "Update invoice no details to have a default currency sign",
            'patch' => "UPDATE `" . TB_PREFIX . "preferences` SET pref_currency_sign = '$' WHERE pref_id =2 LIMIT 1",
            'date' => "20060514"
        );
        self::makePatch('2', $patch);

        $patch = array(
            'name' => "Add a row into the defaults table to handle the default number of line items",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "defaults` ADD def_number_line_items INT( 25 ) NOT NULL",
            'date' => "20060514"
        );
        self::makePatch('3', $patch);

        $patch = array(
            'name' => "Set the default number of line items to 5",
            'patch' => "UPDATE `" . TB_PREFIX . "defaults` SET def_number_line_items = 5 WHERE def_id =1 LIMIT 1",
            'date' => "20060514"
        );
        self::makePatch('4', $patch);

        $patch = array(
            'name' => "Add logo and invoice footer support to biller",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD b_co_logo VARCHAR( 50 ), ADD b_co_footer TEXT",
            'date' => "20060514"
        );
        self::makePatch('5', $patch);

        $patch = array(
            'name' => "Add default invoice template option",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "defaults` ADD def_inv_template VARCHAR( 25 ) DEFAULT 'print_preview.php' NOT NULL",
            'date' => "20060514"
        );
        self::makePatch('6', $patch);

        $patch = array(
            'name' => "Edit tax description field length to 50",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` CHANGE tax_description tax_description VARCHAR( 50 ) DEFAULT NULL",
            'date' => "20060526"
        );
        self::makePatch('7', $patch);

        $patch = array(
            'name' => "Edit default invoice template field length to 50",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "defaults` CHANGE def_inv_template def_inv_template VARCHAR( 50 ) DEFAULT NULL",
            'date' => "20060526"
        );
        self::makePatch('8', $patch);

        $patch = array(
            'name' => "Add consulting style invoice",
            'patch' => "INSERT INTO `" . TB_PREFIX . "invoice_type` ( inv_ty_id , inv_ty_description ) VALUES (3, 'Consulting')",
            'date' => "20060531"
        );
        self::makePatch('9', $patch);

        $patch = array(
            'name' => "Add enabled to biller",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD b_enabled varchar(1) NOT NULL default '1'",
            'date' => "20060815"
        );
        self::makePatch('10', $patch);

        $patch = array(
            'name' => "Add enabled to customers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD c_enabled varchar(1) NOT NULL default '1'",
            'date' => "20060815"
        );
        self::makePatch('11', $patch);

        $patch = array(
            'name' => "Add enabled to preferences",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD pref_enabled varchar(1) NOT NULL default '1'",
            'date' => "20060815"
        );
        self::makePatch('12', $patch);

        $patch = array(
            'name' => "Add enabled to products",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD prod_enabled varchar(1) NOT NULL default '1'",
            'date' => "20060815"
        );
        self::makePatch('13', $patch);

        $patch = array(
            'name' => "Add enabled to products",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` ADD tax_enabled varchar(1) NOT NULL default '1'",
            'date' => "20060815"
        );
        self::makePatch('14', $patch);

        $patch = array(
            'name' => "Add tax_id into invoice_items table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` ADD inv_it_tax_id VARCHAR( 25 ) NOT NULL default '0' AFTER inv_it_unit_price",
            'date' => "20060815"
        );
        self::makePatch('15', $patch);

        $patch = array(
            'name' => "Add Payments table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "account_payments` (`ac_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                                          `ac_inv_id` VARCHAR( 10 ) NOT NULL ,
                                                                          `ac_amount` DOUBLE( 25, 2 ) NOT NULL ,
                                                                          `ac_notes` TEXT NOT NULL ,
                                                                          `ac_date` DATETIME NOT NULL)
                                                                           ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20060827"
        );
        self::makePatch('16', $patch);

        $patch = array(
            'name' => "Adjust data type of quantity field",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_quantity` `inv_it_quantity` FLOAT NOT NULL DEFAULT '0'",
            'date' => "20060827"
        );
        self::makePatch('17', $patch);

        $patch = array(
            'name' => "Create Payment Types table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "payment_types` (`pt_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                                       `pt_description` VARCHAR( 250 ) NOT NULL ,
                                                                       `pt_enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1')
                                                                        ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
            'date' => "20060909"
        );
        self::makePatch('18', $patch);

        $patch = array(
            'name' => "Add info into the Payment Type table",
            'patch' => "INSERT INTO `" . TB_PREFIX . "payment_types` ( `pt_id` , `pt_description` ) VALUES (NULL , 'Cash'), (NULL , 'Credit Card')",
            'date' => "20060909"
        );
        self::makePatch('19', $patch);

        $patch = array(
            'name' => "Adjust accounts payments table to add a type field",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "account_payments` ADD `ac_payment_type` INT( 10 ) NOT NULL DEFAULT '1'",
            'date' => "20060909"
        );
        self::makePatch('20', $patch);

        $patch = array(
            'name' => "Adjust the defaults table to add a payment type field",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "defaults` ADD `def_payment_type` VARCHAR( 25 ) DEFAULT '1'",
            'date' => "20060909"
        );
        self::makePatch('21', $patch);

        $patch = array(
            'name' => "Add note field to customer",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD `c_notes` TEXT NULL AFTER `c_email`",
            'date' => "20061026"
        );
        self::makePatch('22', $patch);

        $patch = array(
            'name' => "Add note field to Biller",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD `b_notes` TEXT NULL AFTER `b_co_footer`",
            'date' => "20061026"
        );
        self::makePatch('23', $patch);

        $patch = array(
            'name' => "Add note field to Products",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `prod_notes` TEXT NOT NULL AFTER `prod_unit_price`",
            'date' => "20061026"
        );
        self::makePatch('24', $patch);

        /*Custom fields patches - start */
        $patch = array(
            'name' => "Add street address 2 to customers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD `c_street_address2` VARCHAR( 50 ) AFTER `c_street_address` ",
            'date' => "20061211"
        );
        self::makePatch('25', $patch);

        $patch = array(
            'name' => "Add custom fields to customers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD `c_custom_field1` VARCHAR( 50 ) AFTER `c_notes` ,
                                                                 ADD `c_custom_field2` VARCHAR( 50 ) AFTER `c_custom_field1` ,
                                                                 ADD `c_custom_field3` VARCHAR( 50 ) AFTER `c_custom_field2` ,
                                                                 ADD `c_custom_field4` VARCHAR( 50 ) AFTER `c_custom_field3` ;",
            'date' => "20061211"
        );
        self::makePatch('26', $patch);

        $patch = array(
            'name' => "Add mobile phone to customers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD `c_mobile_phone` VARCHAR( 50 ) AFTER `c_phone`",
            'date' => "20061211"
        );
        self::makePatch('27', $patch);

        $patch = array(
            'name' => "Add street address 2 to billers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD `b_street_address2` VARCHAR( 50 ) AFTER `b_street_address` ",
            'date' => "20061211"
        );
        self::makePatch('28', $patch);

        $patch = array(
            'name' => "Add custom fields to billers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD `b_custom_field1` VARCHAR( 50 ) AFTER `b_notes` ,
                                                              ADD `b_custom_field2` VARCHAR( 50 ) AFTER `b_custom_field1` ,
                                                              ADD `b_custom_field3` VARCHAR( 50 ) AFTER `b_custom_field2` ,
                                                              ADD `b_custom_field4` VARCHAR( 50 ) AFTER `b_custom_field3` ;",
            'date' => "20061211"
        );
        self::makePatch('29', $patch);

        $patch = array(
            'name' => "Creating the custom fields table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "custom_fields` (`cf_id` INT NOT NULL AUTO_INCREMENT ,
                                                                       `cf_custom_field` VARCHAR( 50 ) NOT NULL ,
                                                                       `cf_custom_label` VARCHAR( 50 ) ,
                                                                       `cf_display` VARCHAR( 1 ) DEFAULT '1' NOT NULL ,
                               PRIMARY KEY(`cf_id`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20061211"
        );
        self::makePatch('30', $patch);

        $patch = array(
            'name' => "Adding data to the custom fields table",
            'patch' => "INSERT INTO `" . TB_PREFIX . "custom_fields` ( `cf_id` , `cf_custom_field` , `cf_custom_label` , `cf_display` )
                        VALUES (NULL,'biller_cf1'  ,NULL,'0'),(NULL,'biller_cf2'  ,NULL,'0'),(NULL,'biller_cf3'  ,NULL,'0'),(NULL,'biller_cf4'  ,NULL,'0'),
                               (NULL,'customer_cf1',NULL,'0'),(NULL,'customer_cf2',NULL,'0'),(NULL,'customer_cf3',NULL,'0'),(NULL,'customer_cf4',NULL,'0'),
                               (NULL,'product_cf1' ,NULL,'0'),(NULL,'product_cf2' ,NULL,'0'),(NULL,'product_cf3' ,NULL,'0'),(NULL,'product_cf4' ,NULL,'0');",
            'date' => "20061211"
        );
        self::makePatch('31', $patch);

        $patch = array(
            'name' => "Adding custom fields to products",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `prod_custom_field1` VARCHAR( 50 ) AFTER `prod_unit_price`,
                                                                ADD `prod_custom_field2` VARCHAR( 50 ) AFTER `prod_custom_field1`,
                                                                ADD `prod_custom_field3` VARCHAR( 50 ) AFTER `prod_custom_field2`,
                                                                ADD `prod_custom_field4` VARCHAR( 50 ) AFTER `prod_custom_field3`;",
            'date' => "20061211"
        );
        self::makePatch('32', $patch);

        $patch = array(
            'name' => "Alter product custom field 4",
            'patch' => "UPDATE `" . TB_PREFIX . "custom_fields` SET `cf_custom_field` = 'product_cf4' WHERE `" . TB_PREFIX . "custom_fields`.`cf_id` =12 LIMIT 1 ;",
            'date' => "20061214"
        );
        self::makePatch('33', $patch);

        $patch = array(
            'name' => "Reset invoice template to default refer Issue 70",
            'patch' => "UPDATE `" . TB_PREFIX . "defaults` SET `def_inv_template` = 'default' WHERE `def_id` =1 LIMIT 1;",
            'date' => "20070125"
        );
        self::makePatch('34', $patch);

        $patch = array(
            'name' => "Adding data to the custom fields table for invoices",
            'patch' => "INSERT INTO `" . TB_PREFIX . "custom_fields` ( `cf_id` , `cf_custom_field` , `cf_custom_label` , `cf_display` )
                        VALUES (NULL,'invoice_cf1',NULL,'0'),(NULL,'invoice_cf2',NULL,'0'),(NULL,'invoice_cf3',NULL,'0'),(NULL,'invoice_cf4',NULL,'0');",
            'date' => "20070204"
        );
        self::makePatch('35', $patch);

        $patch = array(
            'name' => "Adding custom fields to the invoices table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD `invoice_custom_field1` VARCHAR( 50 ) AFTER `inv_date` ,
                                                                ADD `invoice_custom_field2` VARCHAR( 50 ) AFTER `invoice_custom_field1` ,
                                                                ADD `invoice_custom_field3` VARCHAR( 50 ) AFTER `invoice_custom_field2` ,
                                                                ADD `invoice_custom_field4` VARCHAR( 50 ) AFTER `invoice_custom_field3` ;",
            'date' => "20070204"
        );
        self::makePatch('36', $patch);

        $patch = array(
            'name' => "Reset invoice template to default due to new invoice template system",
            'patch' => "UPDATE `" . TB_PREFIX . "defaults` SET `def_inv_template` = 'default' WHERE `def_id` =1 LIMIT 1 ;",
            'date' => "20070523"
        );
        self::makePatch('37', $patch);

        $patch = array(
            'name' => "Alter custom field table - field length now 255 for field name",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "custom_fields` CHANGE `cf_custom_field` `cf_custom_field` VARCHAR( 255 )",
            'date' => "20070523"
        );
        self::makePatch('38', $patch);

        $patch = array(
            'name' => "Alter custom field table - field length now 255 for field label",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "custom_fields` CHANGE `cf_custom_label` `cf_custom_label` VARCHAR( 255 )",
            'date' => "20070523"
        );
        self::makePatch('39', $patch);

        $patch = array(
            'name' => "Alter field name in sql_patchmanager",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "sql_patchmanager` CHANGE `sql_patch` `sql_patch` VARCHAR( 255 ) NOT NULL",
            'date' => "20070523"
        );
        self::makePatch('40', $patch);

        $patch = array(
            'name' => "Alter field name in " . TB_PREFIX . "account_payments",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "account_payments` CHANGE  `ac_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
            'date' => "20070523"
        );
        self::makePatch('41', $patch);

        $patch = array(
            'name' => "Alter field name b_name to name",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_name`  `name` VARCHAR( 255 ) NULL DEFAULT NULL;",
            'date' => "20070523"
        );
        self::makePatch('42', $patch);

        $patch = array(
            'name' => "Alter field name b_id to id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
            'date' => "20070523"
        );
        self::makePatch('43', $patch);

        $patch = array(
            'name' => "Alter field name b_street_address to street_address",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_street_address`  `street_address` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('44', $patch);

        $patch = array(
            'name' => "Alter field name b_street_address2 to street_address2",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_street_address2`  `street_address2` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('45', $patch);

        $patch = array(
            'name' => "Alter field name b_city to city",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_city`  `city` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('46', $patch);

        $patch = array(
            'name' => "Alter field name b_state to state",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_state`  `state` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('47', $patch);

        $patch = array(
            'name' => "Alter field name b_zip_code to zip_code",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_zip_code`  `zip_code` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('48', $patch);

        $patch = array(
            'name' => "Alter field name b_country to country",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_country`  `country` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('49', $patch);

        $patch = array(
            'name' => "Alter field name b_phone to phone",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_phone`  `phone` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('50', $patch);

        $patch = array(
            'name' => "Alter field name b_mobile_phone to mobile_phone",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_mobile_phone`  `mobile_phone` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('51', $patch);

        $patch = array(
            'name' => "Alter field name b_fax to fax",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_fax`  `fax` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('52', $patch);

        $patch = array(
            'name' => "Alter field name b_email to email",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_email`  `email` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('53', $patch);

        $patch = array(
            'name' => "Alter field name b_co_logo to logo",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_co_logo`  `logo` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('54', $patch);

        $patch = array(
            'name' => "Alter field name b_co_footer to footer",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_co_footer` `footer` TEXT NULL DEFAULT NULL ",
            'date' => "20070523"
        );
        self::makePatch('55', $patch);

        $patch = array(
            'name' => "Alter field name b_notes to notes",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_notes` `notes` TEXT NULL DEFAULT NULL ",
            'date' => "20070523"
        );
        self::makePatch('56', $patch);

        $patch = array(
            'name' => "Alter field name b_enabled to enabled",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'",
            'date' => "20070523"
        );
        self::makePatch('57', $patch);

        $patch = array(
            'name' => "Alter field name b_custom_field1 to custom_field1",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('58', $patch);

        $patch = array(
            'name' => "Alter field name b_custom_field2 to custom_field2",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('59', $patch);

        $patch = array(
            'name' => "Alter field name b_custom_field3 to custom_field3",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('60', $patch);

        $patch = array(
            'name' => "Alter field name b_custom_field4 to custom_field4",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('61', $patch);

        $patch = array(
            'name' => "Introduce system_defaults table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "system_defaults` (`id` int(11) NOT NULL auto_increment,
                                                                         `name` varchar(30) NOT NULL,
                                                                         `value` varchar(30) NOT NULL,
                               PRIMARY KEY  (`id`),
                               UNIQUE KEY `name` (`name`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
            'date' => "20070523"
        );
        self::makePatch('62', $patch);

        $patch = array(
            'name' => "Inserts data into the system_defaults table",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` (`id`, `name`, `value`)
                        VALUES
                          ( 1, 'biller'         , '4'),
                          ( 2, 'customer'       , '3'),
                          ( 3, 'tax'            , '1'),
                          ( 4, 'preference'     , '1'),
                          ( 5, 'line_items'     , '5'),
                          ( 6, 'template'       , 'default'),
                          ( 7, 'payment_type'   , '1'),
                          ( 8, 'language'       , 'en'),
                          ( 9, 'dateformat'     , 'Y-m-d'),
                          (10, 'spreadsheet'    , 'xls'),
                          (11, 'wordprocessor'  , 'doc'),
                          (12, 'pdfscreensize'  , '800'),
                          (13, 'pdfpapersize'   , 'A4'),
                          (14, 'pdfleftmargin'  , '15'),
                          (15, 'pdfrightmargin' , '15'),
                          (16, 'pdftopmargin'   , '15'),
                          (17, 'pdfbottommargin', '15'),
                          (18, 'emailhost'      , 'localhost'),
                          (19, 'emailusername'  , ''),
                          (20, 'emailpassword'  , '');",
            'date' => "20070523"
        );
        self::makePatch('63', $patch);

        $patch = array(
            'name' => "Alter field name prod_id to id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT",
            'date' => "20070523"
        );
        self::makePatch('64', $patch);

        $patch = array(
            'name' => "Alter field name prod_description to description",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_description` `description` TEXT NOT NULL ",
            'date' => "20070523"
        );
        self::makePatch('65', $patch);

        $patch = array(
            'name' => "Alter field name prod_unit_price to unit_price",
            'patch' => " ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_unit_price` `unit_price` DECIMAL( 25, 2 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('66', $patch);

        $patch = array(
            'name' => "Alter field name prod_custom_field1 to custom_field1",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('67', $patch);

        $patch = array(
            'name' => "Alter field name prod_custom_field2 to custom_field2",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('68', $patch);

        $patch = array(
            'name' => "Alter field name prod_custom_field3 to custom_field3",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('69', $patch);

        $patch = array(
            'name' => "Alter field name prod_custom_field4 to custom_field4",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('70', $patch);

        $patch = array(
            'name' => "Alter field name prod_notes to notes",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_notes` `notes` TEXT NOT NULL",
            'date' => "20070523"
        );
        self::makePatch('71', $patch);

        $patch = array(
            'name' => "Alter field name prod_enabled to enabled",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'",
            'date' => "20070523"
        );
        self::makePatch('72', $patch);

        $patch = array(
            'name' => "Alter field name c_id to id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
            'date' => "20070523"
        );
        self::makePatch('73', $patch);

        $patch = array(
            'name' => "Alter field name c_attention to attention",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_attention` `attention` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('74', $patch);

        $patch = array(
            'name' => "Alter field name c_name to name",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_name` `name` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('75', $patch);

        $patch = array(
            'name' => "Alter field name c_street_address to street_address",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_street_address` `street_address` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('76', $patch);

        $patch = array(
            'name' => "Alter field name c_street_address2 to street_address2",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_street_address2` `street_address2` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('77', $patch);

        $patch = array(
            'name' => "Alter field name c_city to city",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_city` `city` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('78', $patch);

        $patch = array(
            'name' => "Alter field name c_state to state",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_state` `state` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('79', $patch);

        $patch = array(
            'name' => "Alter field name c_zip_code to zip_code",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_zip_code` `zip_code` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('80', $patch);

        $patch = array(
            'name' => "Alter field name c_country to country",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_country` `country` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('81', $patch);

        $patch = array(
            'name' => "Alter field name c_phone to phone",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_phone` `phone` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('82', $patch);

        $patch = array(
            'name' => "Alter field name c_mobile_phone to mobile_phone",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_mobile_phone` `mobile_phone` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('83', $patch);

        $patch = array(
            'name' => "Alter field name c_fax to fax",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_fax` `fax` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('84', $patch);

        $patch = array(
            'name' => "Alter field name c_email to email",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_email` `email` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('85', $patch);

        $patch = array(
            'name' => "Alter field name c_notes to notes",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_notes` `notes` TEXT  NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('86', $patch);

        $patch = array(
            'name' => "Alter field name c_custom_field1 to custom_field1",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('87', $patch);

        $patch = array(
            'name' => "Alter field name c_custom_field2 to custom_field2",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('88', $patch);

        $patch = array(
            'name' => "Alter field name c_custom_field3 to custom_field3",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('89', $patch);

        $patch = array(
            'name' => "Alter field name c_custom_field4 to custom_field4",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('90', $patch);

        $patch = array(
            'name' => "Alter field name c_enabled to enabled",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'",
            'date' => "20070523"
        );
        self::makePatch('91', $patch);

        $patch = array(
            'name' => "Alter field name inv_id to id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
            'date' => "20070523"
        );
        self::makePatch('92', $patch);

        $patch = array(
            'name' => "Alter field name inv_biller_id to biller_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_biller_id` `biller_id` INT( 10 ) NOT NULL DEFAULT '0'",
            'date' => "20070523"
        );
        self::makePatch('93', $patch);

        $patch = array(
            'name' => "Alter field name inv_customer_id to customer_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_customer_id` `customer_id` INT( 10 ) NOT NULL DEFAULT '0'",
            'date' => "20070523"
        );
        self::makePatch('94', $patch);

        $patch = array(
            'name' => "Alter field name inv_type type_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_type` `type_id` INT( 10 ) NOT NULL DEFAULT '0'",
            'date' => "20070523"
        );
        self::makePatch('95', $patch);

        $patch = array(
            'name' => "Alter field name inv_preference to preference_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_preference` `preference_id` INT( 10 ) NOT NULL DEFAULT '0'",
            'date' => "20070523"
        );
        self::makePatch('96', $patch);

        $patch = array(
            'name' => "Alter field name inv_date to date",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_date` `date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'date' => "20070523"
        );
        self::makePatch('97', $patch);

        $patch = array(
            'name' => "Alter field name invoice_custom_field1 to custom_field1",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `invoice_custom_field1` `custom_field1` VARCHAR( 50 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('98', $patch);

        $patch = array(
            'name' => "Alter field name invoice_custom_field2 to custom_field2",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `invoice_custom_field2` `custom_field2` VARCHAR( 50 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('99', $patch);

        $patch = array(
            'name' => "Alter field name invoice_custom_field3 to custom_field3",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `invoice_custom_field3` `custom_field3` VARCHAR( 50 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('100', $patch);

        $patch = array(
            'name' => "Alter field name invoice_custom_field4 to custom_field4",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `invoice_custom_field4` `custom_field4` VARCHAR( 50 ) NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('101', $patch);

        $patch = array(
            'name' => "Alter field name inv_note to note ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_note` `note` TEXT NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('102', $patch);

        $patch = array(
            'name' => "Alter field name inv_it_id to id ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
            'date' => "20070523"
        );
        self::makePatch('103', $patch);

        $patch = array(
            'name' => "Alter field name inv_it_invoice_id to invoice_id ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_invoice_id` `invoice_id` INT( 10 ) NOT NULL DEFAULT '0'",
            'date' => "20070523"
        );
        self::makePatch('104', $patch);

        $patch = array(
            'name' => "Alter field name inv_it_quantity to quantity ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_quantity` `quantity` FLOAT NOT NULL DEFAULT '0'",
            'date' => "20070523"
        );
        self::makePatch('105', $patch);

        $patch = array(
            'name' => "Alter field name inv_it_product_id to product_id ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_product_id` `product_id` INT( 10 ) NULL DEFAULT '0'",
            'date' => "20070523"
        );
        self::makePatch('106', $patch);

        $patch = array(
            'name' => "Alter field name inv_it_unit_price to unit_price ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_unit_price` `unit_price` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'",
            'date' => "20070523"
        );
        self::makePatch('107', $patch);

        $patch = array(
            'name' => "Alter field name inv_it_tax_id to tax_id  ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_tax_id` `tax_id` VARCHAR( 25 ) NOT NULL DEFAULT '0'",
            'date' => "20070523"
        );
        self::makePatch('108', $patch);

        $patch = array(
            'name' => "Alter field name inv_it_tax to tax  ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_tax` `tax` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'",
            'date' => "20070523"
        );
        self::makePatch('109', $patch);

        $patch = array(
            'name' => "Alter field name inv_it_tax_amount to tax_amount  ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_tax_amount` `tax_amount` DOUBLE( 25, 2 ) NULL DEFAULT NULL ",
            'date' => "20070523"
        );
        self::makePatch('110', $patch);

        $patch = array(
            'name' => "Alter field name inv_it_gross_total to gross_total ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_gross_total` `gross_total` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'",
            'date' => "20070523"
        );
        self::makePatch('111', $patch);

        $patch = array(
            'name' => "Alter field name inv_it_description to description ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_description` `description` TEXT NULL DEFAULT NULL",
            'date' => "20070523"
        );
        self::makePatch('112', $patch);

        $patch = array(
            'name' => "Alter field name inv_it_total to total",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_total` `total` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'",
            'date' => "20070523"
        );
        self::makePatch('113', $patch);

        $patch = array(
            'name' => "Add logging table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "log` (`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                             `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
                                                             `userid` INT NOT NULL ,
                                                             `sqlquerie` TEXT NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20070523"
        );
        self::makePatch('114', $patch);

        $patch = array(
            'name' => "Add logging system preference",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` ( `id` , `name` , `value` ) VALUES (NULL , 'logging', '0');",
            'date' => "20070523"
        );
        self::makePatch('115', $patch);

        $patch = array(
            'name' => "System defaults conversion patch - set default biller",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = $defaults[def_biller] where name = 'biller'",
            'date' => "20070523"
        );
        self::makePatch('116', $patch);

        $patch = array(
            'name' => "System defaults conversion patch - set default customer",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = $defaults[def_customer] where name = 'customer'",
            'date' => "20070523"
        );
        self::makePatch('117', $patch);

        $patch = array(
            'name' => "System defaults conversion patch - set default tax",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = $defaults[def_tax] where name = 'tax'",
            'date' => "20070523"
        );
        self::makePatch('118', $patch);

        $patch = array(
            'name' => "System defaults conversion patch - set default invoice reference",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = $defaults[def_inv_preference] where name = 'preference'",
            'date' => "20070523"
        );
        self::makePatch('119', $patch);

        $patch = array(
            'name' => "System defaults conversion patch - set default number of line items",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = $defaults[def_number_line_items] where name = 'line_items'",
            'date' => "20070523"
        );
        self::makePatch('120', $patch);

        $patch = array(
            'name' => "System defaults conversion patch - set default invoice template",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = '$defaults[def_inv_template]' where name = 'template'",
            'date' => "20070523"
        );
        self::makePatch('121', $patch);

        $patch = array(
            'name' => "System defaults conversion patch - set default paymemt type",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = $defaults[def_payment_type] where name = 'payment_type'",
            'date' => "20070523"
        );
        self::makePatch('122', $patch);

        $patch = array(
            'name' => "Add option to delete invoices into the system_defaults table",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` (`id`, `name`, `value`) VALUES (NULL, 'delete', 'N');",
            'date' => "200709"
        );
        self::makePatch('123', $patch);

        $patch = array(
            'name' => "Set default language in new lang system",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = 'en-gb' where name ='language';",
            'date' => "200709"
        );
        self::makePatch('124', $patch);

        $patch = array(
            'name' => "Change log table that usernames are also possible as id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "log` CHANGE `userid` `userid` VARCHAR( 40 ) NOT NULL DEFAULT '0'",
            'date' => "200709"
        );
        self::makePatch('125', $patch);

        $patch = array(
            'name' => "Add visible attribute to the products table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD  `visible` BOOL NOT NULL DEFAULT  '1';",
            'date' => "200709"
        );
        self::makePatch('126', $patch);

        $patch = array(
            'name' => "Add last_id to logging table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "log` ADD  `last_id` INT NULL ;",
            'date' => "200709"
        );
        self::makePatch('127', $patch);

        $u = (checkTableExists(TB_PREFIX . 'users'));
        $ud = (checkFieldExists(TB_PREFIX . 'users', 'user_domain'));
        $patch = array(
            'name' => "Add user table",
            'patch' => ($u ? ($ud ? "SELECT * FROM " . TB_PREFIX . "users;" :
                                    "ALTER TABLE `" . TB_PREFIX . "users` ADD `user_domain` VARCHAR( 255 ) NOT NULL AFTER `user_group`;") :
                                    "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "users` (`user_id` int(11) NOT NULL auto_increment,
                                                                                          `user_email` varchar(255) NOT NULL,
                                                                                          `user_name` varchar(255) NOT NULL,
                                                                                          `user_group` varchar(255) NOT NULL,
                                                                                          `user_domain` varchar(255) NOT NULL,
                                                                                          `user_password` varchar(255) NOT NULL,
                                            PRIMARY KEY(`user_id`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"),
            'date' => "200709"
        );
        self::makePatch('128', $patch);
        unset($u);
        unset($ud);

        $patch = array(
            'name' => "Fill user table with default values",
            'patch' => "INSERT INTO `" . TB_PREFIX . "users` (`user_id`, `user_email`, `user_name`, `user_group`, `user_domain`, `user_password`)
                        VALUES (NULL, 'demo@simpleinvoices.group', 'demo', '1', '1', MD5('demo'))",
            'date' => "200709"
        );
        self::makePatch('129', $patch);

        $ac = (checkTableExists(TB_PREFIX . 'auth_challenges'));
        $patch = array(
            'name' => "Create auth_challenges table",
            'patch' => ($ac ? "SELECT * FROM " . TB_PREFIX . "auth_challenges" :
                              "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "auth_challenges` (`challenges_key` int(11) NOT NULL,
                                                                                              `challenges_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP);"),
            'date' => "200709"
        );
        self::makePatch('130', $patch);

        $patch = array(
            'name' => "Make tax field 3 decimal places",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` CHANGE `tax_percentage` `tax_percentage` DECIMAL (10,3)  NULL",
            'date' => "200709"
        );
        self::makePatch('131', $patch);

        $patch = array(
            'name' => "Correct Foreign Key Tax ID Field Type in Invoice Items Table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `tax_id` `tax_id` int  DEFAULT '0' NOT NULL ;",
            'date' => "20071126"
        );
        self::makePatch('132', $patch);

        $patch = array(
            'name' => "Correct Foreign Key Invoice ID Field Type in Ac Payments Table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "account_payments` CHANGE `ac_inv_id` `ac_inv_id` int  NOT NULL ;",
            'date' => "20071126"
        );
        self::makePatch('133', $patch);

        $patch = array(
            'name' => "Drop non-int compatible default from si_sql_patchmanager",
            'patch' => "SELECT 1+1;",
            'date' => "20071218"
        );
        self::makePatch('134', $patch);

        $patch = array(
            'name' => "Change sql_patch_ref type in sql_patchmanager to int",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "sql_patchmanager` change `sql_patch_ref` `sql_patch_ref` int NOT NULL ;",
            'date' => "20071218"
        );
        self::makePatch('135', $patch);

        $patch = array(
            'name' => "Create domain mapping table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "user_domain` (`id` int(11) NOT NULL auto_increment  PRIMARY KEY,
                                                                     `name` varchar(255) UNIQUE NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
            'date' => "200712"
        );
        self::makePatch('136', $patch);

        $patch = array(
            'name' => "Insert default domain",
            'patch' => "INSERT INTO `" . TB_PREFIX . "user_domain` (name) VALUES ('default');",
            'date' => "200712"
        );
        self::makePatch('137', $patch);

        $patch = array(
            'name' => "Add domain_id to payment_types table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment_types` ADD `domain_id` INT  NOT NULL AFTER `pt_id` ;",
            'date' => "200712"
        );
        self::makePatch('138', $patch);

        $patch = array(
            'name' => "Add domain_id to preferences table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `domain_id` INT  NOT NULL AFTER `pref_id`;",
            'date' => "200712"
        );
        self::makePatch('139', $patch);

        $patch = array(
            'name' => "Add domain_id to products table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `domain_id` INT  NOT NULL AFTER `id` ;",
            'date' => "200712"
        );
        self::makePatch('140', $patch);

        $patch = array(
            'name' => "Add domain_id to billers table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD `domain_id` INT  NOT NULL AFTER `id` ;",
            'date' => "200712"
        );
        self::makePatch('141', $patch);

        $patch = array(
            'name' => "Add domain_id to invoices table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD `domain_id` INT NOT NULL AFTER `id` ;",
            'date' => "200712"
        );
        self::makePatch('142', $patch);

        $patch = array(
            'name' => "Add domain_id to customers table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD `domain_id` INT NOT NULL AFTER `id` ;",
            'date' => "200712"
        );
        self::makePatch('143', $patch);

        $patch = array(
            'name' => "Change group field to user_role_id in users table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "users` CHANGE `user_group` `user_role_id` INT  DEFAULT '1' NOT NULL;",
            'date' => "20080102"
        );
        self::makePatch('144', $patch);

        $patch = array(
            'name' => "Change domain field to user_domain_id in users table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "users` CHANGE `user_domain` `user_domain_id` INT  DEFAULT '1' NOT NULL;",
            'date' => "20080102"
        );
        self::makePatch('145', $patch);

        $patch = array(
            'name' => "Drop old auth_challenges table",
            'patch' => "DROP TABLE IF EXISTS `" . TB_PREFIX . "auth_challenges`;",
            'date' => "20080102"
        );
        self::makePatch('146', $patch);

        $patch = array(
            'name' => "Create user_role table",
            'patch' => "CREATE TABLE " . TB_PREFIX . "user_role (`id` int(11) NOT NULL auto_increment  PRIMARY KEY,
                                                                 `name` varchar(255) UNIQUE NOT NULL) ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20080102"
        );
        self::makePatch('147', $patch);

        $patch = array(
            'name' => "Insert default user group",
            'patch' => "INSERT INTO " . TB_PREFIX . "user_role (name) VALUES ('administrator');",
            'date' => "20080102"
        );
        self::makePatch('148', $patch);

        $patch = array(
            'name' => "Table = Account_payments Field = ac_amount : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "account_payments` CHANGE `ac_amount` `ac_amount` DECIMAL( 25, 6 ) NOT NULL;",
            'date' => "20080128"
        );
        self::makePatch('149', $patch);

        $patch = array(
            'name' => "Table = Invoice_items Field = quantity : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `quantity` `quantity` DECIMAL( 25, 6 ) NOT NULL DEFAULT '0' ",
            'date' => "20080128"
        );
        self::makePatch('150', $patch);

        $patch = array(
            'name' => "Table = Invoice_items Field = unit_price : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `unit_price` `unit_price` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' ",
            'date' => "20080128"
        );
        self::makePatch('151', $patch);

        $patch = array(
            'name' => "Table = Invoice_items Field = tax : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `tax` `tax` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' ",
            'date' => "20080128"
        );
        self::makePatch('152', $patch);

        $patch = array(
            'name' => "Table = Invoice_items Field = tax_amount : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `tax_amount` `tax_amount` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'",
            'date' => "20080128"
        );
        self::makePatch('153', $patch);

        $patch = array(
            'name' => "Table = Invoice_items Field = gross_total : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `gross_total` `gross_total` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'",
            'date' => "20080128"
        );
        self::makePatch('154', $patch);

        $patch = array(
            'name' => "Table = Invoice_items Field = total : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `total` `total` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' ",
            'date' => "20080128"
        );
        self::makePatch('155', $patch);

        $patch = array(
            'name' => "Table = Products Field = unit_price : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `unit_price` `unit_price` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'",
            'date' => "20080128"
        );
        self::makePatch('156', $patch);

        $patch = array(
            'name' => "Table = Tax Field = quantity : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` CHANGE `tax_percentage` `tax_percentage` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'",
            'date' => "20080128"
        );
        self::makePatch('157', $patch);

        $patch = array(
            'name' => "Rename table si_account_payments to si_payment",
            'patch' => "RENAME TABLE `" . TB_PREFIX . "account_payments` TO  `" . TB_PREFIX . "payment`;",
            'date' => "20081201"
        );
        self::makePatch('158', $patch);

        $patch = array(
            'name' => "Add domain_id to payments table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment` ADD  `domain_id` INT NOT NULL ;",
            'date' => "20081201"
        );
        self::makePatch('159', $patch);

        $patch = array(
            'name' => "Add domain_id to tax table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` ADD  `domain_id` INT NOT NULL ;",
            'date' => "20081201"
        );
        self::makePatch('160', $patch);

        $patch = array(
            'name' => "Change user table from si_users to si_user",
            'patch' => "RENAME TABLE `" . TB_PREFIX . "users` TO  `" . TB_PREFIX . "user`;",
            'date' => "20081201"
        );
        self::makePatch('161', $patch);

        $patch = array(
            'name' => "Add new invoice items tax table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "invoice_item_tax` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                                          `invoice_item_id` INT( 11 ) NOT NULL ,
                                                                          `tax_id` INT( 11 ) NOT NULL ,
                                                                          `tax_type` VARCHAR( 1 ) NOT NULL ,
                                                                          `tax_rate` DECIMAL( 25, 6 ) NOT NULL ,
                                                                          `tax_amount` DECIMAL( 25, 6 ) NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20081212"
        );
        self::makePatch('162', $patch);

        //do conversion
        $patch = array(
            'name' => "Convert tax info in si_invoice_items to si_invoice_item_tax",
            'patch' => "INSERT INTO `" . TB_PREFIX . "invoice_item_tax` (invoice_item_id, tax_id, tax_type, tax_rate, tax_amount)
                        SELECT id, tax_id, '%', tax, tax_amount FROM `" . TB_PREFIX . "invoice_items`;",
            'date' => "20081212"
        );
        self::makePatch('163', $patch);


        $patch = array(
            'name' => "Add default tax id into products table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `default_tax_id` INT( 11 ) NULL AFTER `unit_price` ;",
            'date' => "20081212"
        );
        self::makePatch('164', $patch);

        $patch = array(
            'name' => "Add default tax id 2 into products table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `default_tax_id_2` INT( 11 ) NULL AFTER `default_tax_id` ;",
            'date' => "20081212"
        );
        self::makePatch('165', $patch);

        $patch = array(
            'name' => "Add default tax into product items",
            'patch' => "UPDATE `" . TB_PREFIX . "products` SET default_tax_id = (SELECT value FROM `" . TB_PREFIX . "system_defaults` WHERE name ='tax');",
            'date' => "20081212"
        );
        self::makePatch('166', $patch);

        $patch = array(
            'name' => "Add default number of taxes per line item into system_defaults",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` VALUES ('','tax_per_line_item','1')",
            'date' => "20081212"
        );
        self::makePatch('167', $patch);

        $patch = array(
            'name' => "Add tax type",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` ADD `type` VARCHAR( 1 ) NULL AFTER `tax_percentage` ;",
            'date' => "20081212"
        );
        self::makePatch('168', $patch);

        $patch = array(
            'name' => "Set tax type on current taxes to %",
            'patch' => "SELECT 1+1;",
            'date' => "20081212"
        );
        self::makePatch('169', $patch);

        $patch = array(
            'name' => "Set domain_id on tax table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "tax` SET `domain_id` = '1' ;",
            'date' => "20081229"
        );
        self::makePatch('170', $patch);

        $patch = array(
            'name' => "Set domain_id on payment table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "payment` SET `domain_id` = '1' ;",
            'date' => "20081229"
        );
        self::makePatch('171', $patch);

        $patch = array(
            'name' => "Set domain_id on payment_types table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "payment_types` SET `domain_id` = '1' ;",
            'date' => "20081229"
        );
        self::makePatch('172', $patch);

        $patch = array(
            'name' => "Set domain_id on preference table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "preferences` SET `domain_id` = '1' ;",
            'date' => "20081229"
        );
        self::makePatch('173', $patch);

        $patch = array(
            'name' => "Set domain_id on products table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "products` SET `domain_id` = '1' ;",
            'date' => "20081229"
        );
        self::makePatch('174', $patch);

        $patch = array(
            'name' => "Set domain_id on biller table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "biller` SET `domain_id` = '1' ;",
            'date' => "20081229"
        );
        self::makePatch('175', $patch);

        $patch = array(
            'name' => "Set domain_id on invoices table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "invoices` SET `domain_id` = '1' ;",
            'date' => "20081229"
        );
        self::makePatch('176', $patch);

        $patch = array(
            'name' => "Set domain_id on customers table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "customers` SET `domain_id` = '1' ;",
            'date' => "20081229"
        );
        self::makePatch('177', $patch);

        $patch = array(
            'name' => "Rename si_user.user_id to si_user.id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `user_id` `id` int(11) ;",
            'date' => "20081229"
        );
        self::makePatch('178', $patch);

        $patch = array(
            'name' => "Rename si_user.user_email to si_user.email",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `user_email` `email` VARCHAR( 255 );",
            'date' => "20081229"
        );
        self::makePatch('179', $patch);

        $patch = array(
            'name' => "Rename si_user.user_name to si_user.name",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `user_name` `name` VARCHAR( 255 );",
            'date' => "20081229"
        );
        self::makePatch('180', $patch);

        $patch = array(
            'name' => "Rename si_user.user_role_id to si_user.role_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `user_role_id` `role_id` int(11);",
            'date' => "20081229"
        );
        self::makePatch('181', $patch);

        $patch = array(
            'name' => "Rename si_user.user_domain_id to si_user.domain_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `user_domain_id` `domain_id` int(11) ;",
            'date' => "20081229"
        );
        self::makePatch('182', $patch);

        $patch = array(
            'name' => "Rename si_user.user_password to si_user.password",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `user_password` `password` VARCHAR( 255 ) ;",
            'date' => "20081229"
        );
        self::makePatch('183', $patch);

        $patch = array(
            'name' => "Drop name column from si_user table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` DROP `name`  ;",
            'date' => "20081230"
        );
        self::makePatch('184', $patch);

        $patch = array(
            'name' => "Drop old defaults table",
            'patch' => "DROP TABLE `" . TB_PREFIX . "defaults` ;",
            'date' => "20081230"
        );
        self::makePatch('185', $patch);

        $patch = array(
            'name' => "Set domain_id on customers table to 1",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "custom_fields` ADD  `domain_id` INT NOT NULL ;",
            'date' => "20081230"
        );
        self::makePatch('186', $patch);

        $patch = array(
            'name' => "Set domain_id on custom_fields table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "custom_fields` SET `domain_id` = '1' ;",
            'date' => "20081230"
        );
        self::makePatch('187', $patch);

        $patch = array(
            'name' => "Drop tax_id column from si_invoice_items table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` DROP `tax_id`  ;",
            'date' => "20090118"
        );
        self::makePatch('188', $patch);

        $patch = array(
            'name' => "Drop tax column from si_invoice_items table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` DROP `tax`  ;",
            'date' => "20090118"
        );
        self::makePatch('189', $patch);

        $patch = array(
            'name' => "Insert user role - user",
            'patch' => "INSERT INTO " . TB_PREFIX . "user_role (name) VALUES ('user');",
            'date' => "20090215"
        );
        self::makePatch('190', $patch);

        $patch = array(
            'name' => "Insert user role - viewer",
            'patch' => "INSERT INTO " . TB_PREFIX . "user_role (name) VALUES ('viewer');",
            'date' => "20090215"
        );
        self::makePatch('191', $patch);

        $patch = array(
            'name' => "Insert user role - customer",
            'patch' => "INSERT INTO " . TB_PREFIX . "user_role (name) VALUES ('customer');",
            'date' => "20090215"
        );
        self::makePatch('192', $patch);

        $patch = array(
            'name' => "Insert user role - biller",
            'patch' => "INSERT INTO " . TB_PREFIX . "user_role (name) VALUES ('biller');",
            'date' => "20090215"
        );
        self::makePatch('193', $patch);

        $patch = array(
            'name' => "User table - auto increment",
            'patch' => "ALTER TABLE " . TB_PREFIX . "user CHANGE id id INT( 11 ) NOT NULL AUTO_INCREMENT;",
            'date' => "20090215"
        );
        self::makePatch('194', $patch);

        $patch = array(
            'name' => "User table - add enabled field",
            'patch' => "ALTER TABLE " . TB_PREFIX . "user ADD enabled INT( 1 ) NOT NULL ;",
            'date' => "20090215"
        );
        self::makePatch('195', $patch);

        $patch = array(
            'name' => "User table - make all existing users enabled",
            'patch' => "UPDATE " . TB_PREFIX . "user SET enabled = 1 ;",
            'date' => "20090217"
        );
        self::makePatch('196', $patch);

        $patch = array(
            'name' => "Defaults table - add domain_id and extension_id field",
            'patch' => "ALTER TABLE " . TB_PREFIX . "system_defaults ADD `domain_id` INT( 5 ) NOT NULL DEFAULT '1',
                                                                     ADD `extension_id` INT( 5 ) NOT NULL DEFAULT '1',
                                                                     DROP INDEX `name`,
                                                                     ADD INDEX `name` ( `name` );",
            'date' => "20090321"
        );
        self::makePatch('197', $patch);

        $patch = array(
            'name' => "Extension table - create table to hold extension status",
            'patch' => "CREATE TABLE " . TB_PREFIX . "extensions (`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                                  `domain_id` INT( 11 ) NOT NULL ,
                                                                  `name` VARCHAR( 255 ) NOT NULL ,
                                                                  `description` VARCHAR( 255 ) NOT NULL ,
                                                                  `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '0') ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20090322"
        );
        self::makePatch('198', $patch);

        $patch = array(
            'name' => "Update extensions table",
            'patch' => "INSERT INTO " . TB_PREFIX . "extensions (`id`,`domain_id`,`name`,`description`,`enabled`)
                        VALUES ('1','1','core','Core part of SimpleInvoices - always enabled','1');",
            'date' => "20090529"
        );
        self::makePatch('199', $patch);

        $patch = array(
            'name' => "Update extensions table",
            'patch' => "UPDATE " . TB_PREFIX . "extensions SET `id` = '1' WHERE `name` = 'core' LIMIT 1;",
            'date' => "20090529"
        );
        self::makePatch('200', $patch);

        $patch = array(
            'name' => "Set domain_id on system defaults table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET `domain_id` = '1' ;",
            'date' => "20090622"
        );
        self::makePatch('201', $patch);

        $patch = array(
            'name' => "Set extension_id on system defaults table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET `extension_id` = '1' ;",
            'date' => "20090622"
        );
        self::makePatch('202', $patch);

        $patch = array(
            'name' => "Move all old consulting style invoices to itemised",
            'patch' => "UPDATE `" . TB_PREFIX . "invoices` SET `type_id` = '2' where `type_id`=3 ;",
            'date' => "20090704"
        );
        self::makePatch('203', $patch);

        $patch = array(
            'name' => "Creates index table to handle new invoice numbering system",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "index` (`id` INT( 11 ) NOT NULL ,
                                                               `node` VARCHAR( 255 ) NOT NULL ,
                                                               `sub_node` VARCHAR( 255 ) NULL ,
                                                               `domain_id` INT( 11 ) NOT NULL) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20090818"
        );
        self::makePatch('204', $patch);

        $patch = array(
            'name' => "Add index_id to invoice table - new invoice numbering",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD `index_id` INT( 11 ) NOT NULL AFTER `id`;",
            'date' => "20090818"
        );
        self::makePatch('205', $patch);

        $patch = array(
            'name' => "Add status and locale to preferences",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `status` INT( 1 ) NOT NULL ,
                                                                   ADD `locale` VARCHAR( 255 ) NULL ,
                                                                   ADD `language` VARCHAR( 255 ) NULL ;",
            'date' => "20090826"
        );
        self::makePatch('206', $patch);

        $patch = array(
            'name' => "Populate the status, locale, and language fields in preferences table",
            'patch' => "UPDATE `" . TB_PREFIX . "preferences` SET status = '1', locale = '" . $config->local->locale . "', language = '$language' ;",
            'date' => "20090826"
        );
        self::makePatch('207', $patch);

        $patch = array(
            'name' => "Populate the status, locale, and language fields in preferences table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `index_group` INT( 11 ) NOT NULL ;",
            'date' => "20090826"
        );
        self::makePatch('208', $patch);

        $preference = SystemDefaults::getDefaultPreference();
        $patch = array(
            'name' => "Populate the status, locale, and language fields in preferences table",
            'patch' => "UPDATE `" . TB_PREFIX . "preferences` SET index_group = '$preference' ;",
            'date' => "20090826"
        );
        self::makePatch('209', $patch);

        $patch = array(
            'name' => "Create composite primary key for invoice table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`,`id` );",
            'date' => "20090826"
        );
        self::makePatch('210', $patch);

        $patch = array(
            'name' => "Reset auto-increment for invoice table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` AUTO_INCREMENT = 1;",
            'date' => "20090826"
        );
        self::makePatch('211', $patch);

        $patch = array(
            'name' => "Copy invoice.id into invoice.index_id",
            'patch' => "update `" . TB_PREFIX . "invoices` set index_id = id;",
            'date' => "20090902"
        );
        self::makePatch('212', $patch);

        $max_invoice = Invoice::maxIndexId();
        $patch = array(
            'name' => "Update the index table with max invoice id - if required",
            'patch' => ($max_invoice > "0" ? "INSERT INTO `" . TB_PREFIX . "index` (id, node, sub_node, domain_id)
                                              VALUES (" . $max_invoice . ", 'invoice', '" . $defaults['preference'] . "','1');" :
                "SELECT 1+1;"),
            'date' => "20090902"
        );
        self::makePatch('213', $patch);
        unset($defaults);
        unset($max_invoice);

        $patch = array(
            'name' => "Add sub_node_2 to si_index table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "index` ADD  `sub_node_2` VARCHAR( 255 ) NULL AFTER  `sub_node`",
            'date' => "20090912"
        );
        self::makePatch('214', $patch);

        $patch = array(
            'name' => "si_invoices - add composite primary key - patch removed",
            'patch' => "SELECT 1+1;",
            'date' => "20090912"
        );
        self::makePatch('215', $patch);

        $patch = array(
            'name' => "si_payment - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
            'date' => "20090912"
        );
        self::makePatch('216', $patch);

        $patch = array(
            'name' => "si_payment_types - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment_types` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `pt_id`)",
            'date' => "20090912"
        );
        self::makePatch('217', $patch);

        $patch = array(
            'name' => "si_preferences - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `pref_id`)",
            'date' => "20090912"
        );
        self::makePatch('218', $patch);

        $patch = array(
            'name' => "si_products - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
            'date' => "20090912"
        );
        self::makePatch('219', $patch);

        $patch = array(
            'name' => "si_tax - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `tax_id`)",
            'date' => "20090912"
        );
        self::makePatch('220', $patch);

        $patch = array(
            'name' => "si_user - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
            'date' => "20090912"
        );
        self::makePatch('221', $patch);

        $patch = array(
            'name' => "si_biller - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
            'date' => "20100209"
        );
        self::makePatch('222', $patch);

        $patch = array(
            'name' => "si_customers - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
            'date' => "20100209"
        );
        self::makePatch('223', $patch);

        $patch = array(
            'name' => "Add paypal business name",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD `paypal_business_name` VARCHAR( 255 ) NULL AFTER  `footer`",
            'date' => "20100209"
        );
        self::makePatch('224', $patch);

        $patch = array(
            'name' => "Add paypal notify url",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD `paypal_notify_url` VARCHAR( 255 ) NULL AFTER  `paypal_business_name`",
            'date' => "20100209"
        );
        self::makePatch('225', $patch);

        $patch = array(
            'name' => "Define currency in preferences",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `currency_code` VARCHAR( 25 ) NULL ;",
            'date' => "20100209"
        );
        self::makePatch('226', $patch);

        $patch = array(
            'name' => "Create cron table to handle recurrence",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "cron` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
                                                              `domain_id` INT( 11 ) NOT NULL ,
                                                              `invoice_id` INT( 11 ) NOT NULL ,
                                                              `start_date` DATE NOT NULL ,
                                                              `end_date` VARCHAR( 10 ) NULL ,
                                                              `recurrence` INT( 11 ) NOT NULL ,
                                                              `recurrence_type` VARCHAR( 11 ) NOT NULL ,
                                                              `email_biller` INT( 1 ) NULL ,
                                                              `email_customer` INT( 1 ) NULL ,
                               PRIMARY KEY (`domain_id` ,`id`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20100215"
        );
        self::makePatch('227', $patch);

        $patch = array(
            'name' => "Create cron_log table to handle record when cron was run",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "cron_log` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
                                                                  `domain_id` INT( 11 ) NOT NULL ,
                                                                  `run_date` DATE NOT NULL ,
                               PRIMARY KEY (  `domain_id` , `id`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20100216"
        );
        self::makePatch('228', $patch);

        $patch = array(
            'name' => "preferences - add online payment type",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `include_online_payment` VARCHAR( 255 ) NULL ;",
            'date' => "20100209"
        );
        self::makePatch('229', $patch);

        $patch = array(
            'name' => "Add paypal notify url",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "biller` ADD `paypal_return_url` VARCHAR( 255 ) NULL AFTER  `paypal_notify_url`",
            'date' => "20100223"
        );
        self::makePatch('230', $patch);

        $patch = array(
            'name' => "Add paypal payment id into payment table",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "payment` ADD `online_payment_id` VARCHAR( 255 ) NULL AFTER  `domain_id`",
            'date' => "20100226"
        );
        self::makePatch('231', $patch);

        $patch = array(
            'name' => "Define currency display in preferences",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `currency_position` VARCHAR( 25 ) NULL ;",
            'date' => "20100227"
        );
        self::makePatch('232', $patch);

        $patch = array(
            'name' => "Add system default to control invoice number by biller -- dummy patch -- this sql was removed",
            'patch' => "SELECT 1+1;",
            'date' => "20100302"
        );
        self::makePatch('233', $patch);

        $patch = array(
            'name' => "Add eway customer ID",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "biller` ADD `eway_customer_id` VARCHAR( 255 ) NULL AFTER `paypal_return_url`;",
            'date' => "20100315"
        );
        self::makePatch('234', $patch);

        $patch = array(
            'name' => "Add eway card holder name",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "customers` ADD `credit_card_holder_name` VARCHAR( 255 ) NULL AFTER `email`;",
            'date' => "20100315"
        );
        self::makePatch('235', $patch);

        $patch = array(
            'name' => "Add eway card number",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "customers` ADD `credit_card_number` VARCHAR( 255 ) NULL AFTER `credit_card_holder_name`;",
            'date' => "20100315"
        );
        self::makePatch('236', $patch);

        $patch = array(
            'name' => "Add eway card expiry month",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "customers` ADD `credit_card_expiry_month` VARCHAR( 02 ) NULL AFTER `credit_card_number`;",
            'date' => "20100315"
        );
        self::makePatch('237', $patch);

        $patch = array(
            'name' => "Add eway card expirt year",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "customers` ADD `credit_card_expiry_year` VARCHAR( 04 ) NULL AFTER `credit_card_expiry_month` ;",
            'date' => "20100315"
        );
        self::makePatch('238', $patch);

        $patch = array(
            'name' => "cronlog - add invoice id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "cron_log` ADD `cron_id` VARCHAR( 25 ) NULL AFTER `domain_id` ;",
            'date' => "20100321"
        );
        self::makePatch('239', $patch);

        $patch = array(
            'name' => "si_system_defaults - add composite primary key",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "system_defaults` ADD `new_id` INT( 11 ) NOT NULL FIRST;
                        UPDATE `" . TB_PREFIX . "system_defaults` SET new_id = id;
                        ALTER TABLE  `" . TB_PREFIX . "system_defaults` DROP  `id` ;
                        ALTER TABLE  `" . TB_PREFIX . "system_defaults` DROP INDEX `name` ;
                        ALTER TABLE  `" . TB_PREFIX . "system_defaults` CHANGE  `new_id`  `id` INT( 11 ) NOT NULL;
                        ALTER TABLE  `" . TB_PREFIX . "system_defaults` ADD PRIMARY KEY(`domain_id`,`id` );",
            'date' => "20100305"
        );
        self::makePatch('240', $patch);

        $patch = array(
            'name' => "si_system_defaults - add composite primary key",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` VALUES ('','inventory','0','1','1');",
            'date' => "20100409"
        );
        self::makePatch('241', $patch);

        $patch = array(
            'name' => "Add cost to products table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `cost` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' AFTER `default_tax_id_2`;",
            'date' => "20100409"
        );
        self::makePatch('242', $patch);

        $patch = array(
            'name' => "Add reorder_level to products table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `reorder_level` INT( 11 ) NULL AFTER `cost` ;",
            'date' => "20100409"
        );
        self::makePatch('243', $patch);

        $patch = array(
            'name' => "Create inventory table",
            'patch' => "CREATE TABLE  `" . TB_PREFIX . "inventory` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
                                                                    `domain_id` INT( 11 ) NOT NULL ,
                                                                    `product_id` INT( 11 ) NOT NULL ,
                                                                    `quantity` DECIMAL( 25, 6 ) NOT NULL ,
                                                                    `cost` DECIMAL( 25, 6 ) NULL ,
                                                                    `date` DATE NOT NULL ,
                                                                    `note` TEXT NULL ,
                               PRIMARY KEY (`domain_id`, `id`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20100409"
        );
        self::makePatch('244', $patch);

        $patch = array(
            'name' => "Preferences - make locale null field",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "preferences` CHANGE  `locale`  `locale` VARCHAR( 255 ) NULL ;",
            'date' => "20100419"
        );
        self::makePatch('245', $patch);

        $patch = array(
            'name' => "Preferences - make language a null field",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "preferences` CHANGE  `language`  `language` VARCHAR( 255 ) NULL;",
            'date' => "20100419"
        );
        self::makePatch('246', $patch);

        $patch = array(
            'name' => "Custom fields - make sure domain_id is 1",
            'patch' => "update " . TB_PREFIX . "custom_fields set domain_id = '1';",
            'date' => "20100419"
        );
        self::makePatch('247', $patch);

        $patch = array(
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD INDEX(`domain_id`);",
            'date' => "20100419"
        );
        self::makePatch('248', $patch);

        $patch = array(
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD INDEX(`biller_id`) ;",
            'date' => "20100419"
        );
        self::makePatch('249', $patch);

        $patch = array(
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD INDEX(`customer_id`);",
            'date' => "20100419"
        );
        self::makePatch('250', $patch);

        $patch = array(
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment` ADD INDEX(`domain_id`);",
            'date' => "20100419"
        );
        self::makePatch('251', $patch);

        $patch = array(
            'name' => "Language - reset to en_US - due to folder renaming",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value ='en_US' where name='language';",
            'date' => "20100419"
        );
        self::makePatch('252', $patch);

        $patch = array(
            'name' => "Add PaymentsGateway API ID field",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD  `paymentsgateway_api_id` VARCHAR( 255 ) NULL AFTER `eway_customer_id`;",
            'date' => "20110918"
        );
        self::makePatch('253', $patch);

        $patch = array(
            'name' => "Product Matrix - update line items table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` ADD `attribute` VARCHAR( 255 ) NULL ;",
            'date' => "20130313"
        );
        self::makePatch('254', $patch);

        $patch = array(
            'name' => "Product Matrix - update line items table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "products_attributes` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                                             `name` VARCHAR( 255 ) NOT NULL,
                                                                             `type_id` VARCHAR( 255 ) NOT NULL) ENGINE = MYISAM ;",
            'date' => "20130313"
        );
        self::makePatch('255', $patch);

        $patch = array(
            'name' => "Product Matrix - update line items table",
            'patch' => "INSERT INTO `" . TB_PREFIX . "products_attributes` (`id`, `name`, `type_id`) VALUES (NULL, 'Size','1'), (NULL,'Colour','1');",
            'date' => "20130313"
        );
        self::makePatch('256', $patch);

        $patch = array(
            'name' => "Product Matrix - update line items table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "products_values` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                                                         `attribute_id` INT( 11 ) NOT NULL ,
                                                                         `value` VARCHAR( 255 ) NOT NULL) ENGINE = MYISAM ;",
            'date' => "20130313"
        );
        self::makePatch('257', $patch);

        $patch = array(
            'name' => "Product Matrix - update line items table",
            'patch' => "INSERT INTO `" . TB_PREFIX . "products_values` (`id`, `attribute_id`,`value`)
                        VALUES (NULL,'1', 'S'),  (NULL,'1', 'M'), (NULL,'1', 'L'),  (NULL,'2', 'Red'),  (NULL,'2', 'White');",
            'date' => "20130313"
        );
        self::makePatch('258', $patch);

        $patch = array(
            'name' => "Product Matrix - update line items table",
            'patch' => "SELECT 1+1;",  //remove matrix code
            'date' => "20130313"
        );
        self::makePatch('259', $patch);

        $patch = array(
            'name' => "Product Matrix - update line items table",
            'patch' => "SELECT 1+1;", //remove matrix code
            'date' => "20130313"
        );
        self::makePatch('260', $patch);

        $patch = array(
            'name' => "Product Matrix - update line items table",
            'patch' => "SELECT 1+1;", //remove matrix code
            'date' => "20130313"
        );
        self::makePatch('261', $patch);

        $patch = array(
            'name' => "Add product attributes system preference",
            'patch' => "INSERT INTO " . TB_PREFIX . "system_defaults (id, name ,value ,domain_id ,extension_id ) VALUES (NULL , 'product_attributes', '0', '1', '1');",
            'date' => "20130313"
        );
        self::makePatch('262', $patch);

        $patch = array(
            'name' => "Product Matrix - update line items table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `attribute` VARCHAR( 255 ) NULL ;",
            'date' => "20130313"
        );
        self::makePatch('263', $patch);

        $patch = array(
            'name' => "Product - use notes as default line item description",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `notes_as_description` VARCHAR( 1 ) NULL ;",
            'date' => "20130314"
        );
        self::makePatch('264', $patch);

        $patch = array(
            'name' => "Product - expand/show line item description",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `show_description` VARCHAR( 1 ) NULL ;",
            'date' => "20130314"
        );
        self::makePatch('265', $patch);

        $patch = array(
            'name' => "Product - expand/show line item description",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "products_attribute_type` (`id` int(11) NOT NULL AUTO_INCREMENT,
                                                                                 `name` varchar(255) NOT NULL,
                                                                                 PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20130322"
        );
        self::makePatch('266', $patch);

        $patch = array(
            'name' => "Product Matrix - insert attribute types",
            'patch' => "INSERT INTO `" . TB_PREFIX . "products_attribute_type` (`id`, `name`) VALUES (NULL,'list'),  (NULL,'decimal'), (NULL,'free');",
            'date' => "20130325"
        );
        self::makePatch('267', $patch);

        $patch = array(
            'name' => "Product Matrix - insert attribute types",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "products_attributes` ADD `enabled` VARCHAR( 1 ) NULL DEFAULT  '1',
                                                                            ADD `visible` VARCHAR( 1 ) NULL DEFAULT  '1';",
            'date' => "20130327"
        );
        self::makePatch('268', $patch);

        $patch = array(
            'name' => "Product Matrix - insert attribute types",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "products_values` ADD  `enabled` VARCHAR( 1 ) NULL DEFAULT  '1';",
            'date' => "20130327"
        );
        self::makePatch('269', $patch);

        $patch = array(
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment` ADD INDEX(`ac_inv_id`);",
            'date' => "20100419"
        );
        self::makePatch('270', $patch);

        $patch = array(
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment` ADD INDEX(`ac_amount`);",
            'date' => "20100419"
        );
        self::makePatch('271', $patch);

        $patch = array(
            'name' => "Add product attributes system preference",
            'patch' => "INSERT INTO " . TB_PREFIX . "system_defaults (id, name ,value ,domain_id ,extension_id ) VALUES (NULL , 'large_dataset', '0', '1', '1');",
            'date' => "20130313"
        );
        self::makePatch('272', $patch);

        $patch = array(
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` ADD INDEX(`invoice_id`);",
            'date' => "20130927"
        );
        self::makePatch('273', $patch);

        $patch = array(
            'name' => "Only One Default Variable name per domain allowed - add unique index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "system_defaults` ADD UNIQUE INDEX `UnqNameInDomain` (`domain_id`, `name`);",
            'date' => "20131007"
        );
        self::makePatch('274', $patch);

        $patch = array(
            'name' => "Make EMail / Password pair unique per domain - add unique index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `password` `password` VARCHAR(64) NULL,
                                                            ADD UNIQUE INDEX `UnqEMailPwd` (`email`, `password`);",
            'date' => "20131007"
        );
        self::makePatch('275', $patch);

        $patch = array(
            'name' => "Each invoice Item must belong to a specific Invoice with a specific domain_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` ADD COLUMN `domain_id` INT NOT NULL DEFAULT '1' AFTER `invoice_id`;",
            'date' => "20131008"
        );
        self::makePatch('276', $patch);

        $patch = array(
            'name' => "Add Index for Quick Invoice Item Search for a domain_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` ADD INDEX `DomainInv` (`invoice_id`, `domain_id`);",
            'date' => "20131008"
        );
        self::makePatch('277', $patch);

        $patch = array(
            'name' => "Each Invoice Item can have only one instance of each tax",
            //    Patch disabled for old installs with inadequate database integrity
            //    self::$patchLines['278']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_item_tax` ADD UNIQUE INDEX `UnqInvTax` (`invoice_item_id`, `tax_id`);";
            'patch' => "SELECT 1+1;",
            'date' => "20131008"
        );
        self::makePatch('278', $patch);

        $patch = array(
            'name' => "Drop unused superceeded table si_product_matrix if present",
            'patch' => "DROP TABLE IF EXISTS `" . TB_PREFIX . "products_matrix`;",
            'date' => "20131009"
        );
        self::makePatch('279', $patch);

        $patch = array(
            'name' => "Each domain has their own extension instances",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "extensions` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`, `domain_id`);",
            'date' => "20131011"
        );
        self::makePatch('280', $patch);

        $patch = array(
            'name' => "Each domain has their own custom_field id sets",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "custom_fields` DROP PRIMARY KEY,
                                                                     ADD PRIMARY KEY (`cf_id`, `domain_id`);",
            'date' => "20131011"
        );
        self::makePatch('281', $patch);

        $patch = array(
            'name' => "Each domain has their own logs",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "log` ADD COLUMN `domain_id` INT NOT NULL DEFAULT '1' AFTER `id`,
                                                           DROP PRIMARY KEY,
                                                           ADD PRIMARY KEY (`id`, `domain_id`);",
            'date' => "20131011"
        );
        self::makePatch('282', $patch);

        $patch = array(
            'name' => "Match field type with foreign key field si_user.id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "log` CHANGE `userid` `userid` INT NOT NULL DEFAULT '1';",
            'date' => "20131012"
        );
        self::makePatch('283', $patch);

        $patch = array(
            'name' => "Make si_index sub_node and sub_node_2 fields as integer",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "index` CHANGE `node` `node` VARCHAR(64) NOT NULL,
                                                             CHANGE `sub_node` `sub_node` INT NOT NULL,
                                                             CHANGE `sub_node_2` `sub_node_2` INT NOT NULL;",
            'date' => "20131016"
        );
        self::makePatch('284', $patch);

        $patch = array(
            'name' => "Fix compound Primary Key for si_index table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "index` ADD PRIMARY KEY (`node`, `sub_node`, `sub_node_2`, `domain_id`);",
            'date' => "20131016"
        );
        self::makePatch('285', $patch);

        $patch = array(
            'name' => "Speedup lookups from si_index table with indices in si_invoices table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices`
                        ADD UNIQUE INDEX `UniqDIB` (`index_id`, `preference_id`, `biller_id`, `domain_id`),
                        ADD INDEX `IdxDI` (`index_id`, `preference_id`, `domain_id`);",
            'date' => "20131016"
        );
        self::makePatch('286', $patch);

        $patch = array(
            'name' => "Populate additional user roles like domain_administrator",
            'patch' => "INSERT IGNORE INTO `" . TB_PREFIX . "user_role` (`name`) VALUES ('domain_administrator'), ('customer'), ('biller');",
            'date' => "20131017"
        );
        self::makePatch('287', $patch);

        $patch = array(
            'name' => "Fully relational now - do away with the si_index table",
            'patch' => "SELECT 1+1;",
            'date' => "20131017"
        );
        self::makePatch('288', $patch);

        $patch = array(
            'name' => "Each cron_id can run a maximum of only once a day for each domain_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "cron_log` ADD UNIQUE INDEX `CronIdUnq` (`domain_id`, `cron_id`, `run_date`);",
            'date' => "20131108"
        );
        self::makePatch('289', $patch);

        $patch = array(
            'name' => "Set all Flag fields to tinyint(1) and other 1 byte fields to char",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;
                        ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;
                        ALTER TABLE `" . TB_PREFIX . "extensions` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 0 NOT NULL;
                        ALTER TABLE `" . TB_PREFIX . "payment_types` CHANGE `pt_enabled` `pt_enabled` TINYINT(1) DEFAULT 1 NOT NULL;
                        ALTER TABLE `" . TB_PREFIX . "preferences` CHANGE `pref_enabled` `pref_enabled` TINYINT(1) DEFAULT 1 NOT NULL,
                                                                   CHANGE `status` `status` TINYINT(1) NOT NULL;
                        ALTER TABLE `" . TB_PREFIX . "products` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
                                                                CHANGE `notes_as_description` `notes_as_description` CHAR(1) NULL,
                                                                CHANGE `show_description` `show_description` CHAR(1) NULL;
                        ALTER TABLE `" . TB_PREFIX . "tax` CHANGE `tax_enabled` `tax_enabled` TINYINT(1) DEFAULT 1 NOT NULL;
                        ALTER TABLE `" . TB_PREFIX . "cron` CHANGE `email_biller` `email_biller` TINYINT(1) DEFAULT 0 NOT NULL,
                                                            CHANGE `email_customer` `email_customer` TINYINT(1) DEFAULT 0 NOT NULL;
                        ALTER TABLE `" . TB_PREFIX . "custom_fields` CHANGE `cf_display` `cf_display` TINYINT(1) DEFAULT 1 NOT NULL;
                        ALTER TABLE `" . TB_PREFIX . "invoice_item_tax` CHANGE `tax_type` `tax_type` CHAR(1) DEFAULT '%' NOT NULL;
                        ALTER TABLE `" . TB_PREFIX . "tax` CHANGE `type` `type` CHAR(1) DEFAULT '%' NOT NULL;
                        ALTER TABLE `" . TB_PREFIX . "products_attributes` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL,
                                                                           CHANGE `visible` `visible` TINYINT(1) DEFAULT 1 NOT NULL;
                        ALTER TABLE `" . TB_PREFIX . "products_VALUES` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;
                        ALTER TABLE `" . TB_PREFIX . "user` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;",
            'date' => "20131109"
        );
        self::makePatch('290', $patch);

        $patch = array(
            'name' => "Clipped size of zip_code and credit_card_number fields to realistic values",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `zip_code` `zip_code` VARCHAR(20) NULL,
                                                                 CHANGE `credit_card_number` `credit_card_number` VARCHAR(20) NULL;
                        ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `zip_code` `zip_code` VARCHAR(20) NULL;",
            'date' => "20131111"
        );
        self::makePatch('291', $patch);

        $patch = array(
            'name' => "Added Customer/Biller User ID column to user table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` ADD COLUMN `user_id` INT  DEFAULT 0 NOT NULL AFTER `enabled`;",
            'date' => "20140103"
        );
        self::makePatch('292', $patch);

        $patch = array(
            'name' => "Add department to the customers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD COLUMN `department` VARCHAR(255) NULL AFTER `name`;",
            'date' => "20161004"
        );
        self::makePatch('293', $patch);

        $ud = checkTableExists(TB_PREFIX . 'custom_flags');
        $patch = array(
            'name' => 'Add custom_flags table for products.',
            'patch' => ($ud ? "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'custom_flags';" :
                              "CREATE TABLE `" . TB_PREFIX . "custom_flags` (
                                              `domain_id`        int(11) DEFAULT '1' NOT NULL,
                                              `associated_table` char(10)            NOT NULL COMMENT 'Table flag is associated with. Only defined for products for now.',
                                              `flg_id`           tinyint(3) unsigned NOT NULL COMMENT 'Flag number ranging from 1 to 10',
                                              `field_label`      varchar(20)         NOT NULL COMMENT 'Label to use on screen where option is set.',
                                              `enabled`          tinyint(1)          NOT NULL COMMENT 'Defaults to enabled when record created. Can disable to retire flag.',
                                              `field_help`       varchar(255)        NOT NULL COMMENT 'Help information to display for this field.',
                                        PRIMARY KEY `uid` (`domain_id`, `associated_table`, `flg_id`),
                                                KEY `domain_id` (`domain_id`),
                                                KEY `dtable`    (`domain_id`, `associated_table`)) ENGINE=InnoDB COMMENT='Specifies an allowed setting for a flag field';
                              ALTER TABLE `" . TB_PREFIX . "products` ADD `custom_flags` CHAR( 10 ) NOT NULL COMMENT 'User defined flags';
                              INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, flg_id, enabled) VALUES (1,'products',1,0);
                              INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, flg_id, enabled) VALUES (1,'products',2,0);
                              INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, flg_id, enabled) VALUES (1,'products',3,0);
                              INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, flg_id, enabled) VALUES (1,'products',4,0);
                              INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, flg_id, enabled) VALUES (1,'products',5,0);
                              INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, flg_id, enabled) VALUES (1,'products',6,0);
                              INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, flg_id, enabled) VALUES (1,'products',7,0);
                              INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, flg_id, enabled) VALUES (1,'products',8,0);
                              INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, flg_id, enabled) VALUES (1,'products',9,0);
                              INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, flg_id, enabled) VALUES (1,'products',10,0);
                              DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'custom_flags';"),
            'date' => "20180922"
        );
        self::makePatch('294', $patch);
        unset($ud);

        $patch = array(
            'name' => 'Add net income report.',
            'patch' => "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'net_income_report';",
            'date' => "20180923"
        );
        self::makePatch('295', $patch);

        $patch = array(
            'name' => 'Add past due report.',
            'patch' => "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'past_due_report';",
            'date' => "20180924"
        );
        self::makePatch('296', $patch);

        $ud = (checkFieldExists("user", "username"));
        $conam = $LANG['company_name'];
        $cologo = 'simple_invoices_logo.png';
        $patch = array(
            'name' => 'Add User Security enhancement fields and values',
            'patch' => ($ud ? "UPDATE `" . TB_PREFIX . "system_defaults` SET `extension_id` = 1 WHERE `name` IN
                                    ('company_logo','company_name','company_name_item','password_min_length','password_lower','password_number','password_special','password_upper','session_timeout');                                
                               DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'user_security';" :
                              "ALTER TABLE `" . TB_PREFIX . "user` ADD `username` VARCHAR(255) DEFAULT '' NOT NULL AFTER `id`;
                               ALTER TABLE `" . TB_PREFIX . "user` DROP INDEX `UnqEMailPwd`;
                               UPDATE `" . TB_PREFIX . "user` AS U1, `" . TB_PREFIX . "user` AS U2 SET U1.username = U2.email WHERE U2.id = U1.id;
                               ALTER TABLE `" . TB_PREFIX . "user` ADD UNIQUE INDEX `uname` (`username`); 
                               ALTER TABLE `" . TB_PREFIX . "system_defaults` CHANGE `value` `value` VARCHAR(60);
                               INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domain_id, 'company_logo'        , '$cologo', 1);
                               INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domain_id, 'company_name'        , '$conam' , 1);
                               INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domain_id, 'company_name_item'   , '$conam' , 1);
                               INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domain_id, 'password_min_length' , 8        , 1);
                               INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domain_id, 'password_lower'      , 1        , 1);
                               INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domain_id, 'password_number'     , 1        , 1);
                               INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domain_id, 'password_special'    , 1        , 1);
                               INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domain_id, 'password_upper'      , 1        , 1);
                               INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domain_id, 'session_timeout'     , 15       , 1);
                               DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'user_security';"),
            'date' => "20180924"
        );
        self::makePatch('297', $patch);
        unset($ud);
        unset($conam);
        unset($cologo);

        $ud = (checkFieldExists(TB_PREFIX . 'biller', 'signature'));
        $patch = array(
            'name' => 'Add Signature field to the biller table.',
            'patch' => ($ud ? "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'signature_field';" :
                              "ALTER TABLE `" . TB_PREFIX . "biller` ADD `signature` varchar(255) DEFAULT '' NOT NULL COMMENT 'Email signature' AFTER `email`;
                               DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'signature_field';"),
            'date' => "20181003"
        );
        self::makePatch('298', $patch);
        unset($ud);

        $ud = (checkFieldExists(TB_PREFIX . 'payment', 'ac_check_number'));
        $patch = array(
            'name' => 'Add check number field to the payment table.',
            'patch' => ($ud ? "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'payments';" :
                              "ALTER TABLE `" . TB_PREFIX . "payment` ADD `ac_check_number` varchar(10) DEFAULT '' NOT NULL COMMENT 'Check number for CHECK payment types';
                               DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'payments';"),
            'date' => "20181003"
        );
        self::makePatch('299', $patch);
        unset($ud);
        // @formatter:on
    }

}