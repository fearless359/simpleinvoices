<?php

use Inc\Claz\ExpenseAccount;

global $LANG;

header("Content-type: text/xml");

// @formatter:off
$dir  = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "id" ;
$rp   = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25" ;
$page = (isset($_POST['page'])     ) ? $_POST['page']      : "1" ;

$expense_accounts = ExpenseAccount::xmlSql('', $dir, $sort, $rp, $page);
$count = ExpenseAccount::xmlSql('count',$dir, $sort, $rp, $page);

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";
foreach ($expense_accounts as $row) {
    $xml .= "<row id='".$row['id']."'>";
    $xml .= 
        "<cell><![CDATA[" .
            "<a class='index_table' title='$LANG[view]' href='index.php?module=expense_account&amp;view=details&amp;id=$row[id]&amp;action=view'>" .
                "<img src='images/common/view.png' height='16' border='-5px' />" .
            "</a>" .
            "<a class='index_table' title='$LANG[edit]' href='index.php?module=expense_account&amp;view=details&amp;id=$row[id]&amp;action=edit'>" .
                "<img src='images/common/edit.png' height='16' border='-5px' />" .
            "</a>" .
        "]]></cell>";
    $xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
    $xml .= "<cell><![CDATA[".$row['name']."]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";
// @formatter:on

echo $xml;
