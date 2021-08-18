<?php
namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class OnClauseTest
 * @name OnClauseTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20201201
 * @package Inc\Claz
 */
class OnClauseTest extends TestCase
{

    public function testOnClause()
    {
        try {
            $oc = new OnClause(new OnItem(true, 'db_field', "=", 10, false, 'OR'));
            $oc->addItem(new OnItem(false, 'db_field2', "=", "Jones", true));
            $keyPairs = null;
            $stmt = $oc->build($keyPairs);
            $cnt = $oc->getTokenCnt();
            self::assertEquals("ON (`db_field` = :db_field_000  OR `db_field2` = :db_field2_001 ) ", $stmt, 'OnClause build test failed');
            self::assertEquals(2, $cnt, 'OnClause cnt test failed');
            self::assertEquals(10, $keyPairs[':db_field_000'], 'OnClause keyPairs test 1 failed');
            self::assertEquals("Jones", $keyPairs[':db_field2_001'], 'OnClause keyPairs test 2 failed');
        } catch (PdoDbException $pde) {
            self::fail("testOnClauseCLass() Unexpected error thrown. Error: {$pde->getMessage()}");
        }
    }
}
