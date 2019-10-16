<?php

use Inc\Claz\Acl;
use Inc\Claz\ApiAuth;
use Inc\Claz\CheckPermission;
use Inc\Claz\Db;
use Inc\Claz\Extensions;
use Inc\Claz\Log;
use Inc\Claz\PdoDb;
use Inc\Claz\PdoDbException;
use Inc\Claz\SiError;
use Inc\Claz\SqlPatchManager;
use Inc\Claz\SystemDefaults;
use Inc\Claz\Util;

/**
 * @var PdoDb $pdoDb_admin
 */
global $auth_session, $config, $pdoDb_admin;

require_once 'smarty/libs/Smarty.class.php';
require_once 'library/paypal/paypal.class.php';
require_once 'library/HTMLPurifier/HTMLPurifier.standalone.php';

// It's possible that we are in the initial install mode. If so, set
// a flag so we won't terminate on an "Unknown database" error later.
$databaseBuilt = $pdoDb_admin->checkTableExists('biller');

$timeout = 0;
if ($api_request) {
    if (!$databaseBuilt) {
        exit("Database not built. Can't run batch job.");
    }
} else {
    // If session_timeout is defined in the database, use it. If not
    // set it to the 60-minute default.
    if ($databaseBuilt) {
        try {
            $pdoDb_admin->addSimpleWhere('name', 'session_timeout');
            $pdoDb_admin->setSelectList('value');
            $rows = $pdoDb_admin->request('SELECT', 'system_defaults');
            $timeout = (empty($rows) ? 0 : intval($rows[0]['value']));
            Log::out("session_timeout loaded[$timeout]", Zend_Log::DEBUG);
        } catch (PdoDbException $pde) {
            $timeout = 0;
        }
    }
}

// Force api request timeout to 60 seconds.
if ($api_request || $timeout <= 0) {
    $timeout = 60;
}

try {
    $auth_session->setExpirationSeconds($timeout * 60);
} catch (Zend_Session_Exception $zse) {
    SiError::out('generic', 'Zend_Session_Exception', $zse->getMessage());
}

$frontendOptions = array('lifetime' => ($timeout * 60), 'automatic_serialization' => true);
Log::out("init.php - frontendOptions - " . print_r($frontendOptions,true), Zend_log::DEBUG);

/* *************************************************************
 * Zend Framework cache section - start
 * -- must come after the tmp dir writable check
 * *************************************************************/
$backendOptions = array('cache_dir' => './tmp/cache'); // Directory where to put the cache files

// getting a Zend_Cache_Core object
try {
    $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
} catch (Zend_Cache_Exception $zce) {
    SiError::out('generic', 'Zend_Cache_Exception', $zce->getMessage());
}

// required for some servers
try {
    Zend_Date::setOptions(array('cache' => $cache)); // Active per Zend_Locale
} catch (Zend_Date_Exception $zde) {
    SiError::out('generic', 'Zend_Date_Exception', $zde->getMessage());
}
/* *************************************************************
 * Zend Framework cache section - end
 * *************************************************************/

$smarty = new Smarty();

// Should be false in production. Set to true to test new smarty update only.
$smarty->debugging = false;
$smarty->setConfigDir("config")
       ->setTemplateDir("templates")
       ->setCompileDir("tmp/template_c")
       ->setCacheDir("tmp/cache")
       ->setPluginsDir(array("library/smarty/libs/plugins", "include/smarty_plugins"));

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
// SC: Install path handling will need changes if used in non-HTML contexts
$install_path = Util::htmlsafe($path['dirname']);

// With the database built, a connection should be able to be made
// if the configuration user, password, etc. are set correctly.
$db = ($databaseBuilt ? Db::getInstance() : NULL);

$patchCount = 0;
if ($databaseBuilt) {
    // Set these global variables.
    $patchCount = SqlPatchManager::lastPatchApplied();
    $databasePopulated = ($patchCount > 0);
    if ($api_request && !$databasePopulated) {
        exit("Database must be populated to run a batch job.");
    }
}

// Turn authorization off if an api request, or
// the database is not built and populated, or
// a request to install sample data.
if ($api_request ||
    (!$databaseBuilt || !$databasePopulated) ||
    ($module == 'install' && $view == 'sample_data')) {
    $config->authentication->enabled = DISABLED;
}

$smarty->assign('patchCount', $patchCount);

