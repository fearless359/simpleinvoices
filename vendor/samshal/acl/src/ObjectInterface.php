<?php declare (strict_types=1);

/**
 * This file is part of the Samshal\Acl library
 *
 * @license MIT
 * @copyright Copyright (c) 2016 Samshal http://samshal.github.com
 */
namespace Samshal\Acl;

/**
 * interface ObjectInterface.
 *
 * Any class that creates a new Object (a new Resouce, Role or Permission) must obey this contract.
 *
 * @package samshal.acl
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since 30/05/2016
 */
interface ObjectInterface
{
    /**
     * Constructor
     *
     * @param string $objectName
     */
    public function __construct(string $objectName);

    /**
     * Returns the name of a Object object.
     *
     * @throws {@todo create exception for 'invalid Object supplied'}
     *
     * @return string
     */
    public function getName() : string;
}
