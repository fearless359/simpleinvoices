<?php
use Inc\Claz\Db;
use Inc\Claz\Import;

global $config, $databaseBuilt;

$menu = false;

// Check if a table that MUST exist in all versions, does exist.
if (!$databaseBuilt) {
    $db = Db::getInstance($config);
    $import = new Import();
    $import->file = "databases/mysql/structure.sql";
    $import->patternFind = ['si_', 'DOMAIN-ID', 'LOCALE', 'LANGUAGE'];
    $import->patternReplace = [TB_PREFIX, '1', 'en_US', 'en_US'];
    $db->query($import->collate());
}
