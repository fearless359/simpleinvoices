<?php
global $smarty;

$displayDetail = isset($_POST['displayDetail']) ? $_POST['displayDetail'] : 'no';

$smarty->assign('displayDetail', $displayDetail);
