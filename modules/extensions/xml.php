<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\PdoDbException;
use Inc\Claz\WhereItem;

global $LANG, $pdoDb;

header("Content-type: text/xml");

// @formatter:off
$start = (isset($_POST['start'])     ? intval($_POST['start'])    : 0);
$dir   = (isset($_POST['sortorder']) ?        $_POST['sortorder'] : "ASC");
$sort  = (isset($_POST['sort'])      ?        $_POST['sort']      : "id");
$rp    = (isset($_POST['rp'])        ? intval($_POST['rp'])       : 25);
$page  = (isset($_POST['page'])      ? intval($_POST['page'])     : 1);
// @formatter:on

$extension_dir = 'extensions';
$extension_entries = array_diff(scandir($extension_dir), Array(".","..")); // Skip entries starting with a dot from dir list

$available_extensions = Array();
foreach($extension_entries as $entry) {
    if (file_exists($extension_dir . "/" . $entry . "/DESCRIPTION")) {
        $description = file_get_contents($extension_dir . "/" . $entry . "/DESCRIPTION");
    } else {
        $description = "DESCRIPTION not available (in $extension_dir/$entry/)";
    }
    
    $available_extensions[$entry] = array("name" => $entry,"enabled" => 0,"registered" => 0,"description" => $description,"id" => "");
}

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

    if (isset($_POST['query']) && isset($_POST['qtype'])) {
        $query = $_POST['query'];
        $qtype = $_POST['qtype'];
        if (in_array($qtype, array('id', 'name', 'description'))) {
            $pdoDb->addToWhere(new WhereItem(false, $qtype, 'LIKE', $query, false, 'AND'));
        }
    }
    $pdoDb->addToWhere(new WhereItem(true, 'domain_id', '=', 0, false, 'OR'));
    $pdoDb->addToWhere(new WhereItem(false, 'domain_id', '=', DomainId::get(), true));

    $pdoDb->setSelectList(array('id', 'name', 'description', 'enabled', new DbField('1', 'registered')));

    $rows = $pdoDb->request('SELECT', 'extensions');
} catch (PdoDbException $pde) {
    error_log("modules/extensions/xml.php - error: " . $pde->getMessage());
}

//$sql = "SELECT id, name, description, 1 AS registered, enabled FROM  " . TB_PREFIX . "extensions
//        WHERE (domain_id = 0 OR  domain_id = :domain_id) $where ORDER BY  $sort $dir $limit";
//if (empty($query)) {
//    $sth = dbQuery($sql, ':domain_id', DomainId::get());
//} else {
//    $sth = dbQuery($sql, ':domain_id', DomainId::get(), ':query', "%$query%");
//}
//$rows = $sth->fetchAll(PDO::FETCH_ASSOC);

// $rows (registered_extensions) have all extensions in the database
// $available_extensions have all extensions in the distribution
foreach($rows as $row) {
    $name = $row['name'];
    if (isset($available_extensions[$name])) {
        unset($available_extensions[$name]);
    }
}

// $extensions set to a complete list of the extensions, with status info (enabled, registered)
$extensions = array_merge($rows, $available_extensions);

$count = count($extensions);

// @formatter:off
$plugin = array();
$plugin[0] = " <img src='images/famfam/plugin_disabled.png' alt='{$LANG['plugin_not_registered']}' />";
$plugin[1] = " <img src='images/famfam/plugin.png'          alt='{$LANG['plugin_registered']}' />";
$plugin[2] = " <img src='images/famfam/plugin_delete.png'   alt='{$LANG['plugin_unregister']}' />";
$plugin[3] = " <img src='images/famfam/plugin_add.png'      alt='{$LANG['plugin_register']}' />";

$light = array();
$light[0] = " <img src='images/famfam/lightbulb_off.png'    alt='{$LANG['disabled']}' />";
$light[1] = " <img src='images/famfam/lightbulb.png'        alt='{$LANG['enabled']}' />";
$light[2] = " <img src='images/common/lightswitch16x16.png' alt='{$LANG['toggle_status']}' />";
// @formatter:on

$xml = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach($extensions as $row) {
    $xml .= "<row id='{$row['id']}'>";
    $xml .= "<cell><![CDATA[";
    if ($row['id'] == 0 && $row['registered'] == 1) {
        $xml .= "Always enabled ";
    } else {
        $ext_row_name = $LANG['extensions'] . ' ' . $row['name'];
        if ($row['registered'] == 1) {
            $img = $plugin[3 - $row['registered']];
            $xml .= "<a class='index_table' title='{$LANG['plugin_unregister']} {$ext_row_name}' " .
                       "href='index.php?module=extensions&amp;view=register&amp;id={$row['id']}&amp;action=unregister'>{$img}</a>";

            $img = $light[2];
            if ($row['enabled'] == 1) {
                $xml .= "<a class='index_table' title='{$LANG['disable']} {$ext_row_name}' " .
                          "href='index.php?module=extensions&amp;view=manage&amp;id={$row['id']}&amp;action=toggle'>{$img}</a>";
            } else {
                $xml .= "<a class='index_table' title='{$LANG['enable']} {$ext_row_name}' " .
                          "href='index.php?module=extensions&amp;view=manage&amp;id={$row['id']}&amp;action=toggle'>{$img}</a>";
            }
        } else {
            $img = $plugin[3 - $row['registered']];
            $xml .= "<a class='index_table' title='{$LANG['plugin_register']} {$ext_row_name}' " .
                      "href='index.php?module=extensions&amp;view=register&amp;name={$row['name']}&amp;action=register&amp;description={$row['description']}'>{$img}</a>";
        }
    }
    $xml .= "]]></cell>";
    $xml .= "<cell><![CDATA[{$row['id']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['name']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['description']}]]></cell>";
    $xml .= "<cell><![CDATA[{$light[$row['enabled']]}{$plugin[$row['registered']]}]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";

echo $xml;
