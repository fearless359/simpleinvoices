<?php declare(strict_types=1);

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
    Registry
};

/**
 * Class Acl
 *
 * @package samshal.acl
 * @author Samuel Adeshina <samueladeshina73@gmail.com>
 * @since 30/05/2016
 */
class Acl implements AclInterface
{
    /**
     * @var Samshal\Acl\Registry\RegistryInterface $roleRegistry
     */
    public $roleRegistry;

    /**
     * @var Samshal\Acl\Registry\RegistryInterface $resourceRegistry
     */
    protected $resourceRegistry;

    /**
     * @var Samshal\Acl\Registry\RegistryInterface $permissionRegistry
     */
    protected $permissionRegistry;

    /**
     * @var Samshal\Acl\Registry\RegistryInterface $globalRegistry
     */
    public $globalRegistry;

    /**
     *  @var string[] $sesion
     */
    protected $session = [];

    /**
     * @var string SYN_ALLOW
     */
    const SYN_ALLOW = "can";

    /**
     * @var string SYN_DENY
     */
    const SYN_DENY = "cannot";

    /**
     * Performs bootstrapping
     */
    public function __construct()
    {
        self::initRegistries();
        self::initSession();
    }

    /**
     * Initalizes the registries
     *
     * @return void
     */
    protected function initRegistries()
    {
        $this->roleRegistry = new Registry();
        $this->resourceRegistry = new Registry();
        $this->permissionRegistry = new Registry();
        $this->globalRegistry = new GlobalRegistry();
    }

    /**
     * Initializes the global session array and sets them to the default value
     *
     * @return void
     */
    protected function initSession()
    {
        $this->session["query"] = true;
        unset($this->session["role"], $this->session["status"]);
    }

    /**
     * Listen for and intercept properties that're not set
     *
     * @param string $role;
     * @throws \Exception
     * @return AclInterface
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
            throw new \Exception(
                sprintf(
                    "The role: %s doesnt exist",
                    (string)$role
                )
            );
        }

		$this->session["role"] = $role;

		return $this;
	}

	/**
	 * Listen for and intercept undefined methods
	 *
	 * @param string $permission
	 * @param string[] $args
	 * @throws \Exception
	 * @return bool|null
	 */
	public function __call(string $permission, array $args)
	{
        if (!$this->permissionRegistry->exists($permission)) {
            throw new \Exception(
                sprintf(
                    "The permission: %s doesnt exist",
                    (string)$permission
                )
            );
        }

        foreach ($args as $arg) {
            if (!$this->resourceRegistry->exists($arg)) {
                throw new \Exception(
                    sprintf(
                        "The resource: %s doesnt exist",
                        (string)$arg
                    )
                );
            }
        }

        $args = count($args) > 0 ? $args : $this->resourceRegistry->getRegistryNames();

		if ($this->session["query"])
		{
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
	public function addRole(string ...$role)
	{
		foreach ($role as $_role)
		{
			$this->roleRegistry->save($_role);
		}
	}

	/**
	 * Add a new resource object to the registry
	 *
	 * @param string[] $resource
	 * @return void
	 */
	public function addResource(string ...$resource)
	{
		foreach ($resource as $_resource)
		{
			$this->resourceRegistry->save($_resource);
		}
	}

	/**
	 * Add a new permission object to the registry
	 *
	 * @param string[] $permission
	 * @return void
	 */
	public function addPermission(string ...$permission)
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
	 * @param ObjectInterface[] $objects
	 * @throws \Exception
	 * @return void
	 */
	public function add(ObjectInterface ...$objects)
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
	            throw new \Exception(
	                sprintf(
	                    "%s must implement one of RoleInterface, '.
	                    'ResourceInterface and PermissionInterface",
	                    $object
	                )
	            );
	        }	
		}
	}

    /**
     * Allows roles to inherit from the registries of other roles
     *
     * @param string[] $roles
     */
    public function inherits(string ...$roles)
    {
        foreach ($roles as $role)
        {
            if (!$this->roleRegistry->exists($role)) {
                throw new \Exception(
                    sprintf(
                        "The role: %s doesnt exist",
                        (string)$role
                    )
                );
            }

            $this->roleRegistry->setRegistryValue($this->session["role"], $role);
        }

        $this->initSession();
    }

	/**
	 * Change the status option of an assigned permission to true
	 *
	 * @param string $role;
	 * @param string $permission
	 * @param string $resource
	 * @param bool $status Optional
	 * @throws \Exception
	 * @return void
	 */
	public function allow(string $role, string $permission, string $resource, bool $status=null)
	{
        $status = $status ?? true;
		if (!$this->roleRegistry->exists($role)) {
            throw new \Exception(
                sprintf(
                    "The role: %s doesnt exist",
                    (string)$role
                )
            );
        }

        if (!$this->permissionRegistry->exists($permission)) {
            throw new \Exception(
                sprintf(
                    "The permission: %s doesnt exist",
                    (string)$permission
                )
            );
        }

        if (!$this->resourceRegistry->exists($resource)) {
            throw new \Exception(
                sprintf(
                    "The resource: %s doesnt exist",
                    (string)$resource
                )
            );
        }

        $this->globalRegistry->save($role, $resource, $permission, $status);
    }

    /**
     * Change the status option of an assigned permission to false
     *
     * @param string $role;
     * @param string $permission
     * @param string $resource
     * @return void
     */
    public function deny(string $role, string $permission, string $resource)
    {
        $this->allow($role, $permission, $resource, false);
    }

    /**
     * Retrieve the status of a permission assigned to a role
     *
     * @param string $role;
     * @param string $permission
     * @param string $resource
     * @return bool
     */
    public function getPermissionStatus(string $role, string $permission, string $resource) : bool
    {
        if (!$this->roleRegistry->exists($role)) {
            throw new \Exception(
                sprintf(
                    "The role: %s doesnt exist",
                    (string)$role
                )
            );
        }

        if (!$this->permissionRegistry->exists($permission)) {
            throw new \Exception(
                sprintf(
                    "The permission: %s doesnt exist",
                    (string)$permission
                )
            );
        }

        if (!$this->resourceRegistry->exists($resource)) {
            throw new \Exception(
                sprintf(
                    "The resource: %s doesnt exist",
                    (string)$resource
                )
            );
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
}
