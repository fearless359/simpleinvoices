<?php
namespace Inc\Claz;

/**
 * Class: Index
 * Replacement for primary keys as the ID field in various tables - ie. si_invoices
 * $node = this is the module in question - ie 'invoice', 'products' etc..
 * $sub_node = the sub set of the node - ie. this is the 'invoice preference' if node = 'invoice'
 * $sub_node_2 = 2nd sub set of the node - ir. this is the 'biller' if node = 'invoice'
 */
class Index {

    /**
     * Get next value to be assigned (but don't assign it).
     * @param string $node Unique name of node to obtain value for (ex: "Invoice").
     * @param int $sub_node
     * @param string $domain_id
     * @param int $sub_node_2
     * @return number Next value to assign. If <b>1</b> is returned, then no record exists
     *         for the specified values.
     */
    public static function next($node, $sub_node = 0, $domain_id = '', $sub_node_2 = 0) {
        global $pdoDb_admin;

        $id = 1;
        try {
            $pdoDb_admin->addSimpleWhere("node", $node, "AND");
            $pdoDb_admin->addSimpleWhere("sub_node", $sub_node, "AND");
            $pdoDb_admin->addSimpleWhere("sub_node_2", $sub_node_2, "AND");
            $pdoDb_admin->addSimpleWhere("domain_id", DomainId::get($domain_id));
            $pdoDb_admin->setSelectList("id");
            $rows = $pdoDb_admin->request("SELECT", "index");
            if (empty($rows)) $id = 1;
            else              $id = $rows[0]['id'] + 1;
        } catch (PdoDbException $pde) {
            error_log("Index::next() - Error: " . $pde->getMessage());
        }

        return $id;
    }

    /**
     * Increment the specified record value.
     * @param string $node
     * @param int $sub_node
     * @param string $domain_id
     * @param int $sub_node_2
     * @return int Value just assigned.
     */
    public static function increment($node, $sub_node = 0, $domain_id = '', $sub_node_2 = 0) {
        global $pdoDb_admin;

        $domain_id = DomainId::get($domain_id);
        $next = self::next ( $node, $sub_node, $domain_id, $sub_node_2 );
        try {
            if ($next == 1) {
                $pdoDb_admin->setFauxPost(array(
                    "id" => $next,
                    "node" => $node,
                    "sub_node" => $sub_node,
                    "sub_node_2" => $sub_node_2,
                    "domain_id" => $domain_id));
                $pdoDb_admin->request("INSERT", "index");
            } else {
                $pdoDb_admin->addSimpleWhere("node", $node, "AND");
                $pdoDb_admin->addSimpleWhere("sub_node", $sub_node, "AND");
                $pdoDb_admin->addSimpleWhere("sub_node_2", $sub_node_2, "AND");
                $pdoDb_admin->addSimpleWhere("domain_id", $domain_id);
                $pdoDb_admin->setFauxPost(array("id" => $next));
                $pdoDb_admin->setExcludedFields(array("node", "sub_node", "sub_node_2", "domain_id"));
                $pdoDb_admin->request("UPDATE", "index");
            }
        } catch (PdoDbException $pde) {
            error_log("Index::increment() - Error: " . $pde->getMessage());
        }
        return $next;
    }

    /**
     * Decrement the specified record value.
     * @param string $node
     * @param int $sub_node
     * @param string $domain_id
     * @param int $sub_node_2
     * @return boolean <b>true</b> if request processed; <b>false</b> if not.
     */
    public static function rewind($node, $sub_node = 0, $domain_id = '', $sub_node_2 = 0) {
        global $pdoDb_admin;
        $domain_id = DomainId::get($domain_id);

        $result = false;
        try {
            $pdoDb_admin->addSimpleWhere("node", $node, "AND");
            $pdoDb_admin->addSimpleWhere("sub_node", $sub_node, "AND");
            $pdoDb_admin->addSimpleWhere("sub_node_2", $sub_node_2, "AND");
            $pdoDb_admin->addSimpleWhere("domain_id", $domain_id);
            $pdoDb_admin->addToFunctions("id = (id - 1)");
            $pdoDb_admin->setExcludedFields(array("node", "sub_node", "sub_node_2", "domain_id"));
            $result = $pdoDb_admin->request("UPDATE", "index");
        } catch (PdoDbException $pde) {
            error_log("Index::rewind() - Error: " . $pde->getMessage());
        }
        return $result;
    }
}
