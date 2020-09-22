<table class="si_report_table">
  <thead>
    <tr>
      <th colspan="3">{$LANG.totalSales}</th>
    </tr>
    <tr>
      <th class="align_left">{$LANG.invoicePreferences}</th>
      <th class="align_right">{$LANG.invoicesUc}</th>
      <th class="align_right">{$LANG.totalSales}</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <td class="page_layer si_right" colspan="2">{$LANG.totalSales}:
      </td>
      <td class="page_layer si_right"><span class="bold">{$grandTotalSales|utilNumber:2|default:'-'}</span></td>
    </tr>
  </tfoot>
  <tbody>
    {foreach $data as $totalSales}
    <tr>
      <td class="align_left">{$totalSales.template|htmlSafe}</td>
      <td class="align_right">{$totalSales.count|utilNumber:0|default:'-'}</td>
      <td class="align_right">{$totalSales.total|utilNumber:2|default:'-'}</td>
    </tr>
    {/foreach}
  </tbody>
</table>
