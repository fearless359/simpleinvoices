<?php
namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

if (!defined('TB_PREFIX')) {
    define('TB_PREFIX', "si_");
}

/**
 * Class FromStmtTest
 * @name FromStmtTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190310
 * @package Inc\Claz
 */
class FromStmtTest extends TestCase
{
    /**
     * @runTestsInSeparateProcesses
     * @preserveGlobalState  disabled
     */
    public function testBuild()
    {
        $fr = new FromStmt();
        self::assertTrue($fr->isEmpty(), "Failed FromStmtTest build of empty table.");

        $fr->addTable("myTable", "myAlias");
        $result = $fr->build();
        self::assertEquals("FROM `si_myTable` `myAlias`", $result,
            "Failed FromStmtTest addTable() & build()");

        $fr = new FromStmt("myTable2", "myAlias2");
        $result = $fr->build();
        self::assertEquals("FROM `si_myTable2` `myAlias2`", $result,
            "Failed FromStmtTest FromStmt() & build()");
    }
}
