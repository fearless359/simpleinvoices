<?php

use Inc\Claz\Preferences;

header("Content-type: text/xml");

global $LANG;

// @formatter:off
$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC";
$sort  = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "pref_description";
$rp    = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25";
$page  = (isset($_POST['page'])     ) ? $_POST['page']      : "1";
// @formatter:on

$domain_id = $auth_session->domain_id;
$rows = Preferences::xmlSql('', $dir, $sort, $rp, $page);
$count = Preferences::xmlSql('count', $dir, $sort, $rp, $page);

$xml  = '';
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($rows as $row) {
    $pref_desc = $row['pref_description'];
    $pref_id   = $row['pref_id'];
    $language  = $row['language'];
    $locale    = $row['locale'];
    $enabled   = $row['enabled'];
    if ($enabled == $LANG['enabled']) {
        $pic = "images/common/tick.png";
    } else {
        $pic = "images/common/cross.png";
    }
    $title = $LANG['view'] . " " . $LANG['preference'] . " " . $pref_desc;
    $xml .= "<row id='$pref_id'>";
    $xml .=
        "<cell><![CDATA[" .
            "<a class='index_table' title='$title' " .
               "href='index.php?module=preferences&amp;view=details&amp;id=$pref_id&amp;action=view'>" .
                "<img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' />" .
            "</a>" .
            "<a class='index_table' title='$title' " .
               "href='index.php?module=preferences&amp;view=details&amp;id=$pref_id&amp;action=edit'>" .
                "<img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' />" .
            "</a>" .
        "]]></cell>";
    $xml .= "<cell><![CDATA[$pref_desc]]></cell>";
    $xml .= "<cell><![CDATA[$language]]></cell>";
    $xml .= "<cell><![CDATA[$locale]]></cell>";
    $xml .= "<cell><![CDATA[<img src='$pic' alt='$enabled' title='$enabled' />]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";

echo $xml;

