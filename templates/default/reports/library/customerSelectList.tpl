<tr>
    <td class="details_screen si_right nowrap" style="padding-right: 10px; width: 47%;">
        <label for="custId">{$LANG.customersUc}:</label>
    </td>
    <td>
        <select name="customerId" id="custId">
            <option {if empty($customerId)}selected{/if} value=0>{$LANG.allUc}&nbsp;{$LANG.customersUc}</option>
            {foreach $customers as $customer}
                <option {if $customer.id == $customerId}selected{/if} value={$customer.id}>{$customer.name}{if !empty($customer.last_activity_date)} ({$LANG.lastActivity}: {$customer.last_activity_date}){/if}</option>
            {/foreach}
        </select>
    </td>
</tr>
