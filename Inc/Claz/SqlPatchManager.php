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
    // Note that array is zero based but patch ref is 1 based.
    private static array $patchLines = [];
    private static int $patchCount = 0;
    private static int $numberToBeginPatchListAt = 0;
    private static int $lastPatchApplied = 0;
    private static bool $lastPatchAppliedSet = false;

    /**
     * Add an entry to the $patchLines array.
     * @param int $num Number of patch, must match what is expected next.
     * @param array $patch entry to add to $patchLines.
     */
    private static function makePatch(int $num, array $patch): void
    {
        static $last = 0;
        $last++;

        if ($last != $num) {
            error_log("SqlPatchManager::makePatch() - Patch #$num is out of sequence.");
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
     * @return int number of the last applied to the SI database.
     */
    public static function lastPatchApplied(): int
    {
        global $pdoDbAdmin;

        if (self::$lastPatchAppliedSet) {
            return self::$lastPatchApplied;
        }

        self::$lastPatchAppliedSet = true;
        self::$lastPatchApplied = 0;
        self::$numberToBeginPatchListAt = 0;

        // Get the last patch applied to the database. This is the table entry with
        // the greatest sql_patch_ref value.
        try {
            $pdoDbAdmin->setSelectList(['sql_patch_ref']);
            $pdoDbAdmin->setOrderBy(['sql_patch_ref', 'D']);
            $pdoDbAdmin->setLimit(1);
            $rows = $pdoDbAdmin->request("SELECT", "sql_patchmanager");
        } catch (PdoDbException) {
            return 0;
        }

        // Returns number of patches applied
        if (!empty($rows)) {
            self::$lastPatchApplied = $rows[0]['sql_patch_ref'];
            self::$numberToBeginPatchListAt = self::$lastPatchApplied - 20;
        }

        Log::out("SqlPatchManager::lastPatchAppllied() - lastPatchApplied[" . self::$lastPatchApplied . "] " .
            "numberToBeginPatchListAt[" . self::$numberToBeginPatchListAt . "]");
        return self::$lastPatchApplied;
    }

    /**
     * @return int Count of patches
     */
    public
    static function numberOfUnappliedPatches(): int
    {
        // Initialize patch data if not already done
        self::loadPatches();

        return self::$patchCount - self::lastPatchApplied();
    }

    /**
     * Assign database patches up to date message in smarty "page" variable.
     */
    public
    static function donePatchesMessage(): void
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

    /**
     * Run a single patch.
     * @param int $sql_patch_ref Number of the patch
     * @param array $patch Patch array
     * @return array Smarty rows of arrays to output for text and result of patch.
     */
    private
    static function runSqlPatch(int $sql_patch_ref, array $patch): array
    {
        global $LANG, $pdoDbAdmin;

        Log::out("SqlPatchManager::runSqlPatch() - sql_patch_ref[$sql_patch_ref] patch: " . print_r($patch, true));
        $escaped_sql_patch_ref = Util::htmlSafe($sql_patch_ref);
        $patchName = Util::htmlSafe($patch['name']);

        $smartyRow = [];
        try {
            $pdoDbAdmin->setSelectAll(true);
            $pdoDbAdmin->addSimpleWhere('sql_patch_ref', $sql_patch_ref);
            $rows = $pdoDbAdmin->request('SELECT', 'sql_patchmanager');
            Log::out("SqlPatchManager::runSqlPatch() - rows: " . print_r($rows, true));
            if (!empty($rows) || $sql_patch_ref <= self::lastPatchApplied()) {
                if ($sql_patch_ref < self::$numberToBeginPatchListAt) {
                    if ($sql_patch_ref == 1) {
                        $smartyRow['text'] = "*** Previously applied patches skipped to #" . self::$numberToBeginPatchListAt . " ***";
                        $smartyRow['result'] = "sep";
                    }
                } else {
                    // forget about the patch as it has already been run!!
                    $smartyRow['text'] = "{$LANG['skippingUc']} {$LANG['sqlUc']} {$LANG['patch']} $escaped_sql_patch_ref, $patchName {$LANG['asLc']} {$LANG['it']} <i>{$LANG['has']}</i> {$LANG['already']} {$LANG['been']} {$LANG['applied']}";
                    $smartyRow['result'] = "skip";
                }
            } else {
                // Validate patches before being applied
                if ($sql_patch_ref == 308) {
                    self::prePatch308();
                } elseif ($sql_patch_ref == 318) {
                    self::prePatch318();
                } elseif ($sql_patch_ref == 321) {
                    self::prePatch321();
                } elseif ($sql_patch_ref == 322) {
                    self::prePatch322();
                } elseif ($sql_patch_ref == 331) {
                    self::prePatch331();
                }

                // patch hasn't been run, so run it
                $pdoDbAdmin->query($patch['patch']);

                $smartyRow['text'] = "{$LANG['sqlUc']} {$LANG['patch']} $escaped_sql_patch_ref, $patchName <i>{$LANG['has']}</i> {$LANG['been']} {$LANG['applied']} {$LANG['to']} {$LANG['the']} {$LANG['database']}";
                $smartyRow['result'] = "done";

                // now update the sql_patchmanager table
                $pdoDbAdmin->setFauxPost([
                    'sql_patch_ref' => $sql_patch_ref,
                    'sql_patch' => $patch['name'],
                    'sql_release' => $patch['date'],
                    'sql_statement' => $patch['patch'],
                    'source' => $patch['source']
                ]);

                if ($pdoDbAdmin->request('INSERT', 'sql_patchmanager') == 0) {
                    // Caught below
                    throw new PdoDbException("SqlPatchManager::runSqlPatch() = Unable to insert into sql_patchmanager.");
                }

                if ($sql_patch_ref == 126) {
                    self::postPatch126();
                } else if ($sql_patch_ref == 303) {
                    self::postPatch303();
                } else if ($sql_patch_ref == 304) {
                    self::postPatch304();
                } elseif ($sql_patch_ref == 321) {
                    self::postPatch321();
                } elseif ($sql_patch_ref == 322) {
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
    public
    static function runPatches(): void
    {
        global $LANG, $pdoDbAdmin, $smarty;

        // Initialize patch data if not already done
        self::loadPatches();

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
            $pdoDbAdmin->begin();

            $patchNumber = 0;
            $pageInfo['html'] = '';
            foreach (self::$patchLines as $patch) {
                $patchNumber++;
                $result = self::runSqlPatch($patchNumber, $patch);
                if (!empty($result)) {
                    $pageInfo['rows'][$patchNumber] = $result;
                }
            }

            $initPatchTable = false;
            $pdoDbAdmin->commit();
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
    public
    static function listPatches(): void
    {
        global $LANG, $smarty;

        // Initialize patch data if not already done
        self::loadPatches();

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
        $ndx = 0;
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
    public
    static function sqlPatches(): array
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
     * @param int $sql_patch_ref
     * @return bool true if applied, false if not.
     */
    private
    static function checkIfSqlPatchApplied(int $sql_patch_ref): bool
    {
        return $sql_patch_ref <= self::lastPatchApplied();
    }

    /**
     * Create the sql_patchmanager table and save initial record in it.
     * @return string
     */
    private
    static function initializeSqlPatchTable(): string
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
                'sql_patch' => 'Add set_aging field to si_preferences',
                'sql_release' => '20200123',
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
     * Special handling for patch #126
     */
    private
    static function postPatch126(): void
    {
        global $pdoDbAdmin;

        try {
            $pdoDbAdmin->addSimpleWhere('product_id', 0);
            $rows = $pdoDbAdmin->request('SELECT', 'invoice_items');
            foreach ($rows as $row) {
                $pdoDbAdmin->setSelectList(array(
                    'description' => $row['description'],
                    'unit_price' => $row['gross_total'],
                    'enabled' => DISABLED,
                    'visible' => DISABLED));
                $id = $pdoDbAdmin->request('INSERT', 'invoice_items');

                $pdoDbAdmin->setFauxPost(array('product_id' => $id, 'unit_price' => $row['gross_total']));
                $pdoDbAdmin->addSimpleWhere('id', $row['id']);
                if (!$pdoDbAdmin->request('UPDATE', 'invoice_items')) {
                    throw new PdoDbException("'SqlPatchManager::postPatch126() - Update invoice_items error for " .
                        "id[{$row['id']}] product_id[$id] unit_price[{$row['gross_total']}]");
                }
            }
        } catch (PdoDbException $pde) {
            error_log("SqlPatchManager::postPatch126() - Error: " . $pde->getMessage());
            die ("SqlPatchManager::postPatch126() error. See error log for additional details.");
        }
    }

    /**
     * Create invoice_item_attachments table if it doesn't exist.
     */
    private
    static function prePatch308(): void
    {
        global $pdoDbAdmin;

        if (!$pdoDbAdmin->checkTableExists('invoice_item_attachments')) {
            $pdoDbAdmin->addTableColumns("id", "INT(10)", "NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique ID for this entry'");
            $pdoDbAdmin->addTableColumns("invoice_item_id", "INT(10)", "NOT NULL COMMENT 'ID of invoice item this attachment is associated with'");
            $pdoDbAdmin->addTableColumns("name", "VARCHAR(255)", "NOT NULL COMMENT 'Name of attached object'");
            $pdoDbAdmin->addTableColumns("attachment", "BLOB", "COMMENT 'Attached object'");
            $pdoDbAdmin->addTableEngine("InnoDB");
            if ($pdoDbAdmin->request("CREATE TABLE", "invoice_item_attachments")) {
                try {
                    $pdoDbAdmin->addTableConstraints('id', 'ADD UNIQUE KEY ~ (~)');
                    $pdoDbAdmin->addTableConstraints('invoice_item_id', 'ADD KEY ~ (~)');
                    if (!$pdoDbAdmin->request('ALTER TABLE', 'invoice_item_attachments')) {
                        throw new PdoDbException('Unable to keys to invoice_item_attachments.');
                    }
                } catch (PdoDbException $pde) {
                    error_log("SqlPatchManager::prePatch308() - Error: " . $pde->getMessage());
                }
            }
        }
    }

    /**
     * Set foreign key constraint on all tables that need it after first validating
     * that foreign key values are valid (present in the referenced table.
     * @throws PdoDbException If undefined foreign key values found.
     */
    private
    static function prePatch318(): void
    {
        global $pdoDbAdmin;

        // @formatter::off
        $fk_constraints = [
            [
                'table' => 'cron',
                'constraint' => 'fk_invoice',
                'foreign_key' => 'invoice_id',
                'references' => 'invoices',
                'column' => 'id'
            ],
            [
                'table' => 'cron_log',
                'constraint' => 'fk_cron',
                'foreign_key' => 'cron_id',
                'references' => 'cron',
                'column' => 'id'
            ],
            [
                'table' => 'expense',
                'constraint' => 'fk_biller',
                'foreign_key' => 'biller_id',
                'references' => 'biller',
                'column' => 'id'
            ],
            [
                'table' => 'expense',
                'constraint' => 'fk_customer',
                'foreign_key' => 'customer_id',
                'references' => 'customers',
                'column' => 'id'
            ],
            [
                'table' => 'expense',
                'constraint' => 'fk_invoice',
                'foreign_key' => 'invoice_id',
                'references' => 'invoices',
                'column' => 'id'
            ],
            [
                'table' => 'expense',
                'constraint' => 'fk_product',
                'foreign_key' => 'product_id',
                'references' => 'products',
                'column' => 'id'
            ],
            [
                'table' => 'expense',
                'constraint' => 'fk_expense_account',
                'foreign_key' => 'expense_account_id',
                'references' => 'expense_account',
                'column' => 'id'
            ],
            [
                'table' => 'expense_item_tax',
                'constraint' => 'fk_expense',
                'foreign_key' => 'expense_id',
                'references' => 'expense',
                'column' => 'id'
            ],
            [
                'table' => 'expense_item_tax',
                'constraint' => 'fk_tax',
                'foreign_key' => 'tax_id',
                'references' => 'tax',
                'column' => 'tax_id'
            ],
            [
                'table' => 'inventory',
                'constraint' => 'fk_product',
                'foreign_key' => 'product_id',
                'references' => 'products',
                'column' => 'id'
            ],
            [
                'table' => 'invoices',
                'constraint' => 'fk_biller',
                'foreign_key' => 'biller_id',
                'references' => 'biller',
                'column' => 'id'
            ],
            [
                'table' => 'invoices',
                'constraint' => 'fk_customer',
                'foreign_key' => 'customer_id',
                'references' => 'customers',
                'column' => 'id'
            ],
            [
                'table' => 'invoices',
                'constraint' => 'fk_invoice_type',
                'foreign_key' => 'type_id',
                'references' => 'invoice_type',
                'column' => 'inv_ty_id'
            ],
            [
                'table' => 'invoices',
                'constraint' => 'fk_preference',
                'foreign_key' => 'preference_id',
                'references' => 'preferences',
                'column' => 'pref_id'
            ],
            [
                'table' => 'invoice_items',
                'constraint' => 'fk_invoice',
                'foreign_key' => 'invoice_id',
                'references' => 'invoices',
                'column' => 'id'
            ],
            [
                'table' => 'invoice_items',
                'constraint' => 'fk_product',
                'foreign_key' => 'product_id',
                'references' => 'products',
                'column' => 'id'
            ],
            [
                'table' => 'invoice_item_tax',
                'constraint' => 'fk_tax',
                'foreign_key' => 'tax_id',
                'references' => 'tax',
                'column' => 'tax_id'
            ],
            [
                'table' => 'invoice_item_attachments',
                'constraint' => 'fk_invoice_item',
                'foreign_key' => 'invoice_item_id',
                'references' => 'invoice_items',
                'column' => 'id'
            ],
            [
                'table' => 'payment',
                'constraint' => 'fk_invoice',
                'foreign_key' => 'ac_inv_id',
                'references' => 'invoices',
                'column' => 'id'
            ],
            [
                'table' => 'payment',
                'constraint' => 'fk_payment_type',
                'foreign_key' => 'ac_payment_type',
                'references' => 'payment_types',
                'column' => 'pt_id'
            ],
            [
                'table' => 'products',
                'constraint' => 'fk_tax',
                'foreign_key' => 'default_tax_id',
                'references' => 'tax',
                'column' => 'tax_id'
            ],
            [
                'table' => 'products',
                'constraint' => 'fk_tax_2',
                'foreign_key' => 'default_tax_id_2',
                'references' => 'tax',
                'column' => 'tax_id'
            ],
            [
                'table' => 'products_attributes',
                'constraint' => 'fk_type',
                'foreign_key' => 'type_id',
                'references' => 'products_attribute_type',
                'column' => 'id'
            ],
            [
                'table' => 'products_values',
                'constraint' => 'fk_attribute',
                'foreign_key' => 'attribute_id',
                'references' => 'products_attributes',
                'column' => 'id'
            ],
            [
                'table' => 'user',
                'constraint' => 'fk_domain',
                'foreign_key' => 'domain_id',
                'references' => 'user_domain',
                'column' => 'id'
            ],
            [
                'table' => 'user',
                'constraint' => 'fk_role',
                'foreign_key' => 'role_id',
                'references' => 'user_role',
                'column' => 'id'
            ]
        ];

        $undefined_values = array();
        set_time_limit(240);
        foreach ($fk_constraints as $fk_constraint) {
            $table = $fk_constraint['table'];
            $foreign_key = $fk_constraint['foreign_key'];
            $references = $fk_constraint['references'];
            $column = $fk_constraint['column'];

            $pdoDbAdmin->addToWhere(new WhereItem(false, $foreign_key, 'IS NOT NULL', '', false));
            $pdoDbAdmin->setSelectList($foreign_key);
            $rows = $pdoDbAdmin->request('SELECT', $table);
            foreach ($rows as $row) {
                $value = $row[$foreign_key];
                $pdoDbAdmin->addSimpleWhere($column, $value);
                $pdoDbAdmin->setSelectList($column);
                $recs = $pdoDbAdmin->request('SELECT', $references);
                if (empty($recs)) {
                    // Key construction so it can be exploded in error message and to make sure only one
                    // occurrence of the missing value is displayed in the error message.
                    $undefined_values[$table . ':' . $foreign_key . ':' . $references . ':' . $column . ':' . $value] = $value;
                }
            }
        }

        if (!empty($undefined_values)) {
            $msg = "\nUnable to apply patch 318. Found foreign key table columns with values not in\n" .
                "the reference table column. The following list shows what values in foreign\n" .
                "key columns are missing from reference columns.\n\n";

            $msg .= "There two ways to fix this situation. Either change the row columns to reference\n" .
                "an existing record in the REFERENCE TABLE, or delete the rows that contain\n" .
                "the invalid columns.\n\n";

            $msg .= "To do this, the following example of the SQL statements to execute for the test\n" .
                "case where the 'cron_log' table contains invalid values '2' and '3' in the\n" .
                "'cron_id' column. The SQL statements to consider using are:\n\n";

            $msg .= "    UPDATE si_cron_log SET cron_id = 6 WHERE cron_id IN (2,3);\n";
            $msg .= "                      ----  or  ----\n" .
                "    DELETE FROM si_cron_log WHERE cron_id IN (2,3);\n\n";

            $msg .= "FOREIGN KEY TABLE         COLUMN              REFERENCE TABLE          COLUMN     INVALID VALUE\n" .
                "------------------------  ------------------  -----------------------  ---------  -------------\n";
            foreach ($undefined_values as $key => $value) {
                $parts = explode(':', $key);
                $line = sprintf('%-24s  %-18s  %-23s  %-9s  %s', $parts[0], $parts[1], $parts[2], $parts[3], $value);
                $msg .= $line . "\n";
            }
            error_log($msg);
            throw new PdoDbException("SqlPatchManager::prePatch318() = Unable to set Foreign Keys.");
        }
    }

    /**
     * Save product group information for those with extension enabled.
     * Note: Will perform exit() with error message if error thrown by called methods.
     */
    private
    static function prePatch321(): void
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
    private
    static function postPatch321(): void
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
    private
    static function prePatch322(): void
    {
        global $pdoDbAdmin, $productGroupEnabled;

        try {
            $pdoDbAdmin->addSimpleWhere('name', 'invoice_grouped');
            $rows = $pdoDbAdmin->request('SELECT', 'extensions');

            if (!empty($rows) && $rows[0]['enabled'] == ENABLED) {
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
     * Special handling for patch #303
     */
    private
    static function postPatch303(): void
    {
        global $pdoDbAdmin;

        try {
            $pdoDbAdmin->addSimpleWhere('name', 'inv_custom_field_report', 'AND');
            $pdoDbAdmin->addSimpleWhere('enabled', ENABLED);
            $rows = $pdoDbAdmin->request('SELECT', 'extensions');
            if (!empty($rows)) {
                // Copy invoice custom field 3 value to the new sales representative field.
                $pdoDbAdmin->addToWhere(new WhereItem(false, 'custom_field3', "<>", "", false));
                $rows = $pdoDbAdmin->request("SELECT", "invoices");

                foreach ($rows as $row) {
                    // Note that custom_field3 is intentionally NOT cleared. The user can do that
                    // through the custom field maintenance screen.
                    $pdoDbAdmin->addSimpleWhere('id', $row['id']);
                    $pdoDbAdmin->setFauxPost(array('sales_representative' => $row['custom_field3']));
                    $pdoDbAdmin->request('UPDATE', 'invoices');
                }
            }

            // Delete extension record if present (enabled or not)
            $pdoDbAdmin->addSimpleWhere('name', 'inv_custom_field_report');
            $pdoDbAdmin->request('DELETE', 'extensions');
        } catch (PdoDbException $pde) {
            error_log("SqlPatchManager::postPatch303() - Error: " . $pde->getMessage());
            die ("SqlPatchManager::postPatch303() error. See error log for additional details.");
        }
    }

    /**
     * Special handling for patch #304 - default_invoice.
     * The first step is to see if the default_invoice extension is enabled.
     * If not, there are no values to convert but the system_defaults "default_invoice"
     * record will be added. If the extension is enabled, the following is performed:
     *   1) Non-empty custom_field4 values from the invoices table are converted from
     *      an invoice "id" to the invoice "index_id". Then the "index_id" is stored
     *      in the new "default_invoice" field of the customers record associated with
     *      the invoice.
     *   2) Non-empty custom_field4 values from the customers records are converted
     *      from the invoice "id" to the invoice "index_id" and that "index_id" is
     *      stored in the new "default_invoice" field of the customers record. Note
     *      that in theory, this might overwrite a value stored in step 1 giving
     *      priority to the value in the customers record.
     *   3) If a "default_invoice" entry exists in the system_defaults table and it
     *      contains a non-zero value, that value will be converted from an invoice
     *      "id" to the invoice "index_id" value. If NO "default_invoice" entry exists
     *      a "default_invoice" record with a zero setting will be created.
     */
    private
    static function postPatch304(): void
    {
        global $pdoDbAdmin;

        try {
            $pdoDbAdmin->addSimpleWhere('name', 'default_invoice', 'AND');
            $pdoDbAdmin->addSimpleWhere('enabled', ENABLED);
            $rows = $pdoDbAdmin->request('SELECT', 'extensions');
            if (!empty($rows)) {
                // 1) Convert default invoice id in invoices *custom_field4' to the index_id and then store it in the customers table.
                //    Clear the default value from the invoice.
                $pdoDbAdmin->addToWhere(new WhereItem(false, 'custom_field4', "<>", "", false));
                $rows = $pdoDbAdmin->request("SELECT", "invoices");

                // Convert id to the index_id and store it in the customers record.
                foreach ($rows as $row) {
                    $pdoDbAdmin->setSelectList(array('index_id', 'customer_id'));
                    $pdoDbAdmin->addSimpleWhere('id', $row['custom_field4']);
                    $recs = $pdoDbAdmin->request('SELECT', 'invoices');
                    if (!empty($recs)) {
                        $pdoDbAdmin->setFauxPost(array('default_invoice' => $recs[0]['index_id']));
                        $pdoDbAdmin->addSimpleWhere('id', $recs['customer_id']);
                        $pdoDbAdmin->request('UPDATE', 'customers');
                    }

                    $pdoDbAdmin->setFauxPost(array('custom_field4' => ''));
                    $pdoDbAdmin->addSimpleWhere('id', $row['id']);
                    $pdoDbAdmin->request('UPDATE', 'invoices');
                }

                // 2) Convert non-blank custom_field4 values in the customers table to the corresponding index_id and store
                //    it in the new default_value field. Also clear the existing custom_field4 field. Note this will INTENTIONALLY
                //    overwrite any value set up the invoices file above.
                $pdoDbAdmin->setSelectList(array('id', 'custom_field4'));
                $pdoDbAdmin->addToWhere(new WhereItem(false, 'custom_field4', "<>", "", false));
                $rows = $pdoDbAdmin->request("SELECT", "customers");

                foreach ($rows as $row) {
                    // Convert id to index_id
                    $pdoDbAdmin->setSelectList('index_id');
                    $pdoDbAdmin->addSimpleWhere('id', $row['custom_field4']);
                    $recs = $pdoDbAdmin->request('SELECT', 'invoices');
                    if (!empty($recs)) {
                        // Note that custom_field4 is intentionally NOT cleared. The user can do that
                        // through the custom field maintenance screen.
                        $pdoDbAdmin->setFauxPost(array(
                            'default_invoice' => $recs[0]['index_id'],
                            'custom_field4' => ''
                        ));
                        $pdoDbAdmin->addSimpleWhere('id', $row['id']);
                        $pdoDbAdmin->request('UPDATE', 'customers');
                    }
                }
            }

            // Check for a default_invoice entry in the system_defaults table. If none, put an empty
            // record in there for it. If found, convert from id to index_id for non-zero setting.
            $pdoDbAdmin->addSimpleWhere('name', 'default_invoice', 'AND');
            $pdoDbAdmin->addSimpleWhere('domain_id', DomainId::get());
            $rows = $pdoDbAdmin->request('SELECT', 'system_defaults');
            if (empty($rows)) {
                $pdoDbAdmin->setFauxPost(array(
                    'name' => 'default_invoice',
                    'value' => '',
                    'domain_id' => DomainId::get(),
                    'extension_id' => '1'
                ));
                $pdoDbAdmin->request('INSERT', 'system_defaults');
            } else {
                $invoice_id = $rows['value'];
                $id = $rows['id'];
                if (!empty($invoice_id) && $invoice_id > 0) {
                    $row = Invoice::getOne($invoice_id);
                    $pdoDbAdmin->setFauxPost(array('value' => $row['index_id']));
                    $pdoDbAdmin->addSimpleWhere('id', $id);
                    $pdoDbAdmin->request('UPDATE', 'system_defaults');
                }
            }

            // Clear custom_field4 labels for Customer sand Invoices if present
            $pdoDbAdmin->setSelectAll(true);
            $pdoDbAdmin->addSimpleWhere('domain_id', DomainId::get(), 'AND');
            $pdoDbAdmin->addToWhere(new WhereItem(true, 'cf_custom_field', '=', 'invoice_cf4', false, 'OR'));
            $pdoDbAdmin->addToWhere(new WhereItem(false, 'cf_custom_field', '=', 'customer_cf4', true));
            $rows = $pdoDbAdmin->request('SELECT', 'custom_fields');
            foreach ($rows as $row) {
                $pdoDbAdmin->setFauxPost(array('cf_custom_label' => "", 'cf_display' => DISABLED));
                $pdoDbAdmin->addSimpleWhere('cf_id', $row['cf_id']);
                $pdoDbAdmin->request('UPDATE', 'custom_fields');
            }

            // Delete extension record if present (enabled or not)
            $pdoDbAdmin->addSimpleWhere('name', 'default_invoice');
            $pdoDbAdmin->request('DELETE', 'extensions');
        } catch (PdoDbException $pde) {
            error_log("SqlPatchManager::postPatch304() - Error: " . $pde->getMessage());
            die ("SqlPatchManager::postPatch304() error. See error log for additional details.");
        }
    }

    /**
     * Special handling for patch #322
     * Note: Will perform exit() with error message if error thrown by called methods.
     */
    private
    static function postPatch322(): void
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
    private
    static function prePatch331(): void
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
    private
    static function loadPatches(): void
    {
        global $config, $LANG, $language, $pdoDbAdmin;

        if (self::$patchCount != 0) {
            // Already loaded
            return;
        }

        // @formatter:off
        $domainId = DomainId::get();
        $patch = [
            'name' => "Create sql_patchmanager table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "sql_patchmanager` (sql_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
                                                                        "sql_patch_ref VARCHAR( 50 ) NOT NULL, " .
                                                                        "sql_patch VARCHAR( 255 ) NOT NULL, " .
                                                                        "sql_release VARCHAR( 25 ) NOT NULL, " .
                                                                        "sql_statement TEXT NOT NULL) " .
                                                                        "ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20060514",
            'source' => 'original'
        ];
        self::makePatch('1', $patch);

        $patch = [
            'name' => "Update invoice no details to have a default currency sign",
            'patch' => "UPDATE `" . TB_PREFIX . "preferences` SET pref_currency_sign = '$' WHERE pref_id =2 LIMIT 1;",
            'date' => "20060514",
            'source' => 'original'
        ];
        self::makePatch('2', $patch);

        $patch = [
            'name' => "Add a row into the defaults table to handle the default number of line items",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "defaults` ADD def_number_line_items INT( 25 ) NOT NULL",
            'date' => "20060514",
            'source' => 'original'
        ];
        self::makePatch('3', $patch);

        $patch = [
            'name' => "Set the default number of line items to 5",
            'patch' => "UPDATE `" . TB_PREFIX . "defaults` SET def_number_line_items = 5 WHERE def_id =1 LIMIT 1",
            'date' => "20060514",
            'source' => 'original'
        ];
        self::makePatch('4', $patch);

        $patch = [
            'name' => "Add logo and invoice footer support to biller",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD b_co_logo VARCHAR( 50 ), ADD b_co_footer TEXT",
            'date' => "20060514",
            'source' => 'original'
        ];
        self::makePatch('5', $patch);

        $patch = [
            'name' => "Add default invoice template option",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "defaults` ADD def_inv_template VARCHAR( 25 ) DEFAULT 'print_preview.php' NOT NULL",
            'date' => "20060514",
            'source' => 'original'
        ];
        self::makePatch('6', $patch);

        $patch = [
            'name' => "Edit tax description field length to 50",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` CHANGE tax_description tax_description VARCHAR( 50 ) DEFAULT NULL",
            'date' => "20060526",
            'source' => 'original'
        ];
        self::makePatch('7', $patch);

        $patch = [
            'name' => "Edit default invoice template field length to 50",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "defaults` CHANGE def_inv_template def_inv_template VARCHAR( 50 ) DEFAULT NULL",
            'date' => "20060526",
            'source' => 'original'
        ];
        self::makePatch('8', $patch);

        $patch = [
            'name' => "Add consulting style invoice",
            'patch' => "INSERT INTO `" . TB_PREFIX . "invoice_type` ( inv_ty_id , inv_ty_description ) VALUES (3, 'Consulting')",
            'date' => "20060531",
            'source' => 'original'
        ];
        self::makePatch('9', $patch);

        $patch = [
            'name' => "Add enabled to biller",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD b_enabled varchar(1) NOT NULL default '1'",
            'date' => "20060815",
            'source' => 'original'
        ];
        self::makePatch('10', $patch);

        $patch = [
            'name' => "Add enabled to customers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD c_enabled varchar(1) NOT NULL default '1'",
            'date' => "20060815",
            'source' => 'original'
        ];
        self::makePatch('11', $patch);

        $patch = [
            'name' => "Add enabled to preferences",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD pref_enabled varchar(1) NOT NULL default '1'",
            'date' => "20060815",
            'source' => 'original'
        ];
        self::makePatch('12', $patch);

        $patch = [
            'name' => "Add enabled to products",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD prod_enabled varchar(1) NOT NULL default '1'",
            'date' => "20060815",
            'source' => 'original'
        ];
        self::makePatch('13', $patch);

        $patch = [
            'name' => "Add enabled to products",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` ADD tax_enabled varchar(1) NOT NULL default '1'",
            'date' => "20060815",
            'source' => 'original'
        ];
        self::makePatch('14', $patch);

        $patch = [
            'name' => "Add tax_id into invoice_items table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` ADD inv_it_tax_id VARCHAR( 25 ) NOT NULL default '0' AFTER inv_it_unit_price",
            'date' => "20060815",
            'source' => 'original'
        ];
        self::makePatch('15', $patch);

        $patch = [
            'name' => "Add Payments table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "account_payments` (`ac_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
                                                                        "`ac_inv_id` VARCHAR( 10 ) NOT NULL , " .
                                                                        "`ac_amount` DOUBLE( 25, 2 ) NOT NULL, " .
                                                                        "`ac_notes` TEXT NOT NULL, " .
                                                                        "`ac_date` DATETIME NOT NULL) " .
                                                                        "ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20060827",
            'source' => 'original'
        ];
        self::makePatch('16', $patch);

        $patch = [
            'name' => "Adjust data type of quantity field",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_quantity` `inv_it_quantity` FLOAT NOT NULL DEFAULT '0'",
            'date' => "20060827",
            'source' => 'original'
        ];
        self::makePatch('17', $patch);

        $patch = [
            'name' => "Create Payment Types table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "payment_types` (`pt_id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
                                                                    "`pt_description` VARCHAR( 250 ) NOT NULL, " .
                                                                    "`pt_enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1') " .
                                                                    "ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
            'date' => "20060909",
            'source' => 'original'
        ];
        self::makePatch('18', $patch);

        $patch = [
            'name' => "Add info into the Payment Type table",
            'patch' => "INSERT INTO `" . TB_PREFIX . "payment_types` ( `pt_id` , `pt_description` ) VALUES (NULL , 'Cash'), (NULL , 'Credit Card')",
            'date' => "20060909",
            'source' => 'original'
        ];
        self::makePatch('19', $patch);

        $patch = [
            'name' => "Adjust accounts payments table to add a type field",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "account_payments` ADD `ac_payment_type` INT( 10 ) NOT NULL DEFAULT '1'",
            'date' => "20060909",
            'source' => 'original'
        ];
        self::makePatch('20', $patch);

        $patch = [
            'name' => "Adjust the defaults table to add a payment type field",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "defaults` ADD `def_payment_type` VARCHAR( 25 ) DEFAULT '1'",
            'date' => "20060909",
            'source' => 'original'
        ];
        self::makePatch('21', $patch);

        $patch = [
            'name' => "Add note field to customer",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD `c_notes` TEXT NULL AFTER `c_email`",
            'date' => "20061026",
            'source' => 'original'
        ];
        self::makePatch('22', $patch);

        $patch = [
            'name' => "Add note field to Biller",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD `b_notes` TEXT NULL AFTER `b_co_footer`",
            'date' => "20061026",
            'source' => 'original'
        ];
        self::makePatch('23', $patch);

        $patch = [
            'name' => "Add note field to Products",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `prod_notes` TEXT NOT NULL AFTER `prod_unit_price`",
            'date' => "20061026",
            'source' => 'original'
        ];
        self::makePatch('24', $patch);

        /*Custom fields patches - start */
        $patch = [
            'name' => "Add street address 2 to customers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD `c_street_address2` VARCHAR( 50 ) AFTER `c_street_address` ",
            'date' => "20061211",
            'source' => 'original'
        ];
        self::makePatch('25', $patch);

        $patch = [
            'name' => "Add custom fields to customers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD `c_custom_field1` VARCHAR( 50 ) AFTER `c_notes`, " .
                                                                "ADD `c_custom_field2` VARCHAR( 50 ) AFTER `c_custom_field1`, " .
                                                                "ADD `c_custom_field3` VARCHAR( 50 ) AFTER `c_custom_field2`, " .
                                                                "ADD `c_custom_field4` VARCHAR( 50 ) AFTER `c_custom_field3`;",
            'date' => "20061211",
            'source' => 'original'
        ];
        self::makePatch('26', $patch);

        $patch = [
            'name' => "Add mobile phone to customers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD `c_mobile_phone` VARCHAR( 50 ) AFTER `c_phone`",
            'date' => "20061211",
            'source' => 'original'
        ];
        self::makePatch('27', $patch);

        $patch = [
            'name' => "Add street address 2 to billers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD `b_street_address2` VARCHAR( 50 ) AFTER `b_street_address` ",
            'date' => "20061211",
            'source' => 'original'
        ];
        self::makePatch('28', $patch);

        $patch = [
            'name' => "Add custom fields to billers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD `b_custom_field1` VARCHAR( 50 ) AFTER `b_notes`, " .
                                                             "ADD `b_custom_field2` VARCHAR( 50 ) AFTER `b_custom_field1`, " .
                                                             "ADD `b_custom_field3` VARCHAR( 50 ) AFTER `b_custom_field2`, " .
                                                             "ADD `b_custom_field4` VARCHAR( 50 ) AFTER `b_custom_field3`;",
            'date' => "20061211",
            'source' => 'original'
        ];
        self::makePatch('29', $patch);

        $patch = [
            'name' => "Creating the custom fields table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "custom_fields` (`cf_id` INT NOT NULL AUTO_INCREMENT, " .
                                                                      "`cf_custom_field` VARCHAR( 50 ) NOT NULL, " .
                                                                      "`cf_custom_label` VARCHAR( 50 ), " .
                                                                      "`cf_display` VARCHAR( 1 ) DEFAULT '1' NOT NULL, " .
                "PRIMARY KEY(`cf_id`)) ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20061211",
            'source' => 'original'
        ];
        self::makePatch('30', $patch);

        $patch = [
            'name' => "Adding data to the custom fields table",
            'patch' => "INSERT INTO `" . TB_PREFIX . "custom_fields` ( `cf_id` , `cf_custom_field` , `cf_custom_label` , `cf_display` ) " .
                                                     "VALUES (NULL,'biller_cf1'  ,NULL,'0'), " .
                                                            "(NULL,'biller_cf2'  ,NULL,'0'), " .
                                                            "(NULL,'biller_cf3'  ,NULL,'0'), " .
                                                            "(NULL,'biller_cf4'  ,NULL,'0'), " .
                                                            "(NULL,'customer_cf1',NULL,'0'), " .
                                                            "(NULL,'customer_cf2',NULL,'0'), " .
                                                            "(NULL,'customer_cf3',NULL,'0'), " .
                                                            "(NULL,'customer_cf4',NULL,'0'), " .
                                                            "(NULL,'product_cf1' ,NULL,'0'), " .
                                                            "(NULL,'product_cf2' ,NULL,'0'), ".
                                                            "(NULL,'product_cf3' ,NULL,'0'), " .
                                                            "(NULL,'product_cf4' ,NULL,'0');",
            'date' => "20061211",
            'source' => 'original'
        ];
        self::makePatch('31', $patch);

        $patch = [
            'name' => "Adding custom fields to products",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `prod_custom_field1` VARCHAR( 50 ) AFTER `prod_unit_price`, " .
                                                               "ADD `prod_custom_field2` VARCHAR( 50 ) AFTER `prod_custom_field1`, " .
                                                               "ADD `prod_custom_field3` VARCHAR( 50 ) AFTER `prod_custom_field2`, " .
                                                               "ADD `prod_custom_field4` VARCHAR( 50 ) AFTER `prod_custom_field3`;",
            'date' => "20061211",
            'source' => 'original'
        ];
        self::makePatch('32', $patch);

        $patch = [
            'name' => "Alter product custom field 4",
            'patch' => "UPDATE `" . TB_PREFIX . "custom_fields` SET `cf_custom_field` = 'product_cf4' WHERE `" . TB_PREFIX . "custom_fields`.`cf_id` =12 LIMIT 1 ;",
            'date' => "20061214",
            'source' => 'original'
        ];
        self::makePatch('33', $patch);

        $patch = [
            'name' => "Reset invoice template to default refer Issue 70",
            'patch' => "UPDATE `" . TB_PREFIX . "defaults` SET `def_inv_template` = 'default' WHERE `def_id` =1 LIMIT 1;",
            'date' => "20070125",
            'source' => 'original'
        ];
        self::makePatch('34', $patch);

        $patch = [
            'name' => "Adding data to the custom fields table for invoices",
            'patch' => "INSERT INTO `" . TB_PREFIX . "custom_fields` ( `cf_id` , `cf_custom_field` , `cf_custom_label` , `cf_display` ) " .
                                                     "VALUES (NULL,'invoice_cf1',NULL,'0'), " .
                                                            "(NULL,'invoice_cf2',NULL,'0'), " .
                                                            "(NULL,'invoice_cf3',NULL,'0'), " .
                                                            "(NULL,'invoice_cf4',NULL,'0');",
            'date' => "20070204",
            'source' => 'original'
        ];
        self::makePatch('35', $patch);

        $patch = [
            'name' => "Adding custom fields to the invoices table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD `invoice_custom_field1` VARCHAR( 50 ) AFTER `inv_date`, " .
                                                               "ADD `invoice_custom_field2` VARCHAR( 50 ) AFTER `invoice_custom_field1`, " .
                                                               "ADD `invoice_custom_field3` VARCHAR( 50 ) AFTER `invoice_custom_field2`, " .
                                                               "ADD `invoice_custom_field4` VARCHAR( 50 ) AFTER `invoice_custom_field3`;",
            'date' => "20070204",
            'source' => 'original'
        ];
        self::makePatch('36', $patch);

        $patch = [
            'name' => "Reset invoice template to default due to new invoice template system",
            'patch' => "UPDATE `" . TB_PREFIX . "defaults` SET `def_inv_template` = 'default' WHERE `def_id` =1 LIMIT 1 ;",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('37', $patch);

        $patch = [
            'name' => "Alter custom field table - field length now 255 for field name",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "custom_fields` CHANGE `cf_custom_field` `cf_custom_field` VARCHAR( 255 )",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('38', $patch);

        $patch = [
            'name' => "Alter custom field table - field length now 255 for field label",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "custom_fields` CHANGE `cf_custom_label` `cf_custom_label` VARCHAR( 255 )",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('39', $patch);

        $patch = [
            'name' => "Alter field name in sql_patchmanager",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "sql_patchmanager` CHANGE `sql_patch` `sql_patch` VARCHAR( 255 ) NOT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('40', $patch);

        $patch = [
            'name' => "Alter field name in " . TB_PREFIX . "account_payments",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "account_payments` CHANGE  `ac_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('41', $patch);

        $patch = [
            'name' => "Alter field name b_name to name",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_name`  `name` VARCHAR( 255 ) NULL DEFAULT NULL;",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('42', $patch);

        $patch = [
            'name' => "Alter field name b_id to id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_id`  `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('43', $patch);

        $patch = [
            'name' => "Alter field name b_street_address to street_address",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_street_address`  `street_address` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('44', $patch);

        $patch = [
            'name' => "Alter field name b_street_address2 to street_address2",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_street_address2`  `street_address2` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('45', $patch);

        $patch = [
            'name' => "Alter field name b_city to city",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_city`  `city` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('46', $patch);

        $patch = [
            'name' => "Alter field name b_state to state",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_state`  `state` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('47', $patch);

        $patch = [
            'name' => "Alter field name b_zip_code to zip_code",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_zip_code`  `zip_code` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('48', $patch);

        $patch = [
            'name' => "Alter field name b_country to country",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_country`  `country` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('49', $patch);

        $patch = [
            'name' => "Alter field name b_phone to phone",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_phone`  `phone` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('50', $patch);

        $patch = [
            'name' => "Alter field name b_mobile_phone to mobile_phone",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_mobile_phone`  `mobile_phone` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('51', $patch);

        $patch = [
            'name' => "Alter field name b_fax to fax",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_fax`  `fax` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('52', $patch);

        $patch = [
            'name' => "Alter field name b_email to email",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_email`  `email` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('53', $patch);

        $patch = [
            'name' => "Alter field name b_co_logo to logo",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE  `b_co_logo`  `logo` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('54', $patch);

        $patch = [
            'name' => "Alter field name b_co_footer to footer",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_co_footer` `footer` TEXT NULL DEFAULT NULL ",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('55', $patch);

        $patch = [
            'name' => "Alter field name b_notes to notes",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_notes` `notes` TEXT NULL DEFAULT NULL ",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('56', $patch);

        $patch = [
            'name' => "Alter field name b_enabled to enabled",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('57', $patch);

        $patch = [
            'name' => "Alter field name b_custom_field1 to custom_field1",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('58', $patch);

        $patch = [
            'name' => "Alter field name b_custom_field2 to custom_field2",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('59', $patch);

        $patch = [
            'name' => "Alter field name b_custom_field3 to custom_field3",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('60', $patch);

        $patch = [
            'name' => "Alter field name b_custom_field4 to custom_field4",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `b_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('61', $patch);

        $patch = [
            'name' => "Introduce system_defaults table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "system_defaults` (`id` int(11) NOT NULL auto_increment, " .
                                                                        "`name` varchar(30) NOT NULL, " .
                                                                        "`value` varchar(30) NOT NULL, " .
                                                                        "PRIMARY KEY  (`id`), UNIQUE KEY `name` (`name`)) " .
                "ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('62', $patch);

        $patch = [
            'name' => "Inserts data into the system_defaults table",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` (`id`, `name`, `value`) " .
                                                     "VALUES ( 1, 'biller'         , '4'), " .
                                                            "( 2, 'customer'       , '3'), " .
                                                            "( 3, 'tax'            , '1'), " .
                                                            "( 4, 'preference'     , '1'), " .
                                                            "( 5, 'line_items'     , '5'), " .
                                                            "( 6, 'template'       , 'default'), " .
                                                            "( 7, 'payment_type'   , '1'), " .
                                                            "( 8, 'language'       , 'en'), " .
                                                            "( 9, 'dateformat'     , 'Y-m-d'), " .
                                                            "(10, 'spreadsheet'    , 'xls'), " .
                                                            "(11, 'wordprocessor'  , 'doc'), " .
                                                            "(12, 'pdfscreensize'  , '800'), " .
                                                            "(13, 'pdfpapersize'   , 'A4'), " .
                                                            "(14, 'pdfleftmargin'  , '15'), " .
                                                            "(15, 'pdfrightmargin' , '15'), " .
                                                            "(16, 'pdftopmargin'   , '15'), " .
                                                            "(17, 'pdfbottommargin', '15'), " .
                                                            "(18, 'emailhost'      , 'localhost'), " .
                                                            "(19, 'emailusername'  , ''), " .
                                                            "(20, 'emailpassword'  , '');",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('63', $patch);

        $patch = [
            'name' => "Alter field name prod_id to id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('64', $patch);

        $patch = [
            'name' => "Alter field name prod_description to description",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_description` `description` TEXT NOT NULL ",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('65', $patch);

        $patch = [
            'name' => "Alter field name prod_unit_price to unit_price",
            'patch' => " ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_unit_price` `unit_price` DECIMAL( 25, 2 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('66', $patch);

        $patch = [
            'name' => "Alter field name prod_custom_field1 to custom_field1",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('67', $patch);

        $patch = [
            'name' => "Alter field name prod_custom_field2 to custom_field2",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('68', $patch);

        $patch = [
            'name' => "Alter field name prod_custom_field3 to custom_field3",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('69', $patch);

        $patch = [
            'name' => "Alter field name prod_custom_field4 to custom_field4",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('70', $patch);

        $patch = [
            'name' => "Alter field name prod_notes to notes",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_notes` `notes` TEXT NOT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('71', $patch);

        $patch = [
            'name' => "Alter field name prod_enabled to enabled",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `prod_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('72', $patch);

        $patch = [
            'name' => "Alter field name c_id to id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('73', $patch);

        $patch = [
            'name' => "Alter field name c_attention to attention",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_attention` `attention` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('74', $patch);

        $patch = [
            'name' => "Alter field name c_name to name",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_name` `name` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('75', $patch);

        $patch = [
            'name' => "Alter field name c_street_address to street_address",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_street_address` `street_address` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('76', $patch);

        $patch = [
            'name' => "Alter field name c_street_address2 to street_address2",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_street_address2` `street_address2` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('77', $patch);

        $patch = [
            'name' => "Alter field name c_city to city",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_city` `city` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('78', $patch);

        $patch = [
            'name' => "Alter field name c_state to state",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_state` `state` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('79', $patch);

        $patch = [
            'name' => "Alter field name c_zip_code to zip_code",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_zip_code` `zip_code` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('80', $patch);

        $patch = [
            'name' => "Alter field name c_country to country",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_country` `country` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('81', $patch);

        $patch = [
            'name' => "Alter field name c_phone to phone",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_phone` `phone` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('82', $patch);

        $patch = [
            'name' => "Alter field name c_mobile_phone to mobile_phone",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_mobile_phone` `mobile_phone` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('83', $patch);

        $patch = [
            'name' => "Alter field name c_fax to fax",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_fax` `fax` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('84', $patch);

        $patch = [
            'name' => "Alter field name c_email to email",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_email` `email` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('85', $patch);

        $patch = [
            'name' => "Alter field name c_notes to notes",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_notes` `notes` TEXT  NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('86', $patch);

        $patch = [
            'name' => "Alter field name c_custom_field1 to custom_field1",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_custom_field1` `custom_field1` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('87', $patch);

        $patch = [
            'name' => "Alter field name c_custom_field2 to custom_field2",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_custom_field2` `custom_field2` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('88', $patch);

        $patch = [
            'name' => "Alter field name c_custom_field3 to custom_field3",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_custom_field3` `custom_field3` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('89', $patch);

        $patch = [
            'name' => "Alter field name c_custom_field4 to custom_field4",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_custom_field4` `custom_field4` VARCHAR( 255 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('90', $patch);

        $patch = [
            'name' => "Alter field name c_enabled to enabled",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `c_enabled` `enabled` VARCHAR( 1 ) NOT NULL DEFAULT '1'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('91', $patch);

        $patch = [
            'name' => "Alter field name inv_id to id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('92', $patch);

        $patch = [
            'name' => "Alter field name inv_biller_id to biller_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_biller_id` `biller_id` INT( 10 ) NOT NULL DEFAULT '0'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('93', $patch);

        $patch = [
            'name' => "Alter field name inv_customer_id to customer_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_customer_id` `customer_id` INT( 10 ) NOT NULL DEFAULT '0'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('94', $patch);

        $patch = [
            'name' => "Alter field name inv_type type_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_type` `type_id` INT( 10 ) NOT NULL DEFAULT '0'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('95', $patch);

        $patch = [
            'name' => "Alter field name inv_preference to preference_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_preference` `preference_id` INT( 10 ) NOT NULL DEFAULT '0'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('96', $patch);

        $patch = [
            'name' => "Alter field name inv_date to date",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_date` `date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('97', $patch);

        $patch = [
            'name' => "Alter field name invoice_custom_field1 to custom_field1",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `invoice_custom_field1` `custom_field1` VARCHAR( 50 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('98', $patch);

        $patch = [
            'name' => "Alter field name invoice_custom_field2 to custom_field2",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `invoice_custom_field2` `custom_field2` VARCHAR( 50 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('99', $patch);

        $patch = [
            'name' => "Alter field name invoice_custom_field3 to custom_field3",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `invoice_custom_field3` `custom_field3` VARCHAR( 50 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('100', $patch);

        $patch = [
            'name' => "Alter field name invoice_custom_field4 to custom_field4",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `invoice_custom_field4` `custom_field4` VARCHAR( 50 ) NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('101', $patch);

        $patch = [
            'name' => "Alter field name inv_note to note ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` CHANGE `inv_note` `note` TEXT NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('102', $patch);

        $patch = [
            'name' => "Alter field name inv_it_id to id ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_id` `id` INT( 10 ) NOT NULL AUTO_INCREMENT",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('103', $patch);

        $patch = [
            'name' => "Alter field name inv_it_invoice_id to invoice_id ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_invoice_id` `invoice_id` INT( 10 ) NOT NULL DEFAULT '0'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('104', $patch);

        $patch = [
            'name' => "Alter field name inv_it_quantity to quantity ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_quantity` `quantity` FLOAT NOT NULL DEFAULT '0'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('105', $patch);

        $patch = [
            'name' => "Alter field name inv_it_product_id to product_id ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_product_id` `product_id` INT( 10 ) NULL DEFAULT '0'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('106', $patch);

        $patch = [
            'name' => "Alter field name inv_it_unit_price to unit_price ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_unit_price` `unit_price` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('107', $patch);

        $patch = [
            'name' => "Alter field name inv_it_tax_id to tax_id  ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_tax_id` `tax_id` VARCHAR( 25 ) NOT NULL DEFAULT '0'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('108', $patch);

        $patch = [
            'name' => "Alter field name inv_it_tax to tax  ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_tax` `tax` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('109', $patch);

        $patch = [
            'name' => "Alter field name inv_it_tax_amount to tax_amount  ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_tax_amount` `tax_amount` DOUBLE( 25, 2 ) NULL DEFAULT NULL ",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('110', $patch);

        $patch = [
            'name' => "Alter field name inv_it_gross_total to gross_total ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_gross_total` `gross_total` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('111', $patch);

        $patch = [
            'name' => "Alter field name inv_it_description to description ",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_description` `description` TEXT NULL DEFAULT NULL",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('112', $patch);

        $patch = [
            'name' => "Alter field name inv_it_total to total",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `inv_it_total` `total` DOUBLE( 25, 2 ) NULL DEFAULT '0.00'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('113', $patch);

        $patch = [
            'name' => "Add logging table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "log` (`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
                                                            "`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, " .
                                                            "`userid` INT NOT NULL, " .
                                                            "`sqlquerie` TEXT NOT NULL) " .
                                                      "ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('114', $patch);

        $patch = [
            'name' => "Add logging system preference",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` ( `id` , `name` , `value` ) VALUES (NULL , 'logging', '0');",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('115', $patch);

        $defaults = [];
        if (self::lastPatchApplied() < 124) {
            // system defaults conversion patch
            // defaults query and DEFAULT NUMBER OF LINE ITEMS
            try {
                $pdoDbAdmin->setSelectAll(true);
                $defaults = $pdoDbAdmin->request('SELECT', 'defaults');
            } catch (PdoDbException) {
                // No action needed. $defaults already empty.
            }

        }

        $def_biller = (empty($defaults['def_biller']) ? "" : $defaults['def_biller']);
        $patch = [
            'name' => "System defaults conversion patch - set default biller",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = '$def_biller' where name = 'biller'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('116', $patch);
        unset($def_biller);

        $def_customer = (empty($defaults['def_customer']) ? "" : $defaults['def_customer']);
        $patch = [
            'name' => "System defaults conversion patch - set default customer",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = '$def_customer' where name = 'customer'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('117', $patch);
        unset($def_customer);

        $def_tax = (empty($defaults['def_tax']) ? "" : $defaults['def_tax']);
        $patch = [
            'name' => "System defaults conversion patch - set default tax",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = '$def_tax' where name = 'tax'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('118', $patch);
        unset($def_tax);

        $def_inv_preference = (empty($defaults['def_inv_preference']) ? "" : $defaults['def_inv_preference']);
        $patch = [
            'name' => "System defaults conversion patch - set default invoice reference",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = '$def_inv_preference' where name = 'preference'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('119', $patch);
        unset($def_inv_preference);

        $def_number_line_items = (empty($defaults['def_number_line_items']) ? "" : $defaults['def_number_line_items']);
        $patch = [
            'name' => "System defaults conversion patch - set default number of line items",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = '$def_number_line_items' where name = 'line_items'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('120', $patch);
        unset($def_number_line_items);

        $def_inv_template = (empty($defaults['def_inv_template']) ? "" : $defaults['def_inv_template']);
        $patch = [
            'name' => "System defaults conversion patch - set default invoice template",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = '$def_inv_template' where name = 'template'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('121', $patch);
        unset($def_inv_template);

        $def_payment_type = (empty($defaults['def_payment_type']) ? "" : $defaults['def_payment_type']);
        $patch = [
            'name' => "System defaults conversion patch - set default payment type",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = '$def_payment_type' where name = 'payment_type'",
            'date' => "20070523",
            'source' => 'original'
        ];
        self::makePatch('122', $patch);
        unset($def_payment_type);

        $patch = [
            'name' => "Add option to delete invoices into the system_defaults table",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` (`id`, `name`, `value`) VALUES (NULL, 'delete', 'N');",
            'date' => "20070930",
            'source' => 'original'
        ];
        self::makePatch('123', $patch);

        $patch = [
            'name' => "Set default language in new lang system",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value = 'en-gb' where name ='language';",
            'date' => "20070930",
            'source' => 'original'
        ];
        self::makePatch('124', $patch);

        $patch = [
            'name' => "Change log table that usernames are also possible as id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "log` CHANGE `userid` `userid` VARCHAR( 40 ) NOT NULL DEFAULT '0'",
            'date' => "20070930",
            'source' => 'original'
        ];
        self::makePatch('125', $patch);

        $patch = [
            'name' => "Add visible attribute to the products table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD  `visible` BOOL NOT NULL DEFAULT  '1';",
            'date' => "20070930",
            'source' => 'original'
        ];
        self::makePatch('126', $patch);

        $patch = [
            'name' => "Add last_id to logging table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "log` ADD  `last_id` INT NULL ;",
            'date' => "20070930",
            'source' => 'original'
        ];
        self::makePatch('127', $patch);

        $u = ($pdoDbAdmin->checkTableExists('users'));
        $ud = ($pdoDbAdmin->checkFieldExists('users', 'user_domain'));
        $patch = [
            'name' => "Add user table",
            'patch' => ($u ? ($ud ? "SELECT * FROM " . TB_PREFIX . "users;" :
                                    "ALTER TABLE `" . TB_PREFIX . "users` ADD `user_domain` VARCHAR( 255 ) NOT NULL AFTER `user_group`;") :
                            "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "users` (`user_id` int(11) NOT NULL auto_increment, " .
                                                                                 "`user_email` varchar(255) NOT NULL, " .
                                                                                 "`user_name` varchar(255) NOT NULL, " .
                                                                                 "`user_group` varchar(255) NOT NULL, " .
                                                                                 "`user_domain` varchar(255) NOT NULL, " .
                                                                                 "`user_password` varchar(255) NOT NULL, " .
                                                                                 "PRIMARY KEY(`user_id`)) " .
                                                                         "ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"),
            'date' => "20070930",
            'source' => 'original'
        ];
        self::makePatch('128', $patch);
        unset($u);
        unset($ud);

        $patch = [
            'name' => "Fill user table with default values",
            'patch' => "INSERT INTO `" . TB_PREFIX . "users` (`user_id`, `user_email`, `user_name`, `user_group`, `user_domain`, `user_password`) " .
                                                     "VALUES (NULL, 'demo@simpleinvoices.group', 'demo', '1', '1', MD5('demo'))",
            'date' => "20070930",
            'source' => 'original'
        ];
        self::makePatch('129', $patch);

        $ac = ($pdoDbAdmin->checkTableExists('auth_challenges'));
        $patch = [
            'name' => "Create auth_challenges table",
            'patch' => ($ac ? "SELECT * FROM " . TB_PREFIX . "auth_challenges" :
                              "CREATE TABLE IF NOT EXISTS `" . TB_PREFIX . "auth_challenges` (`challenges_key` int(11) NOT NULL, " .
                                                                                             "`challenges_timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP);"),
            'date' => "20070930",
            'source' => 'original'
        ];
        self::makePatch('130', $patch);
        unset($ac);

        $patch = [
            'name' => "Make tax field 3 decimal places",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` CHANGE `tax_percentage` `tax_percentage` DECIMAL (10,3)  NULL",
            'date' => "20070930",
            'source' => 'original'
        ];
        self::makePatch('131', $patch);

        $patch = [
            'name' => "Correct Foreign Key Tax ID Field Type in Invoice Items Table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `tax_id` `tax_id` int  DEFAULT '0' NOT NULL ;",
            'date' => "20071126",
            'source' => 'original'
        ];
        self::makePatch('132', $patch);

        $patch = [
            'name' => "Correct Foreign Key Invoice ID Field Type in Ac Payments Table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "account_payments` CHANGE `ac_inv_id` `ac_inv_id` int  NOT NULL ;",
            'date' => "20071126",
            'source' => 'original'
        ];
        self::makePatch('133', $patch);

        $patch = [
            'name' => "Drop non-int compatible default from si_sql_patchmanager",
            'patch' => "SELECT 1+1;",
            'date' => "20071218",
            'source' => 'original'
        ];
        self::makePatch('134', $patch);

        $patch = [
            'name' => "Change sql_patch_ref type in sql_patchmanager to int",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "sql_patchmanager` change `sql_patch_ref` `sql_patch_ref` int NOT NULL ;",
            'date' => "20071218",
            'source' => 'original'
        ];
        self::makePatch('135', $patch);

        $patch = [
            'name' => "Create domain mapping table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "user_domain` (`id` int(11) NOT NULL auto_increment  PRIMARY KEY, " .
                                                                    "`name` varchar(255) UNIQUE NOT NULL) " .
                                                      "ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci",
            'date' => "20071231",
            'source' => 'original'
        ];
        self::makePatch('136', $patch);

        $patch = [
            'name' => "Insert default domain",
            'patch' => "INSERT INTO `" . TB_PREFIX . "user_domain` (name) VALUES ('default');",
            'date' => "20071231",
            'source' => 'original'
        ];
        self::makePatch('137', $patch);

        $patch = [
            'name' => "Add domain_id to payment_types table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment_types` ADD `domain_id` INT  NOT NULL AFTER `pt_id` ;",
            'date' => "20071231",
            'source' => 'original'
        ];
        self::makePatch('138', $patch);

        $patch = [
            'name' => "Add domain_id to preferences table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `domain_id` INT  NOT NULL AFTER `pref_id`;",
            'date' => "20071231",
            'source' => 'original'
        ];
        self::makePatch('139', $patch);

        $patch = [
            'name' => "Add domain_id to products table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `domain_id` INT  NOT NULL AFTER `id` ;",
            'date' => "20071231",
            'source' => 'original'
        ];
        self::makePatch('140', $patch);

        $patch = [
            'name' => "Add domain_id to billers table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD `domain_id` INT  NOT NULL AFTER `id` ;",
            'date' => "20071231",
            'source' => 'original'
        ];
        self::makePatch('141', $patch);

        $patch = [
            'name' => "Add domain_id to invoices table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD `domain_id` INT NOT NULL AFTER `id` ;",
            'date' => "20071231",
            'source' => 'original'
        ];
        self::makePatch('142', $patch);

        $patch = [
            'name' => "Add domain_id to customers table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD `domain_id` INT NOT NULL AFTER `id` ;",
            'date' => "20071231",
            'source' => 'original'
        ];
        self::makePatch('143', $patch);

        $patch = [
            'name' => "Change group field to user_role_id in users table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "users` CHANGE `user_group` `user_role_id` INT  DEFAULT '1' NOT NULL;",
            'date' => "20080102",
            'source' => 'original'
        ];
        self::makePatch('144', $patch);

        $patch = [
            'name' => "Change domain field to user_domain_id in users table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "users` CHANGE `user_domain` `user_domain_id` INT  DEFAULT '1' NOT NULL;",
            'date' => "20080102",
            'source' => 'original'
        ];
        self::makePatch('145', $patch);

        $patch = [
            'name' => "Drop old auth_challenges table",
            'patch' => "DROP TABLE IF EXISTS `" . TB_PREFIX . "auth_challenges`;",
            'date' => "20080102",
            'source' => 'original'
        ];
        self::makePatch('146', $patch);

        $patch = [
            'name' => "Create user_role table",
            'patch' => "CREATE TABLE " . TB_PREFIX . "user_role (`id` int(11) NOT NULL auto_increment  PRIMARY KEY, " .
                                                                "`name` varchar(255) UNIQUE NOT NULL) " .
                                                     "ENGINE=MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20080102",
            'source' => 'original'
        ];
        self::makePatch('147', $patch);

        $patch = [
            'name' => "Insert default user group",
            'patch' => "INSERT INTO " . TB_PREFIX . "user_role (name) VALUES ('administrator');",
            'date' => "20080102",
            'source' => 'original'
        ];
        self::makePatch('148', $patch);

        $patch = [
            'name' => "Table = Account_payments Field = ac_amount : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "account_payments` CHANGE `ac_amount` `ac_amount` DECIMAL( 25, 6 ) NOT NULL;",
            'date' => "20080128",
            'source' => 'original'
        ];
        self::makePatch('149', $patch);

        $patch = [
            'name' => "Table = Invoice_items Field = quantity : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `quantity` `quantity` DECIMAL( 25, 6 ) NOT NULL DEFAULT '0' ",
            'date' => "20080128",
            'source' => 'original'
        ];
        self::makePatch('150', $patch);

        $patch = [
            'name' => "Table = Invoice_items Field = unit_price : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `unit_price` `unit_price` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' ",
            'date' => "20080128",
            'source' => 'original'
        ];
        self::makePatch('151', $patch);

        $patch = [
            'name' => "Table = Invoice_items Field = tax : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `tax` `tax` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' ",
            'date' => "20080128",
            'source' => 'original'
        ];
        self::makePatch('152', $patch);

        $patch = [
            'name' => "Table = Invoice_items Field = tax_amount : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `tax_amount` `tax_amount` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'",
            'date' => "20080128",
            'source' => 'original'
        ];
        self::makePatch('153', $patch);

        $patch = [
            'name' => "Table = Invoice_items Field = gross_total : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `gross_total` `gross_total` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'",
            'date' => "20080128",
            'source' => 'original'
        ];
        self::makePatch('154', $patch);

        $patch = [
            'name' => "Table = Invoice_items Field = total : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` CHANGE `total` `total` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' ",
            'date' => "20080128",
            'source' => 'original'
        ];
        self::makePatch('155', $patch);

        $patch = [
            'name' => "Table = Products Field = unit_price : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `unit_price` `unit_price` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'",
            'date' => "20080128",
            'source' => 'original'
        ];
        self::makePatch('156', $patch);

        $patch = [
            'name' => "Table = Tax Field = quantity : change field type and length to decimal",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` CHANGE `tax_percentage` `tax_percentage` DECIMAL( 25, 6 ) NULL DEFAULT '0.00'",
            'date' => "20080128",
            'source' => 'original'
        ];
        self::makePatch('157', $patch);

        $patch = [
            'name' => "Rename table si_account_payments to si_payment",
            'patch' => "RENAME TABLE `" . TB_PREFIX . "account_payments` TO  `" . TB_PREFIX . "payment`;",
            'date' => "20081201",
            'source' => 'original'
        ];
        self::makePatch('158', $patch);

        $patch = [
            'name' => "Add domain_id to payments table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment` ADD  `domain_id` INT NOT NULL ;",
            'date' => "20081201",
            'source' => 'original'
        ];
        self::makePatch('159', $patch);

        $patch = [
            'name' => "Add domain_id to tax table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` ADD  `domain_id` INT NOT NULL ;",
            'date' => "20081201",
            'source' => 'original'
        ];
        self::makePatch('160', $patch);

        $patch = [
            'name' => "Change user table from si_users to si_user",
            'patch' => "RENAME TABLE `" . TB_PREFIX . "users` TO  `" . TB_PREFIX . "user`;",
            'date' => "20081201",
            'source' => 'original'
        ];
        self::makePatch('161', $patch);

        $patch = [
            'name' => "Add new invoice items tax table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "invoice_item_tax` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
                                                                         "`invoice_item_id` INT( 11 ) NOT NULL, " .
                                                                         "`tax_id` INT( 11 ) NOT NULL, " .
                                                                         "`tax_type` VARCHAR( 1 ) NOT NULL, " .
                                                                         "`tax_rate` DECIMAL( 25, 6 ) NOT NULL, " .
                                                                         "`tax_amount` DECIMAL( 25, 6 ) NOT NULL) " .
                                                      "ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20081212",
            'source' => 'original'
        ];
        self::makePatch('162', $patch);

        //do conversion
        $patch = [
            'name' => "Convert tax info in si_invoice_items to si_invoice_item_tax",
            'patch' => "INSERT INTO `" . TB_PREFIX . "invoice_item_tax` (invoice_item_id, tax_id, tax_type, tax_rate, tax_amount) " .
                                                     "SELECT id, tax_id, '%', tax, tax_amount FROM `" . TB_PREFIX . "invoice_items`;",
            'date' => "20081212",
            'source' => 'original'
        ];
        self::makePatch('163', $patch);


        $patch = [
            'name' => "Add default tax id into products table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `default_tax_id` INT( 11 ) NULL AFTER `unit_price` ;",
            'date' => "20081212",
            'source' => 'original'
        ];
        self::makePatch('164', $patch);

        $patch = [
            'name' => "Add default tax id 2 into products table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `default_tax_id_2` INT( 11 ) NULL AFTER `default_tax_id` ;",
            'date' => "20081212",
            'source' => 'original'
        ];
        self::makePatch('165', $patch);

        $patch = [
            'name' => "Add default tax into product items",
            'patch' => "UPDATE `" . TB_PREFIX . "products` SET default_tax_id = (SELECT value FROM `" . TB_PREFIX . "system_defaults` WHERE name ='tax');",
            'date' => "20081212",
            'source' => 'original'
        ];
        self::makePatch('166', $patch);

        $patch = [
            'name' => "Add default number of taxes per line item into system_defaults",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` VALUES ('','tax_per_line_item','1')",
            'date' => "20081212",
            'source' => 'original'
        ];
        self::makePatch('167', $patch);

        $patch = [
            'name' => "Add tax type",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` ADD `type` VARCHAR( 1 ) NULL AFTER `tax_percentage` ;",
            'date' => "20081212",
            'source' => 'original'
        ];
        self::makePatch('168', $patch);

        $patch = [
            'name' => "Set tax type on current taxes to %",
            'patch' => "SELECT 1+1;",
            'date' => "20081212",
            'source' => 'original'
        ];
        self::makePatch('169', $patch);

        $patch = [
            'name' => "Set domain_id on tax table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "tax` SET `domain_id` = '1' ;",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('170', $patch);

        $patch = [
            'name' => "Set domain_id on payment table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "payment` SET `domain_id` = '1' ;",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('171', $patch);

        $patch = [
            'name' => "Set domain_id on payment_types table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "payment_types` SET `domain_id` = '1' ;",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('172', $patch);

        $patch = [
            'name' => "Set domain_id on preference table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "preferences` SET `domain_id` = '1' ;",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('173', $patch);

        $patch = [
            'name' => "Set domain_id on products table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "products` SET `domain_id` = '1' ;",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('174', $patch);

        $patch = [
            'name' => "Set domain_id on biller table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "biller` SET `domain_id` = '1' ;",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('175', $patch);

        $patch = [
            'name' => "Set domain_id on invoices table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "invoices` SET `domain_id` = '1' ;",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('176', $patch);

        $patch = [
            'name' => "Set domain_id on customers table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "customers` SET `domain_id` = '1' ;",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('177', $patch);

        $patch = [
            'name' => "Rename si_user.user_id to si_user.id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `user_id` `id` int(11) ;",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('178', $patch);

        $patch = [
            'name' => "Rename si_user.user_email to si_user.email",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `user_email` `email` VARCHAR( 255 );",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('179', $patch);

        $patch = [
            'name' => "Rename si_user.user_name to si_user.name",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `user_name` `name` VARCHAR( 255 );",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('180', $patch);

        $patch = [
            'name' => "Rename si_user.user_role_id to si_user.role_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `user_role_id` `role_id` int(11);",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('181', $patch);

        $patch = [
            'name' => "Rename si_user.user_domain_id to si_user.domain_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `user_domain_id` `domain_id` int(11) ;",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('182', $patch);

        $patch = [
            'name' => "Rename si_user.user_password to si_user.password",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `user_password` `password` VARCHAR( 255 ) ;",
            'date' => "20081229",
            'source' => 'original'
        ];
        self::makePatch('183', $patch);

        $patch = [
            'name' => "Drop name column from si_user table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` DROP `name`  ;",
            'date' => "20081230",
            'source' => 'original'
        ];
        self::makePatch('184', $patch);

        $patch = [
            'name' => "Drop old defaults table",
            'patch' => "DROP TABLE `" . TB_PREFIX . "defaults` ;",
            'date' => "20081230",
            'source' => 'original'
        ];
        self::makePatch('185', $patch);

        $patch = [
            'name' => "Set domain_id on customers table to 1",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "custom_fields` ADD  `domain_id` INT NOT NULL ;",
            'date' => "20081230",
            'source' => 'original'
        ];
        self::makePatch('186', $patch);

        $patch = [
            'name' => "Set domain_id on custom_fields table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "custom_fields` SET `domain_id` = '1' ;",
            'date' => "20081230",
            'source' => 'original'
        ];
        self::makePatch('187', $patch);

        $patch = [
            'name' => "Drop tax_id column from si_invoice_items table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` DROP `tax_id`  ;",
            'date' => "20090118",
            'source' => 'original'
        ];
        self::makePatch('188', $patch);

        $patch = [
            'name' => "Drop tax column from si_invoice_items table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` DROP `tax`  ;",
            'date' => "20090118",
            'source' => 'original'
        ];
        self::makePatch('189', $patch);

        $patch = [
            'name' => "Insert user role - user",
            'patch' => "INSERT INTO " . TB_PREFIX . "user_role (name) VALUES ('user');",
            'date' => "20090215",
            'source' => 'original'
        ];
        self::makePatch('190', $patch);

        $patch = [
            'name' => "Insert user role - viewer",
            'patch' => "INSERT INTO " . TB_PREFIX . "user_role (name) VALUES ('viewer');",
            'date' => "20090215",
            'source' => 'original'
        ];
        self::makePatch('191', $patch);

        $patch = [
            'name' => "Insert user role - customer",
            'patch' => "INSERT INTO " . TB_PREFIX . "user_role (name) VALUES ('customer');",
            'date' => "20090215",
            'source' => 'original'
        ];
        self::makePatch('192', $patch);

        $patch = [
            'name' => "Insert user role - biller",
            'patch' => "INSERT INTO " . TB_PREFIX . "user_role (name) VALUES ('biller');",
            'date' => "20090215",
            'source' => 'original'
        ];
        self::makePatch('193', $patch);

        $patch = [
            'name' => "User table - auto increment",
            'patch' => "ALTER TABLE " . TB_PREFIX . "user CHANGE id id INT( 11 ) NOT NULL AUTO_INCREMENT;",
            'date' => "20090215",
            'source' => 'original'
        ];
        self::makePatch('194', $patch);

        $patch = [
            'name' => "User table - add enabled field",
            'patch' => "ALTER TABLE " . TB_PREFIX . "user ADD enabled INT( 1 ) NOT NULL ;",
            'date' => "20090215",
            'source' => 'original'
        ];
        self::makePatch('195', $patch);

        $patch = [
            'name' => "User table - make all existing users enabled",
            'patch' => "UPDATE " . TB_PREFIX . "user SET enabled = 1 ;",
            'date' => "20090217",
            'source' => 'original'
        ];
        self::makePatch('196', $patch);

        $patch = [
            'name' => "Defaults table - add domain_id and extension_id field",
            'patch' => "ALTER TABLE " . TB_PREFIX . "system_defaults ADD `domain_id` INT( 5 ) NOT NULL DEFAULT '1', " .
                                                                    "ADD `extension_id` INT( 5 ) NOT NULL DEFAULT '1', " .
                                                                    "DROP INDEX `name`, " .
                                                                    "ADD INDEX `name` ( `name` );",
            'date' => "20090321",
            'source' => 'original'
        ];
        self::makePatch('197', $patch);

        $patch = [
            'name' => "Extension table - create table to hold extension status",
            'patch' => "CREATE TABLE " . TB_PREFIX . "extensions (`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
                                                                 "`domain_id` INT( 11 ) NOT NULL, " .
                                                                 "`name` VARCHAR( 255 ) NOT NULL, " .
                                                                 "`description` VARCHAR( 255 ) NOT NULL, " .
                                                                 "`enabled` VARCHAR( 1 ) NOT NULL DEFAULT '0') " .
                                                     "ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20090322",
            'source' => 'original'
        ];
        self::makePatch('198', $patch);

        $patch = [
            'name' => "Update extensions table",
            'patch' => "INSERT INTO " . TB_PREFIX . "extensions (`id`,`domain_id`,`name`,`description`,`enabled`) " .
                                                        "VALUES ('1','1','core','Core part of SimpleInvoices - always enabled','1');",
            'date' => "20090529",
            'source' => 'original'
        ];
        self::makePatch('199', $patch);

        $patch = [
            'name' => "Update extensions table",
            'patch' => "UPDATE " . TB_PREFIX . "extensions SET `id` = '1' WHERE `name` = 'core' LIMIT 1;",
            'date' => "20090529",
            'source' => 'original'
        ];
        self::makePatch('200', $patch);

        $patch = [
            'name' => "Set domain_id on system defaults table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET `domain_id` = '1' ;",
            'date' => "20090622",
            'source' => 'original'
        ];
        self::makePatch('201', $patch);

        $patch = [
            'name' => "Set extension_id on system defaults table to 1",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET `extension_id` = '1' ;",
            'date' => "20090622",
            'source' => 'original'
        ];
        self::makePatch('202', $patch);

        $patch = [
            'name' => "Move all old consulting style invoices to itemised",
            'patch' => "UPDATE `" . TB_PREFIX . "invoices` SET `type_id` = '2' where `type_id`=3 ;",
            'date' => "20090704",
            'source' => 'original'
        ];
        self::makePatch('203', $patch);

        $patch = [
            'name' => "Creates index table to handle new invoice numbering system",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "index` (`id` INT( 11 ) NOT NULL, " .
                                                              "`node` VARCHAR( 255 ) NOT NULL, " .
                                                              "`sub_node` VARCHAR( 255 ) NULL, " .
                                                              "`domain_id` INT( 11 ) NOT NULL) " .
                                                      "ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20090818",
            'source' => 'original'
        ];
        self::makePatch('204', $patch);

        $patch = [
            'name' => "Add index_id to invoice table - new invoice numbering",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD `index_id` INT( 11 ) NOT NULL AFTER `id`;",
            'date' => "20090818",
            'source' => 'original'
        ];
        self::makePatch('205', $patch);

        $patch = [
            'name' => "Add status and locale to preferences",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `status` INT( 1 ) NOT NULL, " .
                                                                  "ADD `locale` VARCHAR( 255 ) NULL, " .
                                                                  "ADD `language` VARCHAR( 255 ) NULL ;",
            'date' => "20090826",
            'source' => 'original'
        ];
        self::makePatch('206', $patch);

        $patch = [
            'name' => "Populate the status, locale, and language fields in preferences table",
            'patch' => "UPDATE `" . TB_PREFIX . "preferences` SET status = '1', locale = '" . $config->local->locale . "', language = '$language' ;",
            'date' => "20090826",
            'source' => 'original'
        ];
        self::makePatch('207', $patch);

        $patch = [
            'name' => "Populate the status, locale, and language fields in preferences table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `index_group` INT( 11 ) NOT NULL ;",
            'date' => "20090826",
            'source' => 'original'
        ];
        self::makePatch('208', $patch);

        $preference = SystemDefaults::getPreference();
        $patch = [
            'name' => "Populate the status, locale, and language fields in preferences table",
            'patch' => "UPDATE `" . TB_PREFIX . "preferences` SET index_group = '$preference' ;",
            'date' => "20090826",
            'source' => 'original'
        ];
        self::makePatch('209', $patch);
        unset($preference);

        $patch = [
            'name' => "Create composite primary key for invoice table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`,`id` );",
            'date' => "20090826",
            'source' => 'original'
        ];
        self::makePatch('210', $patch);

        $patch = [
            'name' => "Reset auto-increment for invoice table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` AUTO_INCREMENT = 1;",
            'date' => "20090826",
            'source' => 'original'
        ];
        self::makePatch('211', $patch);

        $patch = [
            'name' => "Copy invoice.id into invoice.index_id",
            'patch' => "UPDATE `" . TB_PREFIX . "invoices` set index_id = id;",
            'date' => "20090902",
            'source' => 'original'
        ];
        self::makePatch('212', $patch);

        $preference = intval((empty($defaults['preference']) ? "0" : $defaults['preference']));
        $maxInvoice = Invoice::maxIndexIdForPreference($preference);
        $patch = [
            'name' => "Update the index table with max invoice id - if required",
            'patch' => ($maxInvoice > 0 ? "INSERT INTO `" . TB_PREFIX . "index` (id, node, sub_node, domain_id) " .
                                                                       "VALUES (" . $maxInvoice . ", 'invoice', '$preference','1');" :
                                          "SELECT 1+1;"),
            'date' => "20090902",
            'source' => 'original'
        ];
        self::makePatch('213', $patch);
        unset($preference);
        unset($maxInvoice);
        unset($defaults);

        $patch = [
            'name' => "Add sub_node_2 to si_index table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "index` ADD  `sub_node_2` VARCHAR( 255 ) NULL AFTER  `sub_node`",
            'date' => "20090912",
            'source' => 'original'
        ];
        self::makePatch('214', $patch);

        $patch = [
            'name' => "si_invoices - add composite primary key - patch removed",
            'patch' => "SELECT 1+1;",
            'date' => "20090912",
            'source' => 'original'
        ];
        self::makePatch('215', $patch);

        $patch = [
            'name' => "si_payment - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
            'date' => "20090912",
            'source' => 'original'
        ];
        self::makePatch('216', $patch);

        $patch = [
            'name' => "si_payment_types - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment_types` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `pt_id`)",
            'date' => "20090912",
            'source' => 'original'
        ];
        self::makePatch('217', $patch);

        $patch = [
            'name' => "si_preferences - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `pref_id`)",
            'date' => "20090912",
            'source' => 'original'
        ];
        self::makePatch('218', $patch);

        $patch = [
            'name' => "si_products - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
            'date' => "20090912",
            'source' => 'original'
        ];
        self::makePatch('219', $patch);

        $patch = [
            'name' => "si_tax - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "tax` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `tax_id`)",
            'date' => "20090912",
            'source' => 'original'
        ];
        self::makePatch('220', $patch);

        $patch = [
            'name' => "si_user - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
            'date' => "20090912",
            'source' => 'original'
        ];
        self::makePatch('221', $patch);

        $patch = [
            'name' => "si_biller - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
            'date' => "20100209",
            'source' => 'original'
        ];
        self::makePatch('222', $patch);

        $patch = [
            'name' => "si_customers - add composite primary key",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` DROP PRIMARY KEY, ADD PRIMARY KEY(`domain_id`, `id`)",
            'date' => "20100209",
            'source' => 'original'
        ];
        self::makePatch('223', $patch);

        $patch = [
            'name' => "Add paypal business name",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD `paypal_business_name` VARCHAR( 255 ) NULL AFTER  `footer`",
            'date' => "20100209",
            'source' => 'original'
        ];
        self::makePatch('224', $patch);

        $patch = [
            'name' => "Add paypal notify url",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD `paypal_notify_url` VARCHAR( 255 ) NULL AFTER  `paypal_business_name`",
            'date' => "20100209",
            'source' => 'original'
        ];
        self::makePatch('225', $patch);

        $patch = [
            'name' => "Define currency in preferences",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `currency_code` VARCHAR( 25 ) NULL ;",
            'date' => "20100209",
            'source' => 'original'
        ];
        self::makePatch('226', $patch);

        $patch = [
            'name' => "Create cron table to handle recurrence",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "cron` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT, " .
                                                             "`domain_id` INT( 11 ) NOT NULL, " .
                                                             "`invoice_id` INT( 11 ) NOT NULL, " .
                                                             "`start_date` DATE NOT NULL, " .
                                                             "`end_date` VARCHAR( 10 ) NULL, " .
                                                             "`recurrence` INT( 11 ) NOT NULL, " .
                                                             "`recurrence_type` VARCHAR( 11 ) NOT NULL, " .
                                                             "`email_biller` INT( 1 ) NULL, " .
                                                             "`email_customer` INT( 1 ) NULL, " .
                                                             "PRIMARY KEY (`domain_id` ,`id`)) " .
                                                      "ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20100215",
            'source' => 'original'
        ];
        self::makePatch('227', $patch);

        $patch = [
            'name' => "Create cron_log table to handle record when cron was run",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "cron_log` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT, " .
                                                                 "`domain_id` INT( 11 ) NOT NULL, " .
                                                                 "`run_date` DATE NOT NULL, " .
                                                                 "PRIMARY KEY (  `domain_id` , `id`)) " .
                                                      "ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20100216",
            'source' => 'original'
        ];
        self::makePatch('228', $patch);

        $patch = [
            'name' => "preferences - add online payment type",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `include_online_payment` VARCHAR( 255 ) NULL ;",
            'date' => "20100209",
            'source' => 'original'
        ];
        self::makePatch('229', $patch);

        $patch = [
            'name' => "Add paypal notify url",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "biller` ADD `paypal_return_url` VARCHAR( 255 ) NULL AFTER  `paypal_notify_url`",
            'date' => "20100223",
            'source' => 'original'
        ];
        self::makePatch('230', $patch);

        $patch = [
            'name' => "Add paypal payment id into payment table",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "payment` ADD `online_payment_id` VARCHAR( 255 ) NULL AFTER  `domain_id`",
            'date' => "20100226",
            'source' => 'original'
        ];
        self::makePatch('231', $patch);

        $patch = [
            'name' => "Define currency display in preferences",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD `currency_position` VARCHAR( 25 ) NULL ;",
            'date' => "20100227",
            'source' => 'original'
        ];
        self::makePatch('232', $patch);

        $patch = [
            'name' => "Add system default to control invoice number by biller -- dummy patch -- this sql was removed",
            'patch' => "SELECT 1+1;",
            'date' => "20100302",
            'source' => 'original'
        ];
        self::makePatch('233', $patch);

        $patch = [
            'name' => "Add eway customer ID",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "biller` ADD `eway_customer_id` VARCHAR( 255 ) NULL AFTER `paypal_return_url`;",
            'date' => "20100315",
            'source' => 'original'
        ];
        self::makePatch('234', $patch);

        $patch = [
            'name' => "Add eway card holder name",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "customers` ADD `credit_card_holder_name` VARCHAR( 255 ) NULL AFTER `email`;",
            'date' => "20100315",
            'source' => 'original'
        ];
        self::makePatch('235', $patch);

        $patch = [
            'name' => "Add eway card number",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "customers` ADD `credit_card_number` VARCHAR( 255 ) NULL AFTER `credit_card_holder_name`;",
            'date' => "20100315",
            'source' => 'original'
        ];
        self::makePatch('236', $patch);

        $patch = [
            'name' => "Add eway card expiry month",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "customers` ADD `credit_card_expiry_month` VARCHAR( 02 ) NULL AFTER `credit_card_number`;",
            'date' => "20100315",
            'source' => 'original'
        ];
        self::makePatch('237', $patch);

        $patch = [
            'name' => "Add eway card expirt year",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "customers` ADD `credit_card_expiry_year` VARCHAR( 04 ) NULL AFTER `credit_card_expiry_month` ;",
            'date' => "20100315",
            'source' => 'original'
        ];
        self::makePatch('238', $patch);

        $patch = [
            'name' => "cronlog - add invoice id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "cron_log` ADD `cron_id` VARCHAR( 25 ) NULL AFTER `domain_id` ;",
            'date' => "20100321",
            'source' => 'original'
        ];
        self::makePatch('239', $patch);

        $patch = [
            'name' => "si_system_defaults - add composite primary key",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "system_defaults` ADD `new_id` INT( 11 ) NOT NULL FIRST; " .
                       "UPDATE `" . TB_PREFIX . "system_defaults` SET new_id = id WHERE 1; " .
                       "ALTER TABLE  `" . TB_PREFIX . "system_defaults` DROP  `id`; " .
                       "ALTER TABLE  `" . TB_PREFIX . "system_defaults` DROP INDEX `name`; " .
                       "ALTER TABLE  `" . TB_PREFIX . "system_defaults` CHANGE  `new_id`  `id` INT( 11 ) NOT NULL; " .
                       "ALTER TABLE  `" . TB_PREFIX . "system_defaults` ADD PRIMARY KEY(`domain_id`,`id` );",
            'date' => "20100305",
            'source' => 'original'
        ];
        self::makePatch('240', $patch);

        $patch = [
            'name' => "si_system_defaults - add composite primary key",
            'patch' => "INSERT INTO `" . TB_PREFIX . "system_defaults` VALUES ('','inventory','0','1','1');",
            'date' => "20100409",
            'source' => 'original'
        ];
        self::makePatch('241', $patch);

        $patch = [
            'name' => "Add cost to products table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `cost` DECIMAL( 25, 6 ) NULL DEFAULT '0.00' AFTER `default_tax_id_2`;",
            'date' => "20100409",
            'source' => 'original'
        ];
        self::makePatch('242', $patch);

        $patch = [
            'name' => "Add reorder_level to products table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `reorder_level` INT( 11 ) NULL AFTER `cost` ;",
            'date' => "20100409",
            'source' => 'original'
        ];
        self::makePatch('243', $patch);

        $patch = [
            'name' => "Create inventory table",
            'patch' => "CREATE TABLE  `" . TB_PREFIX . "inventory` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT, " .
                                                                   "`domain_id` INT( 11 ) NOT NULL, " .
                                                                   "`product_id` INT( 11 ) NOT NULL, " .
                                                                   "`quantity` DECIMAL( 25, 6 ) NOT NULL, " .
                                                                   "`cost` DECIMAL( 25, 6 ) NULL, " .
                                                                   "`date` DATE NOT NULL, " .
                                                                   "`note` TEXT NULL, " .
                                                                   "PRIMARY KEY (`domain_id`, `id`)) " .
                                                        "ENGINE = MYISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20100409",
            'source' => 'original'
        ];
        self::makePatch('244', $patch);

        $patch = [
            'name' => "Preferences - make locale null field",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "preferences` CHANGE  `locale`  `locale` VARCHAR( 255 ) NULL ;",
            'date' => "20100419",
            'source' => 'original'
        ];
        self::makePatch('245', $patch);

        $patch = [
            'name' => "Preferences - make language a null field",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "preferences` CHANGE  `language`  `language` VARCHAR( 255 ) NULL;",
            'date' => "20100419",
            'source' => 'original'
        ];
        self::makePatch('246', $patch);

        $patch = [
            'name' => "Custom fields - make sure domain_id is 1",
            'patch' => "UPPDATE " . TB_PREFIX . "custom_fields set domain_id = '1';",
            'date' => "20100419",
            'source' => 'original'
        ];
        self::makePatch('247', $patch);

        $patch = [
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD INDEX(`domain_id`);",
            'date' => "20100419",
            'source' => 'original'
        ];
        self::makePatch('248', $patch);

        $patch = [
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD INDEX(`biller_id`) ;",
            'date' => "20100419",
            'source' => 'original'
        ];
        self::makePatch('249', $patch);

        $patch = [
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD INDEX(`customer_id`);",
            'date' => "20100419",
            'source' => 'original'
        ];
        self::makePatch('250', $patch);

        $patch = [
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment` ADD INDEX(`domain_id`);",
            'date' => "20100419",
            'source' => 'original'
        ];
        self::makePatch('251', $patch);

        $patch = [
            'name' => "Language - reset to en_US - due to folder renaming",
            'patch' => "UPDATE `" . TB_PREFIX . "system_defaults` SET value ='en_US' where name='language';",
            'date' => "20100419",
            'source' => 'original'
        ];
        self::makePatch('252', $patch);

        $patch = [
            'name' => "Add PaymentsGateway API ID field",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` ADD  `paymentsgateway_api_id` VARCHAR( 255 ) NULL AFTER `eway_customer_id`;",
            'date' => "20110918",
            'source' => 'original'
        ];
        self::makePatch('253', $patch);

        $patch = [
            'name' => "Product Matrix - update line items table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` ADD `attribute` VARCHAR( 255 ) NULL ;",
            'date' => "20130313",
            'source' => 'original'
        ];
        self::makePatch('254', $patch);

        $patch = [
            'name' => "Product Matrix - update line items table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "products_attributes` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
                                                                            "`name` VARCHAR( 255 ) NOT NULL, " .
                                                                            "`type_id` VARCHAR( 255 ) NOT NULL) ENGINE = MYISAM ;",
            'date' => "20130313",
            'source' => 'original'
        ];
        self::makePatch('255', $patch);

        $patch = [
            'name' => "Product Matrix - update line items table",
            'patch' => "INSERT INTO `" . TB_PREFIX . "products_attributes` (`id`, `name`, `type_id`) VALUES (NULL, 'Size','1'), (NULL,'Colour','1');",
            'date' => "20130313",
            'source' => 'original'
        ];
        self::makePatch('256', $patch);

        $patch = [
            'name' => "Product Matrix - update line items table",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "products_values` (`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
                                                                        "`attribute_id` INT( 11 ) NOT NULL, " .
                                                                        "`value` VARCHAR( 255 ) NOT NULL) ENGINE = MYISAM ;",
            'date' => "20130313",
            'source' => 'original'
        ];
        self::makePatch('257', $patch);

        $patch = [
            'name' => "Product Matrix - update line items table",
            'patch' => "INSERT INTO `" . TB_PREFIX . "products_values` (`id`, `attribute_id`,`value`) " .
                                                               "VALUES (NULL,'1', 'S'), " .
                                                                      "(NULL,'1', 'M'), " .
                                                                      "(NULL,'1', 'L'), " .
                                                                      "(NULL,'2', 'Red'), " .
                                                                      "(NULL,'2', 'White');",
            'date' => "20130313",
            'source' => 'original'
        ];
        self::makePatch('258', $patch);

        $patch = [
            'name' => "Product Matrix - update line items table",
            'patch' => "SELECT 1+1;",  //remove matrix code
            'date' => "20130313",
            'source' => 'original'
        ];
        self::makePatch('259', $patch);

        $patch = [
            'name' => "Product Matrix - update line items table",
            'patch' => "SELECT 1+1;", //remove matrix code
            'date' => "20130313",
            'source' => 'original'
        ];
        self::makePatch('260', $patch);

        $patch = [
            'name' => "Product Matrix - update line items table",
            'patch' => "SELECT 1+1;", //remove matrix code
            'date' => "20130313",
            'source' => 'original'
        ];
        self::makePatch('261', $patch);

        $patch = [
            'name' => "Add product attributes system preference",
            'patch' => "INSERT INTO " . TB_PREFIX . "system_defaults (id, name ,value ,domain_id ,extension_id ) VALUES (NULL , 'product_attributes', '0', '1', '1');",
            'date' => "20130313",
            'source' => 'original'
        ];
        self::makePatch('262', $patch);

        $patch = [
            'name' => "Product Matrix - update line items table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `attribute` VARCHAR( 255 ) NULL ;",
            'date' => "20130313",
            'source' => 'original'
        ];
        self::makePatch('263', $patch);

        $patch = [
            'name' => "Product - use notes as default line item description",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `notes_as_description` VARCHAR( 1 ) NULL ;",
            'date' => "20130314",
            'source' => 'original'
        ];
        self::makePatch('264', $patch);

        $patch = [
            'name' => "Product - expand/show line item description",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "products` ADD `show_description` VARCHAR( 1 ) NULL ;",
            'date' => "20130314",
            'source' => 'original'
        ];
        self::makePatch('265', $patch);

        $patch = [
            'name' => "Product - expand/show line item description",
            'patch' => "CREATE TABLE `" . TB_PREFIX . "products_attribute_type` (`id` int(11) NOT NULL AUTO_INCREMENT, " .
                                                                                "`name` varchar(255) NOT NULL, " .
                                                                                "PRIMARY KEY (`id`)) " .
                                                      "ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20130322",
            'source' => 'original'
        ];
        self::makePatch('266', $patch);

        $patch = [
            'name' => "Product Matrix - insert attribute types",
            'patch' => "INSERT INTO `" . TB_PREFIX . "products_attribute_type` (`id`, `name`) VALUES (NULL,'list'),  (NULL,'decimal'), (NULL,'free');",
            'date' => "20130325",
            'source' => 'original'
        ];
        self::makePatch('267', $patch);

        $patch = [
            'name' => "Product Matrix - insert attribute types",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "products_attributes` ADD `enabled` VARCHAR( 1 ) NULL DEFAULT  '1', " .
                                                                           "ADD `visible` VARCHAR( 1 ) NULL DEFAULT  '1';",
            'date' => "20130327",
            'source' => 'original'
        ];
        self::makePatch('268', $patch);

        $patch = [
            'name' => "Product Matrix - insert attribute types",
            'patch' => "ALTER TABLE  `" . TB_PREFIX . "products_values` ADD  `enabled` VARCHAR( 1 ) NULL DEFAULT  '1';",
            'date' => "20130327",
            'source' => 'original'
        ];
        self::makePatch('269', $patch);

        $patch = [
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment` ADD INDEX(`ac_inv_id`);",
            'date' => "20100419",
            'source' => 'original'
        ];
        self::makePatch('270', $patch);

        $patch = [
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "payment` ADD INDEX(`ac_amount`);",
            'date' => "20100419",
            'source' => 'original'
        ];
        self::makePatch('271', $patch);

        $patch = [
            'name' => "Add product attributes system preference",
            'patch' => "INSERT INTO " . TB_PREFIX . "system_defaults (id, name ,value ,domain_id ,extension_id ) VALUES (NULL , 'large_dataset', '0', '1', '1');",
            'date' => "20130313",
            'source' => 'original'
        ];
        self::makePatch('272', $patch);

        $patch = [
            'name' => "Make SimpleInvoices faster - add index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` ADD INDEX(`invoice_id`);",
            'date' => "20130927",
            'source' => 'original'
        ];
        self::makePatch('273', $patch);

        $patch = [
            'name' => "Only One Default Variable name per domain allowed - add unique index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "system_defaults` ADD UNIQUE INDEX `UnqNameInDomain` (`domain_id`, `name`);",
            'date' => "20131007",
            'source' => 'original'
        ];
        self::makePatch('274', $patch);

        $patch = [
            'name' => "Make EMail / Password pair unique per domain - add unique index",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `password` `password` VARCHAR(64) NULL, " .
                                                           "ADD UNIQUE INDEX `UnqEMailPwd` (`email`, `password`);",
            'date' => "20131007",
            'source' => 'original'
        ];
        self::makePatch('275', $patch);

        $patch = [
            'name' => "Each invoice Item must belong to a specific Invoice with a specific domain_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` ADD COLUMN `domain_id` INT NOT NULL DEFAULT '1' AFTER `invoice_id`;",
            'date' => "20131008",
            'source' => 'original'
        ];
        self::makePatch('276', $patch);

        $patch = [
            'name' => "Add Index for Quick Invoice Item Search for a domain_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoice_items` ADD INDEX `DomainInv` (`invoice_id`, `domain_id`);",
            'date' => "20131008",
            'source' => 'original'
        ];
        self::makePatch('277', $patch);

        $patch = [
            'name' => "Each Invoice Item can have only one instance of each tax",
            //    Patch disabled for old installs with inadequate database integrity
            //    self::$patchLines['278']['patch'] = "ALTER TABLE `".TB_PREFIX."invoice_item_tax` ADD UNIQUE INDEX `UnqInvTax` (`invoice_item_id`, `tax_id`);";
            'patch' => "SELECT 1+1;",
            'date' => "20131008",
            'source' => 'original'
        ];
        self::makePatch('278', $patch);

        $patch = [
            'name' => "Drop unused superseded table si_product_matrix if present",
            'patch' => "DROP TABLE IF EXISTS `" . TB_PREFIX . "products_matrix`;",
            'date' => "20131009",
            'source' => 'original'
        ];
        self::makePatch('279', $patch);

        $patch = [
            'name' => "Each domain has their own extension instances",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "extensions` DROP PRIMARY KEY, ADD PRIMARY KEY (`id`, `domain_id`);",
            'date' => "20131011",
            'source' => 'original'
        ];
        self::makePatch('280', $patch);

        $patch = [
            'name' => "Each domain has their own custom_field id sets",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "custom_fields` DROP PRIMARY KEY, ADD PRIMARY KEY (`cf_id`, `domain_id`);",
            'date' => "20131011",
            'source' => 'original'
        ];
        self::makePatch('281', $patch);

        $patch = [
            'name' => "Each domain has their own logs",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "log` ADD COLUMN `domain_id` INT NOT NULL DEFAULT '1' AFTER `id`, " .
                                                          "DROP PRIMARY KEY, " .
                                                          "ADD PRIMARY KEY (`id`, `domain_id`);",
            'date' => "20131011",
            'source' => 'original'
        ];
        self::makePatch('282', $patch);

        $patch = [
            'name' => "Match field type with foreign key field si_user.id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "log` CHANGE `userid` `userid` INT NOT NULL DEFAULT '1';",
            'date' => "20131012",
            'source' => 'original'
        ];
        self::makePatch('283', $patch);

        $patch = [
            'name' => "Make si_index sub_node and sub_node_2 fields as integer",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "index` CHANGE `node` `node` VARCHAR(64) NOT NULL, " .
                                                            "CHANGE `sub_node` `sub_node` INT NOT NULL, " .
                                                            "CHANGE `sub_node_2` `sub_node_2` INT NOT NULL;",
            'date' => "20131016",
            'source' => 'original'
        ];
        self::makePatch('284', $patch);

        $patch = [
            'name' => "Fix compound Primary Key for si_index table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "index` ADD PRIMARY KEY (`node`, `sub_node`, `sub_node_2`, `domain_id`);",
            'date' => "20131016",
            'source' => 'original'
        ];
        self::makePatch('285', $patch);

        $patch = [
            'name' => "Speedup lookups from si_index table with indices in si_invoices table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD UNIQUE INDEX `UniqDIB` (`index_id`, `preference_id`, `biller_id`, `domain_id`), " .
                                                               "ADD INDEX `IdxDI` (`index_id`, `preference_id`, `domain_id`);",
            'date' => "20131016",
            'source' => 'original'
        ];
        self::makePatch('286', $patch);

        $patch = [
            'name' => "Populate additional user roles like domain_administrator",
            'patch' => "INSERT IGNORE INTO `" . TB_PREFIX . "user_role` (`name`) VALUES ('domain_administrator'), ('customer'), ('biller');",
            'date' => "20131017",
            'source' => 'original'
        ];
        self::makePatch('287', $patch);

        $patch = [
            'name' => "Fully relational now - do away with the si_index table",
            'patch' => "SELECT 1+1;",
            'date' => "20131017",
            'source' => 'original'
        ];
        self::makePatch('288', $patch);

        $patch = [
            'name' => "Each cron_id can run a maximum of only once a day for each domain_id",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "cron_log` ADD UNIQUE INDEX `CronIdUnq` (`domain_id`, `cron_id`, `run_date`);",
            'date' => "20131108",
            'source' => 'original'
        ];
        self::makePatch('289', $patch);

        $patch = [
            'name' => "Set all Flag fields to tinyint(1) and other 1 byte fields to char",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "extensions` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 0 NOT NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "payment_types` CHANGE `pt_enabled` `pt_enabled` TINYINT(1) DEFAULT 1 NOT NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "preferences` CHANGE `pref_enabled` `pref_enabled` TINYINT(1) DEFAULT 1 NOT NULL, " .
                                                                  "CHANGE `status` `status` TINYINT(1) NOT NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "products` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL, " .
                                                               "CHANGE `notes_as_description` `notes_as_description` CHAR(1) NULL, " .
                                                               "CHANGE `show_description` `show_description` CHAR(1) NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "tax` CHANGE `tax_enabled` `tax_enabled` TINYINT(1) DEFAULT 1 NOT NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "cron` CHANGE `email_biller` `email_biller` TINYINT(1) DEFAULT 0 NOT NULL, " .
                                                           "CHANGE `email_customer` `email_customer` TINYINT(1) DEFAULT 0 NOT NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "custom_fields` CHANGE `cf_display` `cf_display` TINYINT(1) DEFAULT 1 NOT NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "invoice_item_tax` CHANGE `tax_type` `tax_type` CHAR(1) DEFAULT '%' NOT NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "tax` CHANGE `type` `type` CHAR(1) DEFAULT '%' NOT NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "products_attributes` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL, " .
                                                                          "CHANGE `visible` `visible` TINYINT(1) DEFAULT 1 NOT NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "products_VALUES` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "user` CHANGE `enabled` `enabled` TINYINT(1) DEFAULT 1 NOT NULL;",
            'date' => "20131109",
            'source' => 'original'
        ];
        self::makePatch('290', $patch);

        $patch = [
            'name' => "Clipped size of zip_code and credit_card_number fields to realistic values",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` CHANGE `zip_code` `zip_code` VARCHAR(20) NULL, " .
                                                                "CHANGE `credit_card_number` `credit_card_number` VARCHAR(20) NULL; " .
                       "ALTER TABLE `" . TB_PREFIX . "biller` CHANGE `zip_code` `zip_code` VARCHAR(20) NULL;",
            'date' => "20131111",
            'source' => 'original'
        ];
        self::makePatch('291', $patch);

        $patch = [
            'name' => "Added Customer/Biller User ID column to user table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "user` ADD COLUMN `user_id` INT  DEFAULT 0 NOT NULL AFTER `enabled`;",
            'date' => "20140103",
            'source' => 'original'
        ];
        self::makePatch('292', $patch);

        $patch = [
            'name' => "Add department to the customers",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD COLUMN `department` VARCHAR(255) NULL AFTER `name`;",
            'date' => "20161004",
            'source' => 'original'
        ];
        self::makePatch('293', $patch);

        $ud = $pdoDbAdmin->checkTableExists('custom_flags');
        $patch = [
            'name' => 'Add custom_flags table for products.',
            'patch' => ($ud ? "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'custom_flags';" :
                "CREATE TABLE `" . TB_PREFIX . "custom_flags` (`domain_id`        int(11) DEFAULT '1' NOT NULL, " .
                                                              "`associated_table` char(10)            NOT NULL COMMENT 'Table flag is associated with. Only defined for products for now.', " .
                                                              "`flg_id`           tinyint(3) unsigned NOT NULL COMMENT 'Flag number ranging from 1 to 10', " .
                                                              "`field_label`      varchar(20)         NOT NULL COMMENT 'Label to use on screen where option is set.', " .
                                                              "`enabled`          tinyint(1)          NOT NULL COMMENT 'Defaults to enabled when record created. Can disable to retire flag.', " .
                                                              "`field_help`       varchar(255)        NOT NULL COMMENT 'Help information to display for this field.', " .
                                                              "PRIMARY KEY `uid` (`domain_id`, `associated_table`, `flg_id`), " .
                                                              "KEY `domain_id` (`domain_id`), " .
                                                              "KEY `dtable`    (`domain_id`, `associated_table`)) " .
                                                "ENGINE=InnoDB COMMENT='Specifies an allowed setting for a flag field';" .
                "ALTER TABLE `" . TB_PREFIX . "products` ADD `custom_flags` CHAR( 10 ) NOT NULL COMMENT 'User defined flags';" .
                "INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, field_label, flg_id, field_help, enabled) VALUES (1,'products','',1,'',0);" .
                "INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, field_label, flg_id, field_help, enabled) VALUES (1,'products','',2,'',0);" .
                "INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, field_label, flg_id, field_help, enabled) VALUES (1,'products','',3,'',0);" .
                "INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, field_label, flg_id, field_help, enabled) VALUES (1,'products','',4,'',0);" .
                "INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, field_label, flg_id, field_help, enabled) VALUES (1,'products','',5,'',0);" .
                "INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, field_label, flg_id, field_help, enabled) VALUES (1,'products','',6,'',0);" .
                "INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, field_label, flg_id, field_help, enabled) VALUES (1,'products','',7,'',0);" .
                "INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, field_label, flg_id, field_help, enabled) VALUES (1,'products','',8,'',0);" .
                "INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, field_label, flg_id, field_help, enabled) VALUES (1,'products','',9,'',0);" .
                "INSERT INTO `" . TB_PREFIX . "custom_flags` (domain_id, associated_table, field_label, flg_id, field_help, enabled) VALUES (1,'products','',10,'',0);" .
                "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'custom_flags';"),
            'date' => "20180922",
            'source' => 'fearless359'
        ];
        self::makePatch('294', $patch);
        unset($ud);

        $patch = [
            'name' => 'Add net income report.',
            'patch' => "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'net_income_report';",
            'date' => "20180923",
            'source' => 'fearless359'
        ];
        self::makePatch('295', $patch);

        $patch = [
            'name' => 'Add past due report.',
            'patch' => "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'past_due_report';",
            'date' => "20180924",
            'source' => 'fearless359'
        ];
        self::makePatch('296', $patch);

        $ud = ($pdoDbAdmin->checkFieldExists("user", "username"));
        $coNam = $LANG['company_name'];
        $coLogo = 'simple_invoices_logo.png';
        $patch = [
            'name' => 'Add User Security enhancement fields and values',
            'patch' => ($ud ? "UPDATE `" . TB_PREFIX . "system_defaults` SET `extension_id` = 1 WHERE `name` IN " .
                "('company_logo','company_name','company_name_item','password_min_length','password_lower','password_number','password_special','password_upper','session_timeout');" .
                "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'user_security';" :
                "ALTER TABLE `" . TB_PREFIX . "user` ADD `username` VARCHAR(255) DEFAULT '' NOT NULL AFTER `id`;" .
                "ALTER TABLE `" . TB_PREFIX . "user` DROP INDEX `UnqEMailPwd`;" .
                "UPDATE `" . TB_PREFIX . "user` AS U1, `" . TB_PREFIX . "user` AS U2 SET U1.username = U2.email WHERE U2.id = U1.id;" .
                "ALTER TABLE `" . TB_PREFIX . "user` ADD UNIQUE INDEX `uname` (`username`);" .
                "ALTER TABLE `" . TB_PREFIX . "system_defaults` CHANGE `value` `value` VARCHAR(60);" .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domainId, 'company_logo'        , '$coLogo', 1);" .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domainId, 'company_name'        , '$coNam' , 1);" .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domainId, 'company_name_item'   , '$coNam' , 1);" .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domainId, 'password_min_length' , 8        , 1);" .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domainId, 'password_lower'      , 1        , 1);" .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domainId, 'password_number'     , 1        , 1);" .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domainId, 'password_special'    , 1        , 1);" .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domainId, 'password_upper'      , 1        , 1);" .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`domain_id` , `name`, `value`,`extension_id`) VALUES ($domainId, 'session_timeout'     , 15       , 1);" .
                "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'user_security';"),
            'date' => "20180924",
            'source' => 'fearless359'
        ];
        self::makePatch('297', $patch);
        unset($ud);
        unset($coNam);
        unset($coLogo);

        $ud = ($pdoDbAdmin->checkFieldExists('biller', 'signature'));
        $patch = [
            'name' => 'Add Signature field to the biller table.',
            'patch' => ($ud ? "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'signature_field';" :
                "ALTER TABLE `" . TB_PREFIX . "biller` ADD `signature` varchar(255) DEFAULT '' NOT NULL COMMENT 'Email signature' AFTER `email`;" .
                "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'signature_field';"),
            'date' => "20181003",
            'source' => 'fearless359'
        ];
        self::makePatch('298', $patch);
        unset($ud);

        $ud = ($pdoDbAdmin->checkFieldExists('payment', 'ac_check_number'));
        $patch = [
            'name' => 'Add check number field to the payment table.',
            'patch' => ($ud ? "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'payments';" :
                "ALTER TABLE `" . TB_PREFIX . "payment` ADD `ac_check_number` varchar(10) DEFAULT '' NOT NULL COMMENT 'Check number for CHECK payment types';" .
                "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'payments';"),
            'date' => "20181003",
            'source' => 'fearless359'
        ];
        self::makePatch('299', $patch);
        unset($ud);

        $ud = $pdoDbAdmin->checkTableExists( 'install_complete');
        $patch = [
            'name' => 'Add install complete table.',
            'patch' => ($ud ? "UPDATE `" . TB_PREFIX . "install_complete` SET `completed` = 1;" :
                "CREATE TABLE `" . TB_PREFIX . "install_complete` (`completed` tinyint(1) NOT NULL COMMENT 'Flag SI install has completed') " .
                "ENGINE=InnoDB COMMENT='Specifies an allowed setting for a flag field';" .
                "INSERT INTO `" . TB_PREFIX . "install_complete` (completed) VALUES (1);"),
            'date' => "20181008",
            'source' => 'fearless359'
        ];
        self::makePatch('300', $patch);
        unset($ud);

        $patch = [
            'name' => 'Add last_activity_date, aging_date and aging_value to invoices.',
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` " .
                "ADD `last_activity_date` DATETIME DEFAULT '2000-12-31 00:00:00' NOT NULL COMMENT 'Date last activity update to the invoice', " .
                "ADD `aging_date` DATETIME DEFAULT '2000-12-30 00:00:00' NOT NULL COMMENT 'Date aging was last calculated', " .
                "ADD `age_days` SMALLINT(5) UNSIGNED DEFAULT 0 NOT NULL COMMENT 'Age of invoice balance', " .
                "ADD `aging` VARCHAR(5) DEFAULT '' NOT NULL COMMENT 'Aging string (1-14, 15-30, etc.';" .
                "DELETE IGNORE FROM `" . TB_PREFIX . "system_defaults` WHERE `name` = 'large_dataset';",
            'date' => "20181012",
            'source' => 'fearless359'
        ];
        self::makePatch('301', $patch);

        $patch = [
            'name' => "Added owing to invoices table",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD COLUMN `owing` DECIMAL(25,6) DEFAULT 0 NOT NULL COMMENT 'Amount owing as of aging-date' AFTER `note`;" .
                "UPDATE `" . TB_PREFIX . "invoices` SET `owing` = 1 WHERE 1;",
            'date' => "20181017",
            'source' => 'fearless359'
        ];
        self::makePatch('302', $patch);

        $patch = [
            'name' => 'Add invoice custom field report extension to standard application and add sales_representative field.',
            'patch' => "ALTER TABLE `" . TB_PREFIX . "invoices` ADD `sales_representative` VARCHAR(50) DEFAULT '' NOT NULL;",
            'date' => "20181018",
            'source' => 'fearless359'
        ];
        self::makePatch('303', $patch);

        $patch = [
            'name' => 'Add default_invoice field to the customers table.',
            'patch' => "ALTER TABLE `" . TB_PREFIX . "customers` ADD `default_invoice` INT(10) UNSIGNED DEFAULT 0 NOT NULL COMMENT 'Invoice index_id value to use as the default template' AFTER `notes`;",
            'date' => "20181020",
            'source' => 'fearless359'
        ];
        self::makePatch('304', $patch);

        $ud = $pdoDbAdmin->checkTableExists( 'expense');
        $patch = [
            'name' => 'Add expense tables to the database.',
            'patch' => ($ud ? "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'expense';" .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`name`, `value`, `domain_id`, `extension_id`) VALUES ('expense', 0, $domainId, 1) ;" :
                "CREATE TABLE `" . TB_PREFIX . "expense` (id INT(11) NOT NULL AUTO_INCREMENT UNIQUE KEY, " .
                    "domain_id INT(11) NOT NULL, " .
                    "amount DECIMAL(25,6) NOT NULL, " .
                    "expense_account_id INT(11) NOT NULL, " .
                    "biller_id INT(11) NOT NULL, " .
                    "customer_id INT(11) NOT NULL, " .
                    "invoice_id INT(11) NOT NULL, " .
                    "product_id INT(11) NOT NULL, " .
                    "date DATE NOT NULL, " .
                    "note TEXT NOT NULL) ENGINE = InnoDb;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` ADD PRIMARY KEY (domain_id, id);" .
                "CREATE TABLE `" . TB_PREFIX . "expense_account` (id INT(11) NOT NULL AUTO_INCREMENT UNIQUE KEY, " .
                    "domain_id INT(11) NOT NULL, " .
                    "name VARCHAR(255) NOT NULL) ENGINE = InnoDb;" .
                "ALTER TABLE `" . TB_PREFIX . "expense_account` ADD PRIMARY KEY (domain_id, id);" .
                "CREATE TABLE `" . TB_PREFIX . "expense_item_tax` (id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, " .
                    "expense_id INT(11) NOT NULL, " .
                    "tax_id INT(11) NOT NULL, " .
                    "tax_type VARCHAR(1) NOT NULL, " .
                    "tax_rate DECIMAL(25, 6) NOT NULL, " .
                    "tax_amount DECIMAL(25, 6) NOT NULL) ENGINE = MYISAM;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` ADD status TINYINT(1) NOT NULL;" .
                "INSERT INTO `" . TB_PREFIX . "system_defaults` (`name`, `value`, `domain_id`, `extension_id`) VALUES ('expense', 0, $domainId, 1);" .
                "DELETE IGNORE FROM `" . TB_PREFIX . "extensions` WHERE `name` = 'expense';"),
            'date' => "20181027",
            'source' => 'fearless359'
        ];
        self::makePatch('305', $patch);
        unset($ud);

        $patch = [
            'name' => 'Clean up default_tax_id and default_tax_id_2 for products',
            'patch' =>
                "UPDATE `" . TB_PREFIX . "products` SET `default_tax_id` = NULL WHERE `default_tax_id` = 0;" .
                "UPDATE `" . TB_PREFIX . "products` SET `default_tax_id_2` = NULL WHERE `default_tax_id_2` = 0;",
            'date' => "20190329",
            'source' => 'fearless359'
        ];
        self::makePatch('306', $patch);

        $patch = [
            'name' => 'Fix cron_log cron_id data type',
            'patch' =>
                "DROP INDEX `CronIdUnq` ON `" . TB_PREFIX . "cron_log`;" .
                "ALTER TABLE `" . TB_PREFIX . "cron_log` MODIFY `cron_id` INT(11) NULL;" .
                "UPDATE `" . TB_PREFIX . "cron_log` SET `cron_id` = NULL WHERE `cron_id` = 0;" .
                "ALTER TABLE `" . TB_PREFIX . "cron_log` ADD UNIQUE KEY `CronIdUnq`  (`domain_id`,`cron_id`,`run_date`);",
            'date' => "20190329",
            'source' => 'fearless359'
        ];
        self::makePatch('307', $patch);

        $patch = [
            'name' => 'Remove dup id key from invoice_item_attachments and fix products_attributes type_id data type',
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_attachments` DROP INDEX `id`;" .
                "ALTER TABLE `" . TB_PREFIX . "products_attributes` MODIFY `type_id` INT(11) UNSIGNED NULL;",
            'date' => "20190329",
            'source' => 'fearless359'
        ];
        self::makePatch('308', $patch);

        $patch = [
            'name' => "Rename userid to user_id in log table",
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "log` CHANGE `userid` `user_id` INT(11) NULL;",
            'date' => "20190329",
            'source' => 'fearless359'
        ];
        self::makePatch('309', $patch);

        $patch = [
            'name' => 'Make record unique id fields consistent in size and properties',
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "biller` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_attachments` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "payment` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;",
            'date' => "20190329",
            'source' => 'fearless359'
        ];
        self::makePatch('310', $patch);

        $patch = [
            'name' => 'Remove default from all id fields.',
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "biller` ALTER `id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` ALTER `biller_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` ALTER `customer_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` ALTER `type_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` ALTER `preference_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_items` ALTER `invoice_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_items` ALTER `product_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "payment` ALTER `ac_payment_type` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "system_defaults` ALTER `extension_id` DROP DEFAULT;",
            'date' => "20190329",
            'source' => 'fearless359'
        ];
        self::makePatch('311', $patch);

        $patch = [
            'name' => 'Remove default from domain_id & set misc id characteristics.',
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "biller` ALTER `domain_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "cron` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "cron_log` MODIFY `cron_id` INT(11) UNSIGNED NULL;",
            'date' => "20190329",
            'source' => 'fearless359'
        ];
        self::makePatch('312', $patch);

        $patch = [
            'name' => 'Use common characteristics for all non-autoincrement id type fields.',
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "cron` MODIFY `invoice_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` MODIFY `expense_account_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` MODIFY `biller_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` MODIFY `customer_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` MODIFY `invoice_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` MODIFY `product_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "expense_item_tax` MODIFY `expense_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "expense_item_tax` MODIFY `tax_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "inventory` MODIFY `product_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` MODIFY `biller_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` MODIFY `customer_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` MODIFY `type_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` MODIFY `preference_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_items` MODIFY `invoice_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_items` MODIFY `product_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_attachments` MODIFY `invoice_item_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_tax` MODIFY `invoice_item_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_tax` MODIFY `tax_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "payment` MODIFY `ac_inv_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "payment` MODIFY `ac_payment_type` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "payment_types` MODIFY `pt_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "products_values` MODIFY `attribute_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "system_defaults` MODIFY `extension_id` INT(11) UNSIGNED NULL;",
            'date' => "20190329",
            'source' => 'fearless359'
        ];
        self::makePatch('313', $patch);

        $patch = [
            'name' => 'Create the index items for all auto increment fields.',
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "biller` ADD CONSTRAINT `id` UNIQUE(`id`);" .
                "ALTER TABLE `" . TB_PREFIX . "cron` ADD CONSTRAINT `id` UNIQUE(`id`);" .
                "ALTER TABLE `" . TB_PREFIX . "cron_log` ADD CONSTRAINT `id` UNIQUE(`id`);" .
                "ALTER TABLE `" . TB_PREFIX . "custom_fields` ADD CONSTRAINT `cf_id` UNIQUE(`cf_id`);" .
                "ALTER TABLE `" . TB_PREFIX . "customers` ADD CONSTRAINT `id` UNIQUE(`id`);" .
                "ALTER TABLE `" . TB_PREFIX . "extensions` ADD CONSTRAINT `id` UNIQUE(`id`);" .
                "ALTER TABLE `" . TB_PREFIX . "inventory` ADD CONSTRAINT `id` UNIQUE(`id`);" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` ADD CONSTRAINT `id` UNIQUE(`id`);" .
                "ALTER TABLE `" . TB_PREFIX . "log` ADD CONSTRAINT `id` UNIQUE(`id`);" .
                "ALTER TABLE `" . TB_PREFIX . "payment` ADD CONSTRAINT `id` UNIQUE(`id`);" .
                "ALTER TABLE `" . TB_PREFIX . "payment_types` ADD CONSTRAINT `pt_id` UNIQUE(`pt_id`);" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` ADD CONSTRAINT `pref_id` UNIQUE(`pref_id`);" .
                "ALTER TABLE `" . TB_PREFIX . "products` ADD CONSTRAINT `id` UNIQUE(`id`);" .
                "ALTER TABLE `" . TB_PREFIX . "system_defaults` ADD CONSTRAINT `id` UNIQUE(`id`);" .
                "ALTER TABLE `" . TB_PREFIX . "tax` ADD CONSTRAINT `tax_id` UNIQUE(`tax_id`);" .
                "ALTER TABLE `" . TB_PREFIX . "user` ADD CONSTRAINT `id` UNIQUE(`id`);",
            'date' => "20190329",
            'source' => 'fearless359'
        ];
        self::makePatch('314', $patch);

        $patch = [
            'name' => 'Make all tables InnoDB, utf8 and utr8_unicode_ci',
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "biller` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "cron` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "cron_log` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "custom_fields` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "custom_flags` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "customers` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "expense_account` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "expense_item_tax` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "extensions` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "index` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "install_complete` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "inventory` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_attachments` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_tax` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_items` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_type` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "log` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "payment` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "payment_types` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products_attribute_type` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products_attributes` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products_values` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "sql_patchmanager` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "system_defaults` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "tax` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "user` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "user_domain` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "user_role` ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;",
            'date' => "20190329",
            'source' => 'fearless359'
        ];
        self::makePatch('315', $patch);

        $patch = [
            'name' => 'Change character type field settings to charset utf8 collate utf8_unicode_ci',
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "custom_flags` MODIFY `associated_table` char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "custom_flags` MODIFY `field_label` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "custom_flags` MODIFY `field_help` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` MODIFY `note` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "expense_account` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "expense_item_tax` MODIFY `tax_type` varchar(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "extensions` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "extensions` MODIFY `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "index` MODIFY `node` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "inventory` MODIFY `note` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` MODIFY `custom_field1` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` MODIFY `custom_field2` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` MODIFY `custom_field3` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` MODIFY `custom_field4` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` MODIFY `note` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` MODIFY `sales_representative` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_items` MODIFY `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_items` MODIFY `attribute` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_attachments` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_tax` MODIFY `tax_type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_type` MODIFY `inv_ty_description` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "log` MODIFY `sqlquerie` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "payment` MODIFY `ac_notes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "payment` MODIFY `online_payment_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "payment` MODIFY `ac_check_number` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "payment_types` MODIFY `pt_description` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `pref_description` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `pref_currency_sign` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `pref_inv_heading` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `pref_inv_wording` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `pref_inv_detail_heading` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `pref_inv_detail_line` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `pref_inv_payment_method` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `pref_inv_payment_line1_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `pref_inv_payment_line1_value` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `pref_inv_payment_line2_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `pref_inv_payment_line2_value` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `locale` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `language` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `currency_code` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `include_online_payment` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `currency_position` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `custom_field1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `custom_field2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `custom_field3` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `custom_field4` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `notes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `attribute` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `notes_as_description` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `show_description` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `custom_flags` char(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products_attributes` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products_attribute_type` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "products_values` MODIFY `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "sql_patchmanager` MODIFY `sql_patch` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "sql_patchmanager` MODIFY `sql_release` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "sql_patchmanager` MODIFY `sql_statement` text CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "sql_patchmanager` MODIFY `source` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "system_defaults` MODIFY `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "system_defaults` MODIFY `value` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "tax` MODIFY `tax_description` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "tax` MODIFY `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "user` MODIFY `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "user` MODIFY `password` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "user` MODIFY `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "user_domain` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;" .
                "ALTER TABLE `" . TB_PREFIX . "user_role` MODIFY `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci;",
            'date' => "20190329",
            'source' => 'fearless359'
        ];
        self::makePatch('316', $patch);

        // Note that si_index table does NOT have an auto_increment field.
        $patch = [
            'name' => "Additional field characteristic setting changes prior to declaring foreign keys.",
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "biller` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "cron` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "cron_log` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "cron_log` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "customers` ALTER `domain_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "customers` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "customers` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "customers` MODIFY `parent_customer_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "custom_fields` MODIFY `cf_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "custom_fields` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "custom_flags` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "expense_account` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "expense_account` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "expense_item_tax` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "extensions` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "extensions` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "index` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "index` MODIFY `id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "inventory` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "inventory` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_items` ALTER `domain_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_items` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_items` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_tax` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_type` MODIFY `inv_ty_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` ALTER `domain_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` MODIFY `index_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "log` ALTER `domain_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "log` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "log` MODIFY `id` BIGINT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "log` MODIFY `last_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "log` MODIFY `user_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "payment` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "payment_types` ALTER `domain_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "payment_types` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "payment_types` MODIFY `pt_id` INT(11) UNSIGNED NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` ALTER `domain_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "preferences` MODIFY `pref_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `default_tax_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `default_tax_id_2` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "products` ALTER `domain_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "products` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "products_attributes` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "products_attribute_type` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "products_values` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "sql_patchmanager` MODIFY `sql_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "system_defaults` ALTER `domain_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "system_defaults` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "system_defaults` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "tax` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "tax` MODIFY `tax_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "user` ALTER `domain_id` DROP DEFAULT;" .
                "ALTER TABLE `" . TB_PREFIX . "user` MODIFY `domain_id` INT(11) UNSIGNED NOT NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "user` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "user` MODIFY `role_id` INT(11) UNSIGNED NULL;" .
                "ALTER TABLE `" . TB_PREFIX . "user_domain` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;" .
                "ALTER TABLE `" . TB_PREFIX . "user_role` MODIFY `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;",
            'date' => "20190425",
            'source' => 'fearless359'
        ];
        self::makePatch('317', $patch);

        $patch = [
            'name' => 'Add foreign keys to tables.',
            'patch' =>
                "ALTER TABLE `" . TB_PREFIX . "cron` ADD FOREIGN KEY (`invoice_id`) REFERENCES `" . TB_PREFIX . "invoices` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "cron_log` ADD FOREIGN KEY (`cron_id`) REFERENCES `" . TB_PREFIX . "cron` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` ADD FOREIGN KEY (`biller_id`) REFERENCES `" . TB_PREFIX . "biller` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT; " .
                "ALTER TABLE `" . TB_PREFIX . "expense` ADD FOREIGN KEY (`customer_id`) REFERENCES `" . TB_PREFIX . "customers` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` ADD FOREIGN KEY (`invoice_id`) REFERENCES `" . TB_PREFIX . "invoices` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT; " .
                "ALTER TABLE `" . TB_PREFIX . "expense` ADD FOREIGN KEY (`product_id`) REFERENCES `" . TB_PREFIX . "products` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "expense` ADD FOREIGN KEY (`expense_account_id`) REFERENCES `" . TB_PREFIX . "expense_account` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "expense_item_tax` ADD FOREIGN KEY (`expense_id`) REFERENCES `" . TB_PREFIX . "expense` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "expense_item_tax` ADD FOREIGN KEY (`tax_id`) REFERENCES `" . TB_PREFIX . "tax` (`tax_id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "inventory` ADD FOREIGN KEY (`product_id`) REFERENCES `" . TB_PREFIX . "products` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` ADD FOREIGN KEY (`biller_id`) REFERENCES `" . TB_PREFIX . "biller` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT; " .
                "ALTER TABLE `" . TB_PREFIX . "invoices` ADD FOREIGN KEY (`customer_id`) REFERENCES `" . TB_PREFIX . "customers` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` ADD FOREIGN KEY (`type_id`) REFERENCES `" . TB_PREFIX . "invoice_type` (`inv_ty_id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoices` ADD FOREIGN KEY (`preference_id`) REFERENCES `" . TB_PREFIX . "preferences` (`pref_id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_items` ADD FOREIGN KEY (`invoice_id`) REFERENCES `" . TB_PREFIX . "invoices` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_items` ADD FOREIGN KEY (`product_id`) REFERENCES `" . TB_PREFIX . "products` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_tax` ADD FOREIGN KEY (`tax_id`) REFERENCES `" . TB_PREFIX . "tax` (`tax_id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "invoice_item_attachments` ADD FOREIGN KEY (`invoice_item_id`) REFERENCES `" . TB_PREFIX . "invoice_items` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "log` ADD FOREIGN KEY (`user_id`) REFERENCES `" . TB_PREFIX . "user` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "payment` ADD FOREIGN KEY (`ac_inv_id`) REFERENCES `" . TB_PREFIX . "invoices` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "payment` ADD FOREIGN KEY (`ac_payment_type`) REFERENCES `" . TB_PREFIX . "payment_types` (`pt_id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "products` ADD FOREIGN KEY (`default_tax_id`) REFERENCES `" . TB_PREFIX . "tax` (`tax_id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "products` ADD FOREIGN KEY (`default_tax_id_2`) REFERENCES `" . TB_PREFIX . "tax` (`tax_id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "products_attributes` ADD FOREIGN KEY (`type_id`) REFERENCES `" . TB_PREFIX . "products_attribute_type` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "products_values` ADD FOREIGN KEY (`attribute_id`) REFERENCES `" . TB_PREFIX . "products_attributes` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "user` ADD FOREIGN KEY (`domain_id`) REFERENCES `" . TB_PREFIX . "user_domain` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;" .
                "ALTER TABLE `" . TB_PREFIX . "user` ADD FOREIGN KEY (`role_id`) REFERENCES `" . TB_PREFIX . "user_role` (`id`) ON UPDATE CASCADE ON DELETE RESTRICT;",
            'date' => "20190507",
            'source' => 'fearless359'
        ];
        self::makePatch('318', $patch);

        $patch = [
            'name' => "Add set_aging field to si_preferences",
            'patch' => "ALTER TABLE `" . TB_PREFIX . "preferences` ADD COLUMN `set_aging` BOOL NOT NULL DEFAULT  '0' AFTER `index_group`;" .
                "UPDATE `" . TB_PREFIX . "preferences` SET `set_aging` = 1 WHERE pref_id = '1';",
            'date' => "20200123",
            'source' => 'fearless359'
        ];
        self::makePatch('319', $patch);

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
        unset($tblExists);

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
        unset($tblExists);

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
        unset($tblExists);

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
        unset($foreignKeyExists);

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
        unset($tblExists);

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
