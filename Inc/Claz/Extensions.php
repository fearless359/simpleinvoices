<?php
namespace Inc\Claz;

/**
 * @name Extensions.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181106
 */

/**
 * Class Extensions
 * @package Inc\Claz
 */
class Extensions
{

    /**
     * @param int $id for extension record to retrieve.
     * @return array of selected rows
     */
    public static function getOne($id) {
        return self::getExtenstions($id);
    }

    /**
     * Get all rows from the extensions table.
     * @return array rows selected.
     */
    public static function getAll() {
        return self::getExtenstions();
    }

    /**
     * @return array All rows in the extensions table plus pseudo rows created
     *          for extension directories that are not yet registered (not in
     *          the table).
     */
    public static function getAllWithDirs() {
        return self::getExtenstions(null, true);
    }

    /**
     * Retrieve requested records from the extensions tables.
     * @param int $id If not null, id of specified record to retrieve.
     * @param bool $include_all_dirs (Defaults to false). If true, the records in the
     *          table plus psuedo records for extension directories not in the table
     *          (aka not registered), will be returned.
     * @return array row(s) selected from the extensions table. Note that rows in the
     *          table will have the registered field set to ENABLED whereas pseudo
     *          entries will have the registered field set to DISABLED.
     */
    private static function getExtenstions($id = null, $include_all_dirs = false) {
        global $LANG, $pdoDb;

        $extensions = array();
        try {
            $pdoDb->setOrderBy('name');
            if (isset($id)) {
                $pdoDb->addSimpleWhere('id', $id, 'AND');
            }
            $pdoDb->addToWhere(new WhereItem(true, 'domain_id', '=', 0, false, 'OR'));
            $pdoDb->addToWhere(new WhereItem(false, 'domain_id', '=', DomainId::get(), true));

            $ca = new CaseStmt("enabled", "enabled_text");
            $ca->addWhen( "=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setSelectList(array(
                'id',
                'domain_id',
                'name',
                'description',
                'enabled',
                new DbField(ENABLED, 'registered')
            ));

            $rows = $pdoDb->request('SELECT', 'extensions');
            if ($include_all_dirs) {
                // Add pseudo rows for extension directories not yet in the tabled (not registered).
                $extension_dir = 'extensions';
                $extension_entries = array_diff(scandir($extension_dir), Array(".", "..")); // Skip entries starting with a dot from dir list

                $available_extensions = Array();
                foreach ($extension_entries as $entry) {
                    if (file_exists($extension_dir . "/" . $entry . "/DESCRIPTION")) {
                        $description = file_get_contents($extension_dir . "/" . $entry . "/DESCRIPTION");
                    } else {
                        $description = "DESCRIPTION not available (in $extension_dir/$entry/)";
                    }

                    $available_extensions[$entry] = array(
                        "id" => 0,
                        "domain_id" => DomainId::get(),
                        "name" => $entry,
                        "description" => $description,
                        "enabled" => DISABLED,
                        "registered" => DISABLED,
                        "enabled_text" => $LANG['disabled']
                    );
                }

                // $rows (registered_extensions) have all extensions in the database
                // $available_extensions have all extensions in the distribution
                foreach ($rows as $row) {
                    $name = $row['name'];
                    if (isset($available_extensions[$name])) {
                        // This is a registered row (in the database) so drop from unregistered array
                        unset($available_extensions[$name]);
                    }
                }

                // $extensions set to a complete list of the extensions,
                // with status info (enabled, registered)
                $rows = array_merge($rows, $available_extensions);

                foreach ($rows as $row) {
                    $ext_row_name = $LANG['extensions'] . " " . $row['name'];
                    $row['plugin_registered'] = $LANG['plugin_register'] . ' ' . $ext_row_name;
                    $row['plugin_unregister'] = $LANG['plugin_unregister'] . ' ' . $ext_row_name;
                    $row['plugin_disable'] = $LANG['disable'] . ' ' . $ext_row_name;
                    $row['plugin_enable'] = $LANG['enable'] . ' ' . $ext_row_name;
                    $extensions[] = $row;
                }
            } else {
                $extensions = $rows;
            }
        } catch (PdoDbException $pde) {
            error_log("Extensions::getExtensions() - " . (isset($id) ? "id[$id] " : "") .
                "include_all_dirs[$include_all_dirs] - Error: " . $pde->getMessage());
        }

        if (empty($extensions)) {
            return array();
        }

