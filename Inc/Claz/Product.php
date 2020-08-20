<?php

namespace Inc\Claz;

/**
 * Class Product
 * @package Inc\Claz
 */
class Product
{
    /**
     * @return int count of product rows
     */
    public static function count(): int
    {
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
    public static function getOne(int $id): array
    {
        return self::getProducts($id);
    }

    /**
     * @param bool $enabled true, if only enabled rows should be selected,
     *              false (default) if all rows should be selected.
     * @return array Product rows selected or empty array if none.
     */
    public static function getAll(bool $enabled = false): array
    {
        return self::getProducts(null, $enabled);
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @param bool $inventory Set true if inventory is enabled; false if not.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(bool $inventory): array
    {
        global $config;

        $rows = self::getProducts();
        $tableRows = [];
        foreach ($rows as $row) {
            // @formatter:off
            $action = "<a class='index_table' title=\"{$row['vname']}\" " .
                         "href=\"index.php?module=products&amp;view=details&amp;id={$row['id']}&amp;action=view\">" .
                          "<img src=\"images/view.png\" class=\"action\" alt=\"{$row['vname']}\" />" .
                      "</a>" .
                      "<a class=\"index_table\" title=\"{$row['ename']}\" " .
                         "href=\"index.php?module=products&amp;view=details&amp;id={$row['id']}&amp;action=edit\">" .
                          "<img src=\"images/edit.png\" class=\"action\" alt=\"{$row['ename']}\" />" .
                      "</a>";

            $image = $row['enabled'] == ENABLED ? "images/tick.png" : "images/cross.png";
            $enabled = "<span style=\"display: none\">{$row['enabled_text']}</span>" .
                       "<img src=\"{$image}\" alt=\"{$row['enabled_text']}\" title=\"{$row['enabled_text']}\" />";

            if ($inventory) {
                $tableRows[] = [
                    'action' => $action,
                    'description' => $row['description'],
                    'unit_price' => $row['unit_price'],
                    'quantity' => isset($row['quantity']) ? $row['quantity'] : '0',
                    'enabled' => $enabled,
                    'currency_code' => $config ->local->currency_code,
                    'locale' => preg_replace('/^(.*)_(.*)$/','$1-$2', $config->local->locale)
                ];
            } else {
                $tableRows[] = [
                    'action' => $action,
                    'description' => $row['description'],
                    'unit_price' => $row['unit_price'],
                    'enabled' => $enabled,
                    'currency_code' => $config ->local->currency_code,
                    'locale' => preg_replace('/^(.*)_(.*)$/','$1-$2', $config->local->locale)
                ];
            }
            // @formatter:on
        }
        return $tableRows;
    }

    /**
     * Accessor for product table records.
     * @param int|null $id If not null, the id of the product record to retrieve
     *          subject to other parameters.
     * @param bool $enabled If true, only enabled records are retrieved.
     *          If false (default), all records are retrieved subject to
     *          other parameters.
     * @return array
     */
    private static function getProducts(?int $id = null, bool $enabled = false): array
    {
        global $LANG, $pdoDb;

        $products = [];
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
            $wc->addSimpleItem('inv.domain_id', new DbField('p.domain_id'));
            $se = new Select($fn, $fr, $wc, null, 'qty_in');
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt('COALESCE', 'p.reorder_level,0');
            $se = new Select($fn, null, null, null, 'reorder_level');
            $pdoDb->addToSelectStmts($se);

            $fn = new FunctionStmt('', 'qty_in');
            $fn->addPart('-', 'qty_out');
            $se = new Select($fn, null, null, null, 'quantity');
            $pdoDb->addToSelectStmts($se);

            $ca = new CaseStmt('p.enabled', 'enabled_text');
            $ca->addWhen('=', ENABLED, $LANG['enabled']);
            $ca->addWhen('!=', ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setOrderBy([
                ['p.enabled', 'D'],
                ['p.description', 'A'],
                ['id', 'A']
            ]);

            $pdoDb->setSelectAll(true);

            $rows = $pdoDb->request('SELECT', 'products', 'p');
            foreach ($rows as $row) {
                $row['vname'] = $LANG['view'] . ' ' . $row['description'];
                $row['ename'] = $LANG['edit'] . ' ' . $row['description'];
                $row['image'] = $row['enabled'] == ENABLED ? "images/tick.png" : "images/cross.png";
                $products[] = $row;
            }
        } catch (PdoDbException $pde) {
            error_log("Product::xmlSql() - error: " . $pde->getMessage());
        }
        if (empty($products)) {
            return [];
        }

        return isset($id) ? $products[0] : $products;
    }

    /**
     * Insert a new record in the products table.
     * @param int $enabled Product enabled/disabled status used if not present in
     *        the <b>$_POST</b> array. Defaults to ENABLED (1) or set to DISABLED (0).
     * @param int $visible Flags record seen in list. Defaults to ENABLED (1) for
     *        visible or DISABLED (0) for not visible.
     * @return int New ID if insert OK. 0 if insert failed.
     */
    public static function insertProduct(int $enabled = ENABLED, int $visible = ENABLED): int
    {
        global $pdoDb;

        if (isset($_POST['enabled'])) {
            $enabled = $_POST['enabled'];
        }

        try {
            $attributes = $pdoDb->request("SELECT", "products_attributes");
        } catch (PdoDbException $pde) {
            error_log("Products::insertProduct() - Error: " . $pde->getMessage());
            return false;
        }

        $attr = [];
        foreach ($attributes as $val) {
            if (isset($_POST['attribute' . $val['id']]) && $_POST['attribute' . $val['id']] == 'true') {
                $attr[$val['id']] = $_POST['attribute' . $val['id']];
            }
        }

        $notesAsDescription = isset($_POST['notes_as_description']) && $_POST['notes_as_description'] == 'true' ? 'Y' : null;
        $showDescription = isset($_POST['show_description']) && $_POST['show_description'] == 'true' ? 'Y' : null;

        $customFlags = '0000000000';
        for ($idx = 1; $idx <= 10; $idx++) {
            if (isset($_POST['custom_flags_' . $idx]) && $_POST['custom_flags_' . $idx] == ENABLED) {
                $customFlags = substr_replace($customFlags, ENABLED, $idx - 1, 1);
            }
        }

        $result = 0;
        try {
            $description = isset($_POST['description']) ? $_POST['description'] : "";
            $unitPrice = isset($_POST['unit_price']) ? SiLocal::dbStd($_POST['unit_price']) : "0";
            $cost = isset($_POST['cost']) ? SiLocal::dbStd($_POST['cost']) : "0";
            $fauxPost = [
                'domain_id' => DomainId::get(),
                'description' => $description,
                'unit_price' => $unitPrice,
                'cost' => $cost,
                'reorder_level' => isset($_POST['reorder_level']) ? $_POST['reorder_level'] : "0",
                'custom_field1' => isset($_POST['custom_field1']) ? $_POST['custom_field1'] : "",
                'custom_field2' => isset($_POST['custom_field2']) ? $_POST['custom_field2'] : "",
                'custom_field3' => isset($_POST['custom_field3']) ? $_POST['custom_field3'] : "",
                'custom_field4' => isset($_POST['custom_field4']) ? $_POST['custom_field4'] : "",
                'notes' => isset($_POST['notes']) ? $_POST['notes'] : "",
                'default_tax_id' => empty($_POST['default_tax_id']) ? null : $_POST['default_tax_id'],
                'custom_flags' => $customFlags,
                'enabled' => $enabled,
                'visible' => $visible,
                'attribute' => json_encode($attr),
                'notes_as_description' => $notesAsDescription,
                'show_description' => $showDescription
            ];
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
    public static function updateProduct(): bool
    {
        global $pdoDb;

        try {
            if (($attributes = $pdoDb->request("SELECT", "products_attributes")) === false) {
                return false;
            }

            $attr = [];
            foreach ($attributes as $val) {
                $tmp = isset($_POST['attribute' . $val['id']]) ? $_POST['attribute' . $val['id']] : "";
                if ($tmp == 'true') {
                    $attr[$val['id']] = $tmp;
                }
            }

            // @formatter:off
            $notesAsDescription = isset($_POST['notes_as_description']) && $_POST['notes_as_description'] == 'true' ? 'Y' : NULL;
            $showDescription     = isset($_POST['show_description'])     && $_POST['show_description']     == 'true' ? 'Y' : NULL;

            $customFlags = '0000000000';
            for ($idx = 1; $idx <= 10; $idx++) {
                if (isset($_POST['custom_flags_' . $idx]) && $_POST['custom_flags_' . $idx] == ENABLED) {
                    $customFlags = substr_replace($customFlags, ENABLED, $idx - 1, 1);
                }
            }

            $unitPrice = isset($_POST['unit_price']) ? SiLocal::dbStd($_POST['unit_price']) : "0";
            $cost       = isset($_POST['cost'])       ? SiLocal::dbStd($_POST['cost'])       : "0";
            $fauxPost = [
                'description'          => isset($_POST['description'])    ? $_POST['description']    : "",
                'enabled'              => isset($_POST['enabled'])        ? $_POST['enabled']        : "",
                'notes'                => isset($_POST['notes'])          ? $_POST['notes']          : "",
                'default_tax_id'       => isset($_POST['default_tax_id']) ? $_POST['default_tax_id'] : "",
                'custom_field1'        => isset($_POST['custom_field1'])  ? $_POST['custom_field1']  : "",
                'custom_field2'        => isset($_POST['custom_field2'])  ? $_POST['custom_field2']  : "",
                'custom_field3'        => isset($_POST['custom_field3'])  ? $_POST['custom_field3']  : "",
                'custom_field4'        => isset($_POST['custom_field4'])  ? $_POST['custom_field4']  : "",
                'custom_flags'         => $customFlags,
                'unit_price'           => $unitPrice,
                'cost'                 => $cost,
                'reorder_level'        => isset($_POST['reorder_level'])  ? $_POST['reorder_level']  : "0",
                'attribute'            => json_encode($attr),
                'notes_as_description' => $notesAsDescription,
                'show_description'     => $showDescription
            ];
            $pdoDb->setFauxPost($fauxPost);

            $pdoDb->addSimpleWhere("id", $_GET['id'], "AND");
            $pdoDb->addSimpleWhere("domain_id", DomainId::get());

            $pdoDb->setExcludedFields(["id", "domain_id"]);
            // @formatter:on

            $result = $pdoDb->request("UPDATE", "products");
        } catch (PdoDbException $pde) {
            error_log("Product::updateProduct() - Database error: " . $pde->getMessage());
            $result = false;
        }
        return $result;
    }
}
