<?php
/**
 * @name CaseStmtTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190310
 */

namespace test\Inc\Claz;

use Inc\Claz\CaseStmt;
use PHPUnit\Framework\TestCase;

class CaseStmtTest extends TestCase
{

    public function testBuild()
    {
        $cs = new CaseStmt("Age");
        $cs->addWhen("<=", "14", "0-14" );
        $cs->addWhen("<=", "30", "15-30" );
        $cs->addWhen(">" , "90", "30+", true);

        $result = $cs->build();
        $this->assertEquals("(CASE WHEN Age <= 14 THEN '0-14' WHEN Age <= 30 THEN '15-30' WHEN Age > 90 THEN '30+' END) as Age", $result);
    }
}
