<?php
/** @noinspection PhpMethodMayBeStaticInspection */

namespace Inc\Claz;

use PHPUnit\Framework\TestCase;

include_once 'vendor/autoload.php';

/**
 * Class UtilTest
 * @name UtilTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190315
 * @package test\Inc\Claz
 */
class UtilTest extends TestCase
{
    protected static bool $customDefaultTemplatePathCreated = false;
    protected static string $existingCustomDefaultTemplateFileName;
    protected static string $nonExistingCustomDefaultTemplateFileName;
    protected static string $existingCustomDefaultPath;
    protected static string $nonExistingCustomDefaultTemplatePath;
    protected static bool $existingTemplatesDefaultPathCreated = false;
    protected static string $existingTemplatesDefaultPath;

    protected static bool $customModulePathCreated = false;
    protected static string $existingCustomModuleFileName;
    protected static string $nonExistingCustomModuleFileName;
    protected static string $existingCustomModulePath;
    protected static string $nonExistingCustomModulePath;
    protected static bool $existingModulePathCreated = false;
    protected static string $existingModulePath;

    protected static string $parentDir;

    public static function setUpBeforeClass(): void
    {
        global $apiRequest, $config;

        parent::setUpBeforeClass();

        $apiRequest = false;
        $setup = new Setup(CONFIG_SECTION, false, Config::CONFIG_FILE);
        $config = $setup->getConfigIni();

        $parts = explode('\\', dirname(__FILE__, 4));
        $ndx = count($parts) - 1;
        self::$parentDir = $parts[$ndx];

        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['PHP_SELF'] = '/' . self::$parentDir . '/index.php';
        $_SERVER['HTTPS'] = 'off';

        //***************************************************************
        //******* template settings for custom/defaultTemplate directory
        self::$existingCustomDefaultTemplateFileName = 'file_exists';
        self::$existingCustomDefaultPath = 'custom/defaultTemplate/' . self::$existingCustomDefaultTemplateFileName . '.tpl';
        if (!file_exists(self::$existingCustomDefaultPath)) {
            self::$customDefaultTemplatePathCreated = true;
            touch(self::$existingCustomDefaultPath);
        }

        // Find a file that does not exist in custom/defaultTemplate directory and
        // make sure it does exist in templates/default directory.
        $idx = 0;
        while (true) {
            self::$nonExistingCustomDefaultTemplateFileName = 'no_such_file_' . $idx++;
            self::$nonExistingCustomDefaultTemplatePath = 'custom/defaultTemplate/' . self::$nonExistingCustomDefaultTemplateFileName . '.tpl';
            if (!file_exists(self::$nonExistingCustomDefaultTemplatePath)) {
                self::$existingTemplatesDefaultPath = 'templates/default/' . self::$nonExistingCustomDefaultTemplateFileName . '.tpl';
                if (!file_exists(self::$existingTemplatesDefaultPath)) {
                    self::$existingTemplatesDefaultPathCreated = true;
                    touch(self::$existingTemplatesDefaultPath);
                }
                break;
            }
        }
        //***************************************************************

        //***************************************************************
        //******* modules settings for custom/modules directory
        self::$existingCustomModuleFileName = 'file_exists';
        self::$existingCustomModulePath = 'custom/modules/' . self::$existingCustomModuleFileName . '.php';
        if (!file_exists(self::$existingCustomModulePath)) {
            self::$customModulePathCreated = true;
            touch(self::$existingCustomModulePath);
        }

        // Find a file that does not exist in custom/defaultTemplate directory and
        // make sure it does exist in templates/default directory.
        $idx = 0;
        while (true) {
            self::$nonExistingCustomModuleFileName = 'no_such_file_' . $idx++;
            self::$nonExistingCustomModulePath = 'custom/modules/' . self::$nonExistingCustomModuleFileName . '.php';
            if (!file_exists(self::$nonExistingCustomModulePath)) {
                self::$existingModulePath = 'modules/' . self::$nonExistingCustomModuleFileName . '.php';
                if (!file_exists(self::$existingModulePath)) {
                    self::$existingModulePathCreated = true;
                    touch(self::$existingModulePath);
                }
                break;
            }
        }
        //***************************************************************

    }

