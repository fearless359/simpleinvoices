<?php

use Inc\Claz\Biller;
use Inc\Claz\Customer;
use Inc\Claz\Invoice;
use Inc\Claz\Funcs;
use Inc\Claz\Log;
use Inc\Claz\PdoDbException;
use Inc\Claz\Product;
use Inc\Claz\Setup;
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
$lcl_path = get_include_path() .
    PATH_SEPARATOR . "./library/" .
    PATH_SEPARATOR . "./include/";
if (set_include_path($lcl_path) === false) {
    echo "<h1>Unable to set include path. Request terminated.</h1>";
    exit();
}

require_once 'vendor/autoload.php';

Util::allowDirectAccess();

$module = isset($_GET['module']) ? Util::filenameEscape($_GET['module']) : null;
$view   = isset($_GET['view'])   ? Util::filenameEscape($_GET['view'])   : null;
$action = isset($_GET['case'])   ? Util::filenameEscape($_GET['case'])   : null;

$api_request = ($module == 'api');

require_once 'config/define.php';

/***********************************************************************
 * Make sure the public and tmp directories exist and are writeable.
 ***********************************************************************/
if (!file_exists('./public')) {
    mkdir('./public');
} else if (!is_writable('./public')) {
    SiError::out('notWritable', 'directory', './public');
}

if (!file_exists('./tmp')) {
    mkdir('./tmp');
} else if (!is_writable('./tmp')) {
    SiError::out('notWritable', 'directory', './tmp');
}

if (!file_exists('./tmp/database_backups')) {
    mkdir('./tmp/database_backups');
} else if (!is_writable('tmp/database_backups')) {
    SiError::out('notWritable', 'file', './tmp/database_backups');
}

if (!file_exists('./tmp/cache')) {
    mkdir('./tmp/cache');
} else if (!is_writable('tmp/cache')) {
    SiError::out('notWritable', 'file', './tmp/cache');
}

if (!file_exists('./tmp/template_c')) {
    mkdir('./tmp/template_c');
} else if (!is_writable('tmp/template_c')) {
    SiError::out('notWritable', 'file', './tmp/template_c');
}

if (!file_exists('./tmp/log')) {
    mkdir('./tmp/log');
} else if (!is_writable('tmp/log')) {
    SiError::out('notWritable', 'directory', './tmp/log');
}

if (!file_exists('./tmp/pdf_tmp')) {
    mkdir('./tmp/pdf_tmp');
} else if (!is_writable('tmp/pdf_tmp')) {
    SiError::out('notWritable', 'directory', './tmp/pdf_tmp');
}

try {
    $updateCustomConfig = empty($module);
    Setup::init($updateCustomConfig, $config, $dbInfo, $pdoDb, $pdoDb_admin);
} catch (PdoDbException $pde) {
    // Error already reported so simply exit.
    exit();
}

try {
    Zend_Session::start();
    $auth_session = new Zend_Session_Namespace('Zend_Auth');
} catch (Zend_Session_Exception $zse) {
    SiError::out('generic', 'Zend_Session_Exception', $zse->getMessage());
}

// globals set in the init.php logic
$databaseBuilt     = false;
$databasePopulated = false;

// Will be set in the following init.php call to extensions that are enabled.
$ext_names = array();
$help_image_path = "images/common/";

include_once "include/init.php";
global $smarty,
       $smarty_output,
       $menu,
       $LANG,
       $siUrl,
       $early_exit;

Log::out("index.php - After init.php - module($module] view[$view]", Zend_Log::DEBUG);
foreach ($ext_names as $ext_name) {
    if (file_exists("extensions/$ext_name/include/init.php")) {
        require_once ("extensions/$ext_name/include/init.php");
    }
}
Log::out("index.php - After processing init.php for extensions", Zend_Log::DEBUG);

$smarty->assign("help_image_path", $help_image_path);
// **********************************************************
// The include configs and requirements stuff section - END
// **********************************************************

