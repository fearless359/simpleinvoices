<?php
namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class OrderByTest
 * @name OrderByTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190314
 * @package Inc\Claz
 */
class OrderByTest extends TestCase
{

    public function testOrderByClass()
    {
        $ob = new OrderBy();
        self::assertTrue($ob->isEmpty(), "OrderBy class failed empty test");

        $ob = new OrderBy('enable_txt', 'D');
        try {
            $ob->addField('id', 'D');
            /** @noinspection PhpRedundantOptionalArgumentInspection */
            $ob->addField('name', 'A');
        } catch (PdoDbException $pde) {
            self::fail("testOrderByClass() Unexpected error thrown for addFields. Error: {$pde->getMessage()}");
        }
        $stmt = $ob->build();
        self::assertEquals("ORDER BY `enable_txt` DESC, `id` DESC, `name` ASC", $stmt, 'OrderBy class test failed');
    }
}