    public static function tearDownAfterClass(): void
    {
        if (self::$customDefaultTemplatePathCreated) {
            unlink(self::$existingCustomDefaultPath);
        }

        if (self::$existingTemplatesDefaultPathCreated) {
            unlink(self::$existingTemplatesDefaultPath);
        }

        if (self::$customModulePathCreated) {
            unlink(self::$existingCustomModulePath);
        }

        if (self::$existingModulePathCreated) {
            unlink(self::$existingModulePath);
        }

        parent::tearDownAfterClass();
    }

    public function testGetLogoList()
    {
        $logos = Util::getLogoList();

        self::assertTrue(in_array('_default_blank_logo.png', $logos), 'Test for default logo in list');
        self::assertTrue(in_array('simple_invoices_logo.png', $logos), 'Test for SI logo in list');
    }

    public function testDropDown()
    {
        $array = [0 => 'Disabled', 1 => 'Enabled'];
        $actual = Util::dropDown($array, 0);
        $expected = "<select name='value'>\n" .
            "<option value='0' selected style='font-weight: bold''>Disabled</option>\n" .
            "<option value='1' '>Enabled</option>\n" .
            "</select>\n";
        self::assertEquals($expected, $actual);
    }

    public function testAllowDirectAccessMethods()
    {
        // NOTE: If this test fails, an exit() will have been executed causing the entire test to stop.
        Util::allowDirectAccess();
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        self::assertNull(Util::directAccessAllowed(), 'Test direct access allowed not redirected');
    }

    public function testGetLogo()
    {
        $url = Util::getURL();

        $biller['logo'] = '';
        $logo = Util::getLogo($biller);
        self::assertEquals('templates/invoices/logos/_default_blank_logo.png', $logo, 'Test for default logo');

        $biller['logo'] = 'simple_invoices_logo.png';
        $logo = Util::getLogo($biller);
        self::assertEquals('templates/invoices/logos/simple_invoices_logo.png', $logo, 'Test for biller logo');
    }

    /** @noinspection PhpMethodMayBeStaticInspection */
    public function testFilenameEscape()
    {
        $safeStr = Util::filenameEscape('escape$_file#1.jpg');
        self::assertEquals('escape__file_1.jpg', $safeStr);
    }

    public function testGetCustomPath()
    {
        // Test template option
        /** @noinspection PhpRedundantOptionalArgumentInspection */
        $customPath = Util::getCustomPath(self::$nonExistingCustomDefaultTemplateFileName, 'template');
        self::assertEquals(self::$existingTemplatesDefaultPath, $customPath, 'Testing tpl file exists in templates/default path');

        // Test module option
        $customPath = Util::getCustomPath(self::$existingCustomModuleFileName, 'module');
        self::assertEquals(self::$existingCustomModulePath, $customPath, 'Testing php file exists in custom/modules path');

        $customPath = Util::getCustomPath(self::$nonExistingCustomModuleFileName, 'module');
        self::assertEquals(self::$existingModulePath, $customPath, 'Testing php files exists in the modules path');
    }

    public function testGetURL()
    {
        $siUrl = Util::getURL();
        self::assertEquals('http://localhost/' . self::$parentDir . '/', $siUrl);
    }

    public function testNumber()
    {
        $actual = Util::number("1234.5678");
        self::assertEquals("1,234.57", $actual);

        $actual = Util::number("1234.5678", 0);
        self::assertEquals("1,235", $actual);

        $actual = Util::number("1234.5678", 2);
        self::assertEquals("1,234.57", $actual);

        $actual = Util::number("1234.5678", 2, "ru_RU");
        self::assertEquals("1 234,57", $actual);
    }

    public function testNumberTrim()
    {
        $actual = Util::numberTrim("1234.");
        self::assertEquals("1,234", $actual);

        $actual = Util::numberTrim("1234.00");
        self::assertEquals("1,234", $actual);

        $actual = Util::numberTrim("1234.15", 0);
        self::assertEquals("1,234", $actual);

        $actual = Util::numberTrim("1234.00", 2);
        self::assertEquals("1,234", $actual);

        $actual = Util::numberTrim("1234.10", 2);
        self::assertEquals("1,234.1", $actual);

        $actual = Util::numberTrim("1234.5678");
        self::assertEquals("1,234.57", $actual);

        $actual = Util::numberTrim("1234.5678", 2);
        self::assertEquals("1,234.57", $actual);

        $actual = Util::numberTrim("1234.5678", 2, "ru_RU");
        self::assertEquals("1 234,57", $actual);
    }

