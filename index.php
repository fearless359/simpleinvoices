<?php /** @noinspection PhpUndefinedFieldInspection */

use Inc\Claz\Biller;
use Inc\Claz\CheckPermission;
use Inc\Claz\Customer;
use Inc\Claz\Invoice;
use Inc\Claz\Funcs;
use Inc\Claz\Log;
use Inc\Claz\PdoDbException;
use Inc\Claz\Product;
use Inc\Claz\Setup;
use Inc\Claz\SiAcl;
use Inc\Claz\SiError;
use Inc\Claz\SqlPatchManager;
use Inc\Claz\Util;

/*
 * Script: index.php
 * Main controller file for SimpleInvoices
 * License:
 * GPL v3 or above
 */

// **********************************************************
// The include configs and requirements stuff section - START
// **********************************************************
$lclPath = get_include_path() .
    PATH_SEPARATOR . "./library/" .
    PATH_SEPARATOR . "./include/";
if (set_include_path($lclPath) === false) {
    echo "<h1>Unable to set include path. Request terminated.</h1>";
    exit();
}

require_once 'vendor/autoload.php';

Util::allowDirectAccess();

$module = isset($_GET['module']) ? Util::filenameEscape($_GET['module']) : "";
$view = isset($_GET['view']) ? Util::filenameEscape($_GET['view']) : "";
$action = isset($_GET['case']) ? Util::filenameEscape($_GET['case']) : "";
$apiRequest = $module == 'api';

require_once 'config/define.php';

/***********************************************************************
 * Make sure the public and tmp directories exist and are writeable.
 ***********************************************************************/
if (!file_exists('./public')) {
    mkdir('./public');
} elseif (!is_writable('./public')) {
    SiError::out('notWritable', 'directory', './public');
}

if (!file_exists('./tmp')) {
    mkdir('./tmp');
} elseif (!is_writable('./tmp')) {
    SiError::out('notWritable', 'directory', './tmp');
}

if (!file_exists('./tmp/database_backups')) {
    mkdir('./tmp/database_backups');
} elseif (!is_writable('tmp/database_backups')) {
    SiError::out('notWritable', 'file', './tmp/database_backups');
}

if (!file_exists('./tmp/cache')) {
    mkdir('./tmp/cache');
} elseif (!is_writable('tmp/cache')) {
    SiError::out('notWritable', 'file', './tmp/cache');
}

if (!file_exists('./tmp/template_c')) {
    mkdir('./tmp/template_c');
} elseif (!is_writable('tmp/template_c')) {
    SiError::out('notWritable', 'file', './tmp/template_c');
}

if (!file_exists('./tmp/log')) {
    mkdir('./tmp/log');
} elseif (!is_writable('tmp/log')) {
    SiError::out('notWritable', 'directory', './tmp/log');
}

if (!file_exists('./tmp/pdf_tmp')) {
    mkdir('./tmp/pdf_tmp');
} elseif (!is_writable('tmp/pdf_tmp')) {
    SiError::out('notWritable', 'directory', './tmp/pdf_tmp');
}

try {
    $setup = new Setup(CONFIG_SECTION, empty($module));
    $config = $setup->getConfig();
    $dbInfo = $setup->getDbInfo();
    $pdoDb = $setup->getPdoDb();
    $pdoDbAdmin = $setup->getPdoDbAdmin();
} catch (Exception $exp) {
    // Error already reported so simply exit.
    exit();
}

// globals set in the init.php logic
$databaseBuilt = false;
$databasePopulated = false;

// It's possible that we are in the initial install mode. If so, set
// a flag so we won't terminate on an "Unknown database" error later.
$databaseBuilt = $pdoDbAdmin->checkTableExists('biller');

