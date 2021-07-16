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
    <div class="grid__area">
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold">{$LANG.paymentId}:</div>
            <div class="cols__5-span-2">{$payment.id|htmlSafe}</div>
            <div class="cols__7-span-2 bold">{$LANG.invoiceId}:</div>
            <div class="cols__9-span-2"><a href='index.php?module=invoices&amp;view=quickView&amp;id={$payment.ac_inv_id|htmlSafe}'>{$payment.iv_index_id|htmlSafe}</a></div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold">{$LANG.amountUc}:</div>
            <div class="cols__5-span-2">{$payment.ac_amount|utilNumber}</div>
            <div class="cols__7-span-2 bold">{$LANG.dateUc}:</div>
            <div class="cols__9-span-2">{$payment.ac_date|date_format:"%Y-%m-%d"}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold">{$LANG.billerUc}:</div>
            <div class="cols__5-span-4">{$payment.bname|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold">{$LANG.customerUc}:</div>
            <div class="cols__5-span-4">{$payment.cname|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold">{$LANG.paymentType}:</div>
            <div class="cols__5-span-2">{$paymentType.pt_description|htmlSafe}</div>
            <div class="cols__7-span-2 bold">{$LANG.checkNumber}:</div>
            <div class="cols__9-span-2">{if strtolower($paymentType.pt_description)=="check"}{$payment.ac_check_number|htmlSafe}{/if}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold">{$LANG.onlinePaymentId}:</div>
            <div class="cols__5-span-2">{$payment.online_payment_id|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__3-span-2 bold">{$LANG.notes}:</div>
            <div class="cols__5-span-6">{$payment.ac_notes|outHtml}</div>
        </div>
        <div class="align__text-center margin__top-2">
            <a href="index.php?module=payments&amp;view=manage" class="button negative">
                <img src="images/cross.png" alt=""/>{$LANG.cancel}
            </a>
        </div>
    </div>
{/if}
