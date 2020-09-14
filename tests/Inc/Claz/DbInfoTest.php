<?php
/**
 * @name DbInfoTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181114
 */

namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

/**
 * Class DbInfoTest
 * @package Inc\Claz
 */
class DbInfoTest extends TestCase
{
    private DbInfo $dbInfo;

    public function setUp()
    {
        parent::setUp();

        // Using config.ini rather than custom.config.ini so value are known.
        $this->dbInfo = new DbInfo(Config::CONFIG_FILE, 'production', "database");
    }

    public function testGetPort()
    {
        self::assertEquals(3306, $this->dbInfo->getPort());
    }

    public function testGetHost()
    {
        self::assertEquals('localhost', $this->dbInfo->getHost());
    }

    public function testGetPassword()
    {
        self::assertEquals('password', $this->dbInfo->getPassword());
    }

    public function testGetSectionName()
    {
        self::assertEquals("production", $this->dbInfo->getSectioNname());
    }

    public function testGetDbname()
    {
        self::assertEquals('simple_invoices', $this->dbInfo->getDbName());
    }

    public function testGetUsername()
    {
        self::assertEquals('root', $this->dbInfo->getUserName());
    }
}
