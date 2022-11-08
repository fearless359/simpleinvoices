{*
 *  Script: create.tpl
 * 	    Payment warehousee add template
 *
 *  Authors:
 *	    Richard Rowley
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group*/
 *}
{if !empty($smarty.post.customer)}
    {include file="templates/default/payment_warehouse/save.tpl"}
{elseif $customerCount == 0}
    <h3 class="si_message_error">{$LANG.allUc} {$LANG.customers} {$LANG.have} {$LANG.warehouse} {$LANG.records}.
        {$LANG.useUc} {$LANG.edit}/{$LANG.delete} {$LANG.options} {$LANG.to} {$LANG.maintain} {$LANG.them}!</h3>
{else}
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=payment_warehouse&amp;view=create">
        <div class="grid__area">
            <div class="grid__container grid__head-10">
                <label for="customerId" class="cols__2-span-3 align__text-right margin__right-1">{$LANG.customerUc}:</label>
                <select name="customer" id="customerId" class="cols__5-span-4" required tabindex="20">
                    <option value="" selected></option>
                    {foreach $customers as $customer}
                        <option value="{$customer.id}">{$customer.name}</option>"
                    {/foreach}
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="balanceId" class="cols__2-span-3 align__text-right margin__right-1">{$LANG.balanceUc}:</label>
                <input type="text" name="balance" id="balanceId" class="cols__5-span-2" required size="20" tabindex="30"/>
            </div>
            <div class="grid__container grid__head-10">
                <label for="pymtTypeId" class="cols__2-span-3 align__text-right margin__right-1">{$LANG.paymentType}:</label>
                <select name="payment_type" id="pymtTypeId" class="cols__5-span-2" required tabindex="40">
                    <option value="" selected></option>
                    {foreach $paymentTypes as $paymentType}
                        <option value="{$paymentType.pt_id}">{$paymentType.pt_description}</option>"
                    {/foreach}
                </select>
            </div>
            <div class="grid__container grid__head-10">
                <label for="checkNumberId" class="cols__2-span-3 align__text-right margin__right-1">{$LANG.checkNumberUc}:</label>
                <input type="text" name="check_number" id="checkNumberId" class="cols__5-span-2 validateCheckNumber" size="20" tabindex="50"/>
            </div>
        </div>
        <div class="align__text-center margin__top-2">
            <button type="submit" class="positive" name="savePaymentWarehouse" value="{$LANG.save}" tabindex="100">
                <img class="button_img" src="images/tick.png" alt="{$LANG.save}"/>{$LANG.save}
            </button>
            <a href="index.php?module=payment_warehouse&amp;view=manage" class="button negative" tabindex="110">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="op" value="create"/>
    </form>
{/if}
