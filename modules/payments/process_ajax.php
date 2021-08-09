<?php

use Inc\Claz\Invoice;
use Inc\Claz\PdoDbException;
use Inc\Claz\Util;

/*
 * Script: auto_complete_search.php
 *     Do the autocomplete of invoice id in the process payment page
 *
 * License:
 *     GPL v3 or above
 */

Util::allowDirectAccess();

//if this page has error with auth remove the above line and figure out how to do it right

$lines = [];

try {
    $invoices = Invoice::getInvoicesWithHtmlTotals($_GET["q"]);
    foreach ($invoices as $invoice) {
        $total = Util::htmlsafe(number_format($invoices['total'], 2));
        $paid = Util::htmlsafe(number_format($invoices['paid'], 2));
        $owing = Util::htmlsafe(number_format($invoices['owing'], 2));
        $lines[] = "$invoice[id]|" .
            "<table>" .
                "<tr>" .
                    "<td class='details_screen'>{$invoice['preference']}:</td>" .
                    "<td>{$invoice['index_id']}</td>" .
                    "<td class='details_screen'>Total: </td>" .
                    "<td>$total</td>" .
                "</tr>" .
                "<tr>" .
                    "<td class='details_screen'>Biller: </td>" .
                    "<td>{$invoice['biller']} </td>" .
                    "<td class='details_screen'>Paid: </td>" .
                    "<td>$paid</td>" .
                "</tr>" .
                "<tr>" .
                    "<td class='details_screen'>Customer: </td>" .
                    "<td>{$invoice['customer']}</td>" .
                    "<td class='details_screen'>Owing: </td>" .
                    "<td><u>$owing</u></td>" .
                "</tr>" .
            "</table>\n";
    }
} catch (PdoDbException $pde) {
    exit("modules/payments/process_ajax.php Unexpected error: {$pde->getMessage()}");
}
foreach($lines as $line) {
    echo $line;
}