$timeout = 0;
if ($apiRequest) {
    if (!$databaseBuilt) {
        exit("Database not built. Can't run batch job.");
    }
} elseif ($databaseBuilt) {
    // If session_timeout is defined in the database, use it. If not
    // set it to the 60-minute default.
    try {
        $pdoDbAdmin->addSimpleWhere('name', 'session_timeout');
        $pdoDbAdmin->setSelectList('value');
        $rows = $pdoDbAdmin->request('SELECT', 'system_defaults');
        $timeout = empty($rows) ? 0 : intval($rows[0]['value']);
        Log::out("index.php - session_timeout loaded[$timeout]");
    } catch (PdoDbException $pde) {
        $timeout = 0;
    }
}

// Force api request timeout to 60 seconds.
if ($apiRequest || $timeout <= 0) {
    $timeout = 60;
}

Util::sessionTimeout($timeout);
session_name('SiAuth');
session_start();

// Will be set in the following init.php call to extensions that are enabled.
$extNames = [];
$helpImagePath = "images/";

// formatter:off
include_once "include/init.php";
global $LANG,
       $earlyExit,
       $extNames,
       $menu,
       $path,
       $siUrl,
       $smarty,
       $smartyOutput;
// formatter:on

Log::out("index.php - After init.php - module($module] view[$view]");
foreach ($extNames as $extName) {
    if (file_exists("extensions/{$extName}/include/init.php")) {
        /** @noinspection PhpIncludeInspection */
        $extInitFile = "extensions/{$extName}/include/init.php";
        Log::out("index.php - extInitFile[{$extInitFile}]");
        require_once $extInitFile;
    }
}
Log::out("index.php - After processing init.php for extensions");

// **********************************************************
// The include configs and requirements stuff section - END
// **********************************************************

$smarty->assign("LANG", $LANG);
$smarty->assign("config", $config);
$smarty->assign("enabled", [$LANG['disabled'], $LANG['enabled']]);
$smarty->assign("ext_names", $extNames);
$smarty->assign("helpImagePath", $helpImagePath);
$smarty->assign("module", $module);
$smarty->assign("siUrl", $siUrl);
$smarty->assign("view", $view);

// Menu - hide or show menu
if (!isset($menu)) {
    $menu = true;
}

// Check for any un-applied SQL patches when going home
// TODO - redo this code
Log::out("index.php - module[$module] view[$view] " .
    "databaseBuilt[$databaseBuilt] databasePopulated[$databasePopulated]");

