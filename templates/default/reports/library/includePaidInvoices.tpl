<!--suppress HtmlFormInputWithoutLabel -->
<tr style="margin: 0 auto; width: 100%;">
    <th class="si_right nowrap" style="padding-right: 10px; width: 47%;">
        {$LANG.includeUc} {$LANG.paid} {$LANG.invoices}:
    </th>
    <td>
        <input type="checkbox"  name="filterByDateRange" id="filterByDateRangeId"
               {if isset($smarty.post.includePaidInvoices) && $smarty.post.includePaidInvoices == "yes"} checked {/if} value="yes"/>
    </td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
