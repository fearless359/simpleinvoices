{*
 *  Script: manage.tpl
 * 	    Products manage template
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *	    https://simpleinvoices.group
 *}
<div class="si_toolbar si_toolbar_form">
    <a href="{$add_button_link}" class="positive">
        <img src="images/add.png" alt=""/>
        {$add_button_msg}
    </a>
</div>
<br />
{if $numberOfRows == 0 }
    {$display_block}
{else}
    <table id="si-data-table" class="display responsive compact">
        <thead>
        <tr>
            <th class="si_center">{$LANG.actions}</th>
            <th class="si_center">{$LANG.dateUc}</th>
            <th class="si_right">{$LANG.amountUc}</th>
            <th class="si_right">{$LANG.tax}</th>
            <th class="si_right">{$LANG.totalUc}</th>
            <th class="si_left">{$LANG.expenseAccounts}</th>
            <th class="si_left">{$LANG.billerUc}</th>
            <th class="si_left">{$LANG.customerUc}</th>
            <th class="si_center">{$LANG.invoiceUc}</th>
            <th class="si_left">{$LANG.status}</th>
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
