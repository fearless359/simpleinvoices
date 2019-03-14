<?php
/**
 * @name DbFieldTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace test\Inc\Claz;

use Inc\Claz\DbField;
use PHPUnit\Framework\TestCase;

class DbFieldTest extends TestCase
{

    public function testDbFieldClass()
    {
        $dbf = new DbField('ivl.customer_id', 'cid');
        $parm = $dbf->genParm();
        $this->assertEquals('`ivl`.`customer_id` AS cid', $parm, 'Generate full parameter failed');

        $alias = $dbf->genParm(true);
        $this->assertEquals('cid', $alias, 'Generate alias failed');
    }
}
