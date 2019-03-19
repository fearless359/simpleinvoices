<?php
/**
 * @name UtilTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190315
 */

namespace test\Inc\Claz;

use Inc\Claz\Setup;
use Inc\Claz\Util;
use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    protected static $customDefaultTemplatePathCreated = false;
    protected static $existingCustomDefaultTemplateFileName;
    protected static $nonExistingCustomDefaultTemplateFileName;
    protected static $existingCustomDefaultPath;
    protected static $nonExistingCustomDefaultTemplatePath;
    protected static $existingTemplatesDefaultPathCreated = false;
    protected static $existingTemplatesDefaultPath;

    protected static $customModulePathCreated = false;
    protected static $existingCustomModuleFileName;
    protected static $nonExistingCustomModuleFileName;
    protected static $existingCustomModulePath;
    protected static $nonExistingCustomModulePath;
    protected static $existingModulePathCreated = false;
    protected static $existingModulePath;

    protected static $parent_dir;

    public static function setUpBeforeClass() {
        global $api_request, $config, $dbInfo, $pdoDb, $pdoDb_admin;

        $api_request = false;
        Setup::setPath();
        Setup::init(false, $config, $dbInfo, $pdoDb, $pdoDb_admin);

        $parts = explode('\\', dirname(__FILE__, 4));
        $ndx = count($parts) - 1;
        self::$parent_dir = $parts[$ndx];

        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['PHP_SELF'] = '/' . self::$parent_dir . '/index.php';
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
        $i = 0;
        while(true) {
            self::$nonExistingCustomDefaultTemplateFileName = 'no_such_file_' . $i++;
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
        $i = 0;
        while(true) {
            self::$nonExistingCustomModuleFileName = 'no_such_file_' . $i++;
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
    }

    public function testGetLogoList()
    {
        $logos = Util::getLogoList();

        $this->assertTrue(in_array('_default_blank_logo.png', $logos), 'Test for default logo in list');
        $this->assertTrue(in_array('simple_invoices_logo.png', $logos), 'Test for SI logo in list');
    }

    public function testDropDown()
    {
        $array = array(0 => 'Disabled', 1 => 'Enabled');
        $actual = Util::dropDown($array, 0);
        $expected =  "<select name='value'>\n" .
                         "<option value='0' selected style='font-weight: bold''>Disabled</option>\n" .
                         "<option value='1' '>Enabled</option>\n" .
                     "</select>\n";
        $this->assertEquals($expected, $actual);
    }

    public function testAllowDirectAccessMethods()
    {
        // NOTE: If this test fails, an exit() will have been executed causing the entire test to stop.
        Util::allowDirectAccess();
        self::assertNull(Util::directAccessAllowed(), 'Test direct access allowed not redirected');
    }

    public function testGetLogo()
    {
        $url = Util::getURL();

        $biller['logo'] = '';
        $logo = Util::getLogo($biller);
        $this->assertEquals($url . 'templates/invoices/logos/_default_blank_logo.png', $logo, 'Test for default logo');

        $biller['logo'] = 'simple_invoices_logo.png';
        $logo = Util::getLogo($biller);
        $this->assertEquals($url . 'templates/invoices/logos/simple_invoices_logo.png', $logo, 'Test for biller logo');
    }

    public function testFilenameEscape()
    {
        $safe_str = Util::filenameEscape('escape$_file#1.jpg');
        $this->assertEquals('escape__file_1.jpg', $safe_str);
    }

    public function testGetCustomPath()
    {
        // Test template option
        $customPath = Util::getCustomPath(self::$existingCustomDefaultTemplateFileName, 'template');
        $this->assertEquals(self::$existingCustomDefaultPath, $customPath, 'Testing tpl file exists in custom/default_template path');

        $customPath = Util::getCustomPath(self::$nonExistingCustomDefaultTemplateFileName, 'template');
        $this->assertEquals(self::$existingTemplatesDefaultPath, $customPath, 'Testing tpl file exists in templates/default path');

        // Test module option
        $customPath = Util::getCustomPath(self::$existingCustomModuleFileName, 'module');
        $this->assertEquals(self::$existingCustomModulePath, $customPath, 'Testing php file exists in custom/modules path');

        $customPath = Util::getCustomPath(self::$nonExistingCustomModuleFileName, 'module');
        $this->assertEquals(self::$existingModulePath, $customPath, 'Testing php files exists in the modules path');
    }

    public function testGetURL()
    {
        $siUrl = Util::getURL();
        $this->assertEquals('http://localhost/' . self::$parent_dir . '/', $siUrl);
    }

    public function testHtmlsafe()
    {
        $str = Util::htmlsafe("\"This\" is a 'test'");
        $this->assertEquals("&quot;This&quot; is a &#039;test&#039;", $str);
    }

    public function testUrlsafe()
    {
        $str = Util::urlsafe('$../(css)/validationEngine.$jquery.css');
        $this->assertEquals( "../css/validationEngine.jquery.css", $str);
    }
}
