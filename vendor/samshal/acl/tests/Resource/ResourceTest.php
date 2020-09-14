<?php declare(strict_types=1);
/**
 * @license MIT
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @copyright Copyright (c) 2016 Samshal http://samshal.github.com
 */
namespace Samshal\Acl\Test\Resource;

use Samshal\Acl\Resource\DefaultResource as Resource;

/**
 * class ResourceTest.
 */
class ResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testNewResourceReturnStringName()
    {
        $newResource = new Resource('viewPatientHistory');

        $expected = 'viewPatientHistory';
        $result = $newResource->getName();
        $this->assertEquals($expected, $result);
    }

    public function testNewResourceReturnNameWhenCastedAsString()
    {
        $newResource = new Resource('viewPatientHistory');

        $expected = 'viewPatientHistory';
        $result = (string) $newResource;

        $this->assertEquals($expected, $result);
    }
}
