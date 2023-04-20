<?php declare (strict_types=1);

/**
 * This file is part of the Samshal\Acl library
 *
 * @license MIT
 * @copyright Copyright (c) 2016 Samshal http://samshal.github.com
 */
namespace Samshal\Acl\Registry;

/**
 * Interface RegistryInterface.
 * A contract that all Registry must always obey
 *
 * @package samshal.acl.registry
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since 30/05/2016
 */
interface RegistryInterface
{
    /**
     * Adds a new value to the global registry
     */
    public function save(string $object, ...$options): void;

    /**
     * removes an object from the global registry
     */
    public function remove(string $object): void;

    /**
     * determines if an object exists in the global registry
     */
    public function exists(string $object) : bool;

    /**
     * retrieves an object index from the registry
     */
    public function get(string $object): mixed;

    /**
     * returns the registry global property
     */
    public function getRegistry(): array;

    /**
     * Sets the value of a registry object
     */
    public function setRegistryValue(string $registryIndex, string $registryValue): void;
}
