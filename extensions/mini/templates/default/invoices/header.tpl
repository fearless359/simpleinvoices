{*
 *  Script: header.tpl
 *      Header file for invoice template
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      2007-07-18
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<table class="center">
    <tr>
        <td class="details_screen">{$LANG.biller}</td>
        <td><input type="text" name="biller_block" size="25"/>
            {if !isset($billers) }
                <p><em>{$LANG.no_billers}</em></p>
            {else}
                <select name="biller_id">
                    {foreach from=$billers item=biller}
                        <option {if $biller.id == $defaults.biller} selected {/if} value="{if isset($biller.id)}{$biller.id}{/if}">{$biller.name}</option>
                    {/foreach}
                </select>
            {/if}
        </td>
    </tr>
    <tr>
        <td class="details_screen">{$LANG.customer}</td>
        <td>
            <input type="text" name="customer_block" size="25"/>
            {if !isset($customers) }
                <p><em>{$LANG.no_customers}</em></p>
            {else}
                <select name="customer_id">
                    {foreach from=$customers item=customer}
                        <option {if $customer.id == $defaultCustomerID} selected {/if} value="{if isset($customer.id)}{$customer.id}{/if}">{$customer.name}</option>
                    {/foreach}
                </select>
            {/if}
        </td>
    </tr>
    <tr>
        <td class="details_screen">{$LANG.date_upper}</td>
        <td>
            <input type="text" class="date-picker" name="date" id="date1" value='{$smarty.now|date_format:"%Y-%m-%d"}'/>
        </td>
    </tr>
    <input type="hidden" name="action" value="insert"/>

