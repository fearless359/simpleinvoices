{*
 *  Script: manage.tpl
 *    Manage invoices template
 *
 *  License:
 *    GPL v3 or above
 *
 *  Website:
 *    https://simpleinvoices.group
 *}

<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=cron&amp;view=create" class="">
        <img src="images/add.png" alt=""/>
        {$LANG.newRecurrence}
    </a>
</div>

{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noCrons} </div>
{else}
    <table id="si-data-table" class="display responsive compact">
        <thead>
        <tr>
            <th class="si_center">{$LANG.actions}</th>
            <th class="si_center">{$LANG.invoiceId}</th>
            <th class="si_center">{$LANG.startDateShort}</th>
            <th class="si_center">{$LANG.endDateShort}</th>
            <th class="si_left">{$LANG.recurEach}</th>
            <th class="si_center">{$LANG.emailBiller}</th>
            <th class="si_center">{$LANG.emailCustomer}</th>
            <th class="si_left">{$LANG.customerUc}</th>
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
                    { "data": "invoiceId" },
                    { "data": "startDate" },
                    { "data": "endDate"},
                    { "data": "recurrenceInfo" },
                    { "data": "emailBillerNice"},
                    { "data": "emailCustomerNice"},
                    { "data": "customerName"},
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [2, "desc"]
                ],
                "columnDefs": [
                    { "targets": 0, "width": "9%", "className": 'dt-body-center', "orderable": false },
                    { "targets": 1, "width": "12%", "className": 'dt-body-center' },
                    { "targets": [2,3], "width": "11%", "className": 'dt-body-center' },
                    { "targets": 4, "width": "12%" },
                    { "targets": 5, "width": "10%", "className": 'dt-body-center' },
                    { "targets": 6, "width": "10%", "className": 'dt-body-center' },
                    { "targets": 7, "width": "40%" }
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
