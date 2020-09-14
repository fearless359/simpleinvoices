<?php
/**
 * @name DbFieldTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class DbFieldTest
 * @package Inc\Claz
 */
class DbFieldTest extends TestCase
{

    public function testDbFieldClass()
    {
        $dbf = new DbField('ivl.customer_id', 'cid');
        $parm = $dbf->genParm();
        self::assertEquals('`ivl`.`customer_id` AS cid', $parm, 'Generate full parameter failed');

        $alias = $dbf->genParm(true);
        self::assertEquals('cid', $alias, 'Generate alias failed');
    }
}
