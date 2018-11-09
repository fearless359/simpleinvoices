<?php

use Inc\Claz\PaymentType;

header("Content-type: text/xml");

global $LANG;

// @formatter:off
$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "A";
$sort  = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "pt_description";
$rp    = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25";
$page  = (isset($_POST['page'])     ) ? $_POST['page']      : "1";

$payment_types = PaymentType::xmlSql(''     , $dir, $sort, $rp, $page);
$count         = PaymentType::xmlSql('count', $dir, $sort, $rp, $page);

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($payment_types as $row) {
    $pt_desc = $row['pt_description'];
    $pt_id = $row['pt_id'];
    $enabled = $row['enabled'];
    $title   = "$LANG[view] $LANG[payment_type] $pt_desc";
    $pic = ($enabled == $LANG['enabled'] ? "images/common/tick.png" : "images/common/cross.png");

    $xml .= "<row id='$pt_id'>";
    $xml .=
        "<cell><![CDATA[" .
            "<a class='index_table' title='$title' " .
               "href='index.php?module=payment_types&amp;view=details&amp;id=$pt_id&amp;action=view'>" .
                "<img src='images/common/view.png' height='16' border='-5px' />" .
            "</a>";
    $xml .=
        "<a class='index_table' title='$title' " .
           "href='index.php?module=payment_types&amp;view=details&amp;id=$pt_id&amp;action=edit'>" .
            "<img src='images/common/edit.png' height='16' border='-5px' />" .
        "</a>" .
    "]]></cell>";
    $xml .= "<cell><![CDATA[{$pt_desc}]]></cell>";
    $xml .= "<cell><![CDATA[<img src='{$pic}' alt='{$enabled}' title='{$enabled}' />]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";
// @formatter:on

echo $xml;
