<?php

use Inc\Claz\Product;
use Inc\Claz\SiLocal;
use Inc\Claz\SystemDefaults;

header("Content-type: text/xml");
global $smarty, $LANG;

$dir   = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
$sort  = (isset($_POST['sortname']))  ? $_POST['sortname']  : "description" ;
$rp    = (isset($_POST['rp']))        ? $_POST['rp']        : "25" ;
$page  = (isset($_POST['page']))      ? $_POST['page']      : "1" ;

$defaults = SystemDefaults::loadValues();
$smarty->assign("defaults",$defaults);

$products_all = Product::xmlSql('', $dir, $sort, $rp, $page);
$count = Product::xmlSql('count',$dir, $sort, $rp, $page);

$xml  = "";
$xml .= "<rows>";
$xml .= "  <page>$page</page>";
$xml .= "  <total>$count</total>";

foreach ($products_all as $row) {
    $image = ($row['enabled'] == $LANG['enabled'] ? "images/common/tick.png" : "images/common/cross.png");

    $xml .= "<row id='{$row['id']}'>";
    $xml .=
        "<cell><![CDATA[
           <a class='index_table' title='" . $LANG['view'] . " " . $row['description'] . "'
              href='index.php?module=products&amp;view=details&amp;id=" . $row['id'] . "&amp;action=view'>
                 <img src='images/common/view.png' height='16' border='-5px' />
               </a>
           <a class='index_table' title='" . $LANG['edit'] . " " . $row['description'] . "'
              href='index.php?module=products&amp;view=details&amp;id=" . $row['id'] . "&amp;action=edit'>
                 <img src='images/common/edit.png' height='16' border='-5px' />
               </a>
         ]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['id']                          . "]]></cell>";
    $xml .= "<cell><![CDATA[" . $row['description']                 . "]]></cell>";
    $xml .= "<cell><![CDATA[" . SiLocal::number($row['unit_price']) . "]]></cell>";
    if($defaults['inventory'] == '1') {
        $xml .= "<cell><![CDATA[" . SiLocal::number_trim($row['quantity']) . "]]></cell>";
    }

    $xml .= "<cell><![CDATA[<img src='$image' alt='$row[enabled]' title='$row[enabled]' />]]></cell>";
    $xml .= "</row>";
}
$xml .= "</rows>";

echo $xml;
