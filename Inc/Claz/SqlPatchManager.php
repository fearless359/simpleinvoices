<?php

namespace Inc\Claz;

/**
 * User: Richard Rowley
 * Date: 10/4/2018
 * Time: 8:16 AM
 */

/**
 * Class SqlPatchManager
 * @package Inc\Claz
 */
class SqlPatchManager
{
    public const BEGINNING_PATCH_NUMBER = 319;

    // Note that array is zero based but patch ref is 1 based.
    private static array $patchLines = [];
    private static int $patchCount = 0;
    private static int $numberToBeginPatchListAt = self::BEGINNING_PATCH_NUMBER;

    /**
     * Add an entry to the $patchLines array.
     * @param int $num Number of patch, must match what is expected next.
     * @param array $patch entry to add to $patchLines.
     */
    private static function makePatch(int $num, array $patch): void
    {
        static $last = self::BEGINNING_PATCH_NUMBER;
        $last++;

        if ($last != $num) {
            error_log("SqlPatchManager::makePatch - Patch #$num is out of sequence.");
            exit("SqlPatchManager::makePatch() error. See error log for more information.");
        }

        self::$patchLines[] = [
            'sql_patch_ref' => $num,
            'name'          => $patch['name'],
            'patch'         => $patch['patch'],
            'date'          => $patch['date'],
            'source'        => $patch['source']
        ];

        self::$patchCount = $num;
    }

    /**
     * Greatest sql_patch_ref value in the sql_patchmanager table.
     * @return int max patch ref value.
     */
    public static function lastPatchApplied(): int
    {
        global $pdoDbAdmin;

        try {
            $pdoDbAdmin->setSelectList(['sql_patch_ref']);
            $pdoDbAdmin->setOrderBy(['sql_patch_ref', 'D']);
            $pdoDbAdmin->setLimit(1);
            $rows = $pdoDbAdmin->request("SELECT", "sql_patchmanager");
        } catch (PdoDbException) {
            return 0;
        }
        // Returns number of patches applied
        if (empty($rows)) {
            $lastPatchApplied = 0;
            self::$numberToBeginPatchListAt = self::BEGINNING_PATCH_NUMBER;
        } else {
            $lastPatchApplied = $rows[0]['sql_patch_ref'];
            self::$numberToBeginPatchListAt = $lastPatchApplied - 20;
        }

        return $lastPatchApplied;
    }

    /**
     * @return int Count of patches
     */
    public static function numberOfUnappliedPatches(): int
    {
        // Initialize patch data if not already done
        if (self::$patchCount == 0) {
            self::loadPatches();
        }

        // Add and initialize source column if not in table.
        //        self::addSourceColumn();
        return self::$patchCount - self::lastPatchApplied();
    }

    /**
     * Assign database patches up to date message in smarty "page" variable.
     */
    public static function donePatchesMessage(): void
    {
        global $LANG, $smarty;
        $pageInfo = [
            'message' => "{$LANG['theUc']} {$LANG['database']} {$LANG['patches']} {$LANG['are']} {$LANG['up']} {$LANG['to']} " .
                "{$LANG['date']}. {$LANG['youUc']} {$LANG['can']} {$LANG['continue']} {$LANG['working']} {$LANG['with']} " .
                "{$LANG['simpleInvoices']}",
            'html' => "<div class='si_toolbar margin__top-3 margin__bottom-2'><a href='index.php' class='button'>{$LANG['homeUcAll']}</a></div>",
            'refresh' => 3
        ];
        $smarty->assign("page", $pageInfo);
    }

