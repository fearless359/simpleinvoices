<?php declare (strict_types=1);

/**
 * This file is part of the Samshal\Acl library
 *
 * @license MIT
 * @copyright Copyright (c) 2016 Samshal http://samshal.github.com
 */
namespace Samshal\Acl;

/**
 * Interface AclInterface.
 *
 * A contract that the Acl class must always obey
 *
 * @package samshal.acl
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since 30/05/2016
 */
interface AclInterface
{
    /**
     * Adds a new role object to the registry
     *
     * @param string $role
     * @return void
     */
    public function addRole(string ...$role);

    /**
     * Adds a new resource object to the registry
     *
     * @param string $resource
     * @return void
     */
    public function addResource(string ...$resource);

    /**
     * Adds a new permission object to the registry
     *
     * @param string $permission
     * @return void
     */
    public function addPermission(string ...$permission);

    /**
     * Adds objects lazily.
     *
     * Automatically determine the type of an object and call the appropriate
     * add method on it.
     *
     * @param ObjectInterface $object
     * @throws \Exception
     * @return void
     */
    public function add(ObjectInterface ...$object);

    /**
     * Change the status option of an assigned permission to true
     *
     * @param string $role;
     * @param string $permission
     * @param string $resource
     * @param boolean $status Optional
     * @throws \Exception
     * @return void
     */
    public function allow(string $role, string $permission, string $resource, bool $status=null);

    /**
     * Change the status option of an assigned permission to false
     *
     * @param string $role;
     * @param string $permission
     * @param string $resource
     * @return void
     */
    public function deny(string $role, string $permission, string $resource);
}
