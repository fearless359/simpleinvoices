<?php

use Inc\Claz\CustomFields;
use Inc\Claz\Util;

/*
 * Script: details.php
 *      Custom fields details page
 *
 * Last edited:
 *      20210617 by Rich Rowley to use CustomFields::getOne() function to access info.
 *
 * License:
 *      GPL v3 or above
 *
 * Website:
 *      https://simpleinvoices.group
 */
global $pdoDb, $smarty;

//stop the direct browsing to this file - let index.php handle which files get displayed
Util::directAccessAllowed();

$cf = CustomFields::getOne($_GET['id']);
$cf['name'] = CustomFields::getCustomFieldName($cf['cf_custom_field']);

$smarty->assign("cf",$cf);

$smarty->assign('pageActive'   , 'customFields');
$smarty->assign('subPageActive', "customFieldsEdit");
$smarty->assign('activeTab'   , '#settings');
