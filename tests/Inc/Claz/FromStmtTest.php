<?php
/**
 * @name FromStmtTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190310
 */

namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

const TB_PREFIX = "si_";

/**
 * Class FromStmtTest
 * @package Inc\Claz
 */
class FromStmtTest extends TestCase
{
    public function testBuild()
    {
        $fr = new FromStmt();
        static::assertTrue($fr->isEmpty(), "Failed FromStmtTest build of empty table.");

        $fr->addTable("myTable", "myAlias");
        $result = $fr->build();
        static::assertEquals("FROM `si_myTable` `myAlias`", $result,
            "Failed FromStmtTest addTable() & build()");

        $fr = new FromStmt("myTable2", "myAlias2");
        $result = $fr->build();
        static::assertEquals("FROM `si_myTable2` `myAlias2`", $result,
            "Failed FromStmtTest FromStmt() & build()");
    }
}
