{*
 *  Script: manage.tpl
 *      Manage payment warehouse template
 *
 *  Authors:
 *      Richard Rowley
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
<div class="align__text-center margin__bottom-2">
    <a href="index.php?module=payment_warehouse&amp;view=create">
        <button><img src="images/add.png" alt="{$LANG.addNewPaymentWarehouse}"/>{$LANG.addNewPaymentWarehouse}</button>
    </a>
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noPaymentWarehouse}</div>
{else}
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.customerUc}</th>
            <th>{$LANG.lastPaymentId}</th>
            <th>{$LANG.balanceUc}</th>
            <th>{$LANG.paymentType}</th>
            <th>{$LANG.checkNumberUc}</th>
        </tr>
        </thead>
    </table>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "orderClasses": false,
                "responsive": true,
                "columns": [
                    { "data": "action" },
                    { "data": "cname" },
                    { "data": "last_payment_id" },
                    {
                        "data": "balance",
                        "render": function (data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currency_code']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "description" },
                    { "data": "check_number" }
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [1, "asc"]
                ],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-center', "orderable": false},
                    {"targets": [2, 3, 5], "className": 'dt-right'}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