    private static function runSqlPatch(int $id, array $patch): array
    {
        global $LANG, $pdoDbAdmin;

        Log::out("SqlPatchManager::runSqlPatch() - id[$id] patch: " . print_r($patch, true));
        $escapedId = Util::htmlSafe($id);
        $patchName = Util::htmlSafe($patch['name']);

        $smartyRow = [];
        try {
            $pdoDbAdmin->setSelectAll(true);
            $pdoDbAdmin->addSimpleWhere('sql_patch_ref', $id);
            $rows = $pdoDbAdmin->request('SELECT', 'sql_patchmanager');
            Log::out("SqlPatchManager::runSqlPatch() - rows: " . print_r($rows, true));
            if (!empty($rows)) {
                if ($id < self::$numberToBeginPatchListAt) {
                    if ($id == 1) {
                        $smartyRow['text'] = "*** Previously applied patches skipped to #" . self::$numberToBeginPatchListAt . " ***";
                        $smartyRow['result'] = "sep";
                    }
                } else {
                    // forget about the patch as it has already been run!!
                    $smartyRow['text'] = "{$LANG['skippingUc']} {$LANG['sqlUc']} {$LANG['patch']} $escapedId, $patchName {$LANG['asLc']} {$LANG['it']} <i>{$LANG['has']}</i> {$LANG['already']} {$LANG['been']} {$LANG['applied']}";
                    $smartyRow['result'] = "skip";
                }
            } else {
                // Validate patches before being applied
                if ($id == 321) {
                    self::prePatch321();
                } elseif ($id == 322) {
                    self::prePatch322();
                } elseif ($id == 331) {
                    self::prePatch331();
                }
                // patch hasn't been run, so run it
                $pdoDbAdmin->query($patch['patch']);

                $smartyRow['text'] = "{$LANG['sqlUc']} {$LANG['patch']} $escapedId, $patchName <i>{$LANG['has']}</i> {$LANG['been']} {$LANG['applied']} {$LANG['to']} {$LANG['the']} {$LANG['database']}";
                $smartyRow['result'] = "done";

                // now update the sql_patchmanager table
                $pdoDbAdmin->setFauxPost([
                    'sql_patch_ref' => $id,
                    'sql_patch'     => $patch['name'],
                    'sql_release'   => $patch['date'],
                    'sql_statement' => $patch['patch'],
                    'source'        => $patch['source']
                ]);

                if ($pdoDbAdmin->request('INSERT', 'sql_patchmanager') == 0) {
                    // Caught below
                    throw new PdoDbException("SqlPatchManager::runSqlPatch() = Unable to insert into sql_patchmanager.");
                }

                if ($id == 321) {
                    self::postPatch321();
                } elseif ($id == 322) {
                    self::postPatch322();
                }
            }
        } catch (PdoDbException $pde) {
            error_log("SqlPatchManager::runSqlPatch() - " . $pde->getMessage());
            exit("SqlPatchManager::runSqlPatch() error. See error log for more information.");
        }

        return $smartyRow;
    }

    /**
     * Run the unapplied patches.
     */
    public static function runPatches(): void
    {
        global $LANG, $pdoDbAdmin, $smarty;

        // Initialize patch data if not already done
        if (self::$patchCount == 0) {
            self::loadPatches();
        }

        try {
            $rows = $pdoDbAdmin->request('SHOW TABLES', 'sql_patchmanager');
        } catch (PdoDbException $pde) {
            error_log("SqlPatchManager::runPatches() - SHOW TABLES failed - Error: " . $pde->getMessage());
            exit("SqlPatchManager::runPatches() error. See error log for additional details.");
        }

        $pageInfo = [];
        $initPatchTable = true;
        if (count($rows) == 1) {
            // Check to see if database patch level contains patches consistent with
            // point where fearless359/simpleinvoices version patches diverged from
            // original simpleinvoices version.
            try {
                $pdoDbAdmin->begin();
            } catch (PdoDbException $pde) {
                error_log("SqlPatchManager::runPatches() - Begin transaction failed - Error: " . $pde->getMessage());
                exit("SqlPatchManager::runPatches() error. See error log for additional details.");
            }

            $ndx = 0;
            $pageInfo['html'] = '';
            foreach (self::$patchLines as $patch) {
                $ndx++;
                $result = self::runSqlPatch($ndx + self::BEGINNING_PATCH_NUMBER, $patch);
                if (!empty($result)) {
                    $pageInfo['rows'][$ndx] = $result;
                }
            }

            $initPatchTable = false;
            try {
                $pdoDbAdmin->commit();
            } catch (PdoDbException $pde) {
                error_log("SqlPatchManager::runPatches() - Commit failed - Error: " . $pde->getMessage());
                exit("SqlPatchManager::runPatches() error. See error log for additional details.");
            }

            $pageInfo['message'] = "{$LANG['theUc']} {$LANG['database']} {$LANG['patches']} {$LANG['have']} {$LANG['now']} " .
                "{$LANG['been']} {$LANG['applied']}. {$LANG['youUc']} {$LANG['can']} {$LANG['now']} " .
                "{$LANG['startWorking']} {$LANG['with']} {$LANG['simpleInvoices']}";
            $pageInfo['html'] .= "<div class='si_toolbar align__text-center margin__top-3 margin__bottom-2'><a href='index.php' class='button'>{$LANG['homeUcAll']}</a></div>";
            $pageInfo['refresh'] = 5;
        }

        if ($initPatchTable) {
            $pageInfo['html'] .= "{$LANG['stepUc']} 1 - {$LANG['thisUc']} {$LANG['is']} {$LANG['the']} {$LANG['first']} {$LANG['time']} " .
                "{$LANG['databaseUc']} {$LANG['updatesUc']} {$LANG['has']} {$LANG['been']} {$LANG['run']}";
            $pageInfo['html'] .= self::initializeSqlPatchTable();
            $pageInfo['html'] .= "<br />{$LANG['nowUc']} {$LANG['that']} {$LANG['the']} {$LANG['databaseUc']} {$LANG['upgrade']} " .
                "{$LANG['table']} {$LANG['has']} {$LANG['been']} {$LANG['initialized']}, {$LANG['click']} {$LANG['the']} " .
                "{$LANG['following']} {$LANG['button']} {$LANG['to']} {$LANG['return']} {$LANG['to']} {$LANG['the']} " .
                "{$LANG['databaseUpgradeManager']} {$LANG['page']} {$LANG['to']} {$LANG['run']} {$LANG['the']} " .
                "{$LANG['remaining']} {$LANG['patches']}." .
                "<div class='si_toolbar align__text-center margin__top-3 margin__bottom-2'><a href='index.php?module=options&amp;view=database_sqlpatches' class='button'>{$LANG['continueUc']}</a></div>.";
        }

        $smarty->assign("page", $pageInfo);
    }

