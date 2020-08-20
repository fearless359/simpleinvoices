<?php

/**
 * @name InvInfoTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190310
 */

namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class InvInfoTest
 * @package Inc\Claz
 */
class InvInfoTest extends TestCase
{

    public static function testConstruct()
    {
        $invInfo = new InvInfo(1, 2, "$50.49", "$50.00", "$0.49");
        static::assertEquals(1, $invInfo->id);
        static::assertEquals(2, $invInfo->indexId);
        static::assertEquals("$50.49", $invInfo->fmtdBilled);
        static::assertEquals("$50.00", $invInfo->fmtdPaid);
        static::assertEquals("$0.49", $invInfo->fmtdOwed);
    }
}
