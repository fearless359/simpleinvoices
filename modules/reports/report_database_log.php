<?php

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\Join;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;
use Inc\Claz\WhereItem;

global $pdoDb, $smarty;

Util::directAccessAllowed();

/**
 * @return string Beginning date for selection.
 */
function firstOfMonth() {
    return date("Y-m-d", strtotime('01-01-' . date('Y') . ' 00:00:00'));
}

/**
 * @return string Ending date for selection.
 */
function lastOfMonth() {
    return date("Y-m-d", strtotime('31-12-' . date('Y') . ' 00:00:00'));
}

$start_date = (isset($_POST['start_date']) ? $_POST['start_date'] : firstOfMonth());
$end_date   = (isset($_POST['end_date']  ) ? $_POST['end_date']   : lastOfMonth());

$rows = array();
try {
$pdoDb->addSimpleWhere('l.domain_id', DomainId::get(), 'AND');
$pdoDb->addToWhere(new WhereItem(false, 'l.timestamp', 'BETWEEN', array($start_date, $end_date), false));

$jn = new Join('INNER', 'user', 'u');
$jn->addSimpleItem('u.id', new DbField('l.userid'), 'AND');
$jn->addSimpleItem('u.domain_id', new DbField('l.domain_id'));
$pdoDb->addToJoins($jn);

$pdoDb->setOrderBy('l.timestamp');

$pdoDb->setSelectList(array('l.*', new DbField('u.email')));

$rows = $pdoDb->request('SELECT', 'log', 'l');
} catch (PdoDbException $pde) {

}
// SELECT l.*, u.email FROM si_log l
// INNER JOIN si_user u ON (u.id = l.userid AND u.domain_id = l.domain_id)
// WHERE l.domain_id = :domain_id AND l.timestamp BETWEEN :start AND :end
// ORDER BY l.timestamp

$patterns = array(
    'insert' => "/.*INSERT\s+INTO\s+" . TB_PREFIX . "invoices\s+/im",
    'update' => "/.*(UPDATE\s+" . TB_PREFIX . "invoices\s+SET.*WHERE\s.*id\s+=\s+)([0-9]+)\s+/im",
    'payment' => "/.*(INSERT\s+INTO\s+" . TB_PREFIX . "payment\s+\(.*\)\s+VALUES\s+\(\s+)([0-9]+)\s*,\s+([0-9\.]+)\s*,/im"
);
$inserts = array();
$updates = array();
$payments = array();
foreach($rows as $row) {
    $user = Util::htmlsafe($row['email']) . ' (id ' . Util::htmlsafe($row['userid']) . ')';
    $match = array();
    if (preg_match($patterns['insert'], $row['sqlquerie'])) {
         $inserts[] = array(
             'user' => $user,
             'last_id' => $row['last_id'],
             'timestamp' => $row['timestamp']);
    } else {
        $match = array();
        if (preg_match($patterns['update'], $row['sqlquerie'], $match)) {
            $updates[] = array(
                'user' => $user,
                'last_id' => $match[2],
                'timestamp' => $row['timestamp']);
        } else {
            $match = array();
            if (preg_match($patterns['payment'], $row['sqlquerie'], $match)) {
                $payments[] = array(
                    'user' => $user,
                    'last_id' => $match[2],
                    'timestamp' => $row['timestamp'],
                    'amount' => $match[3]);
            }
        }
    }
}
$smarty->assign('inserts' , $inserts);
$smarty->assign('updates' , $updates);
$smarty->assign('payments', $payments);

$smarty->assign('start_date', $start_date);
$smarty->assign('end_date'  , $end_date);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
