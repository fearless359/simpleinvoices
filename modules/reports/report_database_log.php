<?php
/** @noinspection SpellCheckingInspection */

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

$startDate = isset($_POST['start_date']) ? $_POST['start_date'] : firstOfMonth();
$endDate   = isset($_POST['end_date']  ) ? $_POST['end_date']   : lastOfMonth();

$rows = [];
try {
    $pdoDb->addSimpleWhere('l.domain_id', DomainId::get(), 'AND');
    $pdoDb->addToWhere(new WhereItem(false, 'l.timestamp', 'BETWEEN', [$startDate, $endDate], false));

    $jn = new Join('INNER', 'user', 'u');
    $jn->addSimpleItem('u.id', new DbField('l.user_id'), 'AND');
    $jn->addSimpleItem('u.domain_id', new DbField('l.domain_id'));
    $pdoDb->addToJoins($jn);

    $pdoDb->setOrderBy('l.timestamp');

    $pdoDb->setSelectList(['l.*', new DbField('u.email')]);

    $rows = $pdoDb->request('SELECT', 'log', 'l');

    $patterns = [
        'insert' => "/.*INSERT\s+INTO\s+" . TB_PREFIX . "invoices\s+/im",
        'update' => "/.*(UPDATE\s+" . TB_PREFIX . "invoices\s+SET.*WHERE\s.*id\s+=\s+)([0-9]+)\s+/im",
        'payment' => "/.*(INSERT\s+INTO\s+" . TB_PREFIX . "payment\s+\(.*\)\s+VALUES\s+\(\s+)([0-9]+)\s*,\s+([0-9\.]+)\s*,/im"
    ];

    $inserts = [];
    $updates = [];
    $payments = [];
    foreach($rows as $row) {
        $user = Util::htmlSafe($row['email']) . ' (id ' . Util::htmlSafe($row['user_id']) . ')';
        $match = [];
        if (preg_match($patterns['insert'], $row['sqlquerie'])) {
             $inserts[] = [
                 'user' => $user,
                 'last_id' => $row['last_id'],
                 'timestamp' => $row['timestamp']
             ];
        } else {
            $match = [];
            if (preg_match($patterns['update'], $row['sqlquerie'], $match)) {
                $updates[] =[
                    'user' => $user,
                    'last_id' => $match[2],
                    'timestamp' => $row['timestamp']
                ];
            } else {
                $match = [];
                if (preg_match($patterns['payment'], $row['sqlquerie'], $match)) {
                    $payments[] = [
                        'user' => $user,
                        'last_id' => $match[2],
                        'timestamp' => $row['timestamp'],
                        'amount' => $match[3]
                    ];
                }
            }
        }
    }

    $smarty->assign('inserts' , $inserts);
    $smarty->assign('updates' , $updates);
    $smarty->assign('payments', $payments);
} catch (PdoDbException $pde) {
    exit("modules/reports/report_database_log.php Unexpected error: {$pde->getMessage()}");
}

$smarty->assign('start_date', $startDate);
$smarty->assign('end_date'  , $endDate);

$smarty->assign('pageActive', 'report');
$smarty->assign('active_tab', '#home');
