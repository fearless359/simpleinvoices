<?php

use Inc\Claz\DomainId;
use Inc\Claz\MyFetcherLocalFile;
use Inc\Claz\PdoDbException;
use Inc\Claz\SiError;
use Inc\Claz\SystemDefaults;
use Inc\Claz\WhereItem;

global $auth_session, $config, $dbh, $dbInfo;

/**
 * @deprecated - Migrate to PdoDb class
 *               Rich Rowley 20160702
 */
$dbh = db_connector();

// Cannot redefine LOGGING (without PHP PECL run kit extension) since already true in define.php
// Ref: http://php.net/manual/en/function.runkit-method-redefine.php
// Hence take from system_defaults into new variable
// Initialize so that while it is being evaluated, it prevents logging
$can_log = false;
$can_chk_log = (LOGGING && (isset($auth_session->id) && $auth_session->id > 0) && SystemDefaults::getDefaultLoggingStatus());
$can_log = $can_chk_log;
unset($can_chk_log);

/**
 * Establish the PDO connector to the database
 * @return PDO
 */
function db_connector() {
    global $config, $databaseBuilt, $dbInfo;

    if (!$databaseBuilt) return NULL;

    if (!defined('PDO::MYSQL_ATTR_INIT_COMMAND') && $dbInfo->getAdapter() == "mysql" && $config->database->adapter->utf8 == true) {
        SiError::out("PDO::mysql_attr");
    }

    try {
        // @formatter:off
        $connlink = new PDO($dbInfo->getAdapter() . ':host=' . $dbInfo->getHost() .
                                                        '; port=' . $dbInfo->getPort() .
                                                      '; dbname=' . $dbInfo->getDbname(),
                                                                    $dbInfo->getUsername(),
                                                                    $dbInfo->getPassword());
        // @formatter:on
    } catch (PDOException $exception) {
        SiError::out("dbConnection", $exception->getMessage());
        die($exception->getMessage());
    }

    return $connlink;
}

/**
 * Replaces any parameter placeholders in a query with the value of that parameter.
 * @param string $query The sql query with parameter placeholders
 * @param array $params The array of substitution parameters
 * @return string The interpolated query
 */
function interpolateQuery($query, $params) {
    $keys = array();
    $values = $params;

    // build a regular expression for each parameter
    foreach ($params as $key => $value) {
        // Add quotes around the named parameters and ? parameters.
        if (is_string($key)) {
            $keys[] = '/' . $key . '/';
        } else {
            $keys[] = '/[?]/';
        }

        // If the value for this is is an array, make it a character separated string.
        if (is_array($value)) $values[$key] = implode(',', $value);
        // If the value is NULL, make it a string value of "NULL".
        if (is_null($value)) $values[$key] = 'NULL';
    }

    // Walk the array to see if we can add single-quotes to strings.
    // The $k == $k test is to remove an unused warning.
    array_walk($values, function(&$v, $k) {if ($k == $k && !is_numeric($v) && $v!="NULL") $v = "'".$v."'";});
    $query = preg_replace($keys, $values, $query, 1);
    return $query;
}

/**
 * Perform sql request.
 * Note: dbQuery is a variadic function that, in its simplest case,
 * functions as the old mysqlQuery does. The added complexity is
 * that it also handles named parameters (arguments) to the queries.
 *
 * Examples:
 * $sth = dbQuery('SELECT b.id, b.name FROM si_biller b WHERE b.enabled');
 * $tth = dbQuery('SELECT c.name FROM si_customers c WHERE c.id = :id', ':id', $id);
 *
 * @param string $sqlQuery Query to be performed.
 * @return bool/PDOStatement Result of query.
 */
function dbQuery($sqlQuery) {
    global $dbh;
    global $databaseBuilt;

    if (!$databaseBuilt) return false;

    $argc = func_num_args();
    $binds = func_get_args();

    // PDO SQL Preparation
    $params = array();
    $sth = $dbh->prepare($sqlQuery);
    if ($argc > 1) {
        array_shift($binds);
        for ($i = 0; $i < count($binds); $i++) {
            $key = $binds[$i];
            $value = $binds[++$i];
            $params[$key] = $value;
            $sth->bindValue($key, $value);
        }
    }

    try {
        $sth->execute();
        dbLogger(interpolateQuery($sqlQuery, $params));
    } catch (Exception $e) {
        echo $e->getMessage();
        echo "dbQuery: Dude, what happened to your query?:<br /><br /> " . htmlsafe($sqlQuery) . "<br />" .
                         htmlsafe(end($sth->errorInfo()));
    }

    return $sth;
}

/**
 * Log database modification entries in the si_log table.
 * @param string $sqlQuery Query to be logged.
 * @throws PdoDbException
 */
