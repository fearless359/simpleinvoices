<?php
use Inc\Claz\Cron;

global $LANG;

header ( "Content-type: text/xml" );

$dir  = (isset($_POST ['sortorder'])) ? $_POST ['sortorder'] : "DESC";
$sort = (isset($_POST ['sortname']) ) ? $_POST ['sortname']  : "cron.id";
$rp   = (isset($_POST ['rp'])       ) ? $_POST ['rp']        : "25";
$page = (isset($_POST ['page'])     ) ? $_POST ['page']      : "1";

$crons = Cron::xmlSql('', $sort, $dir, $rp, $page);
$count = Cron::xmlSql('count', $sort, $dir, $rp, $page);

$xml  = "";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ( $crons as $row ) {
    $row['email_biller_nice']   = ($row['email_biller']   == ENABLED ? $LANG['yes'] : $LANG['no_lowercase']);
    $row['email_customer_nice'] = ($row['email_customer'] == ENABLED ? $LANG['yes'] : $LANG['no_lowercase']);
    $xml .= "<row id='" . $row['id'] . "'>";
    $xml .=
        "<cell><![CDATA[" .
           "<a class='index_table' title='{$LANG['view']} {$row['index_name']}' " .
              "href='index.php?module=cron&amp;view=view&amp;id={$row['id']}'>" .
               "<img src='images/common/view.png' height='16' border='-5px' />" .
           "</a>" .
           "<a class='index_table' title='{$LANG['edit']} {$row['index_name']}' " .
              "href='index.php?module=cron&amp;view=edit&amp;id={$row['id']}'>" .
               "<img src='images/common/edit.png' height='16' border='-5px' />" .
           "</a>" .
           "<a class='index_table' title='{$LANG['delete']} {$row['index_name']}' " .
              "href='index.php?module=cron&amp;view=delete&amp;id={$row['id']}&amp;stage=1&amp;err_message=' >" .
               "<img src='images/common/delete.png' height='16' border='-5px' />" .
           "</a>" .
         "]]></cell>";
    $xml .= "<cell><![CDATA[{$row['index_name']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['start_date']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['end_date']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['recurrence']} {$row['recurrence_type']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['email_biller_nice']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['email_customer_nice']}]]></cell>";
    $xml .= "<cell><![CDATA[{$row['name']}]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";

echo $xml;
