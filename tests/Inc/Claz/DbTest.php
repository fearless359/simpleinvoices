<?php
namespace Inc\Claz;

use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

include_once 'vendor/autoload.php';
include_once 'config/define.php';

/**
 * Class DbTest
 * @name DbTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20201107
 * @package Inc\Claz
 */
class DbTest extends TestCase
{
    private ?array $config;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        try {
            $this->config = Config::init(CONFIG_SECTION, false, Config::CONFIG_FILE);
        } catch (Exception $exp) {
            error_log("DbTest::setUP() - Unexpected exception: {$exp->getMessage()}");
            $this->config = null;
        }
    }

    public function tearDown(): void
    {
        $this->config = null;

        Mockery::close();

        parent::tearDown();
    }

    /** @noinspection PhpUndefinedMethodInspection */
    public function testGetInstance()
    {
        $mock = Mockery::mock('Db');
        $mock->expects()
            ->getInstance($this->config)
            ->andReturnSelf();
        Assert::assertInstanceOf('Db', $mock->getInstance($this->config));
    }
}
