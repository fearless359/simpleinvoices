<?php
namespace Inc\Claz;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

include_once 'vendor/autoload.php';

/**
 * Class SetupTest
 * @name SetupTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20201203
 * @package Inc\Claz
 */
class SetupTest extends TestCase
{

    public function testGetConfigIni()
    {
        $setup = new Setup(CONFIG_SECTION, false, Config::CONFIG_FILE);
        $config = $setup->getConfigIni();
        Assert::assertEquals('pdo_mysql', $config['databaseAdapter']);
        Assert::assertEquals('3306', $config['databasePort']);
        Assert::assertEquals('USD', $config['localCurrencyCode']);
    }
}
