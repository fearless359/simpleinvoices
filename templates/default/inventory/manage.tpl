{*
 *  Script: manage.tpl
 *      Manage invoices template
 *
 *  Last modified:
 *      20210618 by Richard Rowley to add cell-border class to table tag.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
*}
<!--suppress JSUnusedLocalSymbols -->
<div class="align__text-center margin__bottom-2">
    <a href="index.php?module=inventory&amp;view=create" class="">
        <button><img src="images/add.png" alt=""/>{$LANG.newInventoryMovement}</button>
    </a>
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noInventoryMovements}</div>
{else}
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th class="align__text-center">{$LANG.actions}</th>
            <th class="align__text-center">{$LANG.dateUc}</th>
            <th>{$LANG.productUc}</th>
            <th class="align__text-right">{$LANG.quantity}</th>
            <th class="align__text-right">{$LANG.costUc}</th>
            <th class="align__text-right">{$LANG.totalCost}</th>
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

