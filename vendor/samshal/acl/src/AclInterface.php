<?php declare (strict_types=1);

/**
 * This file is part of the Samshal\Acl library
 *
 * @license MIT
 * @copyright Copyright (c) 2016 Samshal http://samshal.github.com
 */
namespace Samshal\Acl;

use Exception;

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
     */
    public function addRole(string ...$role): void;

    /**
     * Adds a new resource object to the registry
     */
    public function addResource(string ...$resource): void;

    /**
     * Adds a new permission object to the registry
     */
    public function addPermission(string ...$permission): void;

    /**
     * Adds objects lazily.
     *
     * Automatically determine the type of an object and call the appropriate
     * add method on it.
     *
     * @throws Exception
     */
    public function add(ObjectInterface ...$object): void;

    /**
     * Change the status option of an assigned permission to true
     *
     * @throws Exception
     */
    public function allow(string $role, string $permission, string $resource, ?bool $status=null): void;

    /**
     * Change the status option of an assigned permission to false
     */
    public function deny(string $role, string $permission, string $resource): void;
}
