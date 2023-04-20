<?php declare (strict_types=1);

/**
 * This file is part of the Samshal\Acl library
 *
 * @license MIT
 * @copyright Copyright (c) 2016 Samshal http://samshal.github.com
 */
namespace Samshal\Acl\Registry;

/**
 * Class Registry
 *
 * @package samshal.acl.registry
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since 31/05/2016
 */
class Registry implements RegistryInterface
{
    protected array $registry = [];

    /**
     * Saves an object to the registry
     * @param string $object
     * @param mixed ...$options
     */
    public function save(string $object, ...$options): void
    {
        if (!$this->exists($object)) {
            $this->registry[$object] = [];
        }
    }

    /**
     * removes an object from the registry
     */
    public function remove(string $object): void
    {
        if ($this->exists($object)) {
            unset($this->registry[$object]);
        }
    }

    /**
     * determines if an object exists in the registry
     */
    public function exists(string $object) : bool
    {
        return !empty($this->registry) && isset($this->registry[$object]);
    }

    /**
     * retrieves an object index from the registry
     */
    public function get(string $object): mixed
    {
        return $this->exists($object) ? $this->registry[$object] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegistry() : array
    {
        return $this->registry;
    }

    /**
     * returns the names of objects stored in the registry
     *
     * @return array
     */
    public function getRegistryNames() : array
    {
        $names = [];
        foreach ($this->getRegistry() as $registryName=>$registryValue)
        {
            $names[] = $registryName;
        }

        return $names;
    }

    /**
     * Sets the value of a registry object
     */
    public function setRegistryValue(string $registryIndex, string $registryValue): void
    {
        $this->registry[$registryIndex][] = $registryValue;
    }
}
