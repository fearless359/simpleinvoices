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
        {$LANG.thank_you} {$LANG.before_starting}
    </div>
    <table class="si_table_toolbar">
        {if $number_of_billers == 0}
            <tr>
                <th>{$LANG.setup_as_biller}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=billers&amp;view=add" class="positive">
                        <img src="../../../images/user_add.png" alt=""/>
                        {$LANG.add_new_biller}
                    </a>
                </td>
            </tr>
        {/if}
        {if $number_of_customers == 0}
            <tr>
                <th>{$LANG.setup_add_customer}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=customers&amp;view=add" class="positive">
                        <img src="../../../images/vcard_add.png" alt=""/>
                        {$LANG.customer_add}
                    </a>
                </td>
            </tr>
        {/if}
        {if $number_of_products == 0}
            <tr>
                <th>{$LANG.setup_add_products}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=products&amp;view=add" class="positive">
                        <img src="../../../images/cart_add.png" alt=""/>
                        {$LANG.add_new_product}
                    </a>
                </td>
            </tr>
        {/if}
        <tr>
            <th>{$LANG.setup_customisation}</th>
            <td class="si_toolbar">
                <a href="index.php?module=system_defaults&amp;view=manage" class="">
                    <img src="../../../images/cog_edit.png" alt=""/>
                    {$LANG.si_defaults}
                </a>
            </td>
        </tr>
    </table>
{else}
    <div class="si_toolbar si_toolbar_top">
        <a href="index.php?module=customers&amp;view=add" class="">
            <img src="../../../images/add.png" alt=""/>
            {$LANG.customer_add}
        </a>
    </div>
    {if $number_of_customers == 0}
        <div class="si_message">{$LANG.no_customers}</div>
    {else}
        <table id="si-data-table" class="display compact" >
            <thead>
                <tr>
                    <th>{$LANG.actions}</th>
                    <th>{$LANG.name}</th>
                    <th>{$LANG.customer_department}</th>
                    <th>{$LANG.last_invoice}</th>
                    <th>{$LANG.total}</th>
                    <th>{$LANG.paid}</th>
                    <th>{$LANG.owing}</th>
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
                                    'currency': row['currency_code']
                                });
                                return formatter.format(data);
                            }
                        },
                        { "data": "paid",
                            "render": function(data, type, row) {
                                let formatter = new Intl.NumberFormat(row['locale'], {
                                    'style': 'currency',
                                    'currency': row['currency_code']
                                });
                                return formatter.format(data);
                            }
                        },
                        { "data": "owing",
                            "render": function(data, type, row) {
                                let formatter = new Intl.NumberFormat(row['locale'], {
                                    'style': 'currency',
                                    'currency': row['currency_code']
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
{/if}
