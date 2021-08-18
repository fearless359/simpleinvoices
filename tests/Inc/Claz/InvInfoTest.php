<?php
namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class InvInfoTest
 * @name InvInfoTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190310
 * @package Inc\Claz
 */
class InvInfoTest extends TestCase
{

    public static function testConstruct()
    {
        $invInfo = new InvInfo(1, 2, "$50.49", "$50.00", "$0.49");
        self::assertEquals(1, $invInfo->id);
        self::assertEquals(2, $invInfo->indexId);
        self::assertEquals("$50.49", $invInfo->billed);
        self::assertEquals("$50.00", $invInfo->paid);
        self::assertEquals("$0.49", $invInfo->owed);
    }
}
