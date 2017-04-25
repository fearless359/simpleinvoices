<?php
//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

$domain_id = class_exists('domain_id') ? domain_id::get() : si_classes\DomainId::get();
$saved = false;
if ($_POST['action'] == "register") {

  $sql = "INSERT INTO ".TB_PREFIX."extensions (`id`,`name`,`description`,`domain_id`,`enabled`) VALUES ( NULL, :name ,  :description , :domain_id , '0');";
  $sth = dbQuery($sql, ':name',$_POST['name'],':description',$_POST['description'],':domain_id', $domain_id);
$saved = true;
} elseif ($_POST['action'] == "unregister") {

  $sql = "DELETE FROM ".TB_PREFIX."extensions WHERE id = :id AND domain_id = :domain_id; DELETE FROM ".TB_PREFIX."system_defaults WHERE extension_id = :id AND domain_id = :domain_id;";
  $sth = dbQuery($sql, ':id', $_POST['id'],':domain_id', $domain_id);
$saved = true;
} else {

  die("Dude, this action is unknown to me!");
}
$smarty->assign('saved',$saved);
$smarty->assign('pageActive','setting');
$smarty->assign('subPageActive', 'setting_extensions');
$smarty->assign('active_tab','#setting');
