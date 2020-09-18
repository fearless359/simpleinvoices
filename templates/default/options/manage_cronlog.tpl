{*
 *  Script: manage_cronlogs.tpl
 *      Manage Cron Logs template
 *
 *  Authors:
 *      Ap.Muthu
 *
 *  Last edited:
 *      2017-01-18
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}

<h3>{$LANG.cron_uc} {$LANG.logUc} - {$LANG.recurrent} {$LANG.invoices_uc} {$LANG.inserted}</h3>
<hr />
<table class="manage" id="live-grid" class="center">
  <colgroup>
    <col style='width: 20%;' />
    <col style='width: 30%;' />
    <col style='width: 20%;' />
    <!--    <col style='width:30%;' /> -->
  </colgroup>
  <thead>
    <tr>
      <th class="sortable">{$LANG.id}</th>
      <th class="sortable">{$LANG.date_uc}</th>
      <th class="sortable">{$LANG.cron_uc} {$LANG.id}</th>
      <!--    <th class="sortable">Invoice No</th> -->
    </tr>
  </thead>
  {foreach $cronLogs as $cronlog}
  <tr>
    <td class='index_table'>{$cronlog.id|htmlSafe}</td>
    <td class='index_table'>{$cronlog.run_date|htmlSafe}</td>
    <td class='index_table'><a href="index.php?module=cron&amp;view=view&amp;id={$cronlog.cron_id|htmlSafe}">{$cronlog.cron_id|htmlSafe}</a></td>
  </tr>
  {/foreach}
</table>
