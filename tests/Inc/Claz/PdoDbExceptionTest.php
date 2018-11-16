<?php
/**
 * @name PdoDbExceptionTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181114
 */

use Inc\Claz\PdoDbException;
use PHPUnit\Framework\TestCase;

class PdoDbExceptionTest extends TestCase
{

    public function test__construct()
    {
        $msg = "This is the test message";
        $code = 3;
        $pde = new PdoDbException($msg, $code);
        $this->assertTrue($pde->getMessage() == $msg,
            "PdoDbExceptionTest::test_construct() - Failed getMessage() test.");
        $this->assertTrue($pde->getCode() == $code,
            "PdoDbExceptionTest::test_construct() - Failed getCode() test.");
    }

    public function test__toString()
    {
        $str = "Inc\Claz\PdoDbException: [3]: This is the test message\n";
        $msg = "This is the test message";
        $code = 3;
        $pde = new PdoDbException($msg, $code);
        $this->assertTrue($pde->__toString() == $str,
            "PdoDbExceptionTest::test_toString() - Found[{$pde->__toString()}], expected[{$str}]");
    }

}