    /**
     * List all patches and their status.
     */
    public static function listPatches(): void
    {
        global $LANG, $smarty;
        // Initialize patch data if not already done
        if (self::$patchCount == 0) {
            self::loadPatches();
        }

        $pageInfo = [];
        $pageInfo['message'] = "Your version of SimpleInvoices can now be upgraded. With this new release there are database patches that need to be applied";
        $pageInfo['html'] =
            "<div class='si_message_install'>" .
            "{$LANG['theUc']} {$LANG['list']} {$LANG['below']} {$LANG['describes']} {$LANG['which']} {$LANG['patches']} {$LANG['have']} {$LANG['andLc']} " .
            "{$LANG['have']} {$LANG['notLc']} {$LANG['been']} {$LANG['applied']} {$LANG['to']} {$LANG['the']} {$LANG['database']}. {$LANG['if']} " .
            "{$LANG['there']} {$LANG['are']} {$LANG['patches']} {$LANG['that']} {$LANG['have']} {$LANG['notLc']} {$LANG['been']} {$LANG['applied']}, " .
            "{$LANG['run']} {$LANG['the']} {$LANG['database']} {$LANG['update']} {$LANG['by']} {$LANG['clicking']} {$LANG['update']}." .
            "</div>" .
            "<div class='si_message_warning'>{$LANG['warningUcAll']}: {$LANG['pleaseUc']} {$LANG['backupYourDatabase']} {$LANG['before']} {$LANG['upgrading']}!</div>" .
            "<div class='align__text-center margin__top-3 margin__bottom-2'>" .
            "  <a href='index.php?case=run' class='button'><img src='../../images/tick.png' alt='update'/>{$LANG['updateUc']}</a>" .
            "</div>";
        $ndx = 319;
        foreach (self::$patchLines as $patch) {
            $ndx++;
            $patchName = Util::htmlSafe($patch['name']);
            $patchDate = Util::htmlSafe($patch['date']);
            if (self::checkIfSqlPatchApplied($ndx)) {
                if ($ndx < self::$numberToBeginPatchListAt) {
                    if ($ndx == 1) {
                        $pageInfo['rows'][$ndx]['text'] = "*** Previously applied patches skipped to #" . self::$numberToBeginPatchListAt . " ***";
                        $pageInfo['rows'][$ndx]['result'] = "sep";
                    }
                } else {
                    $pageInfo['rows'][$ndx]['text'] = "{$LANG['sqlUc']} {$LANG['patch']} $ndx, $patchName <i>{$LANG['has']}</i> {$LANG['already']} {$LANG['been']} {$LANG['applied']} {$LANG['in']} {$LANG['release']} $patchDate";
                    $pageInfo['rows'][$ndx]['result'] = 'skip';
                }
            } else {
                $pageInfo['rows'][$ndx]['text'] = "{$LANG['sqlUc']} {$LANG['patch']} $ndx, $patchName " .
                    "<span class='error'><b>{$LANG['has']} {$LANG['notLc']}</b> {$LANG['been']} {$LANG['applied']} {$LANG['to']} {$LANG['the']} {$LANG['database']}</span>";
                $pageInfo['rows'][$ndx]['result'] = 'todo';
            }
        }

        $smarty->assign("page", $pageInfo);
    }

