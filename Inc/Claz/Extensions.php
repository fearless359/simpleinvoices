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
//
//    /**
//     * Global function to see if an extension is enabled.
//     * @param string $ext_name Name of the extension to check for.
//     * @return true if enabled, false if not.
//     */
//    public static function isExtensionEnabled($ext_name) {
//        global $ext_names;
//        $enabled = false;
//        foreach ($ext_names as $name) {
//            if ($name == $ext_name) {
//                $enabled = true;
//                break;
//            }
//        }
//        return $enabled;
//    }

    /**
     * @param string $extension_name
     * @return int
     */
    public static function getExtensionID($extension_name) {
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
            error_log("Extensions::getExtensionID() - Error: " . $pde->getMessage());
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
     * @param int $id for extension record to retrieve.
     * @return array of selected rows
     */
    public static function get($id) {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->addSimpleWhere("id", $id, "AND");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $pdoDb->setSelectList(array("name", "description"));
            $rows = $pdoDb->request("SELECT", "extensions");
        } catch (PdoDbException $pde) {
            error_log("Extensions::get(): id[$id] - error: " . $pde->getMessage());
        }

        return (empty($rows) ? $rows : $rows[0]);
    }

    /**
     * Get all rows from the extensions table.
     * @return array rows selected.
     */
    public static function getAll() {
        global $pdoDb;

        $rows = array();
        try {
            $pdoDb->addSimpleWhere('domain_id', 0, 'OR');
            $pdoDb->addSimpleWhere('domain_id', DomainId::get());

            $pdoDb->setOrderBy('name');

            $rows = $pdoDb->request('SELECT', 'extensions');
        } catch (PdoDbException $pde) {
            error_log("modules/extensions/manager.php - getExtensions() - Error: " . $pde->getMessage());
        }

        return $rows;
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

    /**
     * @param string $dir order by direction (ASC/DESC).
     * @param string $sort field to order rows by.
     * @param int $rp Number of rows per page; default is 25.
     * @param int $page Page number that we are displaying.
     * @return array rows selected from the extensions table.
     */
    public static function xmlSql($dir, $sort, $rp, $page) {
        global $pdoDb;

        if (intval($rp) != $rp) {
            $rp = 25;
        }

        if (intval($page) != $page) {
            $page = 1;
        }

        $start = ($page - 1) * $rp;

        if (!preg_match('/^(asc|desc)$/iD', $dir)) {
            $dir = 'ASC';
        }
        if (!in_array($sort, array('id','name','description','enabled'))) {
            $sort = 'id';
        }

        $rows = array();
        try {
            $pdoDb->setOrderBy(array($sort, $dir));

            $pdoDb->setLimit($rp, $start);

            if (!empty($_POST['query']) && !empty($_POST['qtype'])) {
                $query = $_POST['query'];
                $qtype = $_POST['qtype'];
                if (in_array($qtype, array('id', 'name', 'description'))) {
                    $pdoDb->addToWhere(new WhereItem(false, $qtype, 'LIKE', $query, false, 'AND'));
                }
            }
            $pdoDb->addToWhere(new WhereItem(true, 'domain_id', '=', 0, false, 'OR'));
            $pdoDb->addToWhere(new WhereItem(false, 'domain_id', '=', DomainId::get(), true));

            $pdoDb->setSelectList(array('id', 'name', 'description', 'enabled', new DbField(1, 'registered')));

            $rows = $pdoDb->request('SELECT', 'extensions');
        } catch (PdoDbException $pde) {
            error_log("modules/extensions/xml.php - error: " . $pde->getMessage());
        }

        return $rows;
    }
}