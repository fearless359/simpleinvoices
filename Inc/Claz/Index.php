<?php

namespace Inc\Claz;

/**
 * Class: Index
 * Replacement for primary keys as the ID field in various tables - ie. si_invoices
 * $node = this is the module in question - ie 'invoice', 'products' etc..
 * $subNode = the sub set of the node - ie. this is the 'invoice preference' if node = 'invoice'
 * $subNode2 = 2nd sub set of the node - ir. this is the 'biller' if node = 'invoice'
 */
class Index
{

    /**
     * Increment the specified record value.
     * @param string $node
     * @param int $subNode
     * @param int|null $domainId
     * @param int $subNode2
     * @return int Value just assigned.
     * @throws PdoDbException
     */
    public static function increment(string $node, int $subNode = 0, ?int $domainId = null, int $subNode2 = 0): int
    {
        global $pdoDbAdmin;

        $domId = DomainId::get($domainId);
        $next = self::next($node, $subNode, $domId, $subNode2);
        try {
            if ($next == 1) {
                $pdoDbAdmin->setFauxPost([
                    "id" => $next,
                    "node" => $node,
                    "sub_node" => $subNode,
                    "sub_node_2" => $subNode2,
                    "domain_id" => $domId
                ]);
                $pdoDbAdmin->request("INSERT", "index");
            } else {
                $pdoDbAdmin->addSimpleWhere("node", $node, "AND");
                $pdoDbAdmin->addSimpleWhere("sub_node", $subNode, "AND");
                $pdoDbAdmin->addSimpleWhere("sub_node_2", $subNode2, "AND");
                $pdoDbAdmin->addSimpleWhere("domain_id", $domId);
                $pdoDbAdmin->setFauxPost(["id" => $next]);
                $pdoDbAdmin->setExcludedFields(["node", "sub_node", "sub_node_2", "domain_id"]);
                $pdoDbAdmin->request("UPDATE", "index");
            }
        } catch (PdoDbException $pde) {
            error_log("Index::increment() - Error: " . $pde->getMessage());
            throw $pde;
        }
        return $next;
    }

    /**
     * Create a new si_index record.
     * @param int $id Last assigned invoice index_id.
     * @param int $subNode ID for the preference record of this index.
     * @throws PdoDbException if insert fails
     */
    public static function insert(int $id, int $subNode) {
        global $pdoDbAdmin;

        try {
            $pdoDbAdmin->setFauxPost([
                "id"         => $id,
                "node"       => "invoice",
                "sub_node"   => $subNode,
                "sub_node_2" => 0,
                "domain_id"  => DomainId::get()
            ]);
            $pdoDbAdmin->request("INSERT", "index");
        } catch(PdoDbException $pde) {
            error_log("Index::insert() - Error: " . $pde->getMessage());
            throw $pde;
        }
    }

    /**
     * Get next value to be assigned (but don't assign it).
     * @param string $node Unique name of node to obtain value for (ex: "Invoice").
     * @param int $subNode
     * @param int|null $domainId
     * @param int $subNode2
     * @return int Next value to assign. If <b>1</b> is returned, then no record exists
     *         for the specified values.
     * @throws PdoDbException
     */
    public static function next(string $node, int $subNode = 0, ?int $domainId = null, int $subNode2 = 0): int
    {
        global $pdoDbAdmin;

        try {
            $pdoDbAdmin->addSimpleWhere("node", $node, "AND");
            $pdoDbAdmin->addSimpleWhere("sub_node", $subNode, "AND");
            $pdoDbAdmin->addSimpleWhere("sub_node_2", $subNode2, "AND");
            $pdoDbAdmin->addSimpleWhere("domain_id", DomainId::get($domainId));
            $pdoDbAdmin->setSelectList("id");
            $rows = $pdoDbAdmin->request("SELECT", "index");
            if (empty($rows)) {
                $id = 1;
            } else {
                $id = $rows[0]['id'] + 1;
            }
        } catch (PdoDbException $pde) {
            error_log("Index::next() - Error: " . $pde->getMessage());
            throw $pde;
        }

        return $id;
    }

    /**
     * Decrement the specified record value.
     * @param string $node
     * @param int $subNode
     * @param int|null $domainId
     * @param int $subNode2
     * @return bool <b>true</b> if request processed; <b>false</b> if not.
     * @throws PdoDbException
     */
    public static function rewind(string $node, int $subNode = 0, ?int $domainId = null, int $subNode2 = 0): bool
    {
        global $pdoDbAdmin;

        try {
            $pdoDbAdmin->addSimpleWhere("node", $node, "AND");
            $pdoDbAdmin->addSimpleWhere("sub_node", $subNode, "AND");
            $pdoDbAdmin->addSimpleWhere("sub_node_2", $subNode2, "AND");
            $pdoDbAdmin->addSimpleWhere("domain_id", DomainId::get($domainId));
            $pdoDbAdmin->addToFunctions("id = (id - 1)");
            $pdoDbAdmin->setExcludedFields(["node", "sub_node", "sub_node_2", "domain_id"]);
            $result = $pdoDbAdmin->request("UPDATE", "index");
        } catch (PdoDbException $pde) {
            error_log("Index::rewind() - Error: " . $pde->getMessage());
            throw $pde;
        }
        return $result;
    }
}