if ($module == "options" && $view == "database_sqlpatches") {
    SqlPatchManager::donePatchesMessage();
} else {
    // Check that database structure has been built and populated.
    $applyDbPatches = true;
    if (!$databaseBuilt) {
        $module = "install";
        $view == "structure" ? $view = "structure" : $view = "index";
        $applyDbPatches = false; // do installer
    } elseif (!$databasePopulated) {
        $module = "install";
        $view == "essential" ? $view = "essential" : $view = "structure";
        $applyDbPatches = false; // do installer
    } elseif ($module == 'install' && $view == 'sample_data') {
        $applyDbPatches = false;
    }

    Log::out("index.php - apply_db_patches[$applyDbPatches]");

    // See if we need to verify patches have been loaded.
    if ($applyDbPatches) {
        Log::out("index.php - config->authentication->enabled[{$config['authenticationEnabled']}] " .
                                  "_SESSION['id']: " . print_r($_SESSION['id'], true));
        // If default user or an active session exists, proceed with check.
        if ($config['authenticationEnabled'] == DISABLED || isset($_SESSION['id'])) {
            // Check if there are patches to process
            if (SqlPatchManager::numberOfUnappliedPatches() > 0) {
                $module = "options";
                $view = "database_sqlpatches";
                Log::out("index.php - module[$module] view[$view] action[$action] Unapplied patches found");
                if ($action == "run") {
                    SqlPatchManager::runPatches();
                } else {
                    SqlPatchManager::listPatches();
                }
                $menu = false;
            } else {
                Log::out("index.php - all patches applied - module[$module] view[$view]");
                // All patches have been applied. Now check to see if the database has been set up.
                // It is considered setup when there is at least one biller, one customer and one product.
                // If it has not been set up, allow the user to add a biller, customer, product or to
                // modify the setting options.
                if (!empty($module)) {
                    if ($view == 'create' && ($module == 'billers' || $module == 'customers' || $module == 'products') ||
                        $module == 'system_defaults' && ($view == 'manage' || $view == 'edit' || $view == 'save')) {
                        $stillDoingSetup = false;
                    } else {
                        $stillDoingSetup = false;
                        try {
                            if (Invoice::count() == 0) {
                                $stillDoingSetup = true;
                                if (Biller::count() > 0 && Customer::count() > 0 && Product::count() > 0) {
                                    $stillDoingSetup = false;

                                    // Biller, Customer and Product set up but no invoices. Check to
                                    // see if this is the first time we've encountered this. If so,
                                    // flag $stillDoingSetup but set install completed status in
                                    // database so subsequent requests will go to the specified screen.
                                    $rows = $pdoDb->request('SELECT', 'install_complete');
                                    if (empty($rows) || $rows[0]['completed'] != ENABLED) {
                                        $pdoDb->setFauxPost(['completed' => ENABLED]);
                                        if (empty($rows)) {
                                            $pdoDb->request('INSERT', 'install_complete');
                                        } else {
                                            $pdoDb->request('UPDATE', 'install_complete');
                                        }
                                        $stillDoingSetup = true;
                                    }
                                }
                            }
                        } catch (PdoDbException $pde) {
                            error_log("index.php: Unable to set install_complete flag. Error: " . $pde->getMessage());
                            die("Unable to set install complete flag. See error log for additional information.");
                        }
                    }
                } else {
                    $stillDoingSetup = true;
                }

                Log::out("index.php - stillDoingSetup[$stillDoingSetup]");

                if ($stillDoingSetup) {
                    try {
                        if (Invoice::count() > 0) {
                            Invoice::updateAging();
                            $module = "invoices";
                            $view = "manage";
                        } else {
                            $module = "index";
                            $view = "index";
                        }
                    } catch (PdoDbException $pde) {
                        error_log("index.php: Unable to get Invoice count to set default module and view. Error: " . $pde->getMessage());
                        die("Unable to set install complete flag. See error log for additional information.");
                    }
                }
            }
        }
    }
}

Log::out("index.php - module[" . (empty($module) ? "" : $module) .
    "] view[" . (empty($view) ? "" : $view) .
    "] action[" . (empty($action) ? "" : $action) .
    "] id[" . (empty($_GET['id']) ? "" : $_GET['id']) .
    "] menu[$menu]");
$smarty->assign('menu', $menu);

if (!CheckPermission::isAllowed($module, $view)) {
    $role = SiAcl::getSessionRole();
    Log::out("index.php - CheckPermission::isAllowed('{$module}', '{$view}') returned false for role[{$role}].");
    $module = "errorPages";
    $view = "401";
}

// This logic is for the default_invoice where the invoice "template" (aka record)
// is used to make the new invoice.
if ($module == "invoices" && strstr($view, "template")) {
    // Get the default module path php if their aren't any for enabled extensions.
    $myPath = Util::getCustomPath("invoices/template", 'module');
    Log::out("index.php - default invoice template path[$myPath]");
    if (!empty($myPath)) {
        /** @noinspection PhpIncludeInspection */
        include_once $myPath;
    }
    exit();
}
Log::out("index.php - After invoices/template");

// Check for "api" module or a "xml" or "ajax" "page request" (aka view)
if ($apiRequest || strstr($view, "xml") || strstr($view, "ajax")) {
    $extensionXml = 0;
    foreach ($extNames as $extName) {
        if (file_exists("extensions/{$extName}/modules/$module/$view.php")) {
            /** @noinspection PhpIncludeInspection */
            include "extensions/{$extName}/modules/$module/$view.php";
            $extensionXml++;
        }
    }

    // Load default if none found for enabled extensions.
    $myPath = Util::getCustomPath("$module/$view", 'module');
    if ($extensionXml == 0 && isset($myPath)) {
        /** @noinspection PhpIncludeInspection */
        include $myPath;
    }

    exit(0);
}
Log::out("index.php - After api/xml or ajax");

