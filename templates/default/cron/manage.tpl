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
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.invoiceId}</th>
            <th>{$LANG.startDateShort}</th>
            <th>{$LANG.endDateShort}</th>
            <th>{$LANG.recurEach}</th>
            <th>{$LANG.emailBiller}</th>
            <th>{$LANG.emailCustomer}</th>
            <th>{$LANG.customerUc}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $crons as $cron}
            <tr>
                <td class="si_center">
                    <a class='index_table' title='{$LANG.view} {$cron['index_name']}'
                       href='index.php?module=cron&amp;view=view&amp;id={$cron['id']}'>
                        <img src='images/view.png' alt="{$LANG.view} {$cron['index_name']}"/>
                    </a>
                    <a class='index_table' title='{$LANG.edit} {$cron['index_name']}'
                       href='index.php?module=cron&amp;view=edit&amp;id={$cron['id']}'>
                        <img src='images/edit.png' alt="{$LANG.edit} {$cron['index_name']}"/>
                    </a>
                    <a class='index_table' title='{$LANG.delete} {$cron['index_name']}'
                       href='index.php?module=cron&amp;view=delete&amp;id={$cron['id']}&amp;stage=1&amp;err_message='>
                        <img src='images/delete.png' alt="{$LANG.delete} {$cron['index_name']}"/>
                    </a>
                </td>
                <td class="si_right">
                    <a href="index.php?module=invoices&amp;view=quick_view&amp;id={$cron.invoice_id|htmlSafe}">
                        {$cron.index_id|htmlSafe}
                    </a>
                </td>
                <td class="si_center">{$cron['start_date']}</td>
                <td class="si_center">{$cron['end_date']}</td>
                <td>{$cron['recurrence']} {$cron['recurrence_type']}</td>
                <td class="si_center">{$cron['email_biller_nice']}</td>
                <td class="si_center">{$cron['email_customer_nice']}</td>
                <td>{$cron['name']}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [2, "desc"]
                ],
                "columnDefs": [
                    { "targets": 0, "width": "9%", "orderable": false },
                    { "targets": 1, "width": "12%" },
                    { "targets": [2,3], "width": "11%" },
                    { "targets": 4, "width": "12%" },
                    { "targets": 5, "width": "10%" },
                    { "targets": 6, "width": "10%" },
                    { "targets": 7, "width": "40%" }
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
