<?php declare (strict_types=1);

/**
 * This file is part of the Samshal\Acl library
 *
 * @license MIT
 * @copyright Copyright (c) 2016 Samshal http://samshal.github.com
 */
namespace Samshal\Acl\Registry;

/**
 * Class GlobalRegistry
 *
 * @package samshal.acl.registry
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since 30/05/2016
 */
class GlobalRegistry extends Registry
{
    /**
     * Overrides the global save method variadic
     *
     * @param string $role
     * @param mixed ...$options
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    public function save(string $role, ...$options): void
    {
        $resource = (string)$options[0];
        $permission = (string)$options[1];
        $status = !(isset($options[2])) || $options[2];

        $this->registry[$role][$resource][$permission]["status"] = $status;
    }
}
