<?php
global $smarty;

$displayDetail = $_POST['displayDetail'] ?? 'no';

$smarty->assign('displayDetail', $displayDetail);