// **********************************************************
// Prep the page - load the header stuff - START
// **********************************************************

$extensionJqueryFiles = "";
foreach ($extNames as $extName) {
    $path = "extensions/$extName/include/jquery/$extName.jquery.ext.js";
    if (file_exists($path)) {
        $extensionJqueryFiles .=  "<script src='{$path}'></script>";
    }
}
$smarty->assign("extension_jquery_files", $extensionJqueryFiles);
Log::out("index.php - After extension_jquery_files");

// Load any hooks that are defined for extensions
foreach ($extNames as $extName) {
    if (file_exists("extensions/$extName/templates/default/hooks.tpl")) {
        $smarty->$smartyOutput("extensions/$extName/templates/default/hooks.tpl");
    }
}
// Load standard hooks file. Note that any module hooks loaded will not be
// impacted by loading this file.
$smarty->$smartyOutput("custom/hooks.tpl");
Log::out("index.php - after custom/hooks.tpl");

if (!in_array($module . "_" . $view, $earlyExit)) {
    $extensionHeader = 0;
    foreach ($extNames as $extName) {
        $phpFile = "extensions/$extName/templates/default/header.tpl";
        if (file_exists("extensions/$extName/templates/default/header.tpl")) {
            $smarty->$smartyOutput("extensions/$extName/templates/default/header.tpl");
            $extensionHeader++;
        }
    }

    if ($extensionHeader == 0) {
        $myPath = Util::getCustomPath('header');
        if (isset($myPath)) {
            $smarty->$smartyOutput($myPath);
        }
    }
}
Log::out("index.php - after header.tpl");

// **********************************************************
// Prep the page - load the header stuff - END
// **********************************************************

// **********************************************************
// Include php file for the requested page section - START
// **********************************************************
// See https://simpleinvoices.group/howto page extension topic.
$extensionPhpInsertFiles = [];

$performExtensionPhpInsertions = $module == 'system_defaults' && $view == 'edit';
$extensionPhpFile = 0;
foreach ($extNames as $extName) {
        $phpFile = "extensions/$extName/modules/$module/$view.php";
    if (file_exists($phpFile)) {
        Log::out("index.php - extension module exists - phpFile[$phpFile]");
        // If $performExtensionPhpInsertions is true, then the extension php
        // file content is to be included in the standard php file. Otherwise,
        // the file is a replacement for the standard php file.
        if ($performExtensionPhpInsertions) {
            // @formatter:off
            $vals = [
                "file" => $phpFile,
                "module" => $module,
                "view"   => $view
            ];
            $extensionPhpInsertFiles[$extName] = $vals;
            // @formatter:on
        } else {
            /** @noinspection PhpIncludeInspection */
            include $phpFile;
            $extensionPhpFile++;
        }
    }
}
Log::out("index.php - After extension_php_insert_files, etc.");
if ($module == "reports" && ($view == "export" || $view == "email")) {
    // TODO: Make this relative to an extension if present.
    $path = 'templates/default/reports/';
    $smarty->assign('path', $path);
    Log::out("index.php - reports export/email path[{$path}]");
}

if ($extensionPhpFile == 0) {
    $myPath = Util::getCustomPath("$module/$view", 'module');
    if (isset($myPath)) {
        Log::out("index.php - my_path[$myPath]");
        /** @noinspection PhpIncludeInspection */
        include $myPath;
    }
}

// **********************************************************
// Include php file for the requested page section - END
// **********************************************************
if ($module == "export" || $view == "export") {
    Log::out("index.php - path[{$path}] Before export exit");
    exit(0);
}
Log::out("index.php - After export/export exit");

// **********************************************************
// Post load javascript files - START
// NOTE: This is loaded after the .php file so that it can
// use script load for the .php file.
// **********************************************************
foreach ($extNames as $extName) {
    if (file_exists("extensions/{$extName}/include/jquery/{$extName}.post_load.jquery.ext.js.tpl")) {
        $smarty->$smartyOutput("extensions/{$extName}/include/jquery/{$extName}.post_load.jquery.ext.js.tpl");
    }
}

