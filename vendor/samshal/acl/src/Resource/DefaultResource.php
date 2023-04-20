<?php declare (strict_types=1);

/**
 * This file is part of the Samshal\Acl library
 *
 * @license MIT
 * @copyright Copyright (c) 2016 Samshal http://samshal.github.com
 */
namespace Samshal\Acl\Resource;

use Samshal\Acl\ObjectInterface as ObjectInterface;

/**
 * Class DefaultResource.
 *
 * A base object for creating new Resource objects
 *
 * @package samshal.acl.resource
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since 30/05/2016
 */
class DefaultResource implements ResourceInterface, ObjectInterface
{
    protected string $resourceName;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $resourceName)
    {
        $this->resourceName = $resourceName;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return $this->resourceName;
    }

    /**
     * Returns the ResourceName when this class is treated as a string.
     */
    public function __toString() : string
    {
        return $this->getName();
    }
}
