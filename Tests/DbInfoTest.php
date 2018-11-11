<?php
/**
 * @name DbInfoTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181110
 */

use Inc\Claz\DbInfo;
use PHPUnit\Framework\TestCase;

class DbInfoTest extends TestCase
{

    public function testGetDbname()
    {

    }

    public function testGetUsername()
    {

    }

    public function testGetAdapter()
    {

    }

    public function testGetPassword()
    {

    }

    public function test__construct()
    {
        $dbInfo = new DbInfo();
        assertTrue(is_a($dbInfo, 'DbInfo'));
    }

    public function testGetPort()
    {

    }

    public function testGetSectionname()
    {

    }

    public function testLoadSectionInfo()
    {

    }

    public function testGetHost()
    {

    }
}
