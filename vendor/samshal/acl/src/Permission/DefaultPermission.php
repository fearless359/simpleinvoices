<?php declare (strict_types=1);

/**
 * This file is part of the Samshal\Acl library
 *
 * @license MIT
 * @copyright Copyright (c) 2016 Samshal http://samshal.github.com
 */
namespace Samshal\Acl\Permission;

use Samshal\Acl\ObjectInterface;

/**
 * class DefaultPermission.
 *
 * @package samshal.acl.permission
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since 30/05/2016
 */
class DefaultPermission implements PermissionInterface, ObjectInterface
{
    /**
     * @var string $permissionName
     * @access protected
     */
    protected string $permissionName;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $permissionName)
    {
        $this->permissionName = $permissionName;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return $this->permissionName;
    }

    /**
     * Returns the permissionName when this class is treated as a string
     */
    public function __toString() : string
    {
        return $this->getName();
    }
}