    /**
     * Get all patches.
     * @return array Rows retrieved. Test for "=== false" to check for failure.
     */
    public static function sqlPatches(): array
    {
        global $pdoDbAdmin;

        try {
            $pdoDbAdmin->addToWhere(new WhereItem(false, "sql_patch", "<>", "", false, "OR"));
            $pdoDbAdmin->addToWhere(new WhereItem(false, "sql_release", "<>", "", false, "OR"));
            $pdoDbAdmin->addToWhere(new WhereItem(false, "sql_statement", "<>", "", false));
            $pdoDbAdmin->setOrderBy([["sql_release", "A"], ["sql_patch_ref", "A"]]);

            $rows = $pdoDbAdmin->request("SELECT", "sql_patchmanager");
        } catch (PdoDbException $pde) {
            error_log("SqlPatchManager::qqlPatches() - Error: " . $pde->getMessage());
            exit("SqlPatchManager::sqlPatches() error. See error log for additional details.");
        }
        return $rows;
    }

    /**
     * Check to see if patch is in database (aka applied).
     * @param int $patchRef
     * @return bool true if applied, false if not.
     */
    private static function checkIfSqlPatchApplied( int $patchRef ): bool
    {
        global $pdoDbAdmin;

        if ($patchRef == 0) {
            return true; // start patch always applied
        }

        try {
            $pdoDbAdmin->addSimpleWhere('sql_patch_ref', $patchRef);
            $rows = $pdoDbAdmin->request('SELECT', 'sql_patchmanager');
        } catch (PdoDbException) {
            return false;
        }
        return !empty($rows);
    }

    /**
     * Create the sql_patchmanager table and save initial record in it.
     * @return string
     */
    private static function initializeSqlPatchTable(): string
    {
        global $LANG, $pdoDbAdmin;

        try {
            $pdoDbAdmin->addTableColumns("sql_id", "INT", "NOT NULL AUTO_INCREMENT PRIMARY KEY");
            $pdoDbAdmin->addTableColumns("sql_patch_ref", "VARCHAR( 50)", "NOT NULL");
            $pdoDbAdmin->addTableColumns("sql_patch", "VARCHAR(255)", "NOT NULL");
            $pdoDbAdmin->addTableColumns("sql_release", "VARCHAR( 25)", "NOT NULL");
            $pdoDbAdmin->addTableColumns("sql_statement", "TEXT", "NOT NULL");
            $pdoDbAdmin->addTableEngine("MYISAM");
            if (!$pdoDbAdmin->request("CREATE TABLE", "sql_patchmanager")) {
                // Caught below.
                throw new PdoDbException("SqlPatchManager::initializeSqlPatchTable() - Unable to create sql_patchmanager table.");
            }

            $log = "<b>{$LANG['stepUc']} 2</b> - {$LANG['theUc']} {$LANG['sqlUc']} {$LANG['patch']} {$LANG['table']} {$LANG['has']} {$LANG['been']} {$LANG['created']}<br />";

            $pdoDbAdmin->setFauxPost([
                'sql_patch_ref' => '319',
                'sql_patch'     => 'Add set_aging field to si_preferences',
                'sql_release'   => '20200123',
                'sql_statement' => "ALTER TABLE `si_preferences` ADD COLUMN `set_aging` BOOL NOT NULL DEFAULT  '0' AFTER `index_group`;" .
                    "UPDATE `si_preferences` SET `set_aging` = 1 WHERE pref_id = '1';"
            ]);

            if ($pdoDbAdmin->request('INSERT', 'sql_patchmanager') == 0) {
                // Caught below
                throw new PdoDbException("SqlPatchManager::initializeSqlPatchTable() - Unable to save create sql_patchmanager record in table.");
            }

            $log .= "<b>{$LANG['stepUc']} 3</b> - {$LANG['theUc']} {$LANG['sqlUc']} {$LANG['patch']} {$LANG['has']} {$LANG['been']} {$LANG['inserted']} {$LANG['into']} {$LANG['the']} {$LANG['sqlUc']} {$LANG['patch']} {$LANG['table']}<br />";
        } catch (PdoDbException $pde) {
            error_log("SqlPatchManager::initializeSqlPatchTable() - Error: " . $pde->getMessage());
            exit("SqlPatchManager::initializeSqlPatchTable() error. See error log for additional information.");
        }

        return $log;
    }


    /**
     * Save product group information for those with extension enabled.
     * Note: Will perform exit() with error message if error thrown by called methods.
     */
    private static function prePatch321(): void
    {
        global $pdoDbAdmin, $subCustomerExtEnabled;

        // If the extension has been enabled at some time, the "parent_customer_id" field will
        // exist in the "customers" table. If that is the case, we need to determine if the
        // extension is still enabled.
        try {
            $fldExists = $pdoDbAdmin->checkFieldExists("customers", "parent_customer_id");
            if ($fldExists) {
                $pdoDbAdmin->addSimpleWhere("name", "sub_customer", "AND");
                $pdoDbAdmin->addSimpleWhere("domain_id", DomainId::get());
                $recs = $pdoDbAdmin->request("SELECT", "extensions");
                $subCustomerExtEnabled = empty($recs) ? DISABLED : $recs[0]['enabled'];
            } else {
                $subCustomerExtEnabled = DISABLED;
            }
        } catch (PdoDbException $pde) {
            $str = "SqlPatchManager::prePatch321() - Unexpected error: " . $pde->getMessage();
            error_log($str);
            exit($str);
        }
    }

