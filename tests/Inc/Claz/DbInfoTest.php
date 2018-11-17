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
        // Using config.php rather than custom.config.php so value are known.
        $this->dbInfo = new DbInfo(Config::CONFIG_FILE, CONFIG_SECTION, CONFIG_DB_PREFIX);
    }

    public function testGetPort()
    {
        $this->assertEquals($this->dbInfo->getPort(), 3306);
    }

    public function testGetHost()
    {
        $this->assertEquals($this->dbInfo->getHost(), 'localhost');
    }

    public function testGetPassword()
    {
        $this->assertEquals($this->dbInfo->getPassword(), 'password');
    }

    public function testGetSectionname()
    {
        $this->assertEquals($this->dbInfo->getSectionname(), CONFIG_SECTION);
    }

    public function testGetDbname()
    {
        $this->assertEquals($this->dbInfo->getDbName(), 'simple_invoices');
    }

    public function testGetUsername()
    {
        $this->assertEquals($this->dbInfo->getUserName(), 'root');
    }
}
