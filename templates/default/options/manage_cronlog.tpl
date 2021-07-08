{*
 *  Script: manage_cronlogs.tpl
 *      Manage Cron Logs template
 *
 *  Authors:
 *      Ap.Muthu
 *
 *  Last edited:
 *      20210702 by Rich Rowley to use DataTables
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<h3 class="align__text-center">{$LANG.cronUc} {$LANG.logUc} - {$LANG.recurrent} {$LANG.invoicesUc} {$LANG.inserted}</h3>
<table id="data-table" class="display responsive compact cell-border" style="width: 40%;">
    <thead>
    <tr>
        <th class="align__text-right">{$LANG.idUc}</th>
        <th class="align__text-center">{$LANG.dateUc}</th>
        <th class="align__text-right">{$LANG.cronUc} {$LANG.idUc}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $cronLogs as $cronlog}
        <tr>
            <td>{$cronlog.id|htmlSafe}</td>
            <td>{$cronlog.run_date|htmlSafe}</td>
            <td><a href="index.php?module=cron&amp;view=view&amp;id={$cronlog.cron_id|htmlSafe}">{$cronlog.cron_id|htmlSafe}</a></td>
        </tr>
    {/foreach}
    </tbody>
</table>
<script>
    {literal}
    $(document).ready(function () {
        $('#data-table').DataTable({
            "order": [
                [1, "desc"]
            ],
            "columnDefs": [
                {"targets": 0, "className": 'dt-body-right', "width": "10%" },
                {"targets": 1, "className": 'dt-body-center', "width": "20%" },
                {"targets": 2, "className": 'dt-body-right', "width": "10%" }
            ],
            "colReorder": true
        });
    });
    {/literal}
</script>
