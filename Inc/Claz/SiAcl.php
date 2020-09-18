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

        $reports = [
            'index' => [
                'administrator',
                'domain_administrator'
            ],
            'report_biller_by_customer' => [
                'administrator',
                'domain_administrator'
            ],
            'report_biller_total' => [
                'administrator',
                'domain_administrator'
            ],
            'report_database_log' => [
                'administrator',
                'domain_administrator'
            ],
            'report_debtors_aging_total' => [
                'administrator',
                'domain_administrator'
            ],
            'report_debtors_by_aging' => [
                'administrator',
                'domain_administrator'
            ],
            'report_debtors_by_amount' => [
                'administrator',
                'domain_administrator'
            ],
            'report_debtors_owing_by_customer' => [
                'administrator',
                'domain_administrator'
            ],
            'report_expense_account_by_period' => [
                'administrator',
                'domain_administrator'
            ],
            'report_invoice_profit' => [
                'administrator',
                'domain_administrator'
            ],
            'report_net_income' => [
                'administrator',
                'domain_administrator'
            ],
            'report_past_due' => [
                'administrator',
                'domain_administrator'
            ],
            'report_products_sold_by_customer' => [
                'administrator',
                'domain_administrator'
            ],
            'report_products_sold_total' => [
                'administrator',
                'domain_administrator'
            ],
            'report_sales_by_periods' => [
                'administrator',
                'domain_administrator'
            ],
            'report_sales_by_representative' => [
                'administrator',
                'domain_administrator'
            ],
            'report_sales_customers_total' => [
                'administrator',
                'domain_administrator'
            ],
            'report_sales_total' => [
                'administrator',
                'domain_administrator'
            ],
            'report_summary' => [
                'administrator',
                'domain_administrator'
            ],
            'report_tax_total' => [
                'administrator',
                'domain_administrator'
            ],
            'report_tax_vs_sales_by_period' => [
                'administrator',
                'domain_administrator'
            ],
        ];

        // The structure is as follows:
        //  resource
        //      permission
        //          roles
        $resourcePermissions = [
            'api' => [
                'ach' => [
                    'administrator',
                    'domain_administrator'
                ],
                'cron' => [
                    'administrator',
                    'domain_administrator'
                ],
                'invoice' => [
                    'administrator',
                    'domain_administrator'
                ],
                'paypal' => [
                    'administrator',
                    'domain_administrator'
                ],
                'recur' => [
                    'administrator',
                    'domain_administrator'
                ],
                'recorder' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'auth' => [
                'login' => [
                    'all'
                ],
                'logout' => $roles
            ],
            'billers' => [
                'create' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator',
                    'biller'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator',
                    'biller'
                ],
                'save' => [
                    'administrator',
                    'domain_administrator',
                    'biller'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator',
                    'biller'
                ],
            ],
            'cron' => [
                'create' => [
                    'administrator',
                    'domain_administrator'
                ],
                'delete' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'run' => [
                    'administrator',
                    'domain_administrator'
                ],
                'save' => [
                    'administrator',
                    'domain_administrator'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'custom_fields' => [
                'edit' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'custom_flags' => [
                'edit' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'customers' => [
                'create' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator',
                    'customer'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator',
                    'customer'
                ],
                'save' => [
                    'administrator',
                    'domain_administrator',
                    'customer'
                ],
                'usedefault' => [
                    'administrator',
                    'domain_administrator',
                    'customer'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator',
                    'customer'
                ]
            ],
            'documentation' => [
                'view' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'expense' => [
                'create' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'expense_account' => [
                'create' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'export' => [
                'invoice' => [
                    'administrator',
                    'domain_administrator',
                    'biller',
                    'customer'
                ]
            ],
            'extensions' => [
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'register' => [
                    'administrator',
                    'domain_administrator'
                ],
                'save' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'index' => [
                'index' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'install' => [
                'essential' => [
                    'administrator',
                    'domain_administrator'
                ],
                'structure' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'inventory' => [
                'create' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'invoices' => [
                'create' => [
                    'administrator',
                    'domain_administrator',
                    'biller'
                ],
                'delete' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator',
                    'biller'
                ],
                'email' => [
                    'administrator',
                    'domain_administrator',
                    'biller',
                    'customer'
                ],
                'itemised' => [
                    'administrator',
                    'domain_administrator',
                    'biller'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator',
                    'biller',
                    'customer'
                ],
                'quick_view' => [
                    'administrator',
                    'domain_administrator',
                    'biller',
                    'customer'
                ],
                'product_ajax' => [
                    'administrator',
                    'domain_administrator',
                    'biller',
                ],
                'save' => [
                    'administrator',
                    'domain_administrator',
                    'biller',
                ],
                'total' => [
                    'administrator',
                    'domain_administrator',
                    'biller',
                ],
                'usedefault' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'options' => [
                'backup_database' => [
                    'administrator',
                    'domain_administrator'
                ],
                'index' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage_cronlog' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage_sqlpatches' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'payment_types' => [
                'create' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'payments' => [
                'print' => [
                    'administrator',
                    'domain_administrator',
                    'biller'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator',
                    'biller'
                ],
                'process' => [
                    'administrator',
                    'domain_administrator'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator',
                    'biller'
                ]
            ],
            'preferences' => [
                'create' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'product_attribute' => [
                'create' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'product_value' => [
                'create' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'products' => [
                'create' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'save' => [
                    'administrator',
                    'domain_administrator'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'reports' => $reports,
            'si_info' => [
                'index' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'statement' => [
                'index' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'system_defaults' => [
                'edit' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'save' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'tax_rates' => [
                'create' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator'
                ]
            ],
            'user' => [
                'create' => [
                    'administrator',
                    'domain_administrator'
                ],
                'edit' => [
                    'administrator',
                    'domain_administrator',
                    'biller',
                    'customer'
                ],
                'manage' => [
                    'administrator',
                    'domain_administrator',
                    'biller',
                    'customer'
                ],
                'save' => [
                    'administrator',
                    'domain_administrator',
                    'biller',
                    'customer'
                ],
                'view' => [
                    'administrator',
                    'domain_administrator',
                    'biller',
                    'customer'
                ]
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
        /**
         * @var Acl $acl
         */
        $acl = unserialize($_SESSION['acl']);
        /** @noinspection PhpUndefinedFieldInspection */
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
