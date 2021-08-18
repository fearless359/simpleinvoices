<?php
namespace Inc\Claz;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

include_once 'vendor/autoload.php';
include_once 'config/define.php';

/**
 * Class ImportJsonTest
 * @name ImportJsonTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20201201
 */
class ImportJsonTest extends TestCase
{
    private string $jsonFile = "databases/json/essential_data.json";
    private array $find = ['si_','DOMAIN-ID','LOCALE','LANGUAGE'];
    private array $replace = [TB_PREFIX,'1','en_US','en_US'];

    public function testCollate()
    {
        $importJson = new ImportJson($this->jsonFile, $this->find, $this->replace, true);
        Assert::assertStringContainsString('INSERT into', $importJson->collate());
    }

    public function testConstruct()
    {
        $importJson = new ImportJson($this->jsonFile, $this->find, $this->replace, true);

        Assert::assertEquals($this->jsonFile, $importJson->getFileName());
        Assert::assertEquals($this->find, $importJson->getFind());
        Assert::assertEquals($this->replace, $importJson->getReplace());
        Assert::assertTrue($importJson->getDebug());

    }
}
