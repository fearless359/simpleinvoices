{*
 * Script: header.tpl
 *    Header file for invoice template
 *
 * License:
 *   GPL v3 or above
 *
 * Website:
 *  https://simpleinvoices.group
 *}
<!--suppress HtmlFormInputWithoutLabel -->
<input type="hidden" name="op" value="create"/>
<div class="si_filters si_buttons_invoice_header">
    <span class="si_filters_links">
        <a href="index.php?module=invoices&amp;view=itemised{if isset($template)}&amp;template={$template}{/if}{if isset($defaultCustomerID)}&amp;customer_id={$defaultCustomerID}{/if}"
           class="first{if $view=='itemised'} selected{/if}">
            <img class="action" src="../../../images/edit.png" alt=""/>
            {$LANG.itemised_style}
        </a>
        <a href="index.php?module=invoices&amp;view=total{if isset($template)}&amp;template={$template}{/if}{if isset($defaultCustomerID)}&amp;customer_id={$defaultCustomerID}{/if}"
           class="{if $view=='total'}selected{/if}">
            <img class="action" src="../../../images/page_white_edit.png" alt=""/>
            {$LANG.total_style}
        </a>
    </span>
    <span class="si_filters_title">
        <a class="cluetip" href="#" title="{$LANG.invoice_type}"
           rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_types">
            <img src="{$helpImagePath}help-small.png" alt=""/>
        </a>
    </span>
</div>
<table class='si_invoice_top'>
    {if isset($template)}
    <tr>
        <th>{$LANG.copied_from}</th>
        <td>{$template|htmlSafe}</td>
    </tr>
    {/if}
    <tr>
        <th>{$LANG.biller}</th>
        <td>
            {if !isset($billers) }
                <p><em>{$LANG.no_billers}</em></p>
            {else}
                <select name="biller_id" class="validate[min[1],max[1000000]] text-input">
                    {foreach $billers as $biller}
                        <option {if $biller.id == $defaults.biller} selected {/if} value="{if isset($biller.id)}{$biller.id|htmlSafe}{/if}">
                            {$biller.name|htmlSafe}
                        </option>
                    {/foreach}
                </select>
            {/if}
        </td>
    </tr>
    <tr>
        <th>{$LANG.customer}</th>
        <td>
            {if !isset($customers) }
                <em>{$LANG.no_customers}</em>
            {else}
                <select name="customer_id" class="validate[min[1],max[1000000]] text-input">
                    {foreach $customers as $customer}
                        <option {if $customer.id == $defaultCustomerID} selected {/if} value="{if isset($customer.id)}{$customer.id|htmlSafe}{/if}">
                            {$customer.name|htmlSafe}
                        </option>
                    {/foreach}
                </select>
            {/if}
        </td>
    </tr>
    <tr>
        <th>{$LANG.date_formatted}</th>
        <td>
            <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                   size="10" name="date" id="date1"
                   value="{if isset($smarty.get.date)}{$smarty.get.date}{else}{$smarty.now|date_format:"%Y-%m-%d"}{/if}"/>
        </td>
    </tr>
</table>
