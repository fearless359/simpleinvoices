<div class='si_invoice_top'>
    {if isset($template)}
    <div class="grid__container grid__head-10">
        {* This is the case where the invoice content was *}
        {* copied from the designated customer invoice. *}
        <div class="cols__3-span-2 bold">{$LANG.copiedFrom}:&nbsp;</div>
        <div class="cols__5-span-3">{$template|htmlSafe}</div>
    </div>
    {/if}
    <div class="grid__container grid__head-10">
        <label for="billerId" class="cols__3-span-2 bold si_label">{$LANG.billerUc}:&nbsp;</label>
        <div class="cols__5-span-3">
            {if !isset($billers) }
                <em>{$LANG.noBillers}</em>
            {else}
                <select name="biller_id" id="billerId" class="validate[min[1],max[1000000]] text-input">
                    {foreach $billers as $biller}
                        <option {if $biller.id == $defaults.biller} selected {/if} value="{if isset($biller.id)}{$biller.id|htmlSafe}{/if}">
                            {$biller.name|htmlSafe}
                        </option>
                    {/foreach}
                </select>
            {/if}
        </div>
    </div>
    <div class="grid__container grid__head-10">
        <label for="customerId" class="cols__3-span-2 bold si_label">{$LANG.customerUc}:&nbsp;</label>
        <div class="cols__5-span-3">
            {if !isset($customers) }
                <em>{$LANG.noCustomers}</em>
            {else}
                <select name="customer_id" id="customerId" class="validate[min[1],max[1000000]] text-input">
                    {foreach $customers as $customer}
                        <option {if $customer.id == $defaultCustomerID} selected {/if} value="{if isset($customer.id)}{$customer.id|htmlSafe}{/if}">
                            {$customer.name|htmlSafe}
                        </option>
                    {/foreach}
                </select>
            {/if}
        </div>
    </div>
    {* section for sub_customer *}
    <div class="grid__container grid__head-10">
        <label for="subCustId" class="cols__3-span-2 bold si_label">{$LANG.subCustomer}:&nbsp;</label>
        <div class="cols__5-span-3">
            {if !isset($subCustomers) || empty($subCustomers) }
                <em>{$LANG.noSubCustomers}</em>
            {else}
                <select name="custom_field1" id="subCustId">
                    {foreach $subCustomers as $subCustomer}
                        <option {if isset($subCustomer.id) && $subCustomer.id == $defaultCustomerID}selected{/if} value="{if isset($subCustomer.id)}{$subCustomer.id|htmlSafe}{/if}">
                            {$subCustomer.attention|htmlSafe}
                        </option>
                    {/foreach}
                </select>
            {/if}
        </div>
    </div>
    <div class="grid__container grid__head-10">
        <label for="date1" class="cols__3-span-2 bold si_label">{$LANG.dateFormatted}:&nbsp;</label>
        <div class="cols__5-span-3">
            <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                   size="10" name="date" id="date1"
                   value="{if isset($smarty.get.date)}{$smarty.get.date}{else}{$smarty.now|date_format:"%Y-%m-%d"}{/if}"/>
        </div>
    </div>
</div>
