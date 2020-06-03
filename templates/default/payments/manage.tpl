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
        <img src="images/famfam/add.png" alt=""/>
        {$LANG.process_payment}
    </a>
{if isset($smarty.get.id)}
    <a href="index.php?module=payments&amp;view=process&amp;id={$smarty.get.id|urlencode}&amp;op=pay_selected_invoice" class="">
        <img src="images/famfam/money.png" alt=""/>
        {$LANG.payments_filtered_invoice}
    </a>
{/if}
</div>
{if $number_of_rows == 0}
    <div class="si_message">{$no_entry_msg}</div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.payment}#</th>
            <th>{$LANG.invoice}#</th>
            <th>{$LANG.customer}</th>
            <th>{$LANG.biller}</th>
            <th>{$LANG.amount}</th>
            <th>{$LANG.type}</th>
            <th>{$LANG.date_upper}</th>
        </tr>
        </thead>
    </table>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "columns": [
                    { "data": "action" },
                    { "data": "payment_id" },
                    { "data": "invoice_id" },
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
                            return data.replace(/^(.*) .*$/, '$1');
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
