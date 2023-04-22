<?php
/** @noinspection SpellCheckingInspection */

use Inc\Claz\DbField;
use Inc\Claz\DomainId;
use Inc\Claz\Join;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;
use Inc\Claz\WhereItem;

global $endDate, $pdoDb, $smarty, $startDate;

Util::directAccessAllowed();

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

    /** @noinspection RegExpRedundantEscape */
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
                 'lastId' => $row['last_id'],
                 'timestamp' => $row['timestamp']
             ];
        } else {
            $match = [];
            if (preg_match($patterns['update'], $row['sqlquerie'], $match)) {
                $updates[] =[
                    'user' => $user,
                    'lastId' => $match[2],
                    'timestamp' => $row['timestamp']
                ];
            } else {
                $match = [];
                if (preg_match($patterns['payment'], $row['sqlquerie'], $match)) {
                    $payments[] = [
                        'user' => $user,
                        'lastId' => $match[2],
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
    exit("modules/reports/reportDatabaseLog.php Unexpected error: {$pde->getMessage()}");
}