function dbLogger($sqlQuery) {
    global $auth_session, $can_log, $pdoDb_admin;

    // Compact query to be logged
    $sqlQuery = preg_replace('/  +/', ' ', str_replace(PHP_EOL, '', $sqlQuery));
    if ($can_log && (preg_match('/^\s*select/iD', $sqlQuery) == 0) &&
                    (preg_match('/^\s*show\s*tables\s*like/iD', $sqlQuery) == 0)) {
        // Only log queries that could result in data/database modification
        $last = NULL;
        if (preg_match('/^(insert|update|delete)/iD', $sqlQuery)) {
            $sql = 'SELECT last_insert_id()';
            $last = $pdoDb_admin->query($sql);
        }

        // @formatter:off
        $pdoDb_admin->setFauxPost(array("domain_id" => DomainId::get(),
                                        "userid"    => $auth_session->id,
                                        "sqlquerie" => trim($sqlQuery),
                                        "last_id"   => $last));
        $pdoDb_admin->request("INSERT", "log");
    }
}

/**
 * Load SI Extension information into $config->extension.
 * @param &array Reference to the extension names array.
 */
function loadSiExtensions(&$ext_names) {
    global $config, $databaseBuilt, $patchCount, $pdoDb_admin;

    if ($databaseBuilt && $patchCount > "196") {
        $rows = array();
        try {
            $pdoDb_admin->addSimpleWhere("domain_id", DomainId::get(), "OR");
            $pdoDb_admin->addSimpleWhere("domain_id", 0);
            $pdoDb_admin->setOrderBy("domain_id");
            $rows = $pdoDb_admin->request("SELECT", "extensions");
        } catch (PdoDbException $pde) {
            error_log("loadSiExtensions() - Error: " . $pde->getMessage());
        }
        $extensions = array();
        foreach ($rows as $extension) {
            $extensions[$extension['name']] = $extension;
        }
        $config->extension = $extensions;
    }

    // If no extension loaded, load Core
    if (!$config->extension) {
        // @formatter:off
        $extension_core = new Zend_Config(
            array('core' => array('id'         => 1,
                                  'domain_id'  => 1,
                                  'name'       => 'core',
                                  'description'=> 'Core part of SimpleInvoices - always enabled',
                                  'enabled'    => 1)));
        $config->extension = $extension_core;
        // @formatter:on
    }

    // Populate the array of enabled extensions.
    $ext_names = array();
    foreach ($config->extension as $extension) {
        if ($extension->enabled == "1") {
            $ext_names[] = $extension->name;
        }
    }
}

/**
 * Get custom field labels.
 * @param string $domain_id Domain ID logged info.
 * @param boolean $noUndefinedLabels Defaults to <b>false</b>. When set to
 *        <b>true</b> custom fields that do not have a label defined will
 *        not a be assigned a default label so the undefined custom fields
 *        won't be displayed.
 * @return array Rows retrieved. Test for "=== false" to check for failure.
 * @throws PdoDbException
 */
function getCustomFieldLabels($domain_id = '', $noUndefinedLabels = FALSE) {
    global $LANG, $pdoDb_admin;
    $domain_id = DomainId::get($domain_id);

    $pdoDb_admin->addSimpleWhere("domain_id", $domain_id);
    $pdoDb_admin->setOrderBy("cf_custom_field");
    $rows = $pdoDb_admin->request("SELECT", "custom_fields");

    $cfl = $LANG['custom_field'] . ' ';
    $customFields = array();
    $i = 0;
    foreach($rows as $row) {
        // @formatter:off
        $customFields[$row['cf_custom_field']] =
                (empty($row['cf_custom_label']) ?
                            ($noUndefinedLabels ? "" :
                                                  $cfl . (($i % 4) + 1)) :
                                                  $row['cf_custom_label']);
        $i++;
        // @formatter:on
    }
    return $customFields;
}
/**
 * Test for custom flag field.
 * @param $field
 * @return bool
 */
function is_custom_flag_field($field) {
    global $smarty;
    $useit  = false;
    $result = false;
    if (!empty($field)) {
        if (preg_match('/[Ff]lag:/',$field) == 1) {
            $useit = true;
        } else {
            $result = true;
        }
    }
    $smarty->assign('useit', $useit);
    return $result;
}

/**
 * @param $extension_id
 * @param int $status
 * @param string $domain_id
 * @return bool
 * @throws PdoDbException
 */
function setStatusExtension($extension_id, $status = 2, $domain_id = '') {
    global $pdoDb_admin;

    $domain_id = DomainId::get($domain_id);

    // status=2 = toggle status
    if ($status == 2) {
        $pdoDb_admin->setSelectList('enabled');
        $pdoDb_admin->addSimpleWhere('id', $extension_id, 'AND');
        $pdoDb_admin->addSimpleWhere('domain_id', $domain_id);

        $pdoDb_admin->setLimit(1);

        $rows = $pdoDb_admin->request('SELECT', 'extensions');
        $extension_info = $rows[0];
        $status = 1 - $extension_info['enabled'];
    }

    $pdoDb_admin->addSimpleWhere('id', $extension_id, 'AND');
    $pdoDb_admin->addSimpleWhere('domain_id', $domain_id);

    $pdoDb_admin->setFauxPost(array("enabled" => $status));

    $result = $pdoDb_admin->request("UPDATE", 'extensions');
    return $result;
}

