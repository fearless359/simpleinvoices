<?php
/**
 * @name DbInfoTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181114
 */

namespace Test\Inc\Claz;

use PHPUnit\Framework\TestCase;

use Inc\Claz\Config;
use Inc\Claz\DbInfo;

include 'config/define.php';

class DbInfoTest extends TestCase
{
    private $dbInfo;

    public function setUp()
    {
        $this->dbInfo = new DbInfo(Config::CONFIG_FILE, CONFIG_SECTION, CONFIG_DB_PREFIX);
    }

    public function testGetPort()
    {
        $this->assertTrue($this->dbInfo->getPort() == 3306,
            "DbInfoTest::testGetPort() - expected 3306 found {$this->dbInfo->getPort()}");
    }

    public function testGetHost()
    {
        $this->assertTrue($this->dbInfo->getHost() == 'localhost',
            "DbInfoTest::testGetHost() - expected 'localhost' found '{$this->dbInfo->getHost()}'");
    }

    public function testGetPassword()
    {
        $this->assertTrue($this->dbInfo->getPassword() == 'password',
            "DbInfoTest::testGetPassword() - expected 'password' found '{$this->dbInfo->getPassword()}'");
    }

    public function testGetSectionname()
    {
        $this->assertTrue($this->dbInfo->getSectionname() == CONFIG_SECTION,
            "DbInfoTest::testGetSectionname() - expected '" . CONFIG_SECTION . "' found '{$this->dbInfo->getSectionname()}'");
    }

    public function testGetDbname()
    {
        $this->assertTrue($this->dbInfo->getDbName() == 'simple_invoices',
            "DbInfoTest::testGetDbName() - expected 'simple_invoices' found '{$this->dbInfo->getDbName()}'");
    }

    public function testGetUsername()
    {
        $this->assertTrue($this->dbInfo->getUserName() == 'root',
            "DbInfoTest::testGetUserName() - expected 'root' found '{$this->dbInfo->getUserName()}'");
    }
}
