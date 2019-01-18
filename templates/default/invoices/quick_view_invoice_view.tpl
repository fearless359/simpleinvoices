<table class='si_invoice_view'>
    <!-- Invoice Summary section -->
    <tr class="tr_head">
        <th>{$preference.pref_inv_wording|htmlsafe}&nbsp;{$LANG.number_short}:</th>
        <td>{$invoice.index_id|htmlsafe}</td>
        <td></td>
        <th>{$preference.pref_inv_wording} {$LANG.date_upper}:</th>
        <td colspan="5">{$invoice.date|htmlsafe}</td>
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
        <td colspan="4">{$biller.name|htmlsafe}</td>
        <td class="si_switch">
            <a href='#' class="show_biller" title="{$LANG.show_details}"
               onclick="$('.biller').show();$('.hide_biller').show();$('.show_biller').hide();">
                <img src="images/common/magnifier_zoom_in.png"/>
            </a>
            <a href='#' class="hide_biller si_hide" title="{$LANG.hide_details}"
               onclick="$('.biller').hide();$('.hide_biller').hide();$('.show_biller').show();">
                <img src="images/common/magnifier_zoom_out.png"/>
            </a>
        </td>
    </tr>
    <tr class="biller si_hide">
        <th>{$LANG.street}:</th>
        <td colspan="5">{$biller.street_address|htmlsafe}</td>
    </tr>
    <tr class="biller si_hide">
        <th>{$LANG.street2}:</th>
        <td colspan="5">{$biller.street_address2|htmlsafe}</td>
    </tr>
    <tr class="biller si_hide">
        <th>{$LANG.city}:</th>
        <td colspan="3">{$biller.city|htmlsafe}</td>
        <th>{$LANG.phone_short}:</th>
        <td>{$biller.phone|htmlsafe}</td>
    </tr>
    <tr class="biller si_hide">
        <th>{$LANG.state}, {$LANG.zip}:</th>
        <td colspan="3">{$biller.state|htmlsafe},&nbsp;{$biller.zip_code|htmlsafe}</td>
        <th>{$LANG.mobile_short}:</th>
        <td>{$biller.mobile_phone|htmlsafe}</td>
    </tr>
    <tr class="biller si_hide">
        <th>{$LANG.country}:</th>
        <td colspan="3">{$biller.country|htmlsafe}</td>
        <th>{$LANG.fax}:</th>
        <td>{$biller.fax|htmlsafe}</td>
    </tr>
    <tr class="biller si_hide">
        <th>{$LANG.email}:</th>
        <td colspan="5">{$biller.email|htmlsafe}</td>
    </tr>
    {if !empty($customFieldLabels.biller_cf1)}
        <tr class="biller si_hide">
            <th>{$customFieldLabels.biller_cf1|htmlsafe}:</th>
            <td colspan="5">{$biller.custom_field1|htmlsafe}</td>
        </tr>
    {/if}
    {if !empty($customFieldLabels.biller_cf2)}
        <tr class="biller si_hide">
            <th>{$customFieldLabels.biller_cf2|htmlsafe}:</th>
            <td colspan="5">{$biller.custom_field2|htmlsafe}</td>
        </tr>
    {/if}
    {if !empty($customFieldLabels.biller_cf3)}
        <tr class="biller si_hide">
            <th>{$customFieldLabels.biller_cf3|htmlsafe}:</th>
            <td colspan="5">{$biller.custom_field3|htmlsafe}</td>
        </tr>
    {/if}
    {if !empty($customFieldLabels.biller_cf4)}
        <tr class="biller si_hide">
            <th>{$customFieldLabels.biller_cf4|htmlsafe}:</th>
            <td colspan="5">{$biller.custom_field4|htmlsafe}</td>
        </tr>
    {/if}
    <tr>
        <td colspan="6"><br/></td>
    </tr>
    <!-- Customer section -->
    <tr class="tr_head">
        <th>{$LANG.customer}:</th>
        <td colspan="4">{$customer.name|htmlsafe}</td>
        <td class="si_switch">
            <a href='#' class="show_customer" title="{$LANG.show_details}"
               onclick="$('.customer').show();$('.hide_customer').show();$('.show_customer').hide();">
                <img src="images/common/magnifier_zoom_in.png"/>
            </a>
            <a href='#' class="hide_customer si_hide" title="{$LANG.hide_details}"
               onclick="$('.customer').hide();$('.hide_customer').hide();$('.show_customer').show();">
                <img src="images/common/magnifier_zoom_out.png"/>
            </a>
        </td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.attention_short}:</th>
        <td colspan="5" align="left">{$customer.attention|htmlsafe}</td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.street}:</th>
        <td colspan="5" align="left">{$customer.street_address|htmlsafe}</td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.street2}:</th>
        <td colspan="5" align="left">{$customer.street_address2|htmlsafe}</td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.city}:</th>
        <td colspan="3">{$customer.city|htmlsafe}</td>
        <th>{$LANG.phone_short}:</th>
        <td>{$customer.phone|htmlsafe}</td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.state}, {$LANG.zip}:</th>
        <td colspan="3">{$customer.state|htmlsafe},&nbsp;{$customer.zip_code|htmlsafe}</td>
        <th>{$LANG.mobile_short}:</th>
        <td>{$customer.mobile_phone|htmlsafe}</td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.country}:</th>
        <td colspan="3">{$customer.country|htmlsafe}</td>
        <th>{$LANG.fax}:</th>
        <td>{$customer.fax|htmlsafe}</td>
    </tr>
    <tr class="customer si_hide">
        <th>{$LANG.email}:</th>
        <td colspan="5">{$customer.email|htmlsafe}</td>
    </tr>
    {if !empty($customFieldLabels.customer_cf1)}
        <tr class="customer si_hide">
            <th>{$customFieldLabels.customer_cf1}:</th>
            <td colspan="5">{$customer.custom_field1|htmlsafe}</td>
        </tr>
    {/if}
    {if !empty($customFieldLabels.customer_cf2)}
        <tr class="customer si_hide">
            <th>{$customFieldLabels.customer_cf2}:</th>
            <td colspan="5">{$customer.custom_field2|htmlsafe}</td>
        </tr>
    {/if}
    {if !empty($customFieldLabels.customer_cf3)}
        <tr class="customer si_hide">
            <th>{$customFieldLabels.customer_cf3}:</th>
            <td colspan="5">{$customer.custom_field3|htmlsafe}</td>
        </tr>
    {/if}
    {if !empty($customFieldLabels.customer_cf4)}
        <tr class="customer si_hide">
            <th>{$customFieldLabels.customer_cf4}:</th>
            <td colspan="5">{$customer.custom_field4|htmlsafe}</td>
        </tr>
    {/if}
    <tr class="customer si_hide">
        <th>{$LANG.default_invoice}:</th>
        <td colspan="4">{if $customer.default_invoice != 0}{$customer.default_invoice}{/if}</td>
        <td class='details_screen align_right'>
            {if $customer.default_invoice != $invoice.index_id}
                <a href="?module=invoices&amp;view=usedefault&amp;action=update_template&amp;index_id={$invoice.index_id}&amp;customer_id={$customer.id}">
                    <img src="images/flexigrid/load.png" title='{$LANG.invoice} {$invoice.index_id} {$LANG.as_template} {$LANG.for} {$customer.name}'/>
                </a>
            {/if}
        </td>
        </td>
    </tr>
</table>
