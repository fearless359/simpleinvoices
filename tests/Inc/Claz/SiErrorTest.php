<?php
namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class SiErrorTest
 * @name SiErrorTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190315
 * @package Inc\Claz
 */
class SiErrorTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        SiError::setReturnMessage(true);
    }

    public function tearDown(): void
    {
        SiError::setReturnMessage(false);
        parent::tearDown();
    }

    public static function testOut()
    {
        $mess = SiError::out('generic', 'generic info', 'Test message');
        $expected = "<br />===========================================" .
            "<br />SimpleInvoices generic info error" .
            "<br />===========================================" .
            "<br />" .
            "<br />Test message";
        self::assertEquals($expected, $mess, 'SiError generic message test failed');

        $mess = SiError::out('notWritable', 'file', 'tmp/php.log');
        $expected = "<br />===========================================" .
            "<br />SimpleInvoices error" .
            "<br />===========================================" .
            "<br />The file <b>tmp/php.log</b> has to be writable";
        self::assertEquals($expected, $mess, 'SiError default message test failed');

        $mess = SiError::out('dbConnection', 'Unable to make connection');
        $expected = "<br />===========================================" .
            "<br />SimpleInvoices database connection problem" .
            "<br />===========================================" .
            "<br />" .
            "<br />Could not connect to the SimpleInvoices database" .
            "<br />" .
            "<br />For information on how to fix this, refer to the following database error: " .
            "<br />--> <b>Unable to make connection</b>" .
            "<br />" .
            "<br />If this is an &quot;Access denied&quot; error please enter the correct database " .
            "connection details config/custom.config.ini." .
            "<br />" .
            "<br /><b>Note:</b> If you are installing SimpleInvoices please follow the below steps:" .
            "<ol>" .
            "<li>Create a blank MySQL database (cPanel or myPHPAdmin). Defined a DB Admin username " .
            "with full access to this database. Assign a password to this DB Admin user.</li>" .
            "<li>Enter the correct database connection details in the config/custom.config.ini file.</li>" .
            "<li>Refresh this page</li>" .
            "</ol>" .
            "<br />===========================================";
        self::assertEquals($expected, $mess, 'SiError dbError message test failed');

        $mess = SiError::out('dbError', 'si_table', 'Invalid table');
        $expected = "<br />===========================================" .
            "<br />SimpleInvoices database error" .
            "<br />===========================================" .
            "<br />" .
            "<br />>si_table - Error: Invalid table";
        self::assertEquals($expected, $mess, 'SiError dbError message test failed');

        $mess = SiError::out('phpVersion', 'Invalid version');
        $expected = "<br />===========================================" .
            "<br />SimpleInvoices - PHP - Version Issue" .
            "<br />===========================================" .
            "<br />" .
            "<br />Invalid version" .
            "<br />" .
            "<br />===========================================" .
            "<br />";
        self::assertEquals($expected, $mess, 'SiError dbError message test failed');

        $mess = SiError::out('default');
        $expected = "<br />===========================================" .
            "<br />SimpleInvoices - Undefined Error Type (default)" .
            "<br />===========================================";
        self::assertEquals($expected, $mess, 'SiError default message test failed');
    }
}
