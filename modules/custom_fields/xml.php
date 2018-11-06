<?php

use Inc\Claz\CustomFields;

global $LANG, $pdoDb;

header("Content-type: text/xml");

$start = (isset($_POST['start'])     ? $_POST['start']     : "0");
$dir   = (isset($_POST['sortorder']) ? $_POST['sortorder'] : "ASC");
$sort  = (isset($_POST['sortname'])  ? $_POST['sortname']  : "cf_id");
$rp    = (isset($_POST['rp'])        ? $_POST['rp']        : "25");
$page  = (isset($_POST['page'])      ? $_POST['page']      : "1");

$cfs = CustomFields::xmlSql($sort, $dir, $rp, $page);
$count = count($cfs);

$xml = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($cfs as $row) {
    $cfn_nice = $LANG['custom_field'] . htmlsafe($row['field_name_nice']);
    $xml .= "<row id='".htmlsafe($row['cf_id'])."'>";
    $xml .=
        "<cell><![CDATA[" .
            "<a class='index_table' title='{$LANG['view']} {$cfn_nice}' " .
                "href='index.php?module=custom_fields&amp;view=details&id={$row['cf_id']}&amp;action=view'>" .
                "<img src='images/common/view.png' height='16' border='-5px' />" .
            "</a>" .
            "<a class='index_table' title='{$LANG['edit']} {$cfn_nice}' " .
               "href='index.php?module=custom_fields&amp;view=details&amp;id={$row['cf_id']}&amp;action=edit'>" .
                "<img src='images/common/edit.png' height='16' border='-5px' />" .
            "</a>" .
        "]]></cell>";
    $xml .= "<cell><![CDATA[".htmlsafe($row['cf_id'])."]]></cell>";
    $xml .= "<cell><![CDATA[".htmlsafe($row['field_name_nice'])."]]></cell>";
    $xml .= "<cell><![CDATA[".htmlsafe($row['cf_custom_label'])."]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";

echo $xml;
