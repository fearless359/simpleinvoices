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