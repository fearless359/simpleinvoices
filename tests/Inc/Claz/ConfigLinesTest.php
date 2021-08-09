<?php
namespace Inc\Claz;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Assert;

/**
 * Class ConfigLinesTest
 * @name ConfigLinesTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20201107
 * @package Inc\Claz
 */
class ConfigLinesTest extends TestCase
{
    public static function testConstruct()
    {
        $configLines = new ConfigLines('testSection', 'testKey', 'testValue');

        Assert::assertEquals('testSection', $configLines->getSection());
        Assert::assertEquals('testKey', $configLines->getKey());
        Assert::assertEquals('testValue', $configLines->getValue());
    }

    public static function testLineType()
    {
        Assert::assertEquals('section', ConfigLines::lineType("[testSection]"));
        Assert::assertEquals('pair', ConfigLines::lineType("testKey = testValue"));
        Assert::assertEquals('other', ConfigLines::lineType("; comment line"));
        Assert::assertEquals('other', ConfigLines::lineType(""));
    }
}
