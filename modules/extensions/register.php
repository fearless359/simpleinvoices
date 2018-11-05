<?php

use Inc\Claz\DomainId;
use Inc\Claz\SystemDefaults;

global $pdoDb, $smarty;

// Stop direct browsing to this file
checkLogin();

// @formatter:off
$extension_id   = (empty($_GET['id']         ) ? "" : $_GET['id']);
$extension_name = (empty($_GET['name']       ) ? "" : $_GET['name']);
$action         = (empty($_GET['action']     ) ? "" : $_GET['action']);
$extension_desc = (empty($_GET['description']) ? "" : $_GET['description']);
// @formatter:on

$domain_id = DomainId::get();
$count = 0;
if (!empty($extension_id)) {
    // retrieve name and description from DB
    $pdoDb->addSimpleWhere("id", $extension_id, "AND");
    $pdoDb->addSimpleWhere("domain_id", $domain_id);
    $pdoDb->setSelectList(array("name","description"));
    $info = $pdoDb->request("SELECT", "extensions");
    $extension_name = $info[0]['name'];
    $extension_desc = $info[0]['description'];

    $count = SystemDefaults::extensionCount($extension_id);
}

// @formatter:off
$smarty->assign('id'         , $extension_id);
$smarty->assign('action'     , $action);
$smarty->assign('name'       , $extension_name);
$smarty->assign('count'      , $count);
$smarty->assign('description', $extension_desc);
// @formatter:on

$smarty->assign('pageActive', 'extensions');
$smarty->assign('active_tab', '#settings');
