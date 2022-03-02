{if isset($template)}
    <div class="grid__container grid__head-10">
        {* This is the case where the invoice content was *}
        {* copied from the designated customer invoice. *}
        <div class="cols__3-span-2 bold">{$LANG.copiedFrom}:</div>
        <div class="cols__5-span-3">{$template|htmlSafe}</div>
    </div>
{/if}
<div class="grid__container grid__head-10">
    <label for="billerId" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.billerUc}:</label>
    <div class="cols__5-span-3">
        {if !isset($billers) }
            <em>{$LANG.noBillers}</em>
        {else}
            <select name="biller_id" id="billerId">
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
    <label for="customerId" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.customerUc}:</label>
    <div class="cols__5-span-3">
        {if !isset($customers) }
            <em>{$LANG.noCustomers}</em>
        {else}
            <select name="customer_id" id="customerId" class="setSubCustomers">
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
    <label for="subCustId" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.subCustomer}:</label>
    <div class="cols__5-span-3">
        {$displayNone = false}
        {if empty($subCustomers)}
            <em id="noSubCustomers" style="display:inline-block;">{$LANG.noSubCustomers}</em>
            {$displayNone = true}
        {/if}
        <select name="custom_field1" id="subCustId" {if $displayNone}style="display:none;"{/if}>
            {foreach $subCustomers as $subCustomer}
                <option {if isset($subCustomer.id) && $subCustomer.id == $defaultCustomerID}selected{/if}
                        value="{$subCustomer.id|htmlSafe}">{$subCustomer.attention|htmlSafe}</option>
            {/foreach}
        </select>
    </div>
</div>
<div class="grid__container grid__head-10">
    <label for="date1" class="cols__3-span-2 align__text-right margin__right-1">{$LANG.dateFormatted}:</label>
    <div class="cols__5-span-1">
        <input type="text" name="date" id="date1" required readonly size="10" class="date-picker"
               value="{if isset($smarty.get.date)}{$smarty.get.date}{else}{$smarty.now|date_format:"%Y-%m-%d"}{/if}"/>
    </div>
</div>
