<?php

use Inc\Claz\Expense;
use Inc\Claz\SiLocal;

global $LANG;

header("Content-type: text/xml");

$dir  = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : 'DESC' ;
$sort = (isset($_POST['sortname']))  ? $_POST['sortname']  : 'id'   ;
$rp   = (isset($_POST['rp']))        ? $_POST['rp']        : '25'   ;
$page = (isset($_POST['page']))      ? $_POST['page']      : '1'    ;

$rows  = Expense::xmlSql('', $dir, $sort, $rp, $page);
$count = Expense::xmlSql('count',$dir, $sort, $rp, $page);

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";
foreach ($rows as $row) {
    $status_wording = ($row['status'] == ENABLED ? $LANG['paid'] : $LANG['not_paid']);

    $xml .= "<row id='".$row['id']."'>";
    $xml .=
        "<cell><![CDATA[" .
            "<a class='index_table' title='{$LANG['view']} {$row['p_desc']}' " .
               "href='index.php?module=expense&amp;view=details&amp;id={$row['id']}&amp;action=view'>" .
                "<img src='images/common/view.png' class='action' />" .
            "</a>" .
            "<a class='index_table' title='{$LANG['edit']} {$row['p_desc']}' " .
               "href='index.php?module=expense&amp;view=details&amp;id={$row['id']}&amp;action=edit'>" .
                "<img src='images/common/edit.png' class='action' />" .
            "</a>" .
        "]]></cell>";
    $xml .= "<cell><![CDATA[".SiLocal::date($row['date'])."]]></cell>";
    $xml .= "<cell><![CDATA[".SiLocal::number($row['amount'])."]]></cell>";
    $xml .= "<cell><![CDATA[".SiLocal::number($row['tax'])."]]></cell>";
    $xml .= "<cell><![CDATA[".SiLocal::number($row['amount'] + $row['tax'])."]]></cell>";
    $xml .= "<cell><![CDATA[{$row['ea_name']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['b_name']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['c_name']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['iv_id']}]]></cell>";
    $xml .= "<cell><![CDATA[{$status_wording}]]></cell>";
    $xml .= "</row>";        
}

$xml .= "</rows>";
echo $xml;
