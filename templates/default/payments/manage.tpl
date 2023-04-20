{*
 *	Script: manage.tpl
 * 		Payments manage template
 *
 *  Authors:
 *	    Richard Rowley
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
<div class="align__text-center margin__bottom-2">
    <a href="index.php?module=payments&amp;view=process&amp;op=pay_invoice" class="">
        <button><img src="images/add.png" alt=""/>{$LANG.processPayment}</button>
    </a>
    {if isset($smarty.get.id)}
        <a href="index.php?module=payments&amp;view=process&amp;id={$smarty.get.id|urlEncode}&amp;op=pay_selected_invoice" class="">
            <button><img src="images/money.png" alt=""/>{$LANG.paymentsFilteredInvoice}</button>
        </a>
    {/if}
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$noEntryMsg}</div>
{else}
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th class="align__text-center">{$LANG.actions}</th>
            <th class="align__text-center">{$LANG.paymentUc}#</th>
            <th class="align__text-center">{$LANG.invoiceUc}#</th>
            <th>{$LANG.customerUc}</th>
            <th>{$LANG.billerUc}</th>
            <th class="align__text-right">{$LANG.amountUc}</th>
            <th>{$LANG.type}</th>
            <th class="align__text-center">{$LANG.dateUc}</th>
        </tr>
        </thead>
    </table>
    <!--suppress JSUnusedLocalSymbols -->
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "orderClasses": false,
                "deferRender": true,
                "responsive": true,
                "order": [[1, 'desc']],
                "columns": [
                    {"data": "action"},
                    {"data": "paymentId"},
                    {"data": "invoiceId"},
                    {"data": "customer"},
                    {"data": "biller"},
                    {
                        "data": "amount",
                        "render": function (data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currency_code']
                            });
                            let fmtdAmt = formatter.format(data);
                            if (row['warehousePaymentType'] === 'T' || row['warehousePaymentType'] === 'F') {
                                fmtdAmt += "*";
                            }
                            return fmtdAmt;
                        }
                    },
                    {"data": "type"},
                    {
                        "data": "date",
                        "render": function (data, type, row) {
                            let dtParts = data.split(' ');
                            return dtParts[0];
                        }
                    },
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false},
                    {"targets": 1, "className": 'dt-body-center'},
                    {"targets": 2, "className": 'dt-body-center'},
                    {"targets": 3},
                    {"targets": 4},
                    {"targets": 5, "className": 'dt-body-right'},
                    {"targets": 6, "width": "10%"},
                    {"targets": 7, "width": "10%"}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
