{if isset($template)}
    <div class="flex__container flex__start">
        {* This is the case where the invoice content was *}
        {* copied from the designated customer invoice. *}
        <div class="bold margin__right-1">{$LANG.copiedFrom}:</div>
        <div>{$template|htmlSafe}</div>
    </div>
{/if}
<div class="row">
    <div class="col__20">
        <label for="billerId">{$LANG.billerUc}:</label>
    </div>
    <div class="col__80">
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
<div class="row">
    <div class="col__20">
        <label for="customerId">{$LANG.customerUc}:</label>
    </div>
    <div class="col__80">
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
    <div class="row">
        <div class="col__20">
            <label for="subCustId">{$LANG.subCustomer}:</label>
        </div>
        <div class="col__80">
            {$displayNone = false}
            {if empty($subCustomers)}
                <em id="noSubCustomers" class="margin__top-1-5" style="display:inline-block;">{$LANG.noSubCustomers}</em>
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
<div class="row">
    <div class="col__20">
        <label for="date1">{$LANG.dateFormatted}:</label>
    </div>
    <div class="col__80">
        <input type="text" name="date" id="date1" required readonly size="10" class="date-picker"
               value="{if isset($smarty.get.date)}{$smarty.get.date}{else}{$smarty.now|date_format:"%Y-%m-%d"}{/if}"/>
    </div>
</div>
