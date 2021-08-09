<?php
namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class PdoDbExceptionTest
 * @name PdoDbExceptionTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181114
 * @package Inc\Claz
 */
class PdoDbExceptionTest extends TestCase
{

    public function testConstruct()
    {
        $msg = "This is the test message";
        $code = 3;
        $pde = new PdoDbException($msg, $code);
        self::assertEquals($pde->getMessage(), $msg);
        self::assertEquals($pde->getCode(), $code);
    }

    public function testToString()
    {
        $str = "Inc\Claz\PdoDbException: [3]: This is the test message\n";
        $msg = "This is the test message";
        $code = 3;
        $pde = new PdoDbException($msg, $code);
        self::assertEquals($pde->__toString(), $str);
    }

}