/**
 * @param string $extension_name
 * @return int
 * @throws PdoDbException
 */
function getExtensionID($extension_name = "none") {
    global $pdoDb_admin;

    $domain_id = DomainId::get();

    $pdoDb_admin->addSimpleWhere('name', $extension_name, 'AND');
    $pdoDb_admin->addToWhere(new WhereItem(true, 'domain_id', '=', 0, false, 'OR'));
    $pdoDb_admin->addToWhere(new WhereItem(false, 'domain_id', '=', $domain_id, true));

    $pdoDb_admin->setOrderBy(array('domain_id', 'D'));

    $pdoDb_admin->setLimit(1);

    $rows = $pdoDb_admin->request('SELECT', 'extensions');
    if (empty($rows)) {
        return -2;
    }

    $extension_info = $rows[0];
    if ($extension_info['enabled'] != ENABLED) {
        return -1; // -1 = extension not enabled
    }
    return $extension_info['id']; // 0 = core, >0 is extension id
}

/**
 * Ensure that there is a time value in the datetime object.
 *
 * @param string $in_date Datetime string in the format, "YYYY/MM/DD HH:MM:SS".
 *        Note: If time part is "00:00:00" it will be set to the current time.
 * @return string Datetime string with time set.
 */
function sqlDateWithTime($in_date) {
    $parts = explode(' ', $in_date);
    $date  = (isset($parts[0]) ? $parts[0] : "");
    $time  = (isset($parts[1]) ? $parts[1] : "00:00:00");
    if (!$time || $time == '00:00:00') {
        $time = date('H:i:s');
    }
    $out_date = "$date $time";
    return $out_date;
}

/**
 * Attempts to delete rows from the database.
 * Currently allows for deletion of invoices, invoice_items, and products entries.
 * All other $module values will fail. $idField is also checked on a per-table
 * basis, i.e. invoice_items can be either "id" or "invoice_id" while products
 * can only be "id". Invalid $module or $idField values return false, as do
 * calls that would fail foreign key checks.
 * @param string $module Table a row
 * @param string  $idField
 * @param integer $id
 * @param string $domain_id
 * @return false if failure otherwise result of dbQuery().
 */