$smarty->assign("ext_names", $ext_names);
$smarty->assign("config"   , $config);
$smarty->assign("module"   , $module);
$smarty->assign("view"     , $view);
$smarty->assign("siUrl"    , $siUrl);
$smarty->assign("LANG"     , $LANG);
$smarty->assign("enabled"  , array($LANG['disabled'],$LANG['enabled']));

// Menu - hide or show menu
if (!isset($menu)) {
    $menu = true;
}

// Check for any unapplied SQL patches when going home
// TODO - redo this code
Log::out("index.php - module[$module] view[$view] " .
             "databaseBuilt[$databaseBuilt] databasePopulated[$databasePopulated]", Zend_Log::DEBUG);
error_log("index.php 161 - module[$module] view[$view] databaseBuild[$databaseBuilt] databasePopulated[$databasePopulated]");
if (($module == "options") && ($view == "database_sqlpatches")) {
    SqlPatchManager::donePatchesMessage();
} else {
    // Check that database structure has been built and populated.
    $apply_db_patches = true;
    if (!$databaseBuilt) {
        $module = "install";
        $view == "structure" ? $view = "structure" : $view = "index";
        $apply_db_patches = false; // do installer
    } else if (!$databasePopulated) {
        $module = "install";
        $view == "essential" ? $view = "essential" : $view = "structure";
        $apply_db_patches = false; // do installer
    } else if($module == 'install' && $view == 'sample_data') {
        $apply_db_patches = false;
    }

    Log::out("index.php - apply_db_patches[$apply_db_patches]", Zend_Log::DEBUG);

    // See if we need to verify patches have been loaded.
    if ($apply_db_patches) {
        Log::out("index.php - config->authentication->enabled[{$config->authentication->enabled}] auth_session->id: " .
            print_r($auth_session->id,true), Zend_Log::DEBUG);
        // If default user or an active session exists, proceed with check.
        if ($config->authentication->enabled == DISABLED || isset($auth_session->id)) {
            // Check if there are patches to process
            if (SqlPatchManager::numberOfUnappliedPatches() > 0) {
                $view = "database_sqlpatches";
                $module = "options";
                Log::out("index.php - view[$view] module[$module]", Zend_Log::DEBUG);
                if ($action == "run") {
                    SqlPatchManager::runPatches();
                } else {
                    SqlPatchManager::listPatches();
                }
                $menu = false;
            } else {
                Log::out("index.php - module - " . print_r($module,true), Zend_Log::DEBUG);
                // All patches have been applied. Now check to see if the database has been set up.
                // It is considered setup when there is at least one biller, one customer and one product.
                // If it has not been set up, allow the user to add a biller, customer, product or to
                // modify the setting options.
                if (isset($module)) {
                    if (($view == 'add' && ($module == 'billers' || $module == 'customers' || $module == 'products')) ||
                        ($module == 'system_defaults' && ($view == 'manage' || $view == 'edit' || $view == 'save'))) {
                        $still_doing_setup = false;
                    } else {
                        $still_doing_setup = false;
                        if (Invoice::count() == 0) {
                            $still_doing_setup = true;
                            if (Biller::count() > 0 && Customer::count() > 0 && Product::count()  > 0) {
                                $still_doing_setup = false;

                                try {
                                    // Biller, Customer and Product set up but no invoices. Check to
                                    // see if this is the first time we've encountered this. If so,
                                    // flag $still_doing_setup but set install completed status in
                                    // database so subsequent requests will go to the specified screen.
                                    $rows = $pdoDb->request('SELECT', 'install_complete');
                                    if (empty($rows) || $rows[0]['completed'] != ENABLED) {
                                        $pdoDb->setFauxPost(array('completed' => ENABLED));
                                        if (empty($rows)) {
                                            $pdoDb->request('INSERT', 'install_complete');
                                        } else {
                                            $pdoDb->request('UPDATE', 'install_complete');
                                        }
                                        $still_doing_setup = true;
                                    }
                                } catch(PdoDbException $pde) {
                                    error_log("index.php: Unable to set install_complete flag. Error: " . $pde->getMessage());
                                    die("Unable to set install complete flag. See error log for additional information.");
                                }
                            }
                        }
                    }
                } else {
                    $still_doing_setup = true;
                }

                Log::out("index.php - still_doing_setup[$still_doing_setup]", Zend_Log::DEBUG);

                if ($still_doing_setup) {
                    if (Invoice::count() > 0) {
                        Invoice::updateAging();
                        $module = "invoices";
                        $view = "manage";
                    } else {
                        $module = "index";
                        $view = "index";
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
                          "] menu[$menu]", Zend_Log::DEBUG);

// This logic is for the default_invoice where the invoice "template" (aka record)
// is used to make the new invoice.
if (($module == "invoices") && (strstr($view, "template"))) {
    // Get the default module path php if their aren't any for enabled extensions.
    $my_path = Util::getCustomPath("invoices/template", 'module');
    Log::out("index.php - default invoice template path[$my_path]");
    if (!empty($my_path)) {
        include_once ($my_path);
    }
    exit(0);
}
Log::out("index.php - After invoices/template", Zend_Log::DEBUG);

// Check for "api" module or a "xml" or "ajax" "page request" (aka view)
if ($api_request || (strstr($view, "xml") || (strstr($view, "ajax")))) {
    $extensionXml = 0;
    foreach ($ext_names as $ext_name) {
        if (file_exists("extensions/$ext_name/modules/$module/$view.php")) {
            include ("extensions/$ext_name/modules/$module/$view.php");
            $extensionXml++;
        }
    }

    // Load default if none found for enabled extensions.
    $my_path = Util::getCustomPath("$module/$view", 'module');
    if ($extensionXml == 0 && isset($my_path)) {
        include ($my_path);
    }

    exit(0);
}
Log::out("index.php - After api/xml or ajax", Zend_Log::DEBUG);

// **********************************************************
// Prep the page - load the header stuff - START
// **********************************************************

$extension_jquery_files = "";
foreach ($ext_names as $ext_name) {
    if (file_exists("extensions/$ext_name/include/jquery/$ext_name.jquery.ext.js")) {
        // @formatter:off
        $extension_jquery_files .=
            '<script type="text/javascript" src="extensions/' .
                     $ext_name . '/include/jquery/' .
                     $ext_name . '.jquery.ext.js">' .
            '</script>';
        // @formatter:on
    }
}
$smarty->assign("extension_jquery_files", $extension_jquery_files);

Log::out("index.php - After extension_jquery_files", Zend_Log::DEBUG);

// Load any hooks that are defined for extensions
foreach ($ext_names as $ext_name) {
    if (file_exists("extensions/$ext_name/templates/default/hooks.tpl")) {
        $smarty->$smarty_output("extensions/$ext_name/templates/default/hooks.tpl");
    }
}
// Load standard hooks file. Note that any module hooks loaded will not be
// impacted by loading this file.
$smarty->$smarty_output("custom/hooks.tpl");

Log::out("index.php - after custom/hooks.tpl", Zend_Log::DEBUG);

if (!in_array($module . "_" . $view, $early_exit)) {
    $extensionHeader = 0;
    foreach ($ext_names as $ext_name) {
        $phpfile = "extensions/$ext_name/templates/default/header.tpl";
        if (file_exists("extensions/$ext_name/templates/default/header.tpl")) {
            $smarty->$smarty_output("extensions/$ext_name/templates/default/header.tpl");
            $extensionHeader++;
        }
    }

    if ($extensionHeader == 0) {
        $my_path = Util::getCustomPath('header');
        if (isset($my_path)) {
            $smarty->$smarty_output($my_path);
        }
    }
}
Log::out("index.php - after header.tpl", Zend_Log::DEBUG);

// **********************************************************
// Prep the page - load the header stuff - END
// **********************************************************

// **********************************************************
// Include php file for the requested page section - START
// **********************************************************
// See https://simpleinvoices.group/howto page extension topic.
$extension_php_insert_files = array();

$perform_extension_php_insertions = (($module == 'system_defaults' && $view == 'edit'));
$extensionPhpFile = 0;
foreach ($ext_names as $ext_name) {
    $phpfile = "extensions/$ext_name/modules/$module/$view.php";
    if (file_exists($phpfile)) {
        // If $perform_extension_php_insertions is true, then the extension php
        // file content is to be included in the standard php file. Otherwise,
        // the file is a replacement for the standard php file.
        if ($perform_extension_php_insertions) {
            // @formatter:off
            $vals = array("file"   => $phpfile,
                          "module" => $module,
                          "view"   => $view);
            $extension_php_insert_files[$ext_name] = $vals;
            // @formatter:on
        } else {
            include ($phpfile);
            $extensionPhpFile++;
        }
    }
}
Log::out("index.php - After extension_php_insert_files, etc.", Zend_Log::DEBUG);

if ($extensionPhpFile == 0) {
    $my_path = Util::getCustomPath("$module/$view", 'module');
    if (isset($my_path)) {
        Log::out("index.php - my_path[$my_path]", Zend_Log::DEBUG);
        include ($my_path);
    }
}
// **********************************************************
// Include php file for the requested page section - END
// **********************************************************
if ($module == "export" || $view == "export") {
    Log::out("index.php - Before export exit", Zend_Log::DEBUG);
    exit(0);
}
Log::out("index.php - After export/export exit", Zend_Log::DEBUG);

// **********************************************************
// Post load javascript files - START
// NOTE: This is loaded after the .php file so that it can
// use script load for the .php file.
// **********************************************************
foreach ($ext_names as $ext_name) {
    if (file_exists("extensions/$ext_name/include/jquery/$ext_name.post_load.jquery.ext.js.tpl")) {
        $smarty->$smarty_output("extensions/$ext_name/include/jquery/$ext_name.post_load.jquery.ext.js.tpl");
    }
}

// NOTE: Don't load the default file if we are processing an authentication "auth" request.
// if ($extensionPostLoadJquery == 0 && $module != 'auth') {
if ($module != 'auth') {
    $smarty->$smarty_output("include/jquery/post_load.jquery.ext.js.tpl");
}
Log::out("index.php - post_load...", Zend_Log::DEBUG);

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
    // {$LANG.custom_flags_upper}
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
    $my_path = Util::getCustomPath('menu');
    if (isset($my_path)) {
        Log::out("index.php - menu my_path[$my_path]", Zend_Log::DEBUG);
        $menutpl = $smarty->fetch($my_path);
        $lines = array();
        $sections = array();
        Funcs::menuSections($menutpl, $lines, $sections);
        $menutpl = Funcs::mergeMenuSections($ext_names, $lines, $sections);
        echo $menutpl;
    }
}
Log::out("index.php - After menutpl processed", Zend_Log::DEBUG);

// **********************************************************
// Main: Custom menu - END
// **********************************************************

// **********************************************************
// Main: Custom layout - START
// **********************************************************
if (!in_array($module . "_" . $view, $early_exit)) {
    $extensionMain = 0;
    foreach ($ext_names as $ext_name) {
        if (file_exists("extensions/$ext_name/templates/default/main.tpl")) {
            $smarty->$smarty_output("extensions/$ext_name/templates/default/main.tpl");
            $extensionMain++;
        }
    }

    if ($extensionMain == "0") {
        $my_path = Util::getCustomPath('main');
        if (isset($my_path)) {
            $smarty->$smarty_output($my_path);
        }
    }
}
Log::out("index.php - After main.tpl", Zend_Log::DEBUG);
// **********************************************************
// Main: Custom layout - END
// **********************************************************

// **********************************************************
// Smarty template load - START
// **********************************************************
$extensionTemplates = 0;
$my_tpl_path = '';
$path = '';
$real_path = '';
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
$extension_insertion_files = array();
$perform_extension_insertions = (($module == 'reports'         && $view == 'index') ||
                                 ($module == 'system_defaults' && $view == 'manage'));

foreach ($ext_names as $ext_name) {
    $tpl_file = "extensions/$ext_name/templates/default/$module/$view.tpl";
    if (file_exists($tpl_file)) {
        // If $perform_extension_insertions is true, the $path and
        // $extensionTemplates are not set/incremented intentionally.
        // The logic runs through the normal report template logic
        // with the index.tpl files for each one of the extensions
        // reports will be loaded for the section it goes in.
        if ($perform_extension_insertions) {
            $content = file_get_contents($tpl_file);
            $type = "";
            if (($pos = strpos($content, 'data-section="')) === false) {
                $section = $smarty->tpl_vars['LANG']->value['other'];
            } else {
                $pos += 14;
                $str = substr($content, $pos);
                if (preg_match('/^BEFORE \{\$LANG\./', $str)) {
                    $pos += 14;
                    $type = "BEFORE ";
                } else {
                    $pos += 7;
                    $type = "";
                }
                $end = strpos($content, '}', $pos);
                $len = $end - $pos;
                $lang_element = substr($content, $pos, $len);
                $section = $smarty->tpl_vars['LANG']->value[$lang_element];
            }
            // @formatter:off
            $vals = array("file"    => $tpl_file,
                          "module"  => $module,
                          "section" => $type . $section);
            $extension_insertion_files[] = $vals;
            // @formatter:on
        } else {
            $path = "extensions/$ext_name/templates/default/$module/";
            $real_path = "templates/default/$module/";
            $my_tpl_path = $tpl_file;
            $extensionTemplates++;
        }
    }
}
Log::out("index.php - After $module/$view.tpl", Zend_Log::DEBUG);

// TODO: if more than one extension has a template for the requested file, that's trouble :(
// This won't happen for reports, standard menu.tpl and system_defaults menu.tpl given
// changes implemented in this file for them. Similar changes should be implemented for
// other templates as needed.
if ($extensionTemplates == 0) {
    /** @var string $my_tpl_path */
    $my_tpl_path = Util::getCustomPath("$module/$view");
    if (isset($my_tpl_path)) {
        $path = dirname($my_tpl_path) . '/';
        $real_path = $path;
        $extensionTemplates++;
    }
}

$smarty->assign("extension_insertion_files"   , $extension_insertion_files);
$smarty->assign("perform_extension_insertions", $perform_extension_insertions);

// If this is not an extension, $path and $real_path are the same. If it is an extension,
// $path is relative to the extension and $real_path is relative to the standard library path.
$smarty->assign("path"                        , $path);
$smarty->assign("real_path"                   , $real_path);

Log::out("index.php - path[$path] my_tpl_path[$my_tpl_path]", Zend_Log::DEBUG);
$smarty->$smarty_output($my_tpl_path);
Log::out("index.php - After output my_tpl_path[$my_tpl_path]", Zend_Log::DEBUG);

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
if (!in_array($module . "_" . $view, $early_exit)) {
    $extensionFooter = 0;
    foreach ($ext_names as $ext_name) {
        if (file_exists("extensions/$ext_name/templates/default/footer.tpl")) {
            $smarty->$smarty_output("extensions/$ext_name/templates/default/footer.tpl");
            $extensionFooter++;
        }
    }

    if ($extensionFooter == 0) {
        $smarty->$smarty_output(Util::getCustomPath('footer'));
    }
}
Log::out("index.php - At END\n\n", Zend_Log::DEBUG);
// **********************************************************
// Footer - END
// **********************************************************
