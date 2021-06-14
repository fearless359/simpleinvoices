<div class='grid__area'>
    <!-- Invoice Summary section -->
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold">{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.numberShort}:</div>
        <div class="cols__3-span-3">{$invoice.index_id|htmlSafe}</div>
        <div class="cols__6-span-2 bold">{$preference.pref_inv_wording} {$LANG.dateUc}:</div>
        <div class="cols__8-span-3">{$invoice.date|htmlSafe}</div>
    </div>

    <!-- Biller section -->
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold">{$LANG.billerUc}:</div>
        <div class="cols__3-span-7">{$biller.name|htmlSafe}</div>
        <div class="si_right">
            <a href='#' class="show_biller" title="{$LANG.showDetails}"
               onclick="$('.biller').show();$('.hide_biller').show();$('.show_biller').hide();">
                <img src="images/magnifier_zoom_in.png" alt="{$LANG.showDetails}"/>
            </a>
            <a href='#' class="hide_biller si_hide" title="{$LANG.hideDetails}"
               onclick="$('.biller').hide();$('.hide_biller').hide();$('.show_biller').show();">
                <img src="images/magnifier_zoom_out.png" alt="{$LANG.hideDetails}"/>
            </a>
        </div>
    </div>
    <div class="biller si_hide">
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.street}:</div>
            <div class="cols__3-span-8">{$biller.street_address|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.street2}:</div>
            <div class="cols__3-span-8">{$biller.street_address2|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.city}:</div>
            <div class="cols__3-span-3">{$biller.city|htmlSafe}</div>
            <div class="cols__6-span-2 bold">{$LANG.phoneShort}:</div>
            <div class="cols__8-span-3">{$biller.phone|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.state}, {$LANG.zip}:</div>
            <div class="cols__3-span-3">{$biller.state|htmlSafe},&nbsp;{$biller.zip_code|htmlSafe}</div>
            <div class="cols__6-span-2 bold">{$LANG.mobileShort}:</div>
            <div class="cols__8-span-3">{$biller.mobile_phone|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.country}:</div>
            <div class="cols__3-span-3">{$biller.country|htmlSafe}</div>
            <div class="cols__6-span-2 bold">{$LANG.fax}:</div>
            <div class="cols__8-span-3">{$biller.fax|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.email}:</div>
            <div class="cols__3-span-8">{$biller.email|htmlSafe}</div>
        </div>
        {if !empty($customFieldLabels.biller_cf1)}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-2 bold">{$customFieldLabels.biller_cf1|htmlSafe}:</div>
                <div class="cols__3-span-8">{$biller.custom_field1|htmlSafe}</div>
            </div>
        {/if}
        {if !empty($customFieldLabels.biller_cf2)}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-2 bold">{$customFieldLabels.biller_cf2|htmlSafe}:</div>
                <div class="cols__3-span-8">{$biller.custom_field2|htmlSafe}</div>
            </div>
        {/if}
        {if !empty($customFieldLabels.biller_cf3)}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-2 bold">{$customFieldLabels.biller_cf3|htmlSafe}:</div>
                <div class="cols__3-span-8">{$biller.custom_field3|htmlSafe}</div>
            </div>
        {/if}
        {if !empty($customFieldLabels.biller_cf4)}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-2 bold">{$customFieldLabels.biller_cf4|htmlSafe}:</div>
                <div class="cols__3-span-8">{$biller.custom_field4|htmlSafe}</div>
            </div>
        {/if}
        <hr/>
    </div>
    <!-- Customer section -->
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold">{$LANG.customerUc}:</div>
        <div class="cols__3-span-7">{$customer.name|htmlSafe}</div>
        <div class="si_right">
            <a href='#' class="show_customer" title="{$LANG.showDetails}"
               onclick="$('.customer').show();$('.hide_customer').show();$('.show_customer').hide();">
                <img src="images/magnifier_zoom_in.png" alt="{$LANG.showDetails}"/>
            </a>
            <a href='#' class="hide_customer si_hide" title="{$LANG.hideDetails}"
               onclick="$('.customer').hide();$('.hide_customer').hide();$('.show_customer').show();">
                <img src="images/magnifier_zoom_out.png" alt="{$LANG.hideDetails}"/>
            </a>
        </div>
    </div>
    <div class="customer si_hide">
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.attentionShort}:</div>
            <div class="cols__3-span-8">{$customer.attention|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.street}:</div>
            <div class="cols__3-span-8">{$customer.street_address|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.street2}:</div>
            <div class="cols__3-span-8">{$customer.street_address2|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.city}:</div>
            <div class="cols__3-span-3">{$customer.city|htmlSafe}</div>
            <div class="cols__6-span-2 bold">{$LANG.phoneShort}:</div>
            <div class="cols__8-span-3">{$customer.phone|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.state}, {$LANG.zip}:</div>
            <div class="cols__3-span-3">{$customer.state|htmlSafe},&nbsp;{$customer.zip_code|htmlSafe}</div>
            <div class="cols__6-span-2 bold">{$LANG.mobileShort}:</div>
            <div class="cols__8-span-3">{$customer.mobile_phone|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.country}:</div>
            <div class="cols__3-span-3">{$customer.country|htmlSafe}</div>
            <div class="cols__6-span-2 bold">{$LANG.fax}:</div>
            <div class="cols__8-span-3">{$customer.fax|htmlSafe}</div>
        </div>
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.email}:</div>
            <div class="cols__3-span-8">{$customer.email|htmlSafe}</div>
        </div>
        {if !empty($customFieldLabels.customer_cf1)}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-2 bold">{$customFieldLabels.customer_cf1}:</div>
                <div class="cols__3-span-8">{$customer.custom_field1|htmlSafe}</div>
            </div>
        {/if}
        {if !empty($customFieldLabels.customer_cf2)}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-2 bold">{$customFieldLabels.customer_cf2}:</div>
                <div class="cols__3-span-8">{$customer.custom_field2|htmlSafe}</div>
            </div>
        {/if}
        {if !empty($customFieldLabels.customer_cf3)}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-2 bold">{$customFieldLabels.customer_cf3}:</div>
                <div class="cols__3-span-8">{$customer.custom_field3|htmlSafe}</div>
            </div>
        {/if}
        {if !empty($customFieldLabels.customer_cf4)}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-2 bold">{$customFieldLabels.customer_cf4}:</div>
                <div class="cols__3-span-8">{$customer.custom_field4|htmlSafe}</div>
            </div>
        {/if}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold">{$LANG.defaultInvoice}:</div>
            <div class="cols__3-span-7">{if $customer.default_invoice != 0}{$customer.default_invoice}{/if}</div>
            <div class='cols__10-span-1 si_right'>
                {if $customer.default_invoice != $invoice.index_id}
                    <a href="?module=invoices&amp;view=usedefault&amp;action=update_template&amp;index_id={$invoice.index_id}&amp;customer_id={$customer.id}"
                       title="{$LANG.invoiceUc} {$invoice.index_id} {$LANG.asTemplate} {$LANG.for} {$customer.name}">
                        <img src="images/load.png" alt="{$LANG.invoiceUc} {$invoice.index_id} {$LANG.asTemplate} {$LANG.for} {$customer.name}"/>
                    </a>
                {/if}
            </div>
        </div>
        <hr/>
    </div>
</div>
