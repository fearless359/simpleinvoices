<?php

use Inc\Claz\DomainId;
use Inc\Claz\PdoDbException;

global $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();
function getExtensions() {
    global $pdoDb;

    $rows = array();
    try {
        $pdoDb->addSimpleWhere('domain_id', DISABLED, 'OR');
        $pdoDb->addSimpleWhere('domain_id', DomainId::get());

        $pdoDb->setOrderBy('name');

        $rows = $pdoDb->request('SELECT', 'extensions');
    } catch (PdoDbException $pde) {
        error_log("modules/extensions/manager.php - getExtensions() - Error: " . $pde->getMessage());
    }

    $exts = null;
    $i = 0;
    foreach ($rows as $row) {
        $exts[$i] = $row;
        $i++;
    }
    $domain_id = DomainId::get();
    
    $sql = "SELECT * FROM ".TB_PREFIX."extensions WHERE domain_id = 0 OR domain_id = :domain_id ORDER BY name";

    $sth = dbQuery($sql, ':domain_id', $domain_id);
    
    $exts = null;
    
    for($i=0; $ext = $sth->fetch(); $i++) {
        $exts[$i] = $ext;
    }
    
    return $exts;
}

isset($_GET['id']) && $extension_id = $_GET['id'];
isset($_GET['action']) && $action = $_GET['action'];

if ($action == 'toggle') {
    if (!setStatusExtension($extension_id)) {
        die(htmlsafe("Something went wrong with the status change!"));
    }
}

$smarty -> assign("exts", getExtensions());

$smarty -> assign('pageActive', 'setting');
$smarty -> assign('active_tab', '#setting');
$smarty -> assign('subPageActive', 'setting_extensions');
