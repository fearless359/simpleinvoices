<?php

namespace Inc\Claz;

use Samshal\Acl\Acl;
use Exception;

/**
 * Class SamshalAcl
 * @package Inc\Claz
 */
class SiAcl
{
    /**
     * Initialize the access control information for SI users.
     * Note that the acl structure will be serialized and stored in $_SESSION['acl']
     * It should be retrieved from $_SESSION['acl'] whenever it is to be used.
     * @throws Exception
     */
    public static function init()
    {
        $acl = new Acl();

        $roles = [
            'administrator',
            'all',
            'biller',
            'customer',
            'domain_administrator',
            'user'
        ];

        $adminAccess = [
            'administrator',
            'domain_administrator'
        ];

        $adminBillerAccess = [
            'administrator',
            'domain_administrator',
            'biller'
        ];

        $adminBillerCustomerAccess = [
            'administrator',
            'domain_administrator',
            'biller',
            'customer'
        ];

        $adminCustomerAccess = [
            'administrator',
            'domain_administrator',
            'customer'
        ];

        $reports = [
            'index' =>$adminBillerCustomerAccess,
            'reportBillerByCustomer' => $adminBillerAccess,
            'reportBillerTotal' => $adminBillerAccess,
            'reportDatabaseLog' => $adminAccess,
            'reportDebtorsAgingTotal' => $adminAccess,
            'reportDebtorsByAging' => $adminAccess,
            'reportDebtorsByAmount' => $adminAccess,
            'reportDebtorsOwingByCustomer' => $adminAccess,
            'reportExpenseAccountByPeriod' => $adminAccess,
            'reportInvoiceProfit' => $adminBillerAccess,
            'reportNetIncome' => $adminBillerAccess,
            'reportPastDue' => $adminBillerAccess,
            'reportProductsSoldByCustomer' => $adminCustomerAccess,
            'reportProductsSoldTotal' => $adminAccess,
            'reportSalesByPeriods' => $adminAccess,
            'reportSalesByRepresentative' => $adminAccess,
            'reportSalesCustomersTotal' => $adminCustomerAccess,
            'reportSalesTotal' => $adminAccess,
            'reportSummary' => $adminAccess,
            'reportTaxTotal' => $adminAccess,
            'reportTaxVsSalesByPeriod' => $adminAccess,
        ];

        // The structure is as follows:
        //  resource
        //      permission
        //          roles
        $resourcePermissions = [
            'api' => [
                'ach' => $adminAccess,
                'cron' => $adminAccess,
                'invoice' => $adminAccess,
                'paypal' => $adminAccess,
                'recur' => $adminAccess,
                'recorder' => $adminAccess
            ],
            'auth' => [
                'login' => [
                    'all'
                ],
                'logout' => $roles
            ],
            'billers' => [
                'create' => $adminAccess,
                'edit' => $adminBillerAccess,
                'manage' => $adminBillerAccess,
                'save' => $adminBillerAccess,
                'view' => $adminBillerAccess,
            ],
            'cron' => [
                'create' => $adminAccess,
                'delete' => $adminAccess,
                'edit' => $adminAccess,
                'manage' => $adminAccess,
                'run' => $adminAccess,
                'save' => $adminAccess,
                'view' => $adminAccess
            ],
            'custom_fields' => [
                'edit' => $adminAccess,
                'manage' => $adminAccess,
                'save' => $adminAccess,
                'view' => $adminAccess
            ],
            'custom_flags' => [
                'edit' => $adminAccess,
                'manage' => $adminAccess,
                'save' => $adminAccess,
                'view' => $adminAccess
            ],
            'customers' => [
                'create' => $adminAccess,
                'edit' => $adminCustomerAccess,
                'manage' => $adminCustomerAccess,
                'save' => $adminCustomerAccess,
                'usedefault' => $adminCustomerAccess,
                'view' => $adminCustomerAccess
            ],
            'documentation' => [
                'view' => $adminAccess
            ],
            'expense' => [
                'create' => $adminAccess,
                'edit' => $adminAccess,
                'manage' => $adminAccess,
                'save' => $adminAccess,
                'view' => $adminAccess
            ],
            'expense_account' => [
                'create' => $adminAccess,
                'edit' => $adminAccess,
                'manage' => $adminAccess,
                'save' => $adminAccess,
                'view' => $adminAccess
            ],
            'export' => [
                'invoice' => $adminBillerCustomerAccess,
            ],
            'extensions' => [
                'manage' => $adminAccess,
                'register' => $adminAccess,
                'save' => $adminAccess
            ],
            'index' => [
                'index' => $adminAccess
            ],
            'install' => [
                'essential' => [
                    'all'
                ],
                'index' => [
                    'all'
                ],
                'structure' => [
                    'all'
                ]
            ],
            'inventory' => [
                'create' => $adminAccess,
                'edit' => $adminAccess,
                'manage' => $adminAccess,
                'save' => $adminAccess,
                'view' => $adminAccess
            ],
            'invoices' => [
                'create' => $adminBillerAccess,
                'delete' => $adminAccess,
                'edit' => $adminBillerAccess,
                'email' => $adminBillerCustomerAccess,
                'itemised' => $adminBillerAccess,
                'manage' => $adminBillerCustomerAccess,
                'quick_view' => $adminBillerCustomerAccess,
                'product_ajax' => $adminBillerAccess,
                'save' => $adminBillerAccess,
                'total' => $adminBillerAccess,
                'usedefault' => $adminAccess
            ],
            'options' => [
                'backup_database' => $adminAccess,
                'database_sqlpatches' => $adminAccess,
                'index' => $adminAccess,
                'manage_cronlog' => $adminAccess,
                'manage_sqlpatches' => $adminAccess
            ],
            'payment_types' => [
                'create' => $adminAccess,
                'edit' => $adminAccess,
                'manage' => $adminAccess,
                'save' => $adminAccess,
                'view' => $adminAccess
            ],
            'payments' => [
                'print' => $adminBillerAccess,
                'manage' => $adminBillerAccess,
                'process' => $adminAccess,
                'save' => $adminBillerAccess,
                'view' => $adminBillerAccess
            ],
            'preferences' => [
                'create' => $adminAccess,
                'edit' => $adminAccess,
                'manage' => $adminAccess,
                'save' => $adminAccess,
                'view' => $adminAccess
            ],
            'product_attribute' => [
                'create' => $adminAccess,
                'edit' => $adminAccess,
                'manage' => $adminAccess,
                'save' => $adminAccess,
                'view' => $adminAccess
            ],
            'product_attribute_values' => [
                'create' => $adminAccess,
                'edit' => $adminAccess,
                'manage' => $adminAccess,
                'save' => $adminAccess,
                'view' => $adminAccess
            ],
            'products' => [
                'create' => $adminAccess,
                'edit' => $adminAccess,
                'manage' => $adminAccess,
                'save' => $adminAccess,
                'view' => $adminAccess
            ],
            'reports' => $reports,
            'si_info' => [
                'index' => $adminAccess
            ],
            'statement' => [
                'index' => $adminAccess,
                'email' => $adminAccess,
                'export' => $adminAccess
            ],
            'system_defaults' => [
                'edit' => $adminAccess,
                'manage' => $adminAccess,
                'save' => $adminAccess
            ],
            'tax_rates' => [
                'create' => $adminAccess,
                'edit' => $adminAccess,
                'manage' => $adminAccess,
                'save' => $adminAccess,
                'view' => $adminAccess
            ],
            'user' => [
                'create' => $adminAccess,
                'edit' => $adminBillerCustomerAccess,
                'manage' => $adminBillerCustomerAccess,
                'save' => $adminBillerCustomerAccess,
                'view' => $adminBillerCustomerAccess
            ]
        ];

        // Add all roles so they are available for use
        foreach ($roles as $role) {
            $acl->addRole($role);
        }

        foreach ($resourcePermissions as $resource => $permissions) {
            $acl->addResource($resource);
            foreach ($permissions as $permission => $roles) {
                $acl->addPermission($permission);
                foreach ($roles as $role) {
                    $acl->{$role}->can->{$permission}($resource);
                }
            }
        }

        session_name('SiAuth');
        session_start();
        $_SESSION['acl'] = serialize($acl);
    }

