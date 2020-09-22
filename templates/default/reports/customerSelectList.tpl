<tr>
    <td class="details_screen" style="text-align:right; padding-right: 10px; white-space: nowrap; width: 47%;">
        <label for="custId">{$LANG.customers}:</label>
    </td>
    <td>
        <select name="customerId" id="custId">
            <option {if empty($customer_id)}selected{/if} value=0>{$LANG.all}&nbsp;{$LANG.customers}</option>
            {foreach $customers as $customer}
                <option {if $customer.id == $customer_id}selected{/if} value={$customer.id}>{$customer.name} ({$LANG.lastActivity}: {$customer.last_activity_date})</option>
            {/foreach}
        </select>
    </td>
</tr>
