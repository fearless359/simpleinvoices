{*
 *	Script: manage.tpl
 * 		Payments manage template
 *
 *	Last edited:
 * 	 	2018-12-30 by Richard Rowley
 *
 * 	License:
 *	    GPL v3 or above
 *
 *	Website:
 *	    https://simpleinvoices.group
 *}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=payments&amp;view=process&amp;op=pay_invoice" class="">
        <img src="images/add.png" alt=""/>
        {$LANG.processPayment}
    </a>
{if isset($smarty.get.id)}
    <a href="index.php?module=payments&amp;view=process&amp;id={$smarty.get.id|urlencode}&amp;op=pay_selected_invoice" class="">
        <img src="images/money.png" alt=""/>
        {$LANG.paymentsFilteredInvoice}
    </a>
{/if}
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$noEntryMsg}</div>
{else}
    <table id="si-data-table" class="display responsive compact">
        <thead>
        <tr>
            <th class="si_center">{$LANG.actions}</th>
            <th class="si_center">{$LANG.paymentUc}#</th>
            <th class="si_center">{$LANG.invoiceUc}#</th>
            <th class="si_left">{$LANG.customerUc}</th>
            <th class="si_left">{$LANG.billerUc}</th>
            <th class="si_right">{$LANG.amountUc}</th>
            <th class="si_left">{$LANG.type}</th>
            <th class="si_center">{$LANG.dateUc}</th>
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
                "columns": [
                    { "data": "action" },
                    { "data": "paymentId" },
                    { "data": "invoiceId" },
                    { "data": "customer" },
                    { "data": "biller" },
                    { "data": "amount",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currency_code']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "type" },
                    { "data": "date",
                        "render": function(data, type, row) {
                            let dtParts = data.split(' ');
                            return dtParts[0];
                        } },
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false },
                    {"targets": 1, "className": 'dt-body-center' },
                    {"targets": 2, "className": 'dt-body-center' },
                    {"targets": 3 },
                    {"targets": 4 },
                    {"targets": 5, "className": 'dt-body-right' },
                    {"targets": 6, "width": "10%" },
                    {"targets": 7, "width": "10%" }
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
