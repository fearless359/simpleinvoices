<?php
use Inc\Claz\ImportJson;
use Inc\Claz\PdoDbException;

global $databaseBuilt, $databasePopulated, $pdoDb;

$menu = false;

if ($databaseBuilt && !$databasePopulated) {
    $debug = false;
    $file = "databases/json/essential_data.json";
    // Note that the "si_" in the find list is replaced by TB_PREFIX which by
    // default is "si_". However, it could be another value and the replacement
    // supports this option.
    $find = array('si_','DOMAIN-ID','LOCALE','LANGUAGE');
    $replace = array(TB_PREFIX,'1','en_US','en_US');

    $importjson = new ImportJson($file, $find, $replace, $debug);
    $collated = $importjson->collate();
    try {
        $result = $pdoDb->query($collated);
        $display_block =
            '<div style="margin:0 auto 40px auto;width:50%;text-align:left;">' .
                '<p>The SimpleInvoices essential data has been imported. Using the buttons ' .
                    'below, you can choose to <strong>Start using SimpleInvoices</strong> or ' .
                    'to <strong>Install Sample Data</strong> to test SimpleInvoices further.</p>' .
                '<p><strong>NOTE:</strong> If the <strong><em>authentication.enabled</em></strong> ' .
                    'setting in the configuration file, <strong>config/custom.config.php</strong>, ' .
                    'is set to <strong>true</strong>. You will need to use the demonstration ID, ' .
                    '<strong>demo</strong>, and the password, <strong>demo</strong>.</p>' .
            '</div>' .
            '<div class="si_toolbar si_toolbar_form">' .
                '<a href="index.php" class="positive">' .
            '<img src="../../images/tick.png" alt="" />' .
                    'Start using SimpleInvoices' .
                '</a>' .
                '<a href="index.php?module=install&amp;view=sample_data" class="positive">' .
            '<img src="../../images/tick.png" alt="" />' .
                    'Install Sample Data' .
                '</a>' .
            '</div>';
    } catch (PdoDbException $pde) {
        error_log("essential.php: " . $pde->getMessage());
        $display_block =
            '<div class="si_form si_message_error">' .
            '<p>An error occurred while trying to import essential data</p>' .
            '<p>Error information should be in the error log</p>';
        '</div>';
    }

    $smarty->assign('display_block', $display_block);
}
