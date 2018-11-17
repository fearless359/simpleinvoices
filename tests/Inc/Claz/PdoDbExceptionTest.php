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
        $this->assertEquals($pde->getMessage(), $msg);
        $this->assertEquals($pde->getCode(), $code);
    }

    public function test__toString()
    {
        $str = "Inc\Claz\PdoDbException: [3]: This is the test message\n";
        $msg = "This is the test message";
        $code = 3;
        $pde = new PdoDbException($msg, $code);
        $this->assertEquals($pde->__toString(), $str);
    }

}
