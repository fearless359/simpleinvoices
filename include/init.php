<?php

use Inc\Claz\ApiAuth;
use Inc\Claz\Db;
use Inc\Claz\Extensions;
use Inc\Claz\Log;
use Inc\Claz\SiAcl;
use Inc\Claz\SiError;
use Inc\Claz\SqlPatchManager;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

global $apiRequest, $config, $databaseBuilt, $databasePopulated, $extNames, $module, $pdoDbAdmin, $view;

require_once 'library/paypal/paypal.class.php';

$smarty = new Smarty();

// Should be false in production. Set to true to test new smarty update only.
$smarty->debugging = false;
$smarty->setConfigDir("config")
       ->setTemplateDir("templates")
       ->setCompileDir("tmp/template_c")
       ->setCacheDir("tmp/cache")
       ->setPluginsDir(["vendor/smarty/smarty/libs/plugins", "include/smarty_plugins"]);

Util::getLocaleList();

if (!is_writable($smarty->getCompileDir())) {
    SiError::out("notWritable", 'folder', $smarty->getCompileDir());
}

// add stripslashes smarty function
try {
    $smarty->registerPlugin('modifier', "unescape", "stripslashes");
} catch (SmartyException $se) {
    SiError::out('generic', 'SmartyException', $se->getMessage());
}

// Keep this line. Uncomment to test smarty
//$smarty->testInstall();
/* *************************************************************
 * Smarty init - end
 * *************************************************************/

$path = pathinfo($_SERVER['REQUEST_URI']);

// With the database built, a connection should be able to be made
// if the configuration user, password, etc. are set correctly.
$db = $databaseBuilt ? Db::getInstance($config) : null;

$patchCount = 0;
if ($databaseBuilt) {
    // Set these global variables.
    $patchCount = SqlPatchManager::lastPatchApplied();
    if ($patchCount > 0 && $patchCount < SqlPatchManager::BEGINNING_PATCH_NUMBER) {
        exit("You need to load Fearless359/SimpleInvoices version master_2019.2 prior to loading this version.");
    }

    $databasePopulated = $patchCount >= SqlPatchManager::BEGINNING_PATCH_NUMBER;
    if ($apiRequest && !$databasePopulated) {
        exit("Database must be populated to run a batch job.");
    }
}

// Turn authorization off if an api request, or
// the database is not built and populated, or
// a request to install sample data.
if ($apiRequest || !$databaseBuilt || !$databasePopulated || $module == 'install' && $view == 'sample_data') {
    Log::out("init.php - apiRequest[$apiRequest] databaseBuilt[$databaseBuilt] databasePopulated[$databasePopulated] module[$module] view[$view]");
    $config['authenticationEnabled'] = DISABLED;
}

$smarty->assign('patchCount', $patchCount);
$unappliedPatches = SqlPatchManager::numberOfUnappliedPatches();

try {
    $smarty->registerPlugin('modifier', "utilCamelCase"      , ["Inc\Claz\Util", "camelCase"]);
    $smarty->registerPlugin('modifier', "utilCurrency"       , ["Inc\Claz\Util", "currency"]);
    $smarty->registerPlugin('modifier', "utilDate"           , ["Inc\Claz\Util", "date"]);
    $smarty->registerPlugin('modifier', "utilIntlDate"       , ["Inc\Claz\Util", "intlDate"]);
    $smarty->registerPlugin('modifier', "utilNumber"         , ["Inc\Claz\Util", "number"]);
    $smarty->registerPlugin('modifier', "utilNumberTrim"     , ["Inc\Claz\Util", "numberTrim"]);
    $smarty->registerPlugin('modifier', "utilRange"          , ["Inc\Claz\Util", "utilRange"]);
    $smarty->registerPlugin('modifier', "utilSqlDataWithTime", ["Inc\Claz\Util", "sqlDateWithTime"]);
    $smarty->registerPlugin('modifier', "utilTrim"           , ["Inc\Claz\Util", "trimmer"]);
    $smarty->registerPlugin('modifier', "utilTruncateStr"    , ["Inc\Claz\Util", "truncateStr"]);

    $smarty->registerPlugin('modifier', 'htmlSafe'   , ['Inc\Claz\Util', 'htmlSafe']);
    $smarty->registerPlugin('modifier', 'outHtml'    , ['Inc\Claz\Util', 'outHtml']);
    $smarty->registerPlugin('modifier', 'urlEncode'  , ['Inc\Claz\Util', 'urlEncode']);
    $smarty->registerPlugin('modifier', 'urlSafe'    , ['Inc\Claz\Util', 'urlSafe']);

    $smarty->registerPlugin('modifier', 'urlencode', 'urlencode'); // PHP function
} catch (SmartyException $se) {
    SiError::out('generic', 'SmartyException', $se->getMessage());
}

