<div class="grid__container grid__head-10">
    <label for="custId" class="cols__2-span-3">{$LANG.customersUc}:</label>
    <select name="customerId" id="custId" class="cols__5-span-5">
        <option {if empty($customerId)}selected{/if} value=0>{$LANG.allUc}&nbsp;{$LANG.customersUc}</option>
        {foreach $customers as $customer}
            <option {if $customer.id == $customerId}selected{/if} value={$customer.id}>{$customer.name}{if !empty($customer.last_activity_date)} ({$LANG.lastActivity}: {$customer.last_activity_date}){/if}</option>
        {/foreach}
    </select>
</div>
