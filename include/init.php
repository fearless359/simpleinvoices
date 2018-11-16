<?php

use Inc\Claz\Config;
use Inc\Claz\Db;
use Inc\Claz\DbInfo;
use Inc\Claz\Log;
use Inc\Claz\PdoDb;
use Inc\Claz\PdoDbException;
use Inc\Claz\Setup;
use Inc\Claz\SiError;
use Inc\Claz\SqlPatchManager;
use Inc\Claz\SystemDefaults;

/* *************************************************************
 * Zend framework init - start
 * *************************************************************/

global $api_request, $config, $module, $pdoDb_admin;
if (!isset($api_request)) $api_request = false;

$lcl_path = get_include_path() .
    PATH_SEPARATOR . "./library/" .
    PATH_SEPARATOR . "./library/pdf" .
    PATH_SEPARATOR . "./library/pdf/fpdf" .
    PATH_SEPARATOR . "./include/";
if (set_include_path($lcl_path) === false) {
    error_log("Error reported by set_include_path() for path: {$lcl_path}");
}

require_once 'smarty/libs/Smarty.class.php';
require_once 'Zend/Loader/Autoloader.php';

/* *************************************************************
 * Zend framework init - beg
 * *************************************************************/
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

try {
    Zend_Session::start();
    $auth_session = new Zend_Session_Namespace('Zend_Auth');
} catch (Zend_Session_Exception $zse) {
    SiError::out('generic', 'Zend_Session_Exception', $zse->getMessage());
}
/* *************************************************************
 * Zend framework init - end
 * *************************************************************/

/* *************************************************************
 * Smarty init - start
 * *************************************************************/
require_once ("library/paypal/paypal.class.php");
require_once ('library/HTMLPurifier/HTMLPurifier.standalone.php');
include_once ('include/functions.php');

if (!is_writable('./tmp')) {
    SiError::out('notWritable', 'directory', './tmp');
}

if (!is_writable('tmp/cache')) {
    SiError::out('notWritable', 'file', './tmp/cache');
}

include_once ('config/define.php');
try {
    $updateCustomConfig = empty($module);
    Setup::init($updateCustomConfig);
} catch (PdoDbException $pde) {
    // Error already reported so simply exit.
    exit();
}

//try {
//    $noModule = empty($module);
//    $config = Config::init(CONFIG_SECTION, $noModule);
//} catch (Exception $e) {
//    echo "<h1 style='font-weight:bold;color:red;'>";
//    echo "  " . $e->getMessage() . " (Error code: {$e->getCode()})";
//    echo "</h1>";
//}

//$logger_level = (isset($config->zend->logger_level) ? strtoupper($config->zend->logger_level) : 'EMERG');
//Log::open($logger_level);
//Log::out("init.php - logger has been setup", \Zend_Log::DEBUG);
//
//try {
//    $dbInfo = new DbInfo(Config::CUSTOM_CONFIG_FILE, CONFIG_SECTION, CONFIG_DB_PREFIX);
//
//    $pdoDb = new PdoDb($dbInfo);
//    $pdoDb->clearAll(); // to eliminate never used warning.
//
//    // For use by admin functions only. This avoids issues of
//    // concurrent use with user app object, <i>$pdoDb</i>.
//    $pdoDb_admin = new PdoDb($dbInfo);
//    $pdoDb_admin->clearAll();
//} catch (PdoDbException $pde) {
//    if (preg_match('/.*{dbname|password|username}/', $pde->getMessage())) {
//        echo "<h1 style='font-weight:bold;color:red;'>Initial setup. Follow the following instructions:</h1>";
//        echo "<ol>";
//        echo "  <li>Make a mySQL compatible database with a user that has full access to it.</li>";
//        echo "  <li>In the \"config\" directory, copy the <b>config.php</b> file to <b>custom.config.php</b></li>";
//        echo "  <li>Modify the database settings in the <b>custom.config.php</b> file for the database made in step 1.";
//        echo "    <ul>";
//        echo "      <li>Set <b>database.params.dbname</b> to the name of the database.";
//        echo "      <li>Set <b>database.params.username</b> to the username of the database administrator.</li>";
//        echo "      <li>Set <b>database.params.password</b> to the database administrator password. Note you might need to include this in single quotes.</li>";
//        echo "    </ul>";
//        echo "  </li>";
//        echo "  <li>In your browser, execute the command to access SI again and follow the instructions.</li>";
//        echo "</ol>";
//    } else {
//        echo "<h1 style='font-weight:bold;color:red;'>";
//        echo "  " . $pde->getMessage() . " (Error code: {$pde->getCode()})";
//        echo "</h1>";
//    }
//    exit();
//}

