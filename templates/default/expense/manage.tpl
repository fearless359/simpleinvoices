{*
 *  Script: manage.tpl
 * 	    Products manage template
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
<div class="align__text-center">
    <a href="{$add_button_link}" class="button positive">
        <img src="images/add.png" alt=""/>{$add_button_msg}
    </a>
</div>
<br/>
{if $numberOfRows == 0 }
    {$display_block}
{else}
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th class="align__text-center">{$LANG.actions}</th>
            <th class="align__text-center">{$LANG.dateUc}</th>
            <th class="align__text-right">{$LANG.amountUc}</th>
            <th class="align__text-right">{$LANG.tax}</th>
            <th class="align__text-right">{$LANG.totalUc}</th>
            <th>{$LANG.expenseAccounts}</th>
            <th>{$LANG.billerUc}</th>
            <th>{$LANG.customerUc}</th>
            <th class="align__text-center">{$LANG.invoiceUc}</th>
            <th>{$LANG.status}</th>
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
                    { "data": "amount",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currencyCode']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "tax",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currencyCode']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "total",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currencyCode']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "eaName" },
                    { "data": "bName"},
                    { "data": "cName" },
                    { "data": "ivId" },
                    { "data": "statusWording" }
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [1, "desc"]
                ],
                "columnDefs": [
                    {"targets": 0, "width": "8%", "className": 'dt-body-center', "orderable": false},
                    {"targets": 1, "width": "10%", "className": 'dt-body-center'},
                    {"targets": [2,3,4], "width": "9%", "className": 'dt-body-right'},
                    {"targets": [8,9], "className": 'dt-body-center'},
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
