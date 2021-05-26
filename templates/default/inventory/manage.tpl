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
<!--suppress JSUnusedLocalSymbols -->
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=inventory&amp;view=create" class="">
        <img src="images/add.png" alt=""/>
        {$LANG.newInventoryMovement}
    </a>
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noInventoryMovements}</div>
{else}
    <table id="si-data-table" class="display responsive compact">
        <thead>
        <tr>
            <th class="si_center">{$LANG.actions}</th>
            <th class="si_center">{$LANG.dateUc}</th>
            <th class="si_left">{$LANG.productUc}</th>
            <th class="si_right">{$LANG.quantity}</th>
            <th class="si_right">{$LANG.costUc}</th>
            <th class="si_right">{$LANG.totalCost}</th>
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
                "responsive": true,
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
                                'currency': row['currencyCode']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "totalCost",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currencyCode']
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