    public function testCurrency()
    {
        $actual = Util::currency("1234.");
        self::assertEquals("$1,234.00", $actual);

        $actual = Util::currency("1234.5678");
        self::assertEquals("$1,234.57", $actual);

        $actual = Util::currency("1234.5678", "ru_RU", 'EUR');
        self::assertEquals("1 234,57 €", $actual);
    }

    public function testDate()
    {
        $actual = Util::date("2020-05-08"); // Defaults to medium
        self::assertEquals("05/08/2020", $actual);

        $actual = Util::date('2020-05-08', 'full');
        self::assertEquals("Friday, May 08, 2020", $actual);

        $actual = Util::date('2020-05-08', 'long');
        self::assertEquals("May 08, 2020", $actual);

        /** @noinspection PhpRedundantOptionalArgumentInspection */
        $actual = Util::date('2020-05-08', 'medium');
        self::assertEquals("05/08/2020", $actual);

        $actual = Util::date('2020-05-08', 'short');
        self::assertEquals("05/08/20", $actual);

        $actual = Util::date('2020-02-08', 'month');
        self::assertEquals("February", $actual);

        $actual = Util::date('2020-02-08', 'month_short');
        self::assertEquals("Feb", $actual);
    }

    public function testDbStd()
    {
        global $config;

        $config['localLocale'] = 'fr_FR';
        $config['localCurrencyCode'] = "EUR";
        $number = "1200.47";
        $actual = Util::dbStd(Util::currency($number));
        self::assertEquals($number, $actual);

        $config['localLocale'] = 'en_US';
        $config['localCurrencyCode'] = "USD";
        $number = "1201.48";
        $actual = Util::dbStd(Util::currency($number));
        self::assertEquals($number, $actual);

        $config['localLocale'] = 'nl_NL';
        $config['localCurrencyCode'] = "ANG";
        $number = "405.47";
        $actual = Util::dbStd(Util::currency($number));
        self::assertEquals($number, $actual);

        $config['localLocale'] = 'en_GB';
        $config['localCurrencyCode'] = "GBP";
        $number = "-11405.47";
        $actual = Util::dbStd(Util::currency($number));
        self::assertEquals($number, $actual);

        $config['localLocale'] = 'en_US';
        $config['localCurrencyCode'] = "USD";
        $number = "1011205.47";
        $actual = Util::dbStd(Util::number($number));
        self::assertEquals($number, $actual);

        $config['localLocale'] = 'en_US';
        $config['localCurrencyCode'] = "USD";
        $number = "-1205.47";
        $actual = Util::dbStd(Util::number($number));
        self::assertEquals($number, $actual);
    }

    public function testGetLocaleList()
    {
        $list = Util::getLocaleList();
        self::assertNotEmpty($list);
        self::assertTrue(in_array('en_US', $list));
        self::assertTrue(in_array('zh_TW', $list));
    }

    public function testHtmlSafe()
    {
        $str = Util::htmlSafe("\"This\" is a 'test'");
        self::assertEquals("&quot;This&quot; is a &#039;test&#039;", $str);
    }

    public function testUrlSafe()
    {
        $str = Util::urlSafe('$../(css)/validationEngine.$jquery.css');
        self::assertEquals("../css/validationEngine.jquery.css", $str);
    }

    //    public function testOutHtml()
    //    {
    //        $actual = Util::outHtml("<b>Inline <del>context <div>No block allowed</div></del></b>");
    //        self::assertEquals("<b>Inline <del>context No block allowed</del></b>", $actual);
    //
    //        $actual = Util::outHtml("<b>Bold");
    //        self::assertEquals("<b>Bold</b>", $actual);
    //
    //        $actual = Util::outHtml("<!--suppress HtmlDeprecatedTag --><center>Centered</center>");
    //        self::assertEquals('<div style="text-align:center;">Centered</div>', $actual);
    //    }

    public function testSqlDateWithTime()
    {
        $actual = Util::sqlDateWithTime("2020-05-08 12:59:58");
        self::assertEquals("2020-05-08 12:59:58", $actual);

        $actual = Util::sqlDateWithTime("2020-05-08 00:00:00");
        $parts = explode(' ', $actual);
        self::assertCount(2, $parts);
        self::assertEquals("2020-05-08", $parts[0]);
        self::assertNotEquals("00:00:00", $parts[1]);
    }
}
