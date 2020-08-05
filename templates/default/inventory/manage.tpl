{*
 *  Script: manage.tpl
 *      Manage invoices template
 *
 *  Modified:
 *      2018-12-14 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
*}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=inventory&amp;view=add" class="">
        <img src="images/common/add.png" alt=""/>
        {$LANG.new_inventory_movement}
    </a>
</div>
{if $number_of_rows == 0}
    <div class="si_message">{$LANG.no_inventory_movements}</div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.date_upper}</th>
            <th>{$LANG.product}</th>
            <th>{$LANG.quantity}</th>
            <th>{$LANG.cost}</th>
            <th>{$LANG.total_cost}</th>
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
                    { "data": "date" },
                    { "data": "description" },
                    { "data": "quantity",
                        "render": function(data, type, row) {
                            let val = data.toString();
                            return parseFloat(val);
                        }
                    },
                    { "data": "cost",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currency_code']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "total_cost",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currency_code']
                            });
                            return formatter.format(data);
                        }
                    },
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [2, "asc"]
                ],
                "columnDefs": [
                    {"targets": 0, "orderable": false, "className": 'dt-body-center'},
                    {"targets": 1, "className": 'dt-body-center'},
                    {"targets": [3,4,5], "className": 'dt-body-right'}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}

