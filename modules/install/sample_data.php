<?php

use Inc\Claz\ImportJson;
use Inc\Claz\Log;
use Inc\Claz\PdoDbException;

global $pdoDb, $smarty;

$menu = false;
$file = "databases/json/sample_data.json";
// Note that the "si_" in the find list is replaced by TB_PREFIX which by default is "si_".
// However, it could be another value and the replacement supports this option.
$find = ['si_','DOMAIN-ID','LOCALE','LANGUAGE'];
$replace = [TB_PREFIX,'1','en_US','en_US'];

$sampleJson = new ImportJson($file, $find, $replace);
$collated = $sampleJson->collate();
Log::out("sample_data.php: collated[$collated]");
try {
    $pdoDb->query($collated);
    $displayBlock =
        '<div class="align__center width_50">' .
            '<p>Sample data has been successfully imported. You are now able to begin ' .
                'testing SimpleInvoices. When you have completed your test and want to ' .
                'install and use SimpleInvoices live, simply delete all the tables from ' .
                'the database and run SimpleInvoices again. It will install the tables ' .
                'and the essential data. Then select the option to being using SimpleInvoices ' .
                'and you will be guided through entry of the required Biller, Customer and ' .
                'Product records after which SimpleInvoices will be ready for you to begin ' .
                'generating new invoices.</p>' .
        '</div>' .
        '<div class="align__text-center  margin__top-3 margin__bottom-2"">' .
            '<a href="index.php" class="button positive">' .
                '<img src="../../images/tick.png" alt="" />Start Testing SimpleInvoices' .
            '</a>' .
        '</div>';

} catch (PdoDbException $pde) {
    error_log("sample_data.php: " . $pde->getMessage());
    $displayBlock =
        '<div class="si_message_error">' .
            '<p>Something bad happened. Sample data has NOT been imported into SimpleInvoices</p>' .
            '<p>Check the error log for information about this issue</p>' .
        '</div>';
}

$smarty->assign('display_block', $displayBlock);

