<?php

use Inc\Claz\User;

header("Content-type: text/xml");

global $LANG;

$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort  = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "username" ;
$rp    = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25" ;
$page  = (isset($_POST['page'])     ) ? $_POST['page']      : "1" ;

$rows  = User::xmlSql('', $dir, $sort, $rp, $page);
$count = User::xmlSql('count', $dir, $sort, $rp, $page);

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";
foreach ($rows as $row) {
    $name = (isset($row['name']) ? $row['name'] : "");
    $xml .= "<row id='" . $row['id'] . "'>";
    $xml .=
        "<cell><![CDATA[" .
            "<a class='index_table' title='{$LANG['view']} {$name}' " .
               "href='index.php?module=user&amp;view=details&amp;id={$row['id']}&amp;action=view'>" .
                "<img src='images/common/view.png' height='16' border='-5px' />" .
            "</a>" .
            "<a class='index_table' title='{$LANG['edit']} {$name}' " .
               "href='index.php?module=user&amp;view=details&amp;id={$row['id']}&amp;action=edit'>" .
                "<img src='images/common/edit.png' height='16' border='-5px' />" .
            "</a>" .
        "]]></cell>";
    $xml .= "<cell><![CDATA[{$row['username']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['email']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['role_name']}]]></cell>";
    if ($row['enabled'] == $LANG['enabled']) {
        $xml .= "<cell><![CDATA[<img src='images/common/tick.png'  alt='{$row['enabled']}' title='{$row['enabled']}' />]]></cell>";
    } else {
        $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='{$row['enabled']}' title='{$row['enabled']}' />]]></cell>";
    }
    $xml .= "<cell><![CDATA[{$row['uid']}]]></cell>";
    $xml .= "</row>";
}

$xml .= "</rows>";
echo $xml;
