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
    public static function getOne(int $id): array
    {
        return self::getExtensions($id);
    }

    /**
     * Get all rows from the extensions table.
     * @return array rows selected.
     */
    public static function getAll(): array
    {
        return self::getExtensions();
    }

    /**
     * @return array All rows in the extensions table plus pseudo rows created
     *          for extension directories that are not yet registered (not in
     *          the table).
     */
    public static function getAllWithDirs(): array
    {
        return self::getExtensions(null, true);
    }

    /**
     * Minimize the amount of data returned to the manage table.
     * @return array Data for the manage table rows.
     */
    public static function manageTableInfo(): array
    {
        global $LANG;

        $rows = self::getAllWithDirs();
        $tableRows = [];
        foreach ($rows as $row) {
            $plugins = [];
            $plugins[] = "<img src=\"images/plugin_disabled.png\" alt=\"{$LANG['pluginNotRegistered']}\" title=\"{$LANG['pluginNotRegistered']}\" />";
            $plugins[] = "<img src=\"images/plugin.png\"          alt=\"{$LANG['pluginRegistered']}\" title=\"{$LANG['pluginRegistered']}\" />";
            $plugins[] = "<img src=\"images/plugin_delete.png\"   alt=\"{$LANG['pluginUnregister']}\" title=\"{$LANG['pluginUnregister']}\" />";
            $plugins[] = "<img src=\"images/plugin_add.png\"      alt=\"{$LANG['pluginRegister']}\" title=\"{$LANG['pluginRegister']}\" />";

            $lights = [];
            $lights[] = "<img src=\"images/lightbulb_off.png\"    alt=\"{$LANG['disabled']}\" title=\"{$LANG['disabled']}\" />";
            $lights[] = "<img src=\"images/lightbulb.png\"        alt=\"{$LANG['enabled']}\" title=\"{$LANG['enabled']}\" />";
            $lights[] = "<img src=\"images/lightswitch16x16.png\" alt=\"{$LANG['toggleStatus']}\" title=\"{$LANG['toggleStatus']}\" />";

            $row['image'] = $plugins[3 - $row['registered']];

            if ($row['name'] == 'core') {
                $action = $LANG['alwaysEnabled'];
            } elseif ($row['registered'] == ENABLED) {
                $action =
                    "<a class='index_table' title='{$row['plugin_unregister']}' " .
                       "href='index.php?module=extensions&amp;view=register&amp;id={$row['id']}&amp;action=unregister'>" .
                        "<span style='display:none'>{$row['plugin_unregister']}</span>" .
                        $row['image'] .
                    "</a>&nbsp;&nbsp;";

                if ($row['enabled'] == ENABLED) {
                    $action .=
                        "<a class='index_table' title='{$row['plugin_disable']}' " .
                            "href='index.php?module=extensions&amp;view=manage&amp;id={$row['id']}&amp;action=toggle'>" .
                             "<span style='display:none'>{$row['.plugin_disable']}</span>" .
                             $lights[2] .
                        "</a>";
                } else {
                    $action .=
                        "<a class='index_table' title='{$row['plugin_enable']}' " .
                           "href='index.php?module=extensions&amp;view=manage&amp;id={$row['id']}&amp;action=toggle'>" .
                            "<span style='display:none'>{$row['plugin_enable']}</span>" .
                            $lights[2] .
                        "</a>";
                }
            } else {
                $action =
                    "<a class='index_table' title='{$row['plugin_registered']}' " .
                       "href='index.php?module=extensions&amp;view=register&amp;name={$row['name']}&amp;action=register&amp;description={$row['description']}'>" .
                        "<span style='display:none'>{$row['plugin_registered']}</span>" .
                        $row['image'] .
                    "</a>";
            }

            $row['status'] =
                "<span style='display: none;'>{$row['enabled']}{$row['registered']}</span>" .
                "{$lights[$row['enabled']]}&nbsp;{$plugins[$row['registered']]}";

            $tableRows[] = [
                'action' => $action,
                'name' => $row['name'],
                'description' => $row['description'],
                'status' => $row['status']
            ];
        }

        return $tableRows;
    }

    /**
     * Retrieve requested records from the extensions tables.
     * @param int|null $id If not null, id of specified record to retrieve.
     * @param bool $include_all_dirs (Defaults to false). If true, the records in the
     *          table plus pseudo columns for extension directories not in the table
     *          (aka not registered), will be returned.
     * @return array row(s) selected from the extensions table. Note that rows in the
     *          table will have the registered field set to ENABLED whereas pseudo
     *          entries will have the registered field set to DISABLED.
     */
    private static function getExtensions(?int $id = null, bool $include_all_dirs = false): array
    {
        global $LANG, $pdoDb;

        $extensions = [];
        try {

            $pdoDb->setOrderBy('name');
            if (isset($id)) {
                $pdoDb->addSimpleWhere('id', $id, 'AND');
            }
            $pdoDb->addToWhere(new WhereItem(true, 'domain_id', '=', 0, false, 'OR'));
            $pdoDb->addToWhere(new WhereItem(false, 'domain_id', '=', DomainId::get(), true));

            $ca = new CaseStmt("enabled", "enabled_text");
            $ca->addWhen("=", ENABLED, $LANG['enabled']);
            $ca->addWhen("!=", ENABLED, $LANG['disabled'], true);
            $pdoDb->addToCaseStmts($ca);

            $pdoDb->setSelectList([
                'id',
                'domain_id',
                'name',
                'description',
                'enabled',
                new DbField(ENABLED, 'registered')
            ]);

            $rows = $pdoDb->request('SELECT', 'extensions');

            if ($include_all_dirs) {
                // Add pseudo rows for extension directories not yet in the tabled (not registered).
                $extensionDir = 'extensions';
                $extensionEntries = array_diff(scandir($extensionDir), [".", ".."]); // Skip entries starting with a dot from dir list

                $availableExtensions = [];
                foreach ($extensionEntries as $entry) {
                    if (file_exists($extensionDir . "/" . $entry . "/DESCRIPTION")) {
                        $description = file_get_contents($extensionDir . "/" . $entry . "/DESCRIPTION");
                    } else {
                        $description = "DESCRIPTION not available (in $extensionDir/$entry/)";
                    }

                    $availableExtensions[$entry] = [
                        "id" => 0,
                        "domain_id" => DomainId::get(),
                        "name" => $entry,
                        "description" => $description,
                        "enabled" => DISABLED,
                        "registered" => DISABLED,
                        "enabled_text" => $LANG['disabled']
                    ];
                }

                // $rows (registered_extensions) have all extensions in the database
                // $availableExtensions have all extensions in the distribution
                foreach ($rows as $row) {
                    $name = $row['name'];
                    if (isset($availableExtensions[$name])) {
                        // This is a registered row (in the database) so drop from unregistered array
                        unset($availableExtensions[$name]);
                    }
                }

                // $extensions set to a complete list of the extensions,
                // with status info (enabled, registered)
                $rows = array_merge($rows, $availableExtensions);

                foreach ($rows as $row) {
                    $extRowName = $LANG['extensionsUc'] . " " . $row['name'];
                    $row['plugin_registered'] = $LANG['pluginRegister'] . ' ' . $extRowName;
                    $row['plugin_unregister'] = $LANG['pluginUnregister'] . ' ' . $extRowName;
                    $row['plugin_disable'] = $LANG['disable'] . ' ' . $extRowName;
                    $row['plugin_enable'] = $LANG['enable'] . ' ' . $extRowName;
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
            return [];
        }

        return isset($id) ? $extensions[0] : $extensions;
    }

    /**
     * Get id for the specified extension.
     * @param string $extension_name
     * @return int
     */
    public static function getExtensionId(string $extension_name): int
    {
        global $pdoDbAdmin;

        $rows = [];
        try {
            $pdoDbAdmin->addSimpleWhere('name', $extension_name, 'AND');
            $pdoDbAdmin->addToWhere(new WhereItem(true, 'domain_id', '=', 0, false, 'OR'));
            $pdoDbAdmin->addToWhere(new WhereItem(false, 'domain_id', '=', DomainId::get(), true));

            $pdoDbAdmin->setOrderBy(['domain_id', 'D']);

            $pdoDbAdmin->setLimit(1);

            $rows = $pdoDbAdmin->request('SELECT', 'extensions');
        } catch (PdoDbException $pde) {
            error_log("Extensions::getExtensionId() - Error: " . $pde->getMessage());
        }

        if (empty($rows)) {
            return -2;
        }

        $extensionInfo = $rows[0];
        if ($extensionInfo['enabled'] != ENABLED) {
            return -1; // -1 = extension not enabled
        }
        return $extensionInfo['id']; // 0 = core, >0 is extension id
    }

    /**
     * Set the status for a specified extension ID.
     * @param int $extensionId
     * @param int $status
     * @return bool
     */
    public static function setStatusExtension(int $extensionId, int $status = 2): bool
    {
        global $pdoDbAdmin;

        $domainId = DomainId::get();

        // status=2 = toggle status
        if ($status == 2) {
            $rows = [];
            try {
                $pdoDbAdmin->setSelectList('enabled');
                $pdoDbAdmin->addSimpleWhere('id', $extensionId, 'AND');
                $pdoDbAdmin->addSimpleWhere('domain_id', $domainId);

                $pdoDbAdmin->setLimit(1);

                $rows = $pdoDbAdmin->request('SELECT', 'extensions');
            } catch (PdoDbException $pde) {
                error_log("Extensions::setStatusExtension() - Error: " . $pde->getMessage());
            }
            $extensionInfo = empty($rows) ? $rows : $rows[0];
            $status = 1 - $extensionInfo['enabled'];
        }

        $result = false;
        try {
            $pdoDbAdmin->addSimpleWhere('id', $extensionId, 'AND');
            $pdoDbAdmin->addSimpleWhere('domain_id', $domainId);

            $pdoDbAdmin->setFauxPost(["enabled" => $status]);

            $result = $pdoDbAdmin->request("UPDATE", 'extensions');
        } catch (PdoDbException $pde) {
            error_log("Extensions::setStatusExtension() - Error(2): " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Load SI Extension information into $config['extension'].
     * @param array $config
     * @param bool $databaseBuilt
     * @param int $patchCount
     * @return array extension names array.
     */
    public static function loadSiExtensions(array $config, bool $databaseBuilt, int $patchCount): array
    {
        global $pdoDbAdmin;

        if ($databaseBuilt && $patchCount > "196") {
            $rows = [];
            try {
                $pdoDbAdmin->addSimpleWhere("domain_id", DomainId::get(), "OR");
                $pdoDbAdmin->addSimpleWhere("domain_id", 0);
                $pdoDbAdmin->setSelectAll(true);
                $pdoDbAdmin->setOrderBy("domain_id");
                $rows = $pdoDbAdmin->request("SELECT", "extensions");
            } catch (PdoDbException $pde) {
                error_log("loadSiExtensions() - Error: " . $pde->getMessage());
            }

            $extensions = [];
            foreach ($rows as $extension) {
                $extensions[$extension['name']] = $extension;
            }

            $config['extension'] = $extensions;
        }

        // If no extension loaded, load Core
        if (empty($config['extension'])) {
            // @formatter:off
            $extensionCore = [
                'core' => [
                    'id'         => 1,
                    'domain_id'  => 1,
                    'name'       => 'core',
                    'description'=> 'Core part of SimpleInvoices - always enabled',
                    'enabled'    => 1
                ]
            ];
            $config['extension'] = $extensionCore;
            // @formatter:on
        }

        // Populate the array of enabled extensions.
        $extNames = [];
        foreach ($config['extension'] as $extension) {
            if ($extension['enabled'] == ENABLED) {
                $extNames[] = $extension['name'];
            }
        }

        return $extNames;
    }

    /**
     * Insert a new record in the extensions table.
     * @return int ID assigned to new record, 0 is insert failed.
     */
    public static function insert(): int
    {
        global $pdoDb;

        $result = 0;
        try {
            $pdoDb->setFauxPost([
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'domain_id' => DomainId::get(),
                'enabled' => DISABLED
            ]);
            $result = $pdoDb->request('INSERT', 'extensions');
        } catch (PdoDbException $pde) {
            error_log("Extensions::insert() - Error: " . $pde->getMessage());
        }
        return $result;
    }

    /**
     * Delete a specified record from the extensions table.
     * @param int $id
     * @return bool true if delete succeeded, otherwise false.
     */
    public static function delete(int $id): bool
    {
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