        return (isset($id) ? $extensions[0] : $extensions);
    }

    /**
     * @param string $extension_name
     * @return int
     */
    public static function getExtensionId($extension_name) {
        global $pdoDb_admin;

        $rows = array();
        try {
            $pdoDb_admin->addSimpleWhere('name', $extension_name, 'AND');
            $pdoDb_admin->addToWhere(new WhereItem(true, 'domain_id', '=', 0, false, 'OR'));
            $pdoDb_admin->addToWhere(new WhereItem(false, 'domain_id', '=', DomainId::get(), true));

            $pdoDb_admin->setOrderBy(array('domain_id', 'D'));

            $pdoDb_admin->setLimit(1);

            $rows = $pdoDb_admin->request('SELECT', 'extensions');
        } catch (PdoDbException $pde) {
            error_log("Extensions::getExtensionId() - Error: " . $pde->getMessage());
        }

        if (empty($rows)) {
            return -2;
        }

        $extension_info = $rows[0];
        if ($extension_info['enabled'] != ENABLED) {
            return -1; // -1 = extension not enabled
        }
        return $extension_info['id']; // 0 = core, >0 is extension id
    }

    /**
     * @param $extension_id
     * @param int $status
     * @return bool
     */
    public static function setStatusExtension($extension_id, $status = 2) {
        global $pdoDb_admin;

        $domain_id = DomainId::get();

        // status=2 = toggle status
        if ($status == 2) {
            $rows = array();
            try {
                $pdoDb_admin->setSelectList('enabled');
                $pdoDb_admin->addSimpleWhere('id', $extension_id, 'AND');
                $pdoDb_admin->addSimpleWhere('domain_id', $domain_id);

                $pdoDb_admin->setLimit(1);

                $rows = $pdoDb_admin->request('SELECT', 'extensions');
            } catch (PdoDbException $pde) {
                error_log("Extensions::setStatusExtension() - Error: " . $pde->getMessage());
            }
            $extension_info = (empty($rows) ? $rows : $rows[0]);
            $status = 1 - $extension_info['enabled'];
        }

        $result = false;
        try {
            $pdoDb_admin->addSimpleWhere('id', $extension_id, 'AND');
            $pdoDb_admin->addSimpleWhere('domain_id', $domain_id);

            $pdoDb_admin->setFauxPost(array("enabled" => $status));

            $result = $pdoDb_admin->request("UPDATE", 'extensions');
        } catch (PdoDbException $pde) {
            error_log("Extensions::setStatusExtension() - Error(2): " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Load SI Extension information into $config->extension.
     * @param &array Reference to the extension names array.
     */
    public static function loadSiExtensions(&$ext_names, $config, $databaseBuilt, $patchCount) {
        global $pdoDb_admin;

        if ($databaseBuilt && $patchCount > "196") {
            $rows = array();
            try {
                $pdoDb_admin->addSimpleWhere("domain_id", DomainId::get(), "OR");
                $pdoDb_admin->addSimpleWhere("domain_id", 0);
                $pdoDb_admin->setOrderBy("domain_id");
                $rows = $pdoDb_admin->request("SELECT", "extensions");
            } catch (PdoDbException $pde) {
                error_log("loadSiExtensions() - Error: " . $pde->getMessage());
            }
            $extensions = array();
            foreach ($rows as $extension) {
                $extensions[$extension['name']] = $extension;
            }
            $config->extension = $extensions;
        }

        // If no extension loaded, load Core
        if (!$config->extension) {
            // @formatter:off
        $extension_core = new Zend_Config(
            array('core' => array('id'         => 1,
                                  'domain_id'  => 1,
                                  'name'       => 'core',
                                  'description'=> 'Core part of SimpleInvoices - always enabled',
                                  'enabled'    => 1)));
        $config->extension = $extension_core;
        // @formatter:on
        }

        // Populate the array of enabled extensions.
        $ext_names = array();
        foreach ($config->extension as $extension) {
            if ($extension->enabled == "1") {
                $ext_names[] = $extension->name;
            }
        }
    }

    /**
     * Insert a new record in the extensions table.
     * @return int ID assigned to new record, 0 is insert failed.
     */
    public static function insert() {
        global $pdoDb;

        $result = 0;
        try {
            $pdoDb->setFauxPost(array(
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'domain_id' => DomainId::get(),
                'enabled' => DISABLED
            ));
            $result = $pdoDb->request('INSERT', 'extensions');
        } catch (PdoDbException $pde) {
            error_log("Extensions::insert() - Error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Delete a specified record from the extensions table.
     * @param $id
     * @return bool true if delete succeeded, otherwise false.
     */
    public static function delete($id) {
        global $pdoDb;

        $result = false;
        try {
            $pdoDb->addSimpleWhere('id', $id, 'AND');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());
            $result = $pdoDb->request('DELETE', 'extensions');
        } catch (PdoDbException $pde) {
            error_log("Extensions::delete(): id[$id] - error: " . $pde->getMessage());
        }
        return $result;
    }
}