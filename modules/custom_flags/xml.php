<?php

use Inc\Claz\CustomFlags;
use Inc\Claz\Util;

global $LANG;

header("Content-type: text/xml");

// @formatter:off
$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC";
$sort  = (isset($_POST['sortname']))  ? $_POST['sortname']  : "associated_table";
$rp    = (isset($_POST['rp']))        ? $_POST['rp']        : "25";
$page  = (isset($_POST['page']))      ? $_POST['page']      : "1";

$cflgs = CustomFlags::xmlSql(''     , $dir, $sort, $rp, $page);
$count = CustomFlags::xmlSql('count', $dir, $sort, $rp, $page);

$xml  = "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";
foreach ($cflgs as $row) {
    $id = Util::htmlsafe($row['associated_table'] . ':' . $row['flg_id']);
    if ($row['enabled'] == ENABLED) {
        $enabled = $LANG['enabled'];
        $image = 'images/common/tick.png';
    } else {
        $enabled = $LANG['disabled'];
        $image = 'images/common/cross.png';
    }

    $xml .= "<row id='$id'>";
    $xml .= 
        "<cell><![CDATA[" .
            "<a class='index_table' title='{$LANG['view']} {$LANG['custom_flags_upper']}' " .
               "href='index.php?module=custom_flags&amp;view=details&amp;id={$id}&amp;action=view'>" .
                "<img src='images/common/view.png' height='16' border='-5px' />" .
            "</a>" .
            "<a class='index_table' title='{$LANG['edit']} {$LANG['custom_flags_upper']}' " .
               "href='index.php?module=custom_flags&amp;view=details&amp;id={$id}&amp;action=edit' >" .
                "<img src='images/common/edit.png' height='16' border='-5px' />" .
            "</a>" .
        "]]></cell>";
    $xml .= "<cell><![CDATA[" . Util::htmlsafe($row['associated_table']) . "]]></cell>";
    $xml .= "<cell><![CDATA[" . Util::htmlsafe($row['flg_id'])           . "]]></cell>";
    $xml .= "<cell><![CDATA[" . Util::htmlsafe($row['field_label'])      . "]]></cell>";
    $xml .= "<cell><![CDATA[<img src='{$image}' alt='{$enabled}' title='{$enabled}' />]]></cell>";
    $xml .= "<cell><![CDATA[" . Util::htmlsafe($row['field_help']) . "]]></cell>";
    $xml .= "</row>";
}
// @formatter:on
$xml .= "</rows>";

echo $xml;
