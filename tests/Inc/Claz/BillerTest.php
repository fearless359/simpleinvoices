<?php
/**
 * @name BillerTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181110
 */
namespace test\Inc\Claz;

use PHPUnit\Framework\TestCase;

use Inc\Claz\Biller;
use Inc\Claz\Config;
use Inc\Claz\DbInfo;
use Inc\Claz\DomainId;
use Inc\Claz\PdoDbException;
use Inc\Claz\Setup;
use Inc\Claz\SystemDefaults;

/**
 * Class BillerTest
 */
class BillerTest extends TestCase
{
    private $id_active;
    private $id_inactive;
    private $name_active;
    private $name_inactive;
    private $count;

    /**
     * Setup information needed for tests in this class.
     * @throws PdoDbException if database error occurs
     */
    public function setUp()
    {
        global $pdoDb;

        Setup::setPath();
        Setup::init(false);

        $this->name_active = '123-Test Record-Active';
        $pdoDb->setFauxPost(array(
            'domain_id' => DomainId::get(),
            'name' => $this->name_active,
            'enabled' => ENABLED
        ));
        $this->id_active = $pdoDb->request('INSERT', 'biller');

        $this->name_inactive = '124-Test Record-Active';
        $pdoDb->setFauxPost(array(
            'domain_id' => DomainId::get(),
            'name' => $this->name_inactive,
            'enabled' => DISABLED
        ));
        $this->id_inactive = $pdoDb->request('INSERT', 'biller');

        $rows = $pdoDb->request('SELECT', 'biller');
        $this->count = count($rows);
    }

    public function tearDown()
    {
        global $pdoDb;

        // Remove records added for this test class.
        $pdoDb->addSimpleWhere('id', $this->id_active, 'OR');
        $pdoDb->addSimpleWhere('id', $this->id_inactive);
        $pdoDb->request('DELETE', 'biller');

        parent::tearDown();
    }

    public function testCount()
    {
        $count = Biller::count();
        $this->assertEquals($count, $this->count);
    }

    public function testGetAll()
    {
        $rows = Biller::getAll();
        $this->assertNotEmpty($rows);
    }

    public function testGetDefaultBiller()
    {
        $defaults = SystemDefaults::loadValues(true);
        $default_biller = $defaults['biller'];

        $row = Biller::getDefaultBiller();
        $this->assertEquals($default_biller, $row['id']);
    }

    public function testInsertBiller()
    {
        global $pdoDb;
        $_POST['name'] = 'testInsertBiller';
        $id = Biller::insertBiller();
        if ($id > 0) {
            $ok = true;
            $pdoDb->addSimpleWhere('id', $id);
            $pdoDb->request('DELETE', 'biller');
        } else {
            $ok = false;
        }

        $this->assertTrue($ok, "BillerTest::testInsertBiller() - failed.");
    }

    public function testSelect()
    {
        $biller = Biller::select($this->id_active);
        $billerName = (empty($biller['name']) ? "" : $biller['name']);
        // @formatter:off
        $this->assertTrue(!empty($billerName) && $billerName == $this->name_active,
            "BillerTest::testSelect() - failed. " .
            (empty($billerName) ? "No biller record found." :
                                  "Expected biller name of " . $this->name_active .
                                    " found {$billerName}"));
        // @formatter:on
    }

    public function testUpdateBiller()
    {
        global $pdoDb;

        $street = '12345 Test Street';
        $_POST['street_address'] = $street;
        $_GET['id'] = $this->id_active;
        $updtOk = Biller::updateBiller();
        if ($updtOk) {
            $pdoDb->addSimpleWhere('id', $this->id_active);
            $rows = $pdoDb->request('SELECT', 'biller');
            $addrOk = (!empty($rows) && $rows[0]['street_address'] == $street);
        }

        $this->assertTrue($updtOk, "BillerTest::testUpdateBiller() - Biller::updateBiller() failed.");
        if ($updtOk) {
            $this->assertTrue($addrOk, "BillerTest::testUpdateBiller() - failed. Expected street_addres, {$street}, found, {$rows[0]['street_address']}");
        }
    }

    public function testXmlSql()
    {
        $rows = Biller::xmlSql();
        $xmlCount = count($rows);

        $count = Biller::count();
        $this->assertTrue($xmlCount == $count,
            "BillerTest::testXmlSql() - failed. Expected count, {$count}, found xmlCount, {$xmlCount}");
    }
}
