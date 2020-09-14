<h3>{$LANG.total_sales}</h3>
<hr />

<table class="si_report_table">
  <thead>
    <tr>
      <th class="align_left">{$LANG.invoice_preferences}</th>
      <th class="align_right">{$LANG.invoices_uc}</th>
      <th class="align_right">{$LANG.total_sales}</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <td class="page_layer si_right" colspan="2">{$LANG.total_sales}:
      </td>
      <td class="page_layer si_right"><span class="bold">{$grandTotalSales|utilNumber:2|default:'-'}</span></td>
    </tr>
  </tfoot>
  <tbody>
    {foreach item=totalSales from=$data}
    <tr>
      <td class="align_left">{$totalSales.template|htmlSafe}</td>
      <td class="align_right">{$totalSales.count|utilNumber:0|default:'-'}</td>
      <td class="align_right">{$totalSales.total|utilNumber:2|default:'-'}</td>
    </tr>
    {/foreach}
  </tbody>
</table>