    /**
     * Special handling for patch #321
     * Note: Will perform exit() with error message if error thrown by called methods.
     */
    private static function postPatch321(): void
    {
        global $subCustomerExtEnabled;

        if ($subCustomerExtEnabled == ENABLED) {
            if (!SystemDefaults::updateDefault('product_groups', ENABLED)) {
                error_log("SqlPatchManager::postPatch321() - DB update failed.");
                exit("SqlPatchManager::postPatch321() error. See error log for additional details.");
            }
        }
    }

    /**
     * Save product group information for those with extension enabled.
     * Note: Will perform exit() with error message if error thrown by called methods.
     */
    private static function prePatch322(): void
    {
        global $pdoDbAdmin, $productGroupEnabled;

        try {
            $pdoDbAdmin->addSimpleWhere('name', 'invoice_grouped');
            $rows = $pdoDbAdmin->request('SELECT', 'extensions');

            $extEnabled = !empty($rows) && $rows[0]['enabled'] == ENABLED;
            if ($extEnabled) {
                $pdoDbAdmin->addSimpleWhere('cf_custom_field', "product_cf1");
                $rows = $pdoDbAdmin->request('SELECT', 'custom_fields');
                $productGroupEnabled = !empty($rows[0]['cf_custom_label']);
            } else {
                $productGroupEnabled = false;
            }
        } catch (PdoDbException $pde) {
            $str = "SqlPatchManager::prePatch322() - Error: {$pde->getMessage()}";
            error_log($str);
            exit($str);
        }
    }

    /**
     * Special handling for patch #322
     * Note: Will perform exit() with error message if error thrown by called methods.
     */
    private static function postPatch322(): void
    {
        global $pdoDbAdmin, $productGroupEnabled;

        if ($productGroupEnabled) {
            try {
                $pdoDbAdmin->setFauxPost(['value' => ENABLED]);
                $pdoDbAdmin->addSimpleWhere('name', 'product_groups');
                if (!$pdoDbAdmin->request('UPDATE', 'system_defaults')) {
                    error_log("SqlPatchManager::postPatch322() - DB update failed.");
                    exit("SqlPatchManager::postPatch322() error. See error log for additional details.");
                }
            } catch (PdoDbException $pde) {
                error_log("SqlPatchManager::postPatch322() - Error: " . $pde->getMessage());
                exit("SqlPatchManager::postPatch322() error. See error log for additional details.");
            }
        }
    }

    /**
     * Set foreign key constraint invoice_item_tax table for invoice_item_id field.
     * @throws PdoDbException If undefined foreign key values found.
     * @noinspection PhpVariableNamingConventionInspection
     */
    private static function prePatch331(): void
    {
        global $pdoDbAdmin;

        $undefined_values = [];
        set_time_limit(240);
        $table = 'invoice_item_tax';
        $foreign_keys = ['invoice_items' => 'invoice_item_id', 'tax' => 'tax_id'];
        $columns = ['invoice_items' => 'id', 'tax' => 'tax_id'];

        // Check for existing records and verify that fields of concern are not null
        // and that records exist for what they point to.
        $pdoDbAdmin->addToWhere(new WhereItem(false, "id", '<>', '0', false));
        $pdoDbAdmin->setSelectAll(true);
        $rows = $pdoDbAdmin->request("SELECT", $table);
        foreach ($rows as $row) {
            foreach ($foreign_keys as $fkKey => $fkItem) {
                // Verify the field we want to make a foreign key is not null
                if (isset($row[$fkItem])) {
                    // Verify the field references and existing value in the reference table.
                    $value = $row[$fkItem];
                    $column = $columns[$fkKey];
                    $pdoDbAdmin->addSimpleWhere($column, $value);
                    $recs = $pdoDbAdmin->request("SELECT", $fkKey);
                    if (empty($recs)) {
                        $undefined_values[$table . ':' . $fkItem . ':' . $fkKey . ':' . $column . ':' . $value] = $value;
                    }
                } else {
                    $undefined_values[$table . ':' . $fkItem . ':' . ':' . ':'] = "NULL";
                }
            }
        }

        if (!empty($undefined_values)) {
            $msg = "\nUnable to apply patch 331. Found foreign key table columns with NULL values or\n" .
                "values not in the reference table column. The following list shows what values in foreign\n" .
                "key columns are missing from reference columns.\n\n";

            $msg .= "The records in the FOREIGN KEY TABLE are most likely invalid and should be deleted.\n\n";

            $msg .= "FOREIGN KEY TABLE         COLUMN              REFERENCE TABLE          COLUMN     INVALID VALUE\n" .
                "------------------------  ------------------  -----------------------  ---------  -------------\n";
            foreach ($undefined_values as $key => $value) {
                $parts = explode(':', $key);
                $line = sprintf('%-24s  %-18s  %-23s  %-9s  %s', $parts[0], $parts[1], $parts[2], $parts[3], $value);
                $msg .= $line . "\n";
            }
            error_log($msg);
            throw new PdoDbException("SqlPatchManager::prePatch331() = Unable to set Foreign Keys.");
        }
    }

