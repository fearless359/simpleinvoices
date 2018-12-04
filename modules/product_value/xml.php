<?php
global $LANG;

use Inc\Claz\ProductValues;

header("Content-type: text/xml");

$dir  = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort = (isset($_POST['sortname']) ) ? $_POST['sortname']  : "name" ;
$rp   = (isset($_POST['rp'])       ) ? $_POST['rp']        : "25" ;
$page = (isset($_POST['page'])     ) ? $_POST['page']      : "1" ;

$rows = ProductValues::xmlSql($dir, $sort, $rp, $page);
$count = ProductValues::count();

$xml  = "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$count</total>";

foreach ($rows as $row) {
    $xml .= "<row id='".$row['id']."'>";
    $xml .=
        "<cell><![CDATA[" .
            "<a class='index_table' title='$LANG[view] ".$row['name']."' " .
               "href='index.php?module=product_value&amp;view=details&amp;action=view&amp;id=".$row['id']."&amp;action=view'>" .
                "<img src='images/common/view.png' height='16' border='-5px' />" .
            "</a>" .
            "<a class='index_table' title='$LANG[edit] ".$row['name']."' " .
               "href='index.php?module=product_value&amp;view=details&amp;action=edit&amp;id=".$row['id']."&amp;action=edit'>" .
                "<img src='images/common/edit.png' height='16' border='-5px' />" .
            "</a>" .
        "]]></cell>";
    $xml .= "<cell><![CDATA[".$row['id']."]]></cell>";
    $xml .= "<cell><![CDATA[".utf8_encode($row['name'])."]]></cell>";
    $xml .= "<cell><![CDATA[".utf8_encode($row['value'])."]]></cell>";
    if ($row['enabled']=='1') {
        $xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";
    }
    else {
        $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";
    }

    $xml .= "</row>";
}

$xml .= "</rows>";
echo $xml;
