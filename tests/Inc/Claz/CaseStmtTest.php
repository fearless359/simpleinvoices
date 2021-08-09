<?php
namespace Inc\Claz;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Class CaseStmtTest
 * @name CaseStmtTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190310
 * @package test\Inc\Claz
 */
class CaseStmtTest extends TestCase
{

    public function testBuild()
    {
        $cs = new CaseStmt("Age");
        try {
            $cs->addWhen("<=", "14", "0-14");
        } catch (PdoDbException $pde) {
            Assert::assertNull($pde, "CaseStmt addWhen for '<=', '14', '0-14' threw error: " . $pde->getMessage());
        }

        try {
            $cs->addWhen("<=", "30", "15-30" );
        } catch (PdoDbException $pde) {
            Assert::assertNull($pde, "CaseStmt addWhen for '<=', '30', '15-30' threw error: " . $pde->getMessage());
        }

        try {
            $cs->addWhen(">" , "90", "30+", true);
        } catch (PdoDbException $pde) {
            Assert::assertNull($pde, "CaseStmt addWhen for '>', '90', '30+', 'true' threw error: " . $pde->getMessage());
        }

        try {
            $result = $cs->build();
            Assert::assertEquals("(CASE WHEN Age <= 14 THEN '0-14' WHEN Age <= 30 THEN '15-30' WHEN Age > 90 THEN '30+' END) as Age", $result);
        } catch (PdoDbException $pde) {
            Assert::assertNull($pde, "CaseStmt build threw error: " . $pde->getMessage());
        }
    }
}
