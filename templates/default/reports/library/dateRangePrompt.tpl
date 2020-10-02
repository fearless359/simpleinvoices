<tr>
    <td class="si_center bold underline" colspan="2"
        style="font-size: 1.5em;">
        {$LANG.reportPeriod}
    </td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
</tr>
<tr style="margin: 0 auto; width: 100%;">
    <td class="si_right nowrap" style="padding-right: 10px; width: 47%;">
        <label for="startDateId">{$LANG.activityUc} {$LANG.startDate}:</label>
    </td>
    <td>
        <input type="text" name="startDate" id="startDateId" size="10"
               class="validate[required,custom[date],length[0,10]] date-picker"
               value='{if isset($startDate)}{$startDate}{/if}'/>
    </td>
</tr>
<tr style="margin: 0 auto; width: 100%;">
    <td class="si_right nowrap" style="padding-right: 10px; width: 47%;">
        <label for="endDateId">{$LANG.activityUc} {$LANG.endDate}:</label>
    </td>
    <td>
        <input type="text"  name="endDate" id="endDateId" size="10"
               class="validate[required,custom[date],length[0,10]] date-picker"
               value='{if isset($endDate)}{$endDate}{/if}'/>
    </td>
</tr>
