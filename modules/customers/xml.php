<?php

use Inc\Claz\Customer;
use Inc\Claz\SiLocal;

global $LANG;

header("Content-type: text/xml");

// @formatter:off
$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC";
$sort  = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "name";
$rp    = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25";
$page  = (isset($_POST['page'])     ) ? $_POST['page']      : "1";
// @formatter:on

$customers = Customer::xmlSql('', $dir, $sort, $rp, $page);
$count = Customer::xmlSql('count', $dir, $sort, $rp, $page);

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

$viewcust = $LANG['view'] . " " . $LANG['customer'];
$editcust = $LANG['edit'] . " " . $LANG['customer'];
$defaultinv = $LANG['new_uppercase'] . " " . $LANG['default_invoice'];
foreach ($customers as $row) {
    $vname = $viewcust . $row['name'];
    $ename = $editcust . $row['name'];
    $image = ($row['enabled'] == 0 ? 'images/common/cross.png' : 'images/common/tick.png');
    $xml .= "<row id='{$row['CID']}'>";
    $xml .=
        "<cell><![CDATA[" .
          "<a class='index_table' title='$vname' href='index.php?module=customers&amp;view=details&amp;id={$row['CID']}&amp;action=view'>" .
            "<img src='images/common/view.png' class='action' />" .
          "</a>" .
          "<a class='index_table' title='$ename' href='index.php?module=customers&amp;view=details&amp;id={$row['CID']}&amp;action=edit'>".
            "<img src='images/common/edit.png' class='action' />" .
          "</a>" .
          "<a class='index_table' title='$defaultinv' href='index.php?module=invoices&amp;view=usedefault&amp;customer_id={$row['CID']}&amp;action=view'>" .
            "<img src='images/common/add.png' class='action' />" .
          "</a>" .
    "]]></cell>";
    $xml .= "<cell><![CDATA[{$row['CID']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['name']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['department']}]]></cell>";
    $xml .= "<cell><![CDATA[<a class='index_table' title='quick view' " .
                              "href='index.php?module=invoices&amp;view=quick_view&amp;id={$row['last_inv_id']}'>{$row['last_invoice']}" .
                           "</a>" .
                    "]]></cell>";
    $xml .= "<cell><![CDATA[" . SiLocal::number($row['customer_total']) . "]]></cell>";
    $xml .= "<cell><![CDATA[" . SiLocal::number($row['paid']) . "]]></cell>";
    $xml .= "<cell><![CDATA[" . SiLocal::number($row['owing']) . "]]></cell>";
    $xml .= "<cell><![CDATA[<img src='$image' alt='{$row['enabled_txt']}' title='{$row['enabled_txt']}' />]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";

echo $xml;
