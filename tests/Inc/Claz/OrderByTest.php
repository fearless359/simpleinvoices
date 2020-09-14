<?php
/**
 * @name OrderByTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 */

namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class OrderByTest
 * @package Inc\Claz
 */
class OrderByTest extends TestCase
{

    public function testOrderByClass()
    {
        try {
            $ob = new OrderBy();
            self::assertTrue($ob->isEmpty(), "OrderBy class failed empty test");
        } catch (PdoDbException $pde) {
            self::assertTrue(false, "testOrderByClass() Unexpected error thrown for empty object instantiation. Error: {$pde->getMessage()}");
        }

        try {
            $ob = new OrderBy('enable_txt', 'D');
            try {
                $ob->addField('id', 'D');
                $ob->addField('name', 'A');
            } catch (PdoDbException $pde) {
                self::assertTrue(false, "testOrderByClass() Unexpected error thrown for addFields. Error: {$pde->getMessage()}");
            }
            try {
                $stmt = $ob->build();
                self::assertEquals("ORDER BY `enable_txt` DESC, `id` DESC, `name` ASC", $stmt, 'OrderBy class test failed');
            } catch (PdoDbException $pde) {
                self::assertTrue(false, "testOrderByClass() Unexpected error thrown for object build. Error: {$pde->getMessage()}");
            }
        } catch (PdoDbException $pde) {
            self::assertTrue(false, "testOrderByClass() Unexpected error thrown for object instantiation. Error: {$pde->getMessage()}");
        }
    }
}
