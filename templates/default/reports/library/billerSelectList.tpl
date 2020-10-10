<tr>
    <td class="details_screen si_right nowrap" style="padding-right: 10px; width: 47%;">
        <label for="bilrId">{$LANG.billersUc}:</label>
    </td>
    <td>
        <select name="billerId" id="bilrId">
            <option {if empty($billerId)}selected{/if} value=0>{$LANG.allUc}&nbsp;{$LANG.billersUc}</option>
            {foreach $billers as $biller}
                <option {if $biller.id == $billerId}selected{/if} value={$biller.id}>{$biller.name}</option>
            {/foreach}
        </select>
    </td>
</tr>