    /**
     * Load all patches to be processed.
     */
    private static function loadPatches(): void
    {
        global $pdoDbAdmin;

        // @formatter:off
        $domainId = DomainId::get();

        $patch = [
            'name' => "Remove deleted extensions mini and measurement",
            'patch' => "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'mini' OR `name` = 'measurement';",
            'date' => "20200822",
            'source' => 'fearless359'
        ];
        self::makePatch('320', $patch);

        $fldExists = $pdoDbAdmin->checkFieldExists("customers", "parent_customer_id");
        $patch = [
            'name' => 'Add parent_customer_id field to the database',
            'patch' => $fldExists ?
                "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'sub_customer';" .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`name`, `value`, `domain_id`, `extension_id`) VALUES ('sub_customer', 0, $domainId, 1);" :
                "ALTER TABLE `" . TB_PREFIX . "customers` ADD `parent_customer_id` INT(11) NULL AFTER `notes`; " .
                "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'sub_customer'; " .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`name`, `value`, `domain_id`, `extension_id`) VALUES ('sub_customer', 0, $domainId, 1);",
            'date' => "20200924",
            'source' => 'fearless359'
        ];
        self::makePatch('321', $patch);
        unset($fldExists);
        unset($extEnabled);

        $tblExists = $pdoDbAdmin->checkTableExists( 'product_groups');
        $patch = [
            'name' => 'Add product_groups table to the database.',
            'patch' => $tblExists ? "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'invoice_grouped';" :
                "CREATE TABLE `" . TB_PREFIX . "product_groups` (name VARCHAR(60) NOT NULL PRIMARY KEY, " .
                                                                "markup INT(2) NOT NULL DEFAULT 0) ENGINE = InnoDb; " .
                "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'invoice_grouped'; " .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`name`, `value`, `domain_id`, `extension_id`) VALUES ('product_groups', 0, $domainId, 1); " .
                "ALTER TABLE `" . TB_PREFIX . "products` ADD product_group VARCHAR(60) NOT NULL DEFAULT ''; " .
                "INSERT INTO `" . TB_PREFIX . "product_groups` (`name`, `markup`) VALUES ('Labor', 0); " .
                "INSERT INTO `" . TB_PREFIX . "product_groups` (`name`, `markup`) VALUES ('Equipment', 0); " .
                "INSERT INTO `" . TB_PREFIX . "product_groups` (`name`, `markup`) VALUES ('Materials', 0); " .
                "INSERT INTO `" . TB_PREFIX . "product_groups` (`name`, `markup`) VALUES ('Subcontractor', 0);",
            'date' => "20201010",
            'source' => 'fearless359'
        ];
        self::makePatch('322', $patch);

        $patch = [
            'name' => "Add invoice description open option.",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` (name ,value ,domain_id ,extension_id ) VALUES ('invoice_description_open', 0, $domainId, 1);",
            'date' => "20210413",
            'source' => 'fearless359'
        ];
        self::makePatch('323', $patch);

        $patch = [
            'name' => "Rename si_products_values table to si_products_attributes_values.",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products_values` RENAME TO " . TB_PREFIX . "products_attributes_values;",
            'date' => "20210527",
            'source' => 'fearless359'
        ];
        self::makePatch('324', $patch);

        $patch = [
            'name' => 'Remove unused items from the si_system_defaults table.',
            'patch' =>
                "DELETE IGNORE FROM `" . TB_PREFIX . "system_defaults` WHERE `name` in " .
                        "('company_name', 'emailhost', 'emailpassword', 'emailusername', 'pdfbottommargin', 'pdfleftmargin', ".
                        "'pdfpapersize', 'pdfrightmargin', 'pdfscreensize', 'pdftopmargin', 'spreadsheet', 'wordprocessor'); " .
                "DELETE IGNORE FROM `" . TB_PREFIX . "system_defaults` WHERE `name` LIKE 'dateformat%';",
            'date' => "20200615",
            'source' => 'fearless359'
        ];
        self::makePatch('325', $patch);

        $patch = [
            'name' => "Add display department option.",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` (name ,value ,domain_id ,extension_id ) VALUES ('display_department', 1, $domainId, 1);",
            'date' => "20210930",
            'source' => 'fearless359'
        ];
        self::makePatch('326', $patch);

        $patch = [
            'name' => "Make invoice_item_id a key field in the invoice_item_tax table.",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_item_tax` ADD KEY `invoice_item_id` (`invoice_item_id`);",
            'date' => "20220909",
            'source' => 'fearless359'
        ];
        self::makePatch('327', $patch);

        $tblExists = $pdoDbAdmin->checkTableExists( 'cron_invoice_items');
        $patch = [
            'name' => 'Add cron_invoice_items table to the database.',
            'patch' => $tblExists ? "SELECT * FROM " . TB_PREFIX . "cron_invoice_items;" :
                "CREATE TABLE `" . TB_PREFIX . "cron_invoice_items` (id int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
                                                                   "`cron_id` int(11) UNSIGNED NOT NULL, " .
                                                                   "`quantity` decimal(25,6) NOT NULL DEFAULT 0.000000, " .
                                                                   "`product_id` int(11) UNSIGNED NOT NULL, " .
                                                                   "`unit_price` decimal(25,6) DEFAULT 0.000000, " .
                                                                   "`tax_amount` decimal(25,6) DEFAULT 0.000000, " .
                                                                   "`gross_total` decimal(25,6) DEFAULT 0.000000, " .
                                                                   "`description` text COLLATE utf8_unicode_ci DEFAULT NULL, " .
                                                                   "`total` decimal(25,6) DEFAULT 0.000000, " .
                                                                   "`attribute` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL) ENGINE = InnoDb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "cron_invoice_items` " .
                    "ADD KEY `cron_id` (`cron_id`), " .
                    "ADD KEY `product_id` (`product_id`);" .
                "ALTER TABLE `" . TB_PREFIX . "cron_invoice_items` " .
                    "ADD CONSTRAINT `" . TB_PREFIX . "cron_invoice_items_ibfk_1` FOREIGN KEY (`cron_id`) REFERENCES `" . TB_PREFIX . "cron` (`id`) ON UPDATE CASCADE, " .
                    "ADD CONSTRAINT `" . TB_PREFIX . "cron_invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `" . TB_PREFIX . "products` (`id`) ON UPDATE CASCADE;",
            'date' => "20220909",
            'source' => 'fearless359'
        ];
        self::makePatch('328', $patch);

        $tblExists = $pdoDbAdmin->checkTableExists( 'cron_invoice_item_tax');
        $patch = [
            'name' => 'Add cron_invoice_item_tax table to the database.',
            'patch' => $tblExists ? "SELECT * FROM " . TB_PREFIX . "cron_invoice_item_tax;" :
                "CREATE TABLE `" . TB_PREFIX . "cron_invoice_item_tax` (" .
                    "`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
                    "`cron_invoice_item_id` int(11) UNSIGNED NOT NULL, " .
                    "`tax_id` int(11) UNSIGNED NOT NULL, " .
                    "`tax_type` char(1) COLLATE utf8_unicode_ci DEFAULT NULL, " .
                    "`tax_rate` decimal(25,6) NOT NULL, " .
                    "`tax_amount` decimal(25,6) NOT NULL) ENGINE = InnoDb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; " .
                "ALTER TABLE `" . TB_PREFIX . "cron_invoice_item_tax` " .
                    "ADD KEY `cron_invoice_item_id` (`cron_invoice_item_id`), " .
                    "ADD KEY `tax_id` (`tax_id`); " .
                "ALTER TABLE `" . TB_PREFIX . "cron_invoice_item_tax` " .
                    "ADD CONSTRAINT `" . TB_PREFIX . "cron_invoice_item_tax_ibfk_1` FOREIGN KEY (`tax_id`) REFERENCES `" . TB_PREFIX . "tax` (`tax_id`) ON UPDATE CASCADE, " .
                    "ADD CONSTRAINT `" . TB_PREFIX . "cron_invoice_item_tax_ibfk_2` FOREIGN KEY (`cron_invoice_item_id`) REFERENCES `" . TB_PREFIX . "cron_invoice_items` (`id`) ON UPDATE CASCADE;",
            'date' => "20220909",
            'source' => 'fearless359'
        ];
        self::makePatch('329', $patch);

        $patch = [
            'name' => 'Add invoice_item_id as a key for the invoice_item_tax table.',
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_tax` " .
                    "MODIFY `invoice_item_id` INT(11) UNSIGNED NOT NULL, " .
                    "MODIFY `tax_id` INT(11) UNSIGNED NOT NULL; " .
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_tax` ADD KEY `invoice_item_id` (`invoice_item_id`);",
            'date' => "20220926",
            'source' => 'fearless359'
        ];
        self::makePatch('330', $patch);

        $foreignKeyExists = $pdoDbAdmin->checkForeignKeyExists("invoice_item_tax_ibfk_2", "invoice_item_tax", "invoice_items");
        $patch = [
            'name' => 'Add foreign key for invoice_item_id to invoice_item_tax table.',
            'patch' => $foreignKeyExists ? "SELECT * FROM " . TB_PREFIX . "invoice_item_tax;" :
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_tax` " .
                    "ADD CONSTRAINT `" . TB_PREFIX . "invoice_item_tax_ibfk_2` FOREIGN KEY (`invoice_item_id`) REFERENCES `" . TB_PREFIX . "invoice_items` (`id`) ON UPDATE CASCADE;",
            'date' => "20221013",
            'source' => 'fearless359'
        ];
        self::makePatch('331', $patch);

        $tblExists = $pdoDbAdmin->checkTableExists( 'payment_warehouse');
        $patch = [
            'name' => 'Add payment_warehouse table to the database.',
            'patch' => $tblExists ? "SELECT * FROM " . TB_PREFIX . "payment_warehouse;" :
                "CREATE TABLE `" . TB_PREFIX . "payment_warehouse` (" .
                    "`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
                    "`customer_id` int(11) UNSIGNED NOT NULL, " .
                    "`last_payment_id` int(11) UNSIGNED, " .
                    "`balance` decimal(25,6) NOT NULL, " .
                    "`payment_type` int(11) UNSIGNED NOT NULL, " .
                    "`check_number` varchar(10) DEFAULT NULL) ENGINE = InnoDb DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "payment_warehouse` " .
                    "ADD KEY `customer_id` (`customer_id`), " .
                    "ADD KEY `last_payment_id` (`last_payment_id`), " .
                    "ADD KEY `payment_type` (`payment_type`);" .
                "ALTER TABLE `" . TB_PREFIX . "payment_warehouse` " .
                    "ADD CONSTRAINT `" . TB_PREFIX . "payment_warehouse_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `" . TB_PREFIX . "customers` (`id`) ON UPDATE CASCADE," .
                    "ADD CONSTRAINT `" . TB_PREFIX . "payment_warehouse_ibfk_2` FOREIGN KEY (`last_payment_id`) REFERENCES `" . TB_PREFIX . "payment` (`id`) ON UPDATE SET NULL ON DELETE SET NULL," .
                    "ADD CONSTRAINT `" . TB_PREFIX . "payment_warehouse_ibfk_3` FOREIGN KEY (`payment_type`) REFERENCES `" . TB_PREFIX . "payment_types` (`pt_id`) ON UPDATE CASCADE;",
            'date' => "20221013",
            'source' => 'fearless359'
        ];
        self::makePatch('332', $patch);

        $patch = [
            'name' => "Add warehouse_amount field to payment table.",
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "payment` " .
                    "ADD `customer_id` int(11) UNSIGNED AFTER `ac_inv_id`, " .
                    "ADD `warehouse_amount` decimal(25,6) NOT NULL DEFAULT 0 AFTER `ac_check_number`; " .
                "ALTER TABLE `" . TB_PREFIX . "payment` " .
                    "ADD KEY `customer_id` (`customer_id`); " .
                "ALTER TABLE `" . TB_PREFIX . "payment` " .
                    "ADD CONSTRAINT `" . TB_PREFIX . "payment_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `" . TB_PREFIX . "customers` (`id`) ON UPDATE SET NULL ON DELETE SET NULL;",
            'date' => "20221019",
            'source' => 'fearless359'
        ];
        self::makePatch('333', $patch);

        $patch = [
            'name' => "Add payment delete days option.",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` (name ,value ,domain_id ,extension_id ) VALUES ('payment_delete_days', 0, $domainId, 1);",
            'date' => "20221022",
            'source' => 'fearless359'
        ];
        self::makePatch('334', $patch);

        $patch = [
            'name' => "Add invoice display days option.",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` (name ,value ,domain_id ,extension_id ) VALUES ('invoice_display_days', 0, $domainId, 1);",
            'date' => "20221110",
            'source' => 'fearless359'
        ];
        self::makePatch('335', $patch);

        $patch = [
            'name' => 'Make id type for si_log table consistent with all other id settings.',
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "log` " .
                "MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;",
            'date' => "20230717",
            'source' => 'fearless359'
        ];
        self::makePatch('336', $patch);

        // @formatter:on
    }

}
