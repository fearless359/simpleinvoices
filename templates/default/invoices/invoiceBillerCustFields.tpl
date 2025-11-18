{if isset($template)}
    <div class="flex__container flex__start">
        {* This is the case where the invoice content was *}
        {* copied from the designated customer invoice. *}
        <div class="bold margin__right-1">{$LANG.copiedFrom}:</div>
        <div>{$template|htmlSafe}</div>
    </div>
{/if}
<div class="flex__container flex__start">
    <label for="billerId" class="margin__right-1">{$LANG.billerUc}:</label>
    <div>
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
<div class="flex__container flex__start">
    <label for="customerId" class="margin__right-1">{$LANG.customerUc}:</label>
    <div>
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
{if ($defaults.sub_customer)}
    <div class="flex__container flex__start">
        <label for="subCustId" class="margin__right-1">{$LANG.subCustomer}:</label>
        <div>
            {$displayNone = false}
            {if empty($subCustomers)}
                <em id="noSubCustomers" style="display:inline-block;">{$LANG.noSubCustomers}</em>
                {$displayNone = true}
            {/if}
            <select name="custom_field1" id="subCustId" {if $displayNone}style="display:none;"{/if}>
                {if !empty($subCustomers)}
                    {foreach $subCustomers as $subCustomer}
                        <option {if isset($subCustomer.id) && $subCustomer.id == $defaultCustomerID}selected{/if}
                                value="{$subCustomer.id|htmlSafe}">{$subCustomer.attention|htmlSafe}</option>
                    {/foreach}
                {/if}
            </select>
        </div>
    </div>
{/if}
<div class="flex__container flex__start">
    <label for="date1" class="margin__right-1">{$LANG.dateFormatted}:</label>
    <input type="text" name="date" id="date1" required readonly size="10" class="date-picker"
           value="{if isset($smarty.get.date)}{$smarty.get.date}{else}{$smarty.now|date_format:"%Y-%m-%d"}{/if}"/>
</div>
