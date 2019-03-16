<?php
/**
 * @name SiErrorTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190315
 */

namespace test\Inc\Claz;

use Inc\Claz\SiError;
use PHPUnit\Framework\TestCase;

class SiErrorTest extends TestCase
{
    public function setUp() {
        SiError::setReturnMessage(true);
    }

    public function tearDown()
    {
        SiError::setReturnMessage(false);
    }

    public function testOut()
    {
        $mess = SiError::out('generic', 'generic info', 'Test message');
        $expected = "<br />===========================================" .
                    "<br />SimpleInvoices generic info error" .
                    "<br />===========================================" .
                    "<br />" .
                    "<br />Test message";
        $this->assertEquals($expected, $mess, 'SiError generic message test failed');

        $mess = SiError::out('notWritable', 'file', 'tmp/php.log');
        $expected = "<br />===========================================" .
                    "<br />SimpleInvoices error" .
                    "<br />===========================================" .
                    "<br />The file <b>tmp/php.log</b> has to be writable";
        $this->assertEquals($expected, $mess, 'SiError default message test failed');

        $mess = SiError::out('dbError', 'si_table', 'Invalid table');
        $expected = "<br />===========================================" .
                    "<br />SimpleInvoices database error" .
                    "<br />===========================================" .
                    "<br />" .
                    "<br />>si_table - Error: Invalid table";
        $this->assertEquals($expected, $mess, 'SiError dbError message test failed');

        $mess = SiError::out('default');
        $expected = "<br />===========================================" .
                    "<br />SimpleInvoices - Undefined Error Type (default)" .
                    "<br />===========================================";
        $this->assertEquals($expected, $mess, 'SiError default message test failed');
    }
}
