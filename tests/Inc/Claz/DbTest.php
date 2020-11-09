<?php
/**
 * @name DbTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20201107
 */

namespace Inc\Claz;

use Mockery;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

include_once 'vendor/autoload.php';

/**
 * Class DbTest
 * @package Inc\Claz
 */
class DbTest extends TestCase
{
    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function testGetInstance()
    {
        $setup = new Setup(CONFIG_SECTION, false);
        $config = $setup->getConfigIni();
        $mock = Mockery::mock('Db');
        $mock->shouldReceive('getInstance')
            ->once()
            ->with($config)
            ->andReturnSelf();
        Assert::assertInstanceOf('Db', $mock->getInstance($config));
    }
}
