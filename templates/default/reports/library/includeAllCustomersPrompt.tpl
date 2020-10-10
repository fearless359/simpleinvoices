<tr style="margin: 0 auto; width: 100%;">
    <th class="si_right nowrap" style="padding-right: 10px; width: 47%;">
        <label for="includeAllCustomersId">{$LANG.includeUc} {$LANG.all} {$LANG.customers}:</label>
    </th>
    <td>
        <input type="checkbox"  name="includeAllCustomers" id="includeAllCustomersId"
               {if $includeAllCustomers == "yes"} checked {/if} value="yes"/>
    </td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
