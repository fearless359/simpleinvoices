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
    <a href="index.php?module=cron&amp;view=add" class=""><img src="images/common/add.png" alt=""/>{$LANG.new_recurrence}</a>
</div>

{if $number_of_rows == 0}
    <div class="si_message">{$LANG.no_crons} </div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.invoice_id}</th>
            <th>{$LANG.start_date_short}</th>
            <th>{$LANG.end_date_short}</th>
            <th>{$LANG.recur_each}</th>
            <th>{$LANG.email_biller}</th>
            <th>{$LANG.email_customer}</th>
            <th>{$LANG.customer}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $crons as $cron}
            <tr>
                <td class="si_center">
                    <a class='index_table' title='{$LANG['view']} {$cron['index_name']}'
                       href='index.php?module=cron&amp;view=details&amp;id={$cron['id']}&amp;action=view'>
                        <img src='images/common/view.png' height='16' border='-5px'/>
                    </a>
                    <a class='index_table' title='{$LANG['edit']} {$cron['index_name']}'
                       href='index.php?module=cron&amp;view=details&amp;id={$cron['id']}&amp;action=edit'>
                        <img src='images/common/edit.png' height='16' border='-5px'/>
                    </a>
                    <a class='index_table' title='{$LANG['delete']} {$cron['index_name']}'
                       href='index.php?module=cron&amp;view=delete&amp;id={$cron['id']}&amp;stage=1&amp;err_message='>
                        <img src='images/common/delete.png' height='16' border='-5px'/>
                    </a>
                </td>
                <td>{$cron['index_name']}</td>
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
