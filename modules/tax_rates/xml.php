<?php

use Inc\Claz\SiLocal;
use Inc\Claz\Taxes;

global $LANG;

header("Content-type: text/xml");

$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort  = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "tax_description" ;
$rp    = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25" ;
$page  = (isset($_POST['page'])     ) ? $_POST['page']      : "1" ;


$rows  = Taxes::xmlSql('', $dir, $sort, $rp, $page);
$count = Taxes::xmlSql('count',$dir, $sort, $rp, $page);

$xml ="";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($rows as $row) {
    $xml .= "<row id='".$row['tax_id']."'>";
    $xml .=
        "<cell><![CDATA[" .
            "<a class='index_table' title='$LANG[view] $LANG[tax_rate] ".$row['tax_description']."' " .
               "href='index.php?module=tax_rates&amp;view=details&amp;id=$row[tax_id]&amp;action=view'>" .
                "<img src='images/common/view.png' height='16' border='-5px' />" .
            "</a>" .
            "<a class='index_table' title='$LANG[edit] $LANG[tax_rate] ".$row['tax_description']."' " .
               "href='index.php?module=tax_rates&amp;view=details&amp;id=$row[tax_id]&amp;action=edit'>" .
                "<img src='images/common/edit.png' height='16' border='-5px' />" .
            "</a>" .
        "]]></cell>";
    $xml .= "<cell><![CDATA[".$row['tax_id']."]]></cell>";
    $xml .= "<cell><![CDATA[".$row['tax_description']."]]></cell>";
    $xml .= "<cell><![CDATA[".SiLocal::number($row['tax_percentage'])." ".$row['type']."]]></cell>";
    if ($row['enabled']==$LANG['enabled']) {
        $xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".utf8_encode($row['enabled'])."' title='".utf8_encode($row['enabled'])."' />]]></cell>";
    }
    else {
        $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".utf8_encode($row['enabled'])."' title='".utf8_encode($row['enabled'])."' />]]></cell>";
    }
    $xml .= "</row>";
}
$xml .= "</rows>";

echo $xml;
