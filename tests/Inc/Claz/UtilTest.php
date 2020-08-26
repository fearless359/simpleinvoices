<?php

/**
 * @name UtilTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190315
 */

namespace Inc\Claz;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Class UtilTest
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

    public static function setUpBeforeClass() {
        global $apiRequest, $config, $dbInfo;

        parent::setUpBeforeClass();

        $apiRequest = false;
        $setup = new Setup(false);
        $config = $setup->getConfig();
        $dbInfo = $setup->getDbInfo();
        $config = $setup->getConfig();

        $parts = explode('\\', dirname(__FILE__, 4));
        $ndx = count($parts) - 1;
        self::$parentDir = $parts[$ndx];

        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['PHP_SELF'] = '/' . self::$parentDir . '/index.php';
        $_SERVER['HTTPS'] = 'off';

        //***************************************************************
        //******* template settings for custom/default_template directory
        self::$existingCustomDefaultTemplateFileName = 'file_exists';
        self::$existingCustomDefaultPath = 'custom/default_template/' . self::$existingCustomDefaultTemplateFileName . '.tpl';
        if(!file_exists(self::$existingCustomDefaultPath)) {
            self::$customDefaultTemplatePathCreated = true;
            touch(self::$existingCustomDefaultPath);
        }

        // Find a file that does not exist in custom/default_template directory and
        // make sure it does exist in templates/default directory.
        $idx = 0;
        while(true) {
            self::$nonExistingCustomDefaultTemplateFileName = 'no_such_file_' . $idx++;
            self::$nonExistingCustomDefaultTemplatePath = 'custom/default_template/' . self::$nonExistingCustomDefaultTemplateFileName . '.tpl';
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
        if(!file_exists(self::$existingCustomModulePath)) {
            self::$customModulePathCreated = true;
            touch(self::$existingCustomModulePath);
        }

        // Find a file that does not exist in custom/default_template directory and
        // make sure it does exist in templates/default directory.
        $idx = 0;
        while(true) {
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

    public static function tearDownAfterClass()
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

    /** @noinspection PhpMethodMayBeStaticInspection */
    public function testGetLogoList()
    {
        $logos = Util::getLogoList();

        Assert::assertTrue(in_array('_default_blank_logo.png', $logos), 'Test for default logo in list');
        Assert::assertTrue(in_array('simple_invoices_logo.png', $logos), 'Test for SI logo in list');
    }

    /** @noinspection PhpMethodMayBeStaticInspection */
    public function testDropDown()
    {
        $array = [0 => 'Disabled', 1 => 'Enabled'];
        $actual = Util::dropDown($array, 0);
        $expected =  "<select name='value'>\n" .
                         "<option value='0' selected style='font-weight: bold''>Disabled</option>\n" .
                         "<option value='1' '>Enabled</option>\n" .
                     "</select>\n";
        Assert::assertEquals($expected, $actual);
    }

    /** @noinspection PhpMethodMayBeStaticInspection */
    public function testAllowDirectAccessMethods()
    {
        // NOTE: If this test fails, an exit() will have been executed causing the entire test to stop.
        Util::allowDirectAccess();
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        self::assertNull(Util::directAccessAllowed(), 'Test direct access allowed not redirected');
    }

    /** @noinspection PhpMethodMayBeStaticInspection */
    public function testGetLogo()
    {
        $url = Util::getURL();

        $biller['logo'] = '';
        $logo = Util::getLogo($biller);
        Assert::assertEquals($url . 'templates/invoices/logos/_default_blank_logo.png', $logo, 'Test for default logo');

        $biller['logo'] = 'simple_invoices_logo.png';
        $logo = Util::getLogo($biller);
        Assert::assertEquals($url . 'templates/invoices/logos/simple_invoices_logo.png', $logo, 'Test for biller logo');
    }

    /** @noinspection PhpMethodMayBeStaticInspection */
    public function testFilenameEscape()
    {
        $safeStr = Util::filenameEscape('escape$_file#1.jpg');
        Assert::assertEquals('escape__file_1.jpg', $safeStr);
    }

    /** @noinspection PhpMethodMayBeStaticInspection */
    public function testGetCustomPath()
    {
        // Test template option
        $customPath = Util::getCustomPath(self::$existingCustomDefaultTemplateFileName, 'template');
        Assert::assertEquals(self::$existingCustomDefaultPath, $customPath, 'Testing tpl file exists in custom/default_template path');

        $customPath = Util::getCustomPath(self::$nonExistingCustomDefaultTemplateFileName, 'template');
        Assert::assertEquals(self::$existingTemplatesDefaultPath, $customPath, 'Testing tpl file exists in templates/default path');

        // Test module option
        $customPath = Util::getCustomPath(self::$existingCustomModuleFileName, 'module');
        Assert::assertEquals(self::$existingCustomModulePath, $customPath, 'Testing php file exists in custom/modules path');

        $customPath = Util::getCustomPath(self::$nonExistingCustomModuleFileName, 'module');
        Assert::assertEquals(self::$existingModulePath, $customPath, 'Testing php files exists in the modules path');
    }

    /** @noinspection PhpMethodMayBeStaticInspection */
    public function testGetURL()
    {
        $siUrl = Util::getURL();
        Assert::assertEquals('http://localhost/' . self::$parentDir . '/', $siUrl);
    }

    /** @noinspection PhpMethodMayBeStaticInspection */
    public function testHtmlsafe()
    {
        $str = Util::htmlsafe("\"This\" is a 'test'");
        Assert::assertEquals("&quot;This&quot; is a &#039;test&#039;", $str);
    }

    /** @noinspection PhpMethodMayBeStaticInspection */
    public function testurlsafe()
    {
        $str = Util::urlsafe('$../(css)/validationEngine.$jquery.css');
        Assert::assertEquals( "../css/validationEngine.jquery.css", $str);
    }
}