// NOTE: Don't load the default file if we are processing an authentication "auth" request.
// if ($extensionPostLoadJquery == 0 && $module != 'auth') {
if ($module != 'auth' && !($module == 'payments' && $view == 'print')) {
    $smarty->$smartyOutput("include/jquery/post_load.jquery.ext.js.tpl");
}
Log::out("index.php - post_load...");

// **********************************************************
// Post load javascript files - END
// **********************************************************

// **********************************************************
// Main: Custom menu - START
// **********************************************************
if ($menu) {
    // Check for menu.tpl files for extensions. The content of these files is:
    //
    // <!-- BEFORE:tax_rates -->
    // <li>
    // <a {if $pageActive == "custom_flags"} class="active"{/if} href="index.php?module=custom_flags&amp;view=manage">
    // {$LANG.customFlagsUc}
    // </a>
    // </li>
    // {if $subPageActive == "custom_flags_view"}
    // <li>
    // <a class="active active_subpage" href="#">
    // {$LANG.view}
    // </a>
    // </li>
    // {/if}
    // {if $subPageActive == "custom_flags_edit"}
    // <li>
    // <a class="active active_subpage" href="#">
    // {$LANG.edit}
    // </a>
    // </li>
    // {/if}
    //
    // This means the content of the extension's menu.tpl file will be inserted before the
    // following line in the default menu.tpl file:
    //
    // <!~- SECTION:tax_rates -->
    //
    // If no matching section is found, the file will NOT be inserted.
    $myPath = Util::getCustomPath('menu');
    if (isset($myPath)) {
        Log::out("index.php - menu my_path[$myPath]");
        try {
            $menuTpl = $smarty->fetch($myPath);
        } catch (Exception $exp) {
            die("Unable to fetch menu path. Error: " . $exp->getMessage());
        }
        $lines = [];
        $sections = [];
        Funcs::menuSections($menuTpl, $lines, $sections);
        $menuTpl = Funcs::mergeMenuSections($extNames, $lines, $sections);
        echo $menuTpl;
    }
}
Log::out("index.php - After menuTpl processed");

// **********************************************************
// Main: Custom menu - END
// **********************************************************

// **********************************************************
// Main: Custom layout - START
// **********************************************************
if (!in_array($module . "_" . $view, $earlyExit)) {
    $extensionMain = 0;
    foreach ($extNames as $extName) {
        if (file_exists("extensions/{$extName}/templates/default/main.tpl")) {
            $smarty->$smartyOutput("extensions/{$extName}/templates/default/main.tpl");
            $extensionMain++;
        }
    }

    if ($extensionMain == "0") {
        $myPath = Util::getCustomPath('main');
        if (isset($myPath)) {
            $smarty->$smartyOutput($myPath);
        }
    }
}
Log::out("index.php - After main.tpl");
// **********************************************************
// Main: Custom layout - END
// **********************************************************

// **********************************************************
// Smarty template load - START
// **********************************************************
$extensionTemplates = 0;
$myTplPath = '';
$path = '';
$realPath = '';
// For extensions with a report, this logic allows them to be inserted into the
// the report menu (index.tpl) without having to replicate the content of that
// file. There two ways to insert content; either as a new menu section or as
// an appendage to an existing section. There are examples of each of these.
// Refer to the "expense" extension report index.tpl file for insertion of
// a new menu section. Note the "data-section" with the "BEFORE" entry. This
// tells the program to insert the menu before the menu section with the
// "$LANG.xxxxx" value that appears following the "BEFORE" statement. To
// append to an existing menu section, refer to the report index.tpl file
// for the "past_due_report" extension. Note the "data-section" attribute
// in the "<span ...>" tag. This tells the program to insert the report
// menu item at the end of the section with "$LANG.xxxxx" value assigned
// to the attribute.
$extensionInsertionFiles = [];
$performExtensionInsertions =
    $module == 'reports' && $view == 'index' ||
    $module == 'system_defaults' && $view == 'manage';
