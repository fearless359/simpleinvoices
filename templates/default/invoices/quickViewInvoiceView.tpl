<!-- Invoice Summary section -->
<div class="row">
    <div class="col__20">
        <span class="label">{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.numberShort}:</span>
    </div>
    <div class="col__80">
        <span class="inputText">{$invoice.index_id|htmlSafe}</span>
    </div>
</div>
<div class="row">
    <div class="col__20">
        <span class="label">{$preference.pref_inv_wording}&nbsp;{$LANG.dateUc}:</span>
    </div>
    <div class="col__80">
        <span class="inputText">{$invoice.date|htmlSafe}</span>
    </div>
</div>

<!-- Biller section -->
<div class="row">
    <div class="col__20">
        <span class="label">{$LANG.billerUc}:</span>
    </div>
    <div class="col__75">
        <span class="inputText">{$biller.name|htmlSafe}</span>
    </div>
    <div class="col__5">
        <span class="showHide">
            <a href='#' class="show_biller" title="{$LANG.showDetails}"
               onclick="$('.biller').show();$('.hide_biller').show();$('.show_biller').hide();">
                <img src="images/magnifier_zoom_in.png" alt="{$LANG.showDetails}"/>
            </a>
            <a href='#' class="hide_biller" title="{$LANG.hideDetails}" style="display:none;"
               onclick="$('.biller').hide();$('.hide_biller').hide();$('.show_biller').show();">
                <img src="images/magnifier_zoom_out.png" alt="{$LANG.hideDetails}"/>
            </a>
        </span>
    </div>
</div>
<div class="biller" style="display:none;">
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.street}:</div>
        <div class="cols__3-span-6 margin__left-0-5">{$biller.street_address|htmlSafe}</div>
    </div>
    {if !empty($biller.street_address2)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.street2}:</div>
            <div class="cols__3-span-6 margin__left-0-5">{$biller.street_address2|htmlSafe}</div>
        </div>
    {/if}
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.city}:</div>
        <div class="cols__3-span-3 margin__left-0-5">{$biller.city|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.state}, {$LANG.zip}:</div>
        <div class="cols__3-span-3 margin__left-0-5">{$biller.state|htmlSafe},&nbsp;{$biller.zip_code|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.country}:</div>
        <div class="cols__3-span-3 margin__left-0-5">{$biller.country|htmlSafe}</div>
    </div>
    {if !empty($biller.phone)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.phoneShort}:</div>
            <div class="cols__3-span-3 margin__left-0-5">{$biller.phone|htmlSafe}</div>
        </div>
    {/if}
    {if !empty($biller.mobile_phone)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.mobileShort}:</div>
            <div class="cols__3-span-3 margin__left-0-5">{$biller.mobile_phone|htmlSafe}</div>
        </div>
    {/if}
    {if !empty($biller.fax)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.fax}:</div>
            <div class="cols__6-span-3 margin__left-0-5">{$biller.fax|htmlSafe}</div>
        </div>
    {/if}
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.email}:</div>
        <div class="cols__3-span-6 margin__left-0-5">{$biller.email|htmlSafe}</div>
    </div>
    {if !empty($customFieldLabels.biller_cf1)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$customFieldLabels.biller_cf1|htmlSafe}:
            </div>
            <div class="cols__3-span-6 margin__left-0-5">{$biller.custom_field1|htmlSafe}</div>
        </div>
    {/if}
    {if !empty($customFieldLabels.biller_cf2)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$customFieldLabels.biller_cf2|htmlSafe}:
            </div>
            <div class="cols__3-span-6 margin__left-0-5">{$biller.custom_field2|htmlSafe}</div>
        </div>
    {/if}
    {if !empty($customFieldLabels.biller_cf3)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$customFieldLabels.biller_cf3|htmlSafe}:
            </div>
            <div class="cols__3-span-6 margin__left-0-5">{$biller.custom_field3|htmlSafe}</div>
        </div>
    {/if}
    {if !empty($customFieldLabels.biller_cf4)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$customFieldLabels.biller_cf4|htmlSafe}:
            </div>
            <div class="cols__3-span-6 margin__left-0-5">{$biller.custom_field4|htmlSafe}</div>
        </div>
    {/if}
    <hr/>