    /**
     * Get the role for the current session.
     * @return mixed|string
     */
    public static function getSessionRole() {
        session_name('SiAuth');
        session_start();
        return empty($_SESSION['role_name']) ? 'all' : $_SESSION['role_name'];
    }

    /**
     * @param string $resource module action to be performed upon.
     * @param string $permission Action to perform
     * @return bool true if action allowed; false if not.
     * @throws Exception
     */
    public static function isAllowed(string $resource, string $permission): bool
    {
        $role = self::getSessionRole();
        if ($role == 'administrator') {
            return true;
        }
        /**
         * @var Acl $acl
         */
        $acl = unserialize($_SESSION['acl']);

        return $acl->can->{$role}->{$permission}($resource);
    }

    /**
     * Add resource(s) to the current Acl object.
     * @param string|array $resource
     * @param Acl $acl
     */
    public static function appendResources($resource, Acl $acl): void
    {
        if (is_array($resource)) {
            foreach ($resource as $lclResource) {
                $acl->addResource($lclResource);
            }
        } else {
            $acl->addResource($resource);
        }
    }

    /**
     * Add resource(s) to the current Acl object.
     * @param string|array $resource
     * @param Acl $acl
     */
    public static function appendPermission($resource, Acl $acl): void
    {
        if (is_array($resource)) {
            foreach ($resource as $lclResource) {
                $acl->addResource($lclResource);
            }
        } else {
            $acl->addResource($resource);
        }
    }

}
