<?php
/** @noinspection PhpUnused */
/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

/**
 * This file is part of the Samshal\Acl library
 *
 * @license MIT
 * @copyright Copyright (c) 2016 Samshal http://samshal.github.com
 */
namespace Samshal\Acl;

use Samshal\Acl\Role\{
    DefaultRole as Role,
    RoleInterface
};
use Samshal\Acl\Resource\{
    DefaultResource as Resource,
    ResourceInterface
};
use Samshal\Acl\Permission\{
    DefaultPermission as Permission,
    PermissionInterface
};
use Samshal\Acl\Registry\{
    GlobalRegistry,
    Registry,
    RegistryInterface
};

use Exception;


/**
 * Class Acl
 *
 * @package samshal.acl
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since 30/05/2016
 */
class Acl implements AclInterface
{
    public RegistryInterface $roleRegistry;

    protected RegistryInterface $resourceRegistry;

    protected RegistryInterface $permissionRegistry;

    public RegistryInterface $globalRegistry;

    /**
     *  @var string[] $sesion
     */
    protected array $session = [];

    protected const SYN_ALLOW = "can";

    protected const SYN_DENY = "cannot";

    /**
     * Performs bootstrapping
     */
    public function __construct()
    {
        self::initRegistries();
        self::initSession();
    }

    /**
     * Initializes the registries
     */
    protected function initRegistries(): void
    {
        $this->roleRegistry = new Registry();
        $this->resourceRegistry = new Registry();
        $this->permissionRegistry = new Registry();
        $this->globalRegistry = new GlobalRegistry();
    }

    /**
     * Initializes the global session array and sets them to the default value
     */
    protected function initSession(): void
    {
        $this->session["query"] = true;
        unset($this->session["role"], $this->session["status"]);
    }

    /**
     * Listen for and intercept properties that are not set
     *
     * @throws Exception
     */
    public function __get(string $role) : AclInterface
    {
        if ($role === self::SYN_ALLOW || $role === self::SYN_DENY)
        {
            $this->session["status"] = $role === self::SYN_ALLOW;

            if (!empty($this->session["role"]))
            {
                $this->session["query"] = false;
            }

            return $this;
        }

        if (!$this->roleRegistry->exists($role)) {
            throw new Exception("The role: $role doesnt exist");
        }

        $this->session["role"] = $role;

        return $this;
    }

    /**
     * Listen for and intercept undefined methods
     *
     * @param string $permission
     * @param string[] $args
     * @throws Exception
     * @return bool|null|void
     */
    public function __call(string $permission, array $args)
    {
        if (!$this->permissionRegistry->exists($permission)) {
            throw new Exception("The permission: $permission doesnt exist");
        }

        foreach ($args as $arg) {
            if (!$this->resourceRegistry->exists($arg)) {
                throw new Exception("The resource: $arg doesnt exist");
            }
        }

        $args = count($args) > 0 ? $args : $this->resourceRegistry->getRegistryNames();

        if ($this->session["query"])
        {
            $result = null;
            foreach ($args as $arg)
            {
                $result = $this->getPermissionStatus(
                    $this->session["role"],
                    $permission,
                    $arg
                );

                if (!$result)
                {
                    break;
                }
            }
            
            $this->initSession();

            return $result;
        }

        foreach ($args as $arg) {
            $this->allow(
                $this->session["role"],
                $permission,
                $arg,
                (boolean)$this->session["status"]
            );
        }

        $this->initSession();
    }

    /**
     * Add a new role object to the registry
     *
     * @param string[] $role
     * @return void
     */
    public function addRole(string ...$role): void
    {
        foreach ($role as $_role)
        {
            $this->roleRegistry->save($_role);
        }
    }

    /**
     * Add a new resource object to the registry
     */
    public function addResource(string ...$resource): void
    {
        foreach ($resource as $_resource)
        {
            $this->resourceRegistry->save($_resource);
        }
    }

    /**
     * Add a new permission object to the registry
     */
    public function addPermission(string ...$permission): void
    {
        foreach ($permission as $_permission)
        {
            $this->permissionRegistry->save($_permission);
        }
    }

    /**
     * Adds objects lazily.
     *
     * Automatically determine the type of an object and call the appropriate
     * add method on it.
     *
     * @throws Exception
     */
    public function add(ObjectInterface ...$objects): void
    {
        foreach ($objects as $object)
        {
            if ($object instanceof RoleInterface)
            {
                $this->addRole((string)$object);
            }
            elseif ($object instanceof ResourceInterface)
            {
                $this->addResource((string)$object);
            }
            elseif ($object instanceof PermissionInterface)
            {
                $this->addPermission((string)$object);
            }
            else {
                throw new Exception("$object must implement one of RoleInterface, " .
                    "ResourceInterface and PermissionInterface");
            }   
        }
    }

    /**
     * Allows roles to inherit from the registries of other roles
     * @throws Exception
     */
    public function inherits(string ...$roles): void
    {
        foreach ($roles as $role)
        {
            if (!$this->roleRegistry->exists($role)) {
                throw new Exception("The role: $role doesnt exist");
            }

            $this->roleRegistry->setRegistryValue($this->session["role"], $role);
        }

        $this->initSession();
    }

    /**
     * Change the status option of an assigned permission to true
     *
     * @throws Exception
     */
    public function allow(string $role, string $permission, string $resource, ?bool $status=null): void
    {
        $status = $status ?? true;
        if (!$this->roleRegistry->exists($role)) {
            throw new Exception("The role: $role doesn't exist");
        }

        if (!$this->permissionRegistry->exists($permission)) {
            throw new Exception("The permission: $permission doesn't exist");
        }

        if (!$this->resourceRegistry->exists($resource)) {
            throw new Exception("The resource: $resource doesn't exist");
        }

        $this->globalRegistry->save($role, $resource, $permission, $status);
    }

    /**
     * Change the status option of an assigned permission to false
     * @throws Exception
     */
    public function deny(string $role, string $permission, string $resource): void
    {
        $this->allow($role, $permission, $resource, false);
    }

    /**
     * Retrieve the status of a permission assigned to a role
     * @throws Exception
     */
    public function getPermissionStatus(string $role, string $permission, string $resource) : bool
    {
        if (!$this->roleRegistry->exists($role)) {
            throw new Exception("The role: $role doesn't exist");
        }

        if (!$this->permissionRegistry->exists($permission)) {
            throw new Exception("The permission: $permission doesn't exist");
        }

        if (!$this->resourceRegistry->exists($resource)) {
            throw new Exception("The resource: $resource doesn't exist");
        }

        $roleObject = $this->globalRegistry->get($role);

        if (isset($roleObject[$resource][$permission]["status"]))
        {
            return $roleObject[$resource][$permission]["status"];
        }

        $parents = $this->roleRegistry->getRegistry()[$role];

        foreach ($parents as $parentRole)
        {
            $permissionStatus = $this->getPermissionStatus($parentRole, $permission, $resource);

            if ($permissionStatus)
            {
                return true;
            }
        }

        return false;
    }

    public function getResources(): array
    {
        return $this->resourceRegistry->getRegistryNames();
    }
}
