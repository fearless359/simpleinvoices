{*
 *  Script: view.tpl
 * 	    Product inquiry template
 *
 *  Authors:
 *	    Justin Kelly, Nicolas Ruflin
 *
 *  Last edited:
 *      20251210 by Rich Rowley to use column size layout for responsiveness and appearence.
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
    <div class="form__container">
        {if $num_payment_recs > 1}
            <h3 class="align__text-center margin__bottom-2">{$LANG.moreThanOnePymtRec}</h3>
        {/if}
        {if isset($message)}
            <h3 class="align__text-center margin__bottom-2 si_message_warning">{$message}</h3>
        {/if}
        <div class="row">
            <div class="col__25">
                <span class="label">{$LANG.paymentId}:</span>
            </div>
            <div class="col__75">
                <span class="inputText">{$payment.id|htmlSafe}</span>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <span class="label">{$LANG.invoiceId}:</span>
            </div>
            <div class="col__75">
                <a href='index.php?module=invoices&amp;view=quickView&amp;id={$payment.ac_inv_id|htmlSafe}'>
                    <span class="inputText">{$payment.iv_index_id|htmlSafe}</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <span class="label">{$LANG.amountUc}:</span>
            </div>
            <div class="col__75">
                <span class="inputText">{$payment.ac_amount|utilNumber:$payment.precision:$payment.locale}&nbsp;
                    {if $payment.warehouse_amount != 0}
                        {if $payment.warehouse_amount > 0}
                            <em>{$LANG.paymentUc} {$LANG.excess} {$LANG.of}
                                {$payment.warehouse_amount|utilCurrency:$payment.locale:$payment.currency_code}&comma; {$LANG.warehoused}</em>
                        {else}
                            <em>{$LANG.paymentUc} {$LANG.applied} {$LANG.to} {$LANG.warehouse} {$LANG.balance}</em>
                        {/if}
                    {/if}
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <span class="label">{$LANG.dateUc}:</span>
            </div>
            <div class="col__75">
                <span class="inputText">{$payment.ac_date|date_format:"%Y-%m-%d"}</span>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <span class="label">{$LANG.billerUc}:</span>
            </div>
            <div class="col__75">
                <span class="inputText">{$payment.bname|htmlSafe}</span>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <span class="label">{$LANG.customerUc}:</span>
            </div>
            <div class="col__75">
                <span class="inputText">{$payment.cname|htmlSafe}</span>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <span class="label">{$LANG.paymentType}:</span>
            </div>
            <div class="col__75">
                <span class="inputText">{$paymentType.pt_description|htmlSafe}</span>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <span class="label">{$LANG.checkNumberUc}:</span>
            </div>
            <div class="col__75">
                <span class="inputText">{if $paymentType.pt_description|lower=="check"}{$payment.ac_check_number|htmlSafe}{/if}</span>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <span class="label">{$LANG.onlinePaymentId}:</span>
            </div>
            <div class="col__75">
                <span class="inputText">{if isset($payment.online_payment)}{$payment.online_payment_id|htmlSafe}{/if}</span>
            </div>
        </div>
        <div class="row">
            <div class="col__25">
                <span class="label">{$LANG.notes}:</span>
            </div>
            <div class="col__75">
                <span class="inputText">{$payment.ac_notes|outHtml}</span>
            </div>
        </div>
        <div class="align__text-center margin__top-2">
            <a href="index.php?module=payments&amp;view=manage" class="button negative">
                <img src="images/cross.png" alt=""/>{$LANG.cancel}
            </a>
        </div>
    </div>
{/if}
