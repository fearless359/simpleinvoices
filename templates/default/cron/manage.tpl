{*
 *  Script: manage.tpl
 *      Manage invoices template
 *
 *  Last modified:
 *      20210618 by Richard Rowley to add cell-border class to table tag.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="align__text-center margin__bottom-2">
    <a href="index.php?module=cron&amp;view=create" class="">
        <button><img src="images/add.png" alt=""/>{$LANG.newRecurrence}</button>
    </a>
</div>

{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noCrons} </div>
{else}
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th class="align__text-center">{$LANG.actions}</th>
            <th class="align__text-center">{$LANG.invUc}#</th>
            <th class="align__text-center">{$LANG.startDateShort}</th>
            <th class="align__text-center">{$LANG.endDateShort}</th>
            <th>{$LANG.recurEach}</th>
            <th class="align__text-center">{$LANG.emailBiller}</th>
            <th class="align__text-center">{$LANG.emailCustomer}</th>
            <th>{$LANG.customerUc}</th>
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
                    { "data": "action", "width": "8%" },
                    { "data": "invoiceId", "width": "8%" },
                    { "data": "startDate", "width": "10%" },
                    { "data": "endDate", "width": "10%" },
                    { "data": "recurrenceInfo", "width": "10%" },
                    { "data": "emailBillerNice", "width": "8%" },
                    { "data": "emailCustomerNice", "width": "8%" },
                    { "data": "customerName"},
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [2, "desc"]
                ],
                "columnDefs": [
                    { "targets": 0, "className": 'dt-body-center', "orderable": false },
                    { "targets": 1, "className": 'dt-body-center' },
                    { "targets": [2,3], "className": 'dt-body-center' },
                    { "targets": 4 },
                    { "targets": [5, 6], "className": 'dt-body-center' },
                    { "targets": 7 }
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
