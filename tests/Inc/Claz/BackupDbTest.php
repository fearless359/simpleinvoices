<?php
namespace Inc\Claz;

use Exception;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

include_once 'vendor/autoload.php';
include_once 'config/define.php';

/**
 * Class BackupDbTest
 * @name BackupDbTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20201109
 * @package Inc\Claz
 */
class BackupDbTest extends TestCase
{
    public ?array $config;

    public function setUp(): void
    {
        parent::setUp();

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

        parent::tearDown();
    }

    public function testConstruct()
    {
        $backupDb = new BackupDb();
        Assert::assertEquals("", $backupDb->getOutput());
    }

    public function testGetOutput()
    {
        $backup = new BackupDb();
        Assert::assertEquals("", $backup->getOutput());
    }
}
