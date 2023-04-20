<?php declare(strict_types=1);
/**
 * @license MIT
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @copyright Copyright (c) 2016 Samshal http://samshal.github.com
 */
namespace Samshal\Acl\Test\Role;

use PHPUnit\Framework\TestCase;
use Samshal\Acl\Role\DefaultRole as Role;

/**
 * class RoleTest.
 */
class RoleTest extends TestCase
{
    public function testNewRoleReturnStringName()
    {
        $newRole = new Role('administrator');

        $expected = 'administrator';
        $result = $newRole->getName();
        $this->assertEquals($expected, $result);
    }

    public function testNewRoleReturnNameWhenCastedAsString()
    {
        $newRole = new Role('administrator');

        $expected = 'administrator';
        $result = (string) $newRole;

        $this->assertEquals($expected, $result);
    }
}
