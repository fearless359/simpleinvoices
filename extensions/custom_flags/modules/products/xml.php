<?php
header("Content-type: text/xml");
global $smarty, $LANG;

// @formatter:off
$dir  = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC";
$sort = (isset($_POST['sortname']))  ? $_POST['sortname']  : "description";
$rp   = (isset($_POST['rp']))        ? $_POST['rp']        : "25";
$page = (isset($_POST['page']))      ? $_POST['page']      : "1";
// @formatter:on

$defaults = getSystemDefaults();
$smarty->assign("defaults", $defaults);

$products = new product();
$products_all = $products->select_all('', $dir, $sort, $rp, $page);
$rows = $products->select_all('count',$dir, $sort, $rp, $page);
$count = $rows[0]['count'];

$xml = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($products_all as $row) {
    $xml .= "<row id='" . $row['id'] . "'>";
    $xml .=
        "<cell><![CDATA[
           <a class='index_table' title='" . $LANG['view'] . " " . $row['description'] . "'
              href='index.php?module=products&view=details&id=" . $row['id'] . "&action=view'>
             <img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />
           </a>
           <a class='index_table' title='" . $LANG['edit'] . " " . $row['description'] . "'
              href='index.php?module=products&view=details&id=" . $row['id'] . "&action=edit'>
             <img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' />
           </a>
         ]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['id'] . "]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['description'] . "]]></cell>";
    $xml .= "<cell><![CDATA[" . siLocal::number($row['unit_price']) . "]]></cell>";
    if ($defaults['inventory'] == '1') {
        $xml .= "<cell><![CDATA[" . siLocal::number_trim($row['quantity']) . "]]></cell>";
    }

    if ($row['enabled'] == $LANG['enabled']) {
        $xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='" . $row['enabled'] . "' title='" .
                         $row['enabled'] . "' />]]></cell>";
    } else {
        $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='" . $row['enabled'] . "' title='" .
                         $row['enabled'] . "' />]]></cell>";
    }
    $xml .= "</row>";
}
$xml .= "</rows>";

echo $xml;