function delete($module, $idField, $id, $domain_id = '') {
    global $dbh;
    $domain_id = DomainId::get($domain_id);

    $has_domain_id = false;

    $lctable = strtolower($module);

    // SC: $valid_tables contains the base names of all tables that can have rows
    // deleted using this function. This is used for whitelisting deletion targets.
    $valid_tables = array('invoices', 'invoice_items', 'invoice_item_tax', 'products');

    if (in_array($lctable, $valid_tables)) {
        // A quick once-over on the dependencies of the possible tables
        if ($lctable == 'invoice_item_tax') {
            // Not required by any FK relationships
            if (!in_array($idField, array('invoice_item_id'))) {
                return false; // Fail, invalid identity field
            }
            $s_idField = $idField;
        } elseif ($lctable == 'invoice_items') {
            // Not required by any FK relationships
            if (!in_array($idField, array('id', 'invoice_id'))) {
                return false; // Fail, invalid identity field
            }
            $s_idField = $idField;
        } elseif ($lctable == 'products') {
            $has_domain_id = true;
            // Check for use of product
            // @formatter:off
            $sth = $dbh->prepare('SELECT count(*) FROM ' . TB_PREFIX . 'invoice_items
                                  WHERE product_id = :id AND domain_id = :domain_id');
            // @formatter:on
            $sth->execute(array(':id' => $id, ':domain_id', $domain_id));
            $sth->fetch();
            if ($sth->fetchColumn() != 0) {
                return false; // Fail, product still in use
            }
            $sth = NULL;

            if (!in_array($idField, array('id'))) {
                return false; // Fail, invalid identity field
            }
            $s_idField = $idField;
        } elseif ($lctable == 'invoices') {
            $has_domain_id = true;
            // Check for existing payments and line items
            // @formatter:off
            $sth = $dbh->prepare('SELECT count(*)
                                  FROM (SELECT id FROM ' . TB_PREFIX . 'invoice_items
                                        WHERE invoice_id = :id AND domain_id = :domain_id
                                          UNION ALL
                                        SELECT id FROM ' . TB_PREFIX . 'payment
                                        WHERE ac_inv_id = :id AND domain_id = :domain_id) x');
            // @formatter:on
            $sth->execute(array(':id' => $id, ':domain_id', $domain_id));
            if ($sth->fetchColumn() != 0) {
                return false; // Fail, line items or payments still exist
            }
            $sth = NULL;

            // SC: Later, may accept other values for $idField
            if (!in_array($idField, array('id'))) {
                return false; // Fail, invalid identity field
            }
            $s_idField = $idField;
        } else {
            return false; // Fail, no checks for this table exist yet
        }
    } else {
        return false; // Fail, invalid table name
    }

    if ($s_idField == '') {
        return false; // Fail, column whitelisting not performed
    }

    // Table name and column both pass whitelisting and FK checks
    $sql = "DELETE FROM " . TB_PREFIX . "$module WHERE $s_idField = :id";
    if ($has_domain_id) {
        $sql .= " AND domain_id = :domain_id";
        return dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
    }
    return dbQuery($sql, ':id', $id);
}

/**
 * @return string
 */
function getURL() {
    global $api_request, $config;

    if ($api_request) {
        $_SERVER['FULL_URL'] = "";
        return "";
    }

    $dir = dirname($_SERVER['PHP_SELF']);
    // remove incorrect slashes for WinXP etc.
    $dir = str_replace('\\', '', $dir);

    // set the port of http(s) section
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $_SERVER['FULL_URL'] = "https://";
    } else {
        $_SERVER['FULL_URL'] = "http://";
    }

    $http_host = (empty($_SERVER['HTTP_HOST']) ? "" : $_SERVER['HTTP_HOST']);
    $_SERVER['FULL_URL'] .= $config->authentication->http . $http_host . $dir;

    if (strlen($_SERVER['FULL_URL']) > 1 &&
	    substr($_SERVER['FULL_URL'], -1, 1) != '/') $_SERVER['FULL_URL'] .= '/';

    return $_SERVER['FULL_URL'];
}

/**
 * Runs the HTML->PDF conversion with default settings
 * Warning: if you have any files (like CSS stylesheets and/or images referenced by this file,
 * use absolute links (like http://my.host/image.gif).
 * @param string $html_to_pdf html path to source html file.
 * @param string $pdfname String path to file to save generated PDF to.
 * @param boolean $download <b>true</b> sets <i>DestinationDownload</i> for the output destination.
 *        <b>false</b> sets <i>DestinationFile</i> for the output destination.
 */
function pdfThis($html_to_pdf, $pdfname, $download) {
    require_once ('./library/pdf/config.inc.php');
    require_once ('./library/pdf/pipeline.factory.class.php');
    require_once ('./library/pdf/pipeline.class.php');

    parse_config_file('./library/pdf/html2ps.config');

    require_once ("include/init.php"); // for getInvoice() and getPreference()

    if (!function_exists('convert_to_pdf')) {
        function convert_to_pdf($html_to_pdf, $pdfname, $download) {
            global $config;

            $destination = $download ? "DestinationDownload" : "DestinationFile";
            $pipeline = PipelineFactory::create_default_pipeline("", ""); // Attempt to auto-detect encoding
            $pipeline->fetchers[] = new MyFetcherLocalFile($html_to_pdf); // Override HTML source
            $baseurl = "";
            $media = Media::predefined($config->export->pdf->papersize);
            $media->set_landscape(false);

            // @formatter:off
            $margins = array('left'   => $config->export->pdf->leftmargin,
                             'right'  => $config->export->pdf->rightmargin,
                             'top'    => $config->export->pdf->topmargin,
                             'bottom' => $config->export->pdf->bottommargin);

            global $g_config;
            $g_config = array('cssmedia'                => 'screen',
                              'renderimages'            => true,
                              'renderlinks'             => true,
                              'renderfields'            => true,
                              'renderforms'             => false,
                              'mode'                    => 'html',
                              'encoding'                => '',
                              'debugbox'                => false,
                              'pdfversion'              => '1.4',
                              'process_mode'            => 'single',
                              'pixels'                  => $config->export->pdf->screensize,
                              'media'                   => $config->export->pdf->papersize,
                              'margins'                 => $margins,
                              'transparency_workaround' => 1,
                              'imagequality_workaround' => 1,
                              'draw_page_border'        => false);
            // @formatter:on

            $media->set_margins($g_config['margins']);
            $media->set_pixels($config->export->pdf->screensize);

            global $g_px_scale;
            $g_px_scale = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) / $media->pixels;

            global $g_pt_scale;
            $g_pt_scale = $g_px_scale * (72 / 96);

            $pipeline->configure($g_config);
            $pipeline->data_filters[] = new DataFilterUTF8("");
            $pipeline->destination = new $destination($pdfname);
            $pipeline->process($baseurl, $media);
        }
    }

    convert_to_pdf($html_to_pdf, $pdfname, $download);
}
