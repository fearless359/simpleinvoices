{*
 *  Script: manage.tpl
 *      Customer manage template
 *
 *  License:
 *      GPL v3 or above
 *
 *  Last modified:
 *      2018-12-10 by Richard Rowley
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
{if $first_run_wizard == true}
    <div class="si_message">
        {$LANG.thankYou} {$LANG.beforeStarting}
    </div>
    <table class="si_table_toolbar">
        {if $number_of_billers == 0}
            <tr>
                <th>{$LANG.setupAsBiller}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=billers&amp;view=create" class="positive">
                        <img src="../../../images/user_add.png" alt=""/>
                        {$LANG.addNewBiller}
                    </a>
                </td>
            </tr>
        {/if}
        {if $number_of_customers == 0}
            <tr>
                <th>{$LANG.setupAddCustomer}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=customers&amp;view=create" class="positive">
                        <img src="../../../images/vcard_add.png" alt=""/>
                        {$LANG.customerAdd}
                    </a>
                </td>
            </tr>
        {/if}
        {if $number_of_products == 0}
            <tr>
                <th>{$LANG.setupAddProducts}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=products&amp;view=create" class="positive">
                        <img src="../../../images/cart_add.png" alt=""/>
                        {$LANG.addNewProduct}
                    </a>
                </td>
            </tr>
        {/if}
        <tr>
            <th>{$LANG.setupCustomization}</th>
            <td class="si_toolbar">
                <a href="index.php?module=system_defaults&amp;view=manage" class="">
                    <img src="../../../images/cog_edit.png" alt=""/>
                    {$LANG.siDefaults}
                </a>
            </td>
        </tr>
    </table>
{else}
    <div class="si_toolbar si_toolbar_top"
         {if $smarty.session.role_name == 'customer'}style="display:none"{/if}>
        <a href="index.php?module=customers&amp;view=create" class="">
            <img src="../../../images/add.png" alt=""/>
            {$LANG.customerAdd}
        </a>
    </div>
    <table id="si-data-table" class="display compact" >
        <thead>
            <tr>
                <th>{$LANG.actions}</th>
                <th>{$LANG.name}</th>
                <th>{$LANG.customerDepartment}</th>
                <th>{$LANG.lastInvoice}</th>
                <th>{$LANG.total}</th>
                <th>{$LANG.paid}</th>
                <th>{$LANG.owingUc}</th>
                <th>{$LANG.enabled}</th>
            </tr>
        </thead>
    </table>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "orderClasses": false,
                "deferRender": true,
                "columns": [
                    { "data": "action" },
                    { "data": "name" },
                    { "data": "department" },
                    { "data": "quick_view" },
                    { "data": "total",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currencyCode']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "paid",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currencyCode']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "owing",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currencyCode']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "enabled" },
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false },
                    {"targets": 1 },
                    {"targets": 2 },
                    {"targets": 3, "className": 'dt-body-right' },
                    {"targets": 4, "className": 'dt-body-right' },
                    {"targets": 5, "className": 'dt-body-right' },
                    {"targets": 6, "className": 'dt-body-right' },
                    {"targets": 7, "className": 'dt-body-center'}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