$extNames = Extensions::loadSiExtensions($config, $databaseBuilt, $patchCount);
Log::out("init.php - extNames: " . print_r($extNames, true));

// point to extension plugin directories if present.
$pluginDirs = [];
foreach ($extNames as $extName) {
    $dirTmp = "extensions/$extName/include/smarty_plugins";
    if (is_dir($dirTmp)) {
        $pluginDirs[] = $dirTmp;
    }
}

Log::out("init.php - pluginDir: " . json_encode($pluginDirs));
if (!empty($pluginDirs)) {
    $smarty->addPluginsDir($pluginDirs);
}

$defaults = SystemDefaults::loadValues($databaseBuilt);
$smarty->assign("defaults", $defaults);

include_once 'include/language.php';

// Load company name values from system_defaults table.
if (isset($defaults['company_name_item'])) {
    $LANG['companyNameItem'] = $defaults['company_name_item'];
} else {
    $LANG['companyNameItem'] = 'SimpleInvoices';
}

if (!$apiRequest) {
    $fakeAuth = $_SESSION['fakeAuth'] ?? "";
    Log::out("init.php - authenticationEnabled[{$config['authenticationEnabled']}] " .
        "$fakeAuth unappliedPatches[$unappliedPatches]");

    // if user logged into SimpleInvoices with authentication set to false,
    // then use the fake authentication, killing the session that was started.
    if ($config['authenticationEnabled'] == ENABLED && $fakeAuth == "1" && $unappliedPatches == 0) {
        session_destroy();

        header('Location: .');
    }

    // Authenticate only after all patches applied.
    if ($config['authenticationEnabled'] == ENABLED && $unappliedPatches == 0) {
        if (!isset($_SESSION['domain_id'])) {
            $_SESSION['domain_id'] = "1";
        }
        ApiAuth::authenticate($module, $view);

        try {
            SiAcl::init();
        } catch (Exception $exp) {
            exit("Unable to perform SiAcl::init() - error: " . $exp->getMessage());
        }

        // For ACL (Access Control List) in an extension, simple place any of the following
        // commands in the acl.php file for the extension. Note, see Inc/Claz/SiAcl.php for
        // examples of each of the following:
        //      acl->addRole(role)
        //      acl->addResource(resource)
        //      acl->allow(role, permission, resource)
        //      acl->deny(role, permission, resource)
        foreach ($extNames as $extName) {
            $fileName = $extName . "acl.php";
            $filePath = "extensions/$extName/Inc/Claz/" . $fileName;
            if (file_exists($filePath)) {
                include_once $filePath;
            }
        }
    } else {
        // If auth not on - use default domain and user id of 1
        // Chuck the user details sans password into $_SESSION.
        $_SESSION['id'] = "1";
        $_SESSION['domain_id'] = "1";
        $_SESSION['username'] = "demo";
        $_SESSION['email'] = "demo@simpleinvoices.group";
        // fakeAuth is identifier to say that user logged in with auth off
        $_SESSION['fakeAuth'] = "1";
        // No Customer login as logins disabled
        $_SESSION['user_id'] = "0";
        Log::out("init.php - \$_SESSION: " . print_r($_SESSION, true));
    }
}

/* *************************************************************
 * Array: $earlyExit - Add pages that don't need a header or
 * that exit prior to adding the template add in here
 * *************************************************************/
$earlyExit = [
    "api_cron",
    "auth_login",
    "auth_logout",
    "documentation_view",
    "export_invoice",
    "export_pdf",
    "invoice_template",
    "payments_print",
    "reports_export",
    "statement_export"
];

$smartyOutput = match ($module) {
    "export" => "fetch",
    default => "display",
};
Log::out("init.php - smartyOutput[$smartyOutput]");

// get the url - used for templates / pdf
$siUrl = Util::getURL();

if (file_exists("public/data.json")) {
    Log::out("init.php - unlink public/data.json");
    if (!unlink("public/data.json")) {
        Log::out("init.php - unlink failed");
    }
}

