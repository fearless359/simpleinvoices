<table class='si_invoice_view'>
    <!-- Invoice Summary section -->
    <tr class="tr_head">
        <th>{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.numberShort}:</th>
        <td>{$invoice.index_id|htmlSafe}</td>
        <td></td>
        <th>{$preference.pref_inv_wording} {$LANG.dateUc}:</th>
        <td colspan="5">{$invoice.display_date|htmlSafe}</td>
    </tr>
    {$customFields.1}
    {$customFields.2}
    {$customFields.3}
    {$customFields.4}
    <tr>
        <td><br/></td>
    </tr>
    <!-- Biller section -->
    <tr class="tr_head">
        <th>{$LANG.biller}:</th>
        <td colspan="4">{$biller.name|htmlSafe}</td>
        <td class="si_switch">
            <a href='#' class="show_biller" title="{$LANG.showDetails}"
               onclick="$('.biller').show();$('.hide_biller').show();$('.show_biller').hide();">
                <img src="../../../images/magnifier_zoom_in.png" alt="{$LANG.showDetails}"/>
            </a>
            <a href='#' class="hide_biller si_hide" title="{$LANG.hideDetails}"
               onclick="$('.biller').hide();$('.hide_biller').hide();$('.show_biller').show();">
                <img src="../../../images/magnifier_zoom_out.png" alt="{$LANG.hideDetails}"/>
            </a>
        </td>
    </tr>
    <tr class="biller si_hide">
        <th>{$LANG.street}:</th>
        <td colspan="5">{$biller.street_address|htmlSafe}</td>
    </tr>
    <tr class="biller si_hide">
        <th>{$LANG.street2}:</th>
        <td colspan="5">{$biller.street_address2|htmlSafe}</td>
    </tr>
    <tr class="biller si_hide">
        <th>{$LANG.city}:</th>
        <td colspan="3">{$biller.city|htmlSafe}</td>
        <th>{$LANG.phoneShort}:</th>
        <td>{$biller.phone|htmlSafe}</td>
    </tr>
    <tr class="biller si_hide">
        <th>{$LANG.state}, {$LANG.zip}:</th>
        <td colspan="3">{$biller.state|htmlSafe},&nbsp;{$biller.zip_code|htmlSafe}</td>
        <th>{$LANG.mobileShort}:</th>
        <td>{$biller.mobile_phone|htmlSafe}</td>
    </tr>
    <tr class="biller si_hide">
        <th>{$LANG.country}:</th>
        <td colspan="3">{$biller.country|htmlSafe}</td>
        <th>{$LANG.fax}:</th>
        <td>{$biller.fax|htmlSafe}</td>
    </tr>
    <tr class="biller si_hide">
        <th>{$LANG.email}:</th>
        <td colspan="5">{$biller.email|htmlSafe}</td>
    </tr>
    {if !empty($customFieldLabels.biller_cf1)}
        <tr class="biller si_hide">
            <th>{$customFieldLabels.biller_cf1|htmlSafe}:</th>
            <td colspan="5">{$biller.custom_field1|htmlSafe}</td>
        </tr>
    {/if}
    {if !empty($customFieldLabels.biller_cf2)}
        <tr class="biller si_hide">
            <th>{$customFieldLabels.biller_cf2|htmlSafe}:</th>
            <td colspan="5">{$biller.custom_field2|htmlSafe}</td>
        </tr>
    {/if}
    {if !empty($customFieldLabels.biller_cf3)}
        <tr class="biller si_hide">
            <th>{$customFieldLabels.biller_cf3|htmlSafe}:</th>
            <td colspan="5">{$biller.custom_field3|htmlSafe}</td>
        </tr>
    {/if}
    {if !empty($customFieldLabels.biller_cf4)}
        <tr class="biller si_hide">
            <th>{$customFieldLabels.biller_cf4|htmlSafe}:</th>
            <td colspan="5">{$biller.custom_field4|htmlSafe}</td>
        </tr>
    {/if}
    <tr>
        <td colspan="6"><br/></td>
    </tr>
    <!-- Customer section -->
    <tr class="tr_head">
        <th>{$LANG.customer}:</th>
        <td colspan="4">{$customer.name|htmlSafe}</td>
        <td class="si_switch">
            <a href='#' class="show_customer" title="{$LANG.showDetails}"
               onclick="$('.customer').show();$('.hide_customer').show();$('.show_customer').hide();">
                <img src="../../../images/magnifier_zoom_in.png" alt="{$LANG.showDetails}"/>
            </a>
            <a href='#' class="hide_customer si_hide" title="{$LANG.hideDetails}"
               onclick="$('.customer').hide();$('.hide_customer').hide();$('.show_customer').show();">
                <img src="../../../images/magnifier_zoom_out.png" alt="{$LANG.hideDetails}"/>
            </a>
        </td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.attentionShort}:</th>
        <td colspan="5" class="align_left">{$customer.attention|htmlSafe}</td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.street}:</th>
        <td colspan="5" class="align_left">{$customer.street_address|htmlSafe}</td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.street2}:</th>
        <td colspan="5" class="align_left">{$customer.street_address2|htmlSafe}</td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.city}:</th>
        <td colspan="3">{$customer.city|htmlSafe}</td>
        <th>{$LANG.phoneShort}:</th>
        <td>{$customer.phone|htmlSafe}</td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.state}, {$LANG.zip}:</th>
        <td colspan="3">{$customer.state|htmlSafe},&nbsp;{$customer.zip_code|htmlSafe}</td>
        <th>{$LANG.mobileShort}:</th>
        <td>{$customer.mobile_phone|htmlSafe}</td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.country}:</th>
        <td colspan="3">{$customer.country|htmlSafe}</td>
        <th>{$LANG.fax}:</th>
        <td>{$customer.fax|htmlSafe}</td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.email}:</th>
        <td colspan="5">{$customer.email|htmlSafe}</td>
    </tr>
    {if !empty($customFieldLabels.customer_cf1)}
        <tr class="customer si_hide">
            <th>{$customFieldLabels.customer_cf1}:</th>
            <td colspan="5">{$customer.custom_field1|htmlSafe}</td>
        </tr>
    {/if}
    {if !empty($customFieldLabels.customer_cf2)}
        <tr class="customer si_hide">
            <th>{$customFieldLabels.customer_cf2}:</th>
            <td colspan="5">{$customer.custom_field2|htmlSafe}</td>
        </tr>
    {/if}
    {if !empty($customFieldLabels.customer_cf3)}
        <tr class="customer si_hide">
            <th>{$customFieldLabels.customer_cf3}:</th>
            <td colspan="5">{$customer.custom_field3|htmlSafe}</td>
        </tr>
    {/if}
    {if !empty($customFieldLabels.customer_cf4)}
        <tr class="customer si_hide">
            <th>{$customFieldLabels.customer_cf4}:</th>
            <td colspan="5">{$customer.custom_field4|htmlSafe}</td>
        </tr>
    {/if}
    <tr class="customer si_hide">
        <th>{$LANG.defaultInvoice}:</th>
        <td colspan="4">{if $customer.default_invoice != 0}{$customer.default_invoice}{/if}</td>
        <td class='details_screen align_right'>
            {if $customer.default_invoice != $invoice.index_id}
                <a href="?module=invoices&amp;view=usedefault&amp;action=update_template&amp;index_id={$invoice.index_id}&amp;customer_id={$customer.id}"
                   title="{$LANG.invoiceUc} {$invoice.index_id} {$LANG.asTemplate} {$LANG.for} {$customer.name}">
                    <img src="../../../images/load.png" alt="{$LANG.invoiceUc} {$invoice.index_id} {$LANG.asTemplate} {$LANG.for} {$customer.name}"/>
                </a>
            {/if}
        </td>
    </tr>
</table>
