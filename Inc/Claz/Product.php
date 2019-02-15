<?php
namespace Inc\Claz;

/**
 * Class Product
 * @package Inc\Claz
 */
class Product {
    /**
     * @return int count of product rows
     */
    public static function count() {
        global $pdoDb;
        $count = 0;
        try {
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());
            $rows = $pdoDb->request("SELECT", "products");
            $count = count($rows);
        } catch (PdoDbException $pde) {
            error_log("Product::count() - error: " . $pde->getMessage());
        }
        return $count;
    }

    /**
     * @param int $id of product record to select.
     * @return array select row or empty array if row not found.
     */
    public static function getOne($id) {
        return self::getProducts($id);
    }

    /**
     * @param bool $enabled true, if only enabled rows should be selected,
     *              false (default) if all rows should be selected.
     * @return array Product rows selected or empty array if none.
     */
    public static function getAll($enabled = false) {
        return self::getProducts(null, $enabled);
    }

    /**
     * Accessor for product table records.
     * @param int $id If not null, the id of the product record to retrieve
     *          subject to other parameters.
     * @param bool $enabled If true, only enabled records are retrieved.
     *          If false (default), all records are retrieved subject to
     *          other parameters.
     * @return array
     */
    private static function getProducts($id = null, $enabled = false) {
        global $LANG, $pdoDb;

        $products = array();
        try {
            if (isset($id)) {
                $pdoDb->addSimpleWhere('id', $id, 'AND');
            }

            if ($enabled) {
                $pdoDb->addSimpleWhere('enabled', ENABLED, 'AND');
            }

            $pdoDb->addSimpleWhere('p.domain_id', DomainId::get());

            $fn = new FunctionStmt('COALESCE', 'SUM(ii.quantity),0');
            $fr = new FromStmt('invoice_items', 'ii');
            $fr->addTable('invoices', 'iv');
            $fr->addTable('preferences', 'pr');
            $wh = new WhereClause();
            $wh->addSimpleItem('ii.product_id', new DbField('p.id'), 'AND');
            $wh->addSimpleItem('ii.domain_id', new DbField('p.domain_id'), 'AND');
            $wh->addSimpleItem('ii.invoice_id', new DbField('iv.id'), 'AND');
            $wh->addSimpleItem('iv.preference_id', new DbField('pr.pref_id'), 'AND');
            $wh->addSimpleItem('pr.status', ENABLED);
            $se = new Select($fn, $fr, $wh, null, 'qty_out');
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt('SUM', 'COALESCE(inv.quantity,0)');
            $fr = new FromStmt('inventory', 'inv');
            $wc = new WhereClause();
            $wc->addSimpleItem('inv.product_id', new DbField('p.id'), 'AND');
            $wc->addSimpleItem('inv.domain_id' , new DbField('p.domain_id'));
            $se = new Select($fn, $fr, $wc, null, 'qty_in');
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt('COALESCE', 'p.reorder_level,0');
            $se = new Select($fn, null, null, null, 'reorder_level');
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt('', 'qty_in');
            $fn->addPart('-',  'qty_out');
            $se = new Select($fn, null, null, null, 'quantity');
            $pdoDb->addToSelectStmts($se);

            $ca = new CaseStmt('p.enabled', 'enabled_text');
            $ca->addWhen( '=', ENABLED, $LANG['enabled']);
            $ca->addWhen('!=', ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setOrderBy(array(
                array('p.enabled', 'D'),
                array('p.description', 'A'),
                array('id', 'A')
            ));

            $pdoDb->setSelectAll(true);

            $rows = $pdoDb->request('SELECT', 'products', 'p');
            foreach ($rows as $row) {
                $row['vname'] = $LANG['view'] . ' ' . $row['description'];
                $row['ename'] = $LANG['edit'] . ' ' . $row['description'];
                $row['image'] = ($row['enabled'] == ENABLED ? "images/common/tick.png" :
                                                              "images/common/cross.png");
                $products[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Product::xmlSql() - error: " . $pde->getMessage());
        }
        if (empty($products)) {
            return array();
        }

        return (isset($id) ? $products[0] : $products);
    }

    /**
     * Insert a new record in the products table.
     * @param int $enabled Product enabled/disabled status used if not present in
     *        the <b>$_POST</b> array. Defaults to ENABLED (1) or set to DISABLED (0).
     * @param int $visible Flags record seen in list. Defaults to ENABLED (1) for
     *        visible or DISABLED (0) for not visible.
     * @return int New ID if insert OK. 0 if insert failed.
     */
    public static function insertProduct($enabled=ENABLED, $visible=ENABLED) {
        global $pdoDb;

        if (isset($_POST['enabled'])) $enabled = $_POST['enabled'];

        try {
            $attributes = $pdoDb->request("SELECT", "products_attributes");
        } catch (PdoDbException $pde) {
            error_log("Products::insertProduct() - Error: " . $pde->getMessage());
            return false;
        }

        $attr = array();
        foreach ($attributes as $v) {
            if (isset($_POST['attribute' . $v['id']]) && $_POST['attribute' . $v['id']] == 'true') {
                $attr[$v['id']] = $_POST['attribute' . $v['id']];
            }
        }

        $notes_as_description = (isset($_POST['notes_as_description']) && $_POST['notes_as_description'] == 'true' ? 'Y' : null);
        $show_description     = (isset($_POST['show_description']    ) && $_POST['show_description'    ] == 'true' ? 'Y' : null);

        $custom_flags = '0000000000';
        for ($i = 1; $i <= 10; $i++) {
            if (isset($_POST['custom_flags_' . $i]) && $_POST['custom_flags_' . $i] == ENABLED) {
                $custom_flags = substr_replace($custom_flags, ENABLED, $i - 1, 1);
            }
        }

        $result = 0;
        try {
            $description = (isset($_POST['description']) ? $_POST['description'] : "");
            $unit_price = (isset($_POST['unit_price']) ? SiLocal::dbStd($_POST['unit_price']) : "0");
            $cost = (isset($_POST['cost']) ? SiLocal::dbStd($_POST['cost']) : "0");
            $fauxPost = array(
                'domain_id' => DomainId::get(),
                'description' => $description,
                'unit_price' => $unit_price,
                'cost' => $cost,
                'reorder_level' => (isset($_POST['reorder_level']) ? $_POST['reorder_level'] : "0"),
                'custom_field1' => (isset($_POST['custom_field1']) ? $_POST['custom_field1'] : ""),
                'custom_field2' => (isset($_POST['custom_field2']) ? $_POST['custom_field2'] : ""),
                'custom_field3' => (isset($_POST['custom_field3']) ? $_POST['custom_field3'] : ""),
                'custom_field4' => (isset($_POST['custom_field4']) ? $_POST['custom_field4'] : ""),
                'notes' => (isset($_POST['notes']) ? $_POST['notes'] : ""),
                'default_tax_id' => (isset($_POST['default_tax_id']) ? $_POST['default_tax_id'] : ""),
                'custom_flags' => $custom_flags,
                'enabled' => $enabled,
                'visible' => $visible,
                'attribute' => json_encode($attr),
                'notes_as_description' => $notes_as_description,
                'show_description' => $show_description);
            $pdoDb->setFauxPost($fauxPost);
            $pdoDb->setExcludedFields("id");

            $result = $pdoDb->request("INSERT", "products");
            if ($result == 0) {
                error_log("Products::insertProduct() - Unable to store products description[$description]");
            }
        } catch (PdoDbException $pde) {
            error_log("Products::insertProduct() - description[$description] - error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Update a product record.
     * @return bool true if update succeeded, false if not.
     */
    public static function updateProduct() {
        global $pdoDb;

        try {
            if (($attributes = $pdoDb->request("SELECT", "products_attributes")) === false) return false;

            $attr = array();
            foreach ($attributes as $v) {
                $tmp = (isset($_POST['attribute' . $v['id']]) ? $_POST['attribute' . $v['id']] : "");
                if ($tmp == 'true') {
                    $attr[$v['id']] = $tmp;
                }
            }

            // @formatter:off
            $notes_as_description = (isset($_POST['notes_as_description']) && $_POST['notes_as_description'] == 'true' ? 'Y' : NULL);
            $show_description     = (isset($_POST['show_description'])     && $_POST['show_description']     == 'true' ? 'Y' : NULL);

            $custom_flags = '0000000000';
            for ($i = 1; $i <= 10; $i++) {
                if (isset($_POST['custom_flags_' . $i]) && $_POST['custom_flags_' . $i] == ENABLED) {
                    $custom_flags = substr_replace($custom_flags, ENABLED, $i - 1, 1);
                }
            }

            $unit_price = (isset($_POST['unit_price']) ? SiLocal::dbStd($_POST['unit_price']) : "0");
            $cost       = (isset($_POST['cost'])       ? SiLocal::dbStd($_POST['cost'])       : "0");
            $fauxPost = array('description'          => (isset($_POST['description'])    ? $_POST['description']    : ""),
                              'enabled'              => (isset($_POST['enabled'])        ? $_POST['enabled']        : ""),
                              'notes'                => (isset($_POST['notes'])          ? $_POST['notes']          : ""),
                              'default_tax_id'       => (isset($_POST['default_tax_id']) ? $_POST['default_tax_id'] : ""),
                              'custom_field1'        => (isset($_POST['custom_field1'])  ? $_POST['custom_field1']  : ""),
                              'custom_field2'        => (isset($_POST['custom_field2'])  ? $_POST['custom_field2']  : ""),
                              'custom_field3'        => (isset($_POST['custom_field3'])  ? $_POST['custom_field3']  : ""),
                              'custom_field4'        => (isset($_POST['custom_field4'])  ? $_POST['custom_field4']  : ""),
                              'custom_flags'         => $custom_flags,
                              'unit_price'           => $unit_price,
                              'cost'                 => $cost,
                              'reorder_level'        => (isset($_POST['reorder_level'])  ? $_POST['reorder_level']  : "0"),
                              'attribute'            => json_encode($attr),
                              'notes_as_description' => $notes_as_description,
                              'show_description'     => $show_description);
            $pdoDb->setFauxPost($fauxPost);

            $pdoDb->addSimpleWhere("id", $_GET['id'], "AND");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $pdoDb->setExcludedFields(array("id", "domain_id"));
            // @formatter:on

            $result = $pdoDb->request("UPDATE", "products");
        } catch (PdoDbException $pde) {
            error_log("Product::updateProduct() - Database error: " . $pde->getMessage());
            $result = false;
        }
        return $result;
    }
}