Log::out("index.php - performExtensionInsertions[{$performExtensionInsertions}]");
Log::out("index.php - extNames: " .print_r($extNames, true));
foreach ($extNames as $extName) {
    if ($extName == "core") {
        continue;
    }
    $tplFile = "extensions/$extName/templates/default/$module/$view.tpl";
    Log::out("index.php - extension tplFile[{$tplFile}]");
    if (file_exists($tplFile)) {
        // If $performExtensionInsertions is true, the $path and
        // $extensionTemplates are not set/incremented intentionally.
        // The logic runs through the normal report template logic
        // with the index.tpl files for each one of the extensions
        // reports will be loaded for the section it goes in.
        if ($performExtensionInsertions) {
            $content = file_get_contents($tplFile);
            $type = "";
            if (($pos = strpos($content, 'data-section="')) === false) {
                $section = $smarty->tpl_vars['LANG']->value['other'];
            } else {
                $pos += 14;
                $str = substr($content, $pos);
                $exp = "^BEFORE \{\$LANG\.";
                if (preg_match($exp, $str)) {
                    $pos += 14;
                    $type = "BEFORE ";
                } else {
                    $pos += 7;
                    $type = "";
                }
                $end = strpos($content, '}', $pos);
                $len = $end - $pos;
                $langElement = substr($content, $pos, $len);
                $section = $smarty->tpl_vars['LANG']->value[$langElement];
            }
            // @formatter:off
            $vals = [
                "file"    => $tplFile,
                "module"  => $module,
                "section" => $type . $section
            ];

            $extensionInsertionFiles[] = $vals;
            // @formatter:on
        } else {
            $path = "extensions/$extName/templates/default/$module/";
            $realPath = "templates/default/$module/";
            $myTplPath = $tplFile;
            $extensionTemplates++;
            Log::out("index.php - extension path[{$path}] realPath[{$realPath}] myTplPath[{$myTplPath}]");
        }
    }
}

// TODO: if more than one extension has a template for the requested file, that's trouble :(
// This won't happen for reports, standard menu.tpl and system_defaults menu.tpl given
// changes implemented in this file for them. Similar changes should be implemented for
// other templates as needed.
if ($extensionTemplates == 0) {
    /** @var string $myTplPath */
    $myTplPath = Util::getCustomPath("$module/$view");
    if (isset($myTplPath)) {
        $path = dirname($myTplPath) . '/';
        $realPath = $path;
        $extensionTemplates++;
    }
}
Log::out("index.php - final path[{$path}] realPath[{$realPath}] myTplPath[{$myTplPath}]");

$smarty->assign("extension_insertion_files", $extensionInsertionFiles);
$smarty->assign("perform_extension_insertions", $performExtensionInsertions);

// If this is not an extension, $path and $realPath are the same. If it is an extension,
// $path is relative to the extension and $realPath is relative to the standard library path.
$smarty->assign("path", $path);
$smarty->assign("realPath", $realPath);

Log::out("index.php - path[$path] my_tpl_path[$myTplPath] realPath[$realPath]");
$smarty->$smartyOutput($myTplPath);
Log::out("index.php - After output my_tpl_path[$myTplPath]");

// If no smarty template - add message
if ($extensionTemplates == 0) {
    error_log("NO TEMPLATE!!! for module[$module] view[$view]");
}
// **********************************************************
// Smarty template load - END
// **********************************************************

// **********************************************************
// Footer - START
// **********************************************************
if (!in_array($module . "_" . $view, $earlyExit)) {
    $extensionFooter = 0;
    foreach ($extNames as $extName) {
        if (file_exists("extensions/$extName/templates/default/footer.tpl")) {
            $smarty->$smartyOutput("extensions/$extName/templates/default/footer.tpl");
            $extensionFooter++;
        }
    }

    if ($extensionFooter == 0) {
        $smarty->$smartyOutput(Util::getCustomPath('footer'));
    }
}
Log::out("index.php - At END\n\n");
// **********************************************************
// Footer - END
// **********************************************************
