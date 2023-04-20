{*
 *  Script: view.tpl
 * 	    Product inquiry template
 *
 *  Authors:
 *	    Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 * 	    20210630 by Rich Rowley to convert to grid layout
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group*/
 *}
{if $num_payment_recs == 0}
    <meta http-equiv="refresh" content="2;URL=index.php?module=invoices&amp;view=manage"/>
    <div class='si_message_error'>{$LANG.zeroInvoiceAmt}</div>
{else}
    {if $num_payment_recs > 1}
        <h3 class="align__text-center margin__bottom-2">{$LANG.moreThanOnePymtRec}</h3>
    {/if}
    {if isset($message)}
        <h3 class="align__text-center margin__bottom-2 si_message_warning">{$message}</h3>
    {/if}
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold align__text-right margin__right-1">{$LANG.paymentId}:</div>
            <div class="cols__5-span-2">{$payment.id|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold align__text-right margin__right-1">{$LANG.invoiceId}:</div>
            <div class="cols__5-span-2"><a href='index.php?module=invoices&amp;view=quickView&amp;id={$payment.ac_inv_id|htmlSafe}'>{$payment.iv_index_id|htmlSafe}</a></div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold align__text-right margin__right-1">{$LANG.amountUc}:</div>
            <div class="cols__5-span-4">{$payment.ac_amount|utilNumber:$payment.precision:$payment.locale}&nbsp;
            {if $payment.warehouse_amount != 0}
                {if $payment.warehouse_amount > 0}
                    <em>{$LANG.paymentUc} {$LANG.excess} {$LANG.of} {$payment.warehouse_amount|utilCurrency:$payment.locale:$payment.currency_code}&comma; {$LANG.warehoused}</em>
                {else}
                    <em>{$LANG.paymentUc} {$LANG.applied} {$LANG.to} {$LANG.warehouse} {$LANG.balance}</em>
                {/if}
            {/if}
            </div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold align__text-right margin__right-1">{$LANG.dateUc}:</div>
            <div class="cols__5-span-2">{$payment.ac_date|date_format:"%Y-%m-%d"}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold align__text-right margin__right-1">{$LANG.billerUc}:</div>
            <div class="cols__5-span-4">{$payment.bname|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold align__text-right margin__right-1">{$LANG.customerUc}:</div>
            <div class="cols__5-span-4">{$payment.cname|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold align__text-right margin__right-1">{$LANG.paymentType}:</div>
            <div class="cols__5-span-2">{$paymentType.pt_description|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold align__text-right margin__right-1">{$LANG.checkNumberUc}:</div>
            <div class="cols__5-span-2">{if strtolower($paymentType.pt_description)=="check"}{$payment.ac_check_number|htmlSafe}{/if}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold align__text-right margin__right-1">{$LANG.onlinePaymentId}:</div>
            <div class="cols__5-span-2">{if isset($payment.online_payment)}{$payment.online_payment_id|htmlSafe}{/if}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold">{$LANG.notes}:</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-6">{$payment.ac_notes|outHtml}</div>
        </div>
        <div class="align__text-center margin__top-2">
            <a href="index.php?module=payments&amp;view=manage" class="button negative">
                <img src="images/cross.png" alt=""/>{$LANG.cancel}
            </a>
        </div>
    </div>
{/if}
