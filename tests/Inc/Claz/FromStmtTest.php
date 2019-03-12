<?php
/**
 * @name FromStmtTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190310
 */

namespace test\Inc\Claz;

use Inc\Claz\FromStmt;
use PHPUnit\Framework\TestCase;

class FromStmtTest extends TestCase
{

    public function testBuild()
    {
        $fr = new FromStmt("myTable", "myAlias");
        $fr->addTable("myTable2", "myAlias2");
        $result = $fr->build();
        $this->assertEquals("FROM `si_myTable` `myAlias`, `si_myTable2` `myAlias2`", $result);
    }
}