// set up app with relevant php setting
//date_default_timezone_set($config->phpSettings->date->timezone);
//error_reporting($config->debug->error_reporting);
//
//ini_set('display_startup_errors', $config->phpSettings->display_startup_errors);
//ini_set('display_errors',         $config->phpSettings->display_errors);
//ini_set('log_errors',             $config->phpSettings->log_errors);
//ini_set('error_log',              $config->phpSettings->error_log);
//
//try {
//    // @formatter:off
//    $zendDb = Zend_Db::factory($config->database->adapter,
//                               array('host'     => $dbInfo->getHost(),
//                                     'username' => $dbInfo->getUsername(),
//                                     'password' => $dbInfo->getPassword(),
//                                     'dbname'   => $dbInfo->getDbname(),
//                                     'port'     => $dbInfo->getPort()));
//    // @formatter:on
//} catch (Zend_Db_Exception $zde) {
//    SiError::out('generic', 'Zend_Db_Exception', $zde->getMessage());
//}

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
            Log::out("session_timeout loaded[$timeout]", \Zend_Log::DEBUG);
        } catch (PdoDbException $pde) {
            $timeout = 0;
        }
    }
}
if ($api_request || $timeout <= 0) {
    $timeout = 60;
}

try {
    $auth_session->setExpirationSeconds($timeout * 60);
} catch (Zend_Session_Exception $zse) {
    SiError::out('generic', 'Zend_Session_Exception', $zse->getMessage());
}

$frontendOptions = array('lifetime' => ($timeout * 60), 'automatic_serialization' => true);
Log::out("init.php - frontendOptions - " . print_r($frontendOptions,true), \Zend_Log::DEBUG);

/* *************************************************************
 * Zend Framework cache section - start
 * -- must come after the tmp dir writable check
 * *************************************************************/
$backendOptions = array('cache_dir' => './tmp/'); // Directory where to put the cache files

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
$smarty->assign("config_file_path", Config::CUSTOM_CONFIG_FILE);

$smarty->debugging = false;
$smarty->setConfigDir("config")
       ->setTemplateDir("templates")
       ->setCompileDir("tmp/template_c")
       ->setCacheDir("tmp/cache")
       ->setPluginsDir(array("library/smarty/libs/plugins", "include/smarty_plugins"));

if (!is_writable($smarty->compile_dir)) {
    SiError::out("notWritable", 'folder', $smarty->compile_dir);
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
$install_path = htmlsafe($path['dirname']);

// With the database built, a connection should be able to be made
// if the configuration user, password, etc. are set correctly.
$db = ($databaseBuilt ? Db::getInstance() : NULL);

require_once ("include/sql_queries.php");

$patchCount = 0;
if ($databaseBuilt) {
    // Set these global variables.
    $patchCount = SqlPatchManager::lastPatchApplied();
    $databasePopulated = ($patchCount > 0);
    if ($api_request && !$databasePopulated) {
        exit("Database must be populated to run a batch job.");
    }
}

// Turn authorization off until database is built. It messes up the install screens.
// Or if this is a batch job
if ($api_request || (!$databaseBuilt || !$databasePopulated)) {
    $config->authentication->enabled = DISABLED;
    $module="";
}

$smarty->assign('patchCount', $patchCount);

try {
    $smarty->registerPlugin('modifier', "siLocal_number"     , array("Inc\Claz\SiLocal", "number"));
    $smarty->registerPlugin('modifier', "siLocal_number_trim", array("Inc\Claz\SiLocal", "number_trim"));
    $smarty->registerPlugin('modifier', "siLocal_currency"   , array("Inc\Claz\SiLocal", "currency"));
    $smarty->registerPlugin('modifier', "siLocal_date"       , array("Inc\Claz\SiLocal", "date"));

    $smarty->registerPlugin('modifier', 'htmlout'  , 'outhtml');
    $smarty->registerPlugin('modifier', 'htmlsafe' , 'htmlsafe');
    $smarty->registerPlugin('modifier', 'outhtml'  , 'outhtml');
    $smarty->registerPlugin('modifier', 'urlencode', 'urlencode');
    $smarty->registerPlugin('modifier', 'urlescape', 'urlencode');
    $smarty->registerPlugin('modifier', 'urlsafe'  , 'urlsafe');
} catch (SmartyException $se) {
    SiError::out('generic', 'SmartyException', $se->getMessage());
}

global $ext_names;
loadSiExtensions($ext_names);

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
    include ('include/include_auth.php');
}

include_once ('include/manageCustomFields.php');
include_once ("include/validation.php");

if ($config->authentication->enabled == ENABLED) {
    include_once ("include/acl.php");
    // if authentication enabled then do acl check etc..
    foreach ($ext_names as $ext_name) {
        if (file_exists("extensions/$ext_name/include/acl.php")) {
            require_once ("extensions/$ext_name/include/acl.php");
        }
    }
    include_once ("include/check_permission.php");
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
$siUrl = getURL();

/* *************************************************************
 * If using the following line, the DB settings should be
 * appended to the config array, instead of replacing it
 * (NOTE: NOT TESTED!)
 * *************************************************************/
include_once ("include/class/BackupDb.php");
