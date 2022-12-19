<?php

namespace Inc\Claz;

/**
 * @name SiError.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181009
 */

/**
 * Class SiError
 * @package Inc\Claz
 */
class SiError
{
    private static bool $returnMessage = false;

    /**
     * Set class property to determine whether message generated in the out() function
     * should be returned to the caller or sent to an exit() call.
     * @param bool $returnMessage
     */
    public static function setReturnMessage(bool $returnMessage): void
    {
        self::$returnMessage = $returnMessage;
    }

    /**
     * Generate error message to report.
     * @param string $type Values are: generic, notWritable, dbConnection, dbError, install,
     *                  PDO, sql, PDO_mysql_attr, PDO_not_mysql. If not one of these, a default
     *                  message is generated.
     * @param string $info1 Varies with $type setting.
     * @param string $info2 Varies with $type setting.
     * @return string Error message formatted for html output.
     */
    public static function out(string $type, string $info1 = "", string $info2 = ""): string
    {
        $dbname = '';
        if ($type == "dbConnection" && strstr($info1, "Unknown database") !== false) {
            $type = "install";
            $parts = explode("'", $info1);
            $dbname = $parts[1];
        }

        // @formatter:off
        switch ($type) {
            case "generic":
                $mess = "<br />===========================================" .
                    "<br />SimpleInvoices $info1 error" .
                    "<br />===========================================" .
                    "<br />" .
                    "<br />$info2";
                break;

            case "notWritable":
                $mess = "<br />===========================================" .
                    "<br />SimpleInvoices error" .
                    "<br />===========================================" .
                    "<br />The " . $info1 . " <b>" . $info2 . "</b> has to be writable";
                break;

            case "dbConnection":
                $mess = "<br />===========================================" .
                    "<br />SimpleInvoices database connection problem" .
                    "<br />===========================================" .
                    "<br />" .
                    "<br />Could not connect to the SimpleInvoices database" .
                    "<br />" .
                    "<br />For information on how to fix this, refer to the following database error: " .
                    "<br />--> <b>$info1</b>" .
                    "<br />" .
                    "<br />If this is an &quot;Access denied&quot; error please enter the correct database " .
                    "connection details config/custom.config.ini." .
                    "<br />" .
                    "<br /><b>Note:</b> If you are installing SimpleInvoices please follow the below steps:" .
                    "<ol>" .
                    "<li>Create a blank MySQL database (cPanel or myPHPAdmin). Define a DB Admin username " .
                    "with full access to this database. Assign a password to this DB Admin user.</li>" .
                    "<li>Enter the correct database connection details in the config/custom.config.ini file.</li>" .
                    "<li>Refresh this page</li>" .
                    "</ol>" .
                    "<br />===========================================";
                break;

            case "dbError":
                $mess = "<br />===========================================" .
                    "<br />SimpleInvoices database error" .
                    "<br />===========================================" .
                    "<br />" .
                    "<br />>$info1 - Error: $info2";
                break;

            case "install":
                $mess = "<div id='containter' class='col si_wrap'>" .
                    "<div id='si_install_logo'>" .
                    "<img src='templates/invoices/logos/simple_invoices_logo.png' alt='' class='si_install_logo' width='300'/>" .
                    "</div>" .
                    "<table style='width:50%'>" .
                    "<tr>" .
                    "<th style='font-weight: bold;text-align:center;'>===========================================</th>" .
                    "</tr>" .
                    "<tr>" .
                    "<th style='font-weight: bold;text-align:center;'>SimpleInvoices database connection problem</th>" .
                    "</tr>" .
                    "<tr>" .
                    "<th style='font-weight: bold;text-align:center;'>===========================================</th>" .
                    "</tr>" .
                    "<tr>" .
                    "<th style='font-weight:normal;'>" .
                    "You&#39;ve reached this page because the name of the database in your " .
                    "configuration file has not been created. Please follow the following " .
                    "instructions before leaving this page." .
                    "<ol>" .
                    "<li>Using your database admin program, phpMyAdmin for MySQL, create a database " .
                    "preferably with UTF-8 collation. It can be named whatever you like but the " .
                    "name currently in the configuration file is, $dbname.</li>" .
                    "<li>Assign an administrative user and password to the database.</li>" .
                    "<li>Enter the database connection details in the <strong>" . Config::CUSTOM_CONFIG_FILE . "</strong> file." .
                    "The fields that need to be set are:" .
                    "<ul style='font-family:\"Lucida Console\", \"Courier New\"'>" .
                    "<li>databaseHost&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;localhost</li>" .
                    "<li>databaseUsername&nbsp;=&nbsp;root</li>" .
                    "<li>databasePassword&nbsp;=&nbsp;&#39;mypassword&#39;</li>" .
                    "<li>databaseDbname&nbsp;&nbsp;&nbsp;=&nbsp;simple_invoices</li>" .
                    "<li>databasePort&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;3306</li>" .
                    "</ul>" .
                    "</li>" .
                    "<li>When you have completed these steps, simply refresh this page and follow " .
                    "the instructions to complete installation of SimpleInvoices.</li>" .
                    "</ol>" .
                    "</th>" .
                    "</tr>" .
                    "</table>" .
                    "</div>";
                break;

            case "PDO":
                $mess = "<br />===========================================" .
                    "<br />SimpleInvoices - PDO problem" .
                    "<br />===========================================" .
                    "<br />" .
                    "<br />PDO is not configured in your PHP installation." .
                    "<br />This means that SimpleInvoices can't be used." .
                    "<br />" .
                    "<br />To fix this please installed the pdo_mysql php extension." .
                    "<br />If you are using a web host please email them and get them to" .
                    "<br />install PDO for PHP with the MySQL extension" .
                    "<br />" .
                    "<br />===========================================" .
                    "<br />";
                break;

            case "sql":
                $mess = "<br />===========================================" .
                    "<br />SimpleInvoices - SQL problem" >
                    "<br />===========================================" .
                    "<br />" .
                    "<br />The following sql statement:" .
                    "<br />$info2" .
                    "<br />" .
                    "<br />had the following error code: $info1" .
                    "<br />with the message of: \"$info2\"" .
                    "<br />" .
                    "<br />===========================================" .
                    "<br />";
                break;

            case "PDO_mysql_attr":
                $mess = "<br />===========================================" .
                    "<br />SimpleInvoices - PDO - MySQL problem" .
                    "<br />===========================================" .
                    "<br />" .
                    "<br />Your SimpleInvoices installation can't use the" .
                    "<br />database settings 'databaseUtf8'." .
                    "<br />" .
                    "<br />To fix this please edit config/config.ini and" .
                    "<br />set 'databaseUtf8' to 'false'" .
                    "<br />" .
                    "<br />===========================================" .
                    "<br />";
                break;

            case "PDO_not_mysql":
                $mess = "<br />===========================================" .
                    "<br />SimpleInvoices - PDO - MySQL problem" .
                    "<br />===========================================" .
                    "<br />" .
                    "<br />Your SimpleInvoices installation can't use database types other than 'mysql'." .
                    "<br />" .
                    "<br />To fix this please edit the config/custom.config.ini file and set the " .
                    "databaseAdapter to 'pdo_mysql' and databaseUtf8 to 'true'." .
                    "<br />" .
                    "<br />===========================================" .
                    "<br />";
                break;

            default:
                $mess = "<br />===========================================" .
                    "<br />SimpleInvoices - Undefined Error Type ($type)" .
                    "<br />===========================================";
                break;
        }
        // @formatter:on

        return self::terminate($mess);
    }

    /**
     * If the $returnMessage property is true, the $mess string is returned.
     * Otherwise and exit($mess) is performed to terminate the process.
     * @param string $mess
     * @return string
     */
    private static function terminate(string $mess): string
    {
        if (self::$returnMessage) {
            return $mess;
        }
        exit($mess);
    }
}