</div>
<!-- Customer section -->
<div class="row">
    <div class="col__20">
        <span class="label">{$LANG.customerUc}:</span>
    </div>
    <div class="col__75">
        <span class="inputText">{$customer.name|htmlSafe}"</span>
    </div>
    <div class="col__5">
        <span class="showHide">
            <a href='#' class="show_customer" title="{$LANG.showDetails}"
               onclick="$('.customer').show();$('.hide_customer').show();$('.show_customer').hide();">
                <img src="images/magnifier_zoom_in.png" alt="{$LANG.showDetails}"/>
            </a>
            <a href='#' class="hide_customer" title="{$LANG.hideDetails}" style="display:none;"
               onclick="$('.customer').hide();$('.hide_customer').hide();$('.show_customer').show();">
                <img src="images/magnifier_zoom_out.png" alt="{$LANG.hideDetails}"/>
            </a>
        </span>
    </div>
</div>
<div class="customer" style="display:none;">
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.attentionShort}:</div>
        <div class="cols__3-span-6">{$customer.attention|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.street}:</div>
        <div class="cols__3-span-6">{$customer.street_address|htmlSafe}</div>
    </div>
    {if !empty($customer.street_address2)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.street2}:</div>
            <div class="cols__3-span-6">{$customer.street_address2|htmlSafe}</div>
        </div>
    {/if}
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.city}:</div>
        <div class="cols__3-span-6">{$customer.city|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.state}, {$LANG.zip}:</div>
        <div class="cols__3-span-6">{$customer.state|htmlSafe},&nbsp;{$customer.zip_code|htmlSafe}</div>
    </div>
    {if !empty($customer.country)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.country}:</div>
            <div class="cols__3-span-6">{$customer.country|htmlSafe}</div>
        </div>
    {/if}
    {if !empty($customer.phone)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.phoneShort}:</div>
            <div class="cols__3-span-6">{$customer.phone|htmlSafe}</div>
        </div>
    {/if}
    {if !empty($customer.mobile_phone)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.mobileShort}:</div>
            <div class="cols__3-span-6">{$customer.mobile_phone|htmlSafe}</div>
        </div>
    {/if}
    {if !empty($customer.fax)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.fax}:</div>
            <div class="cols__3-span-6">{$customer.fax|htmlSafe}</div>
        </div>
    {/if}
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.email}:</div>
        <div class="cols__3-span-6">{$customer.email|htmlSafe}</div>
    </div>
    {if !empty($customFieldLabels.customer_cf1)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$customFieldLabels.customer_cf1}:</div>
            <div class="cols__3-span-6">{$customer.custom_field1|htmlSafe}</div>
        </div>
    {/if}
    {if !empty($customFieldLabels.customer_cf2)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$customFieldLabels.customer_cf2}:</div>
            <div class="cols__3-span-6">{$customer.custom_field2|htmlSafe}</div>
        </div>
    {/if}
    {if !empty($customFieldLabels.customer_cf3)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$customFieldLabels.customer_cf3}:</div>
            <div class="cols__3-span-6">{$customer.custom_field3|htmlSafe}</div>
        </div>
    {/if}
    {if !empty($customFieldLabels.customer_cf4)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-2 bold align__text-right margin__right-1">{$customFieldLabels.customer_cf4}:</div>
            <div class="cols__3-span-6">{$customer.custom_field4|htmlSafe}</div>
        </div>
    {/if}
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.defaultInvoice}:</div>
        <div class="cols__3-span-6">{if $customer.default_invoice != 0}{$customer.default_invoice}{/if}</div>
        <div class='cols__10-span-1 align__text-right'>
            {if $customer.default_invoice != $invoice.index_id}
                <a href="?module=invoices&amp;view=usedefault&amp;action=update_template&amp;index_id={$invoice.index_id}&amp;customer_id={$customer.id}"
                   title="{$LANG.invoiceUc} {$invoice.index_id} {$LANG.asTemplate} {$LANG.for} {$customer.name}">
                    <img src="images/load.png"
                         alt="{$LANG.invoiceUc} {$invoice.index_id} {$LANG.asTemplate} {$LANG.for} {$customer.name}"/>
                </a>
            {/if}
        </div>
    </div>
    <hr/>
</div>
