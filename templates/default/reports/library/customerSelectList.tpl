<tr>
    <td class="details_screen si_right nowrap" style="padding-right: 10px; width: 47%;">
        <label for="custId">{$LANG.customers}:</label>
    </td>
    <td>
        <select name="customerId" id="custId">
            <option {if empty($customerId)}selected{/if} value=0>{$LANG.all}&nbsp;{$LANG.customers}</option>
            {foreach $customers as $customer}
                <option {if $customer.id == $customerId}selected{/if} value={$customer.id}>{$customer.name} ({$LANG.lastActivity}: {$customer.last_activity_date})</option>
            {/foreach}
        </select>
    </td>
</tr>