try {
    $smarty->registerPlugin('modifier', "siLocal_number"         , array("Inc\Claz\SiLocal", "number"));
    $smarty->registerPlugin('modifier', "siLocal_number_trim"    , array("Inc\Claz\SiLocal", "numberTrim"));
    $smarty->registerPlugin('modifier', "siLocal_currency"       , array("Inc\Claz\SiLocal", "currency"));
    $smarty->registerPlugin('modifier', "siLocal_date"           , array("Inc\Claz\SiLocal", "date"));
    $smarty->registerPlugin('modifier', "siLocal_truncateStr"    , array("Inc\Claz\SiLocal", "truncateStr"));
    $smarty->registerPlugin('modifier', "siLocal_sqlDataWithTime", array("Inc\Claz\SiLocal", "sqlDateWithTime"));

    $smarty->registerPlugin('modifier', 'htmlsafe' , array('Inc\Claz\Util', 'htmlsafe'));
    $smarty->registerPlugin('modifier', 'outhtml'  , array('Inc\Claz\Util', 'outhtml'));
    $smarty->registerPlugin('modifier', 'urlencode', 'urlencode'); // PHP function
    $smarty->registerPlugin('modifier', 'urlsafe'  , array('Inc\Claz\Util', 'urlsafe'));
} catch (SmartyException $se) {
    SiError::out('generic', 'SmartyException', $se->getMessage());
}

global $ext_names;
Extensions::loadSiExtensions($ext_names, $config, $databaseBuilt, $patchCount);

// point to extension plugin directories if present.
$plugin_dirs = array();
foreach ($ext_names as $ext_name) {
    $dir_tmp = "extensions/$ext_name/include/smarty_plugins";
    if (is_dir($dir_tmp)) {
        $plugin_dirs[] = $dir_tmp;
    }
}

if (!empty($plugin_dirs)) {
    $smarty->addPluginsDir($plugin_dirs);
}

$defaults = SystemDefaults::loadValues($databaseBuilt);
$smarty->assign("defaults", $defaults);

include_once ('include/language.php');

// Load company name values from system_defaults table.
if (isset($defaults['company_name_item'])) {
    $LANG['company_name_item'] = $defaults['company_name_item'];
} else {
    $LANG['company_name_item'] = 'SimpleInvoices';
}

if (isset($defaults['company_name'])) {
    $LANG['company_name'] = $defaults['company_name'];
} else {
    $LANG['company_name'] = 'SimpleInvoices';
}
if (!$api_request) {
    Log::out("init.php - authentication-enabled[{$config->authentication->enabled}] fake_auth[{$auth_session->fake_auth}]", Zend_Log::DEBUG);

    // if user logged into SimpleInvoices with authentication set to false,
    // then use the fake authentication, killing the session that was started.
    if (($config->authentication->enabled == ENABLED) && ($auth_session->fake_auth == "1")) {
        try {
            Zend_Session::start();
            Zend_Session::destroy(true);
            header('Location: .');
        } catch (Zend_Session_Exception $zse) {
            die('Zend_Session_Exception - ' . $zse->getMessage());
        }
    }

    if ($config->authentication->enabled == ENABLED) {
        // TODO - this needs to be fixed !!
        if ($auth_session->domain_id == null) {
            $auth_session->domain_id = "1";
        }
        ApiAuth::authenticate($module, $auth_session);
    } else {
        // If auth not on - use default domain and user id of 1
        // Chuck the user details sans password into the Zend_auth session
        $auth_session->id = "1";
        $auth_session->domain_id = "1";
        $auth_session->username = "demo";
        $auth_session->email = "demo@simpleinvoices.group";
        // fake_auth is identifier to say that user logged in with auth off
        $auth_session->fake_auth = "1";
        // No Customer login as logins disabled
        $auth_session->user_id = "0";
    }
}

if ($config->authentication->enabled == ENABLED) {
    $acl = null;
    Acl::init($acl);

    // if authentication enabled then do acl check etc..
    foreach ($ext_names as $ext_name) {
        $fileName = $ext_name . "Acl.php";
        $filePath = "extensions/$ext_name/Inc/Claz/" . $fileName;
        if (file_exists($filePath)) {
            include_once($filePath);
            // In the Acl.php file for your extension, have a getResource() method
            // that returns each resource to add to the default.
            Acl::addResource($fileName::getResources(), $acl);
        }
    }

    CheckPermission::isAllowed($module, $acl);
}

/* *************************************************************
 * Array: $early_exit - Add pages that don't need a header or
 * that exit prior to adding the template add in here
 * *************************************************************/
$early_exit = array();
$early_exit[] = "auth_login";
$early_exit[] = "api_cron";
$early_exit[] = "auth_logout";
$early_exit[] = "export_pdf";
$early_exit[] = "export_invoice";
$early_exit[] = "statement_export";
$early_exit[] = "invoice_template";
$early_exit[] = "payments_print";
$early_exit[] = "documentation_view";

switch ($module) {
    case "export":
        $smarty_output = "fetch";
        break;
    default:
        $smarty_output = "display";
        break;
}

// get the url - used for templates / pdf
$siUrl = Util::getURL();

if (file_exists("public/data.json")) {
    Log::out("init.php - unlink public/data.json", Zend_Log::DEBUG);
    if (!unlink("public/data.json")) {
        Log::out("init.php - unlink failed", Zend_Log::DEBUG);
    }
}

