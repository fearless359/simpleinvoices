# Acl [![Build Status](https://travis-ci.org/Samshal/Acl.svg?branch=master)](https://travis-ci.org/Samshal/Acl) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Samshal/Acl/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Samshal/Acl/?branch=master)

Samshal\Acl adds a role based permission system for user authentication. In general, it provides a lightweight access control list for privileges and permission management.

### Why you might need it

Access Control Lists allow an application to control access to its areas, they provide a flexible interface for creating Permissions, Roles, Resources and assigning the created permissions on roles.

This component is an implementation of an ACL, it makes it easy for you to get up and running with user authorization.

### Class Features
- Creation of Resources, Roles and Permissions
- Ability to set Permissions on Resources and granting these Permissions to Roles.
- Support for Role Inheritance.
- Fully Serializable, can work interoperably with any source of data.
- Compatible with PHP v8.1+
- Easy to use

**Resources** are objects which acts in accordance to the permissions defined on them by the ACLs. **Roles** are objects that requests access to resources and can be allowed or denied by the ACL layers. **Permissions** are just rules defined on Resources.

### Metrics of master branch

![Package Metrics](https://raw.githubusercontent.com/Samshal/Acl/master/phpmetric_maintainability.png)

### License
This software is distributed under the [MIT](https://opensource.org/licenses/MIT) license. Please read LICENSE for information on the software availability and distribution.

### Installation
Samshal\Acl is available via [Composer/Packagist](https://packagist.org/packages/samshal/acl), so just add this line to your `composer.json` file:
```json
	{
		"require":{
			"samshal/acl":"^2.0"
		}
	}
```
or
```shell
	composer require samshal/acl
```

### Getting Started
#### Creating an ACL
Creating an ACL component is as easy as instantiating `Samshal\Acl`. The constructor __currently__ accepts no arguments. An example of instantiation is:
```php
<?php
	require "vendor/autoload.php";

	use Samshal\Acl;
	use Samshal\Acl\{
		Role\DefaultRole as Role,
		Resource\DefaultResource as Resource,
		Permission\DefaultPermission as Permission
	};

	$acl = new Acl();
```

#### Adding objects (**Roles**, **Permissions** and **Resources**) to the ACL.
The ACL provides an `add` method for adding new objects generically. In other words, to add a new role to the Acl, just pass in a `Role Object` to the ACL`s `add` method. You can also do the same for Resources and Permissions.

A Role Object is an instance of the `\Samshal\Acl\Role\DefaultRole` object or more generally, an object that implements the `\Samshal\Acl\Role\RoleInterface` and `\Samshal\Acl\ObjectInterface` contracts. It accepts the name of the Role to create as parameter and the description for the created role as optional second parameter.

Similarly Resource objects are instances of the `\Samshal\Acl\Resource\DefaultResource` object which also implements the `\Samshal\Acl\Resource\ResourceInterface` and `\Samshal\Acl\ObjectInterface` interfaces, Likewise for permissions, they must implement the `\Samshal\Acl\Permission\PermissionInterface` and the `\Samshal\Acl\ObjectInterface` contracts or be new instances of the `\Samshal\Acl\Permission\DefaultPermission` class.

Generally, Roles, Resources and Permissions are referred to as Objects. They must all implement the `\Samshal\Acl\ObjectInterface` contract.

```php
	...

	$adminRole = new Role("Admin");
	$accountantRole = new Role("Accountant", "This is optional: anybody who`s not an admin is an accountant");

	$acl->add($adminRole);
	$acl->add($accountantRole);

	$patientFinancialHistoryResource = new Resource("patientFinancialHistory");

	$acl->add($patientFinancialHistoryResource);

	$editPermission = new Permission("edit");
	$viewPermission = new Permission("view");

	$acl->add($editPermission, $viewPermission);

	...
```
Internally, the created objects are stored in Registries which are fully serializable. This makes it easy to transfer/get the objects from anywhere; a persistent storage, a database and anywhere else data can be stored/received. More on this later.

Samshal\Acl provides a more intuitive way to create objects. Instead of creating new objects everytime you need to add them to the registry, why not call a single method that can determine which kind of object you are trying to create and have it do it automatically? The ACL provides an `addRole`, `addResource` and `addPermission` methods for this purpose which all accepts string values as parameters.
Example:

```php
	...

	$acl->addRole('Admin');
	$acl->addRole('Accountant');

	$acl->addResource('patientFinancialHistory');

	$acl->addPermission('edit');
	$acl->addPermission('view');

	...
```

Cool right?
The add methods (addRole, addResource, addPermission and add) are variadic, they can accept an unlimited number of arguments at a time. So we could even make our lives less more boring by doing this while adding the Roles

```php
	...

	$acl->addRole('Admin', 'Accountant');

	...
```

or this for the Permissions.

```php
	...

	$acl->addPermission('edit', 'view', 'create', 'print', 'delete'); //you can add even more permissions!

	...
```

#### Setting Permissions.
The reason why this component exists is to set permissions on resources and grant/deny these permissions to roles. The snippet below gives an example of how to set these permissions.

```php
	...

	/**
	 * In the example below, admin is the name of a Role that's been added to the ACl using add() or addRole().
	 * Similarly view is a permission and patientFinancialHistory is a resource.
	 *
	 * Use the `can` keyword in between a role and a permission in a chain to set make the resource in question accessible or not to the role.
	 */
	$acl->admin->can->view('patientFinancialHistory');

	$acl->accountant->cannot->delete('patientFinancialHistory'); //denying the role Accountant delete right on the PatienFinancialHistory resource.

	...
```

#### Checking Permissions
To check the permission a role has on a certain resource, you can use a snippet similar to the following:

```php
	...

	$booleanResultIndicatingPermission = $acl->can->admin->view('patientFinancialHistory');
	//We are asking a very simple question: can an Admin role View the patientFinancialHistory resource?

	//even better, we could use it in a conditional

	if ($acl->can->accountant->delete('patientFinancialHistory'))
	{
		//delete the patients financial history!
	}
	else
	{
		//this user does not have any permission to delete this resource, let him know that
	}

	...
```

#### Keeping your ACL persistent and safe.
`\Samshal\Acl` stores objects including the permissions on objects in registries which are fully serializable. This means you can convert your entire acl into a string and store that in a database or session and make it exist infinitely until you are ready to destroy it or __never use it again__.

###### How-To

```php
	...

	$serializedAcl = serialize($acl);

	//store $serializedAcl in a session
	session_start();
	$_SESSION["acl"] = $serializedAcl.

	//or in a db
	$sqlQuery = "INSERT INTO my_tbl VALUES ('$serializedAcl')";
```

##### How to retrieve it

```php
	/**
	 * File Name: patientHistories.php
	 */

	session_start();
	$acl = unserialize($_SESSION["acl"]);

	//use it!
	if ($acl->can->accountant->delete('patientFinanicalHistory'))
	{
		//delete the patients financial history!
	}
	else
	{
		//this user does not have any permission to delete this resource, let him know that
	}

	...
```

#### Other Interesting Features
###### Role - Role Inheritance
`\Samshal\acl` allows roles to inherit permissions from other roles. The Acl component has an `inherits` method that accepts a Role object as parameter. You can also pass in a string, but it must be the id of an already existent role object.

###### Automatically grant permissions on all resources.
You can also call a Permission object without any parameter. This grants the permission in question on all resources defined within the ACL on the Role in session.

## Maintainer of this Library
This library is currently developed and maintained by [Samuel Adeshina](http://samshal.github.io)

## ROAD MAP
Road Map draft in progress.

## HOW TO CONTRIBUTE
Support follows PSR-2 PHP coding standards.

Please report any issue you find in the issues page. Pull requests are welcome.
