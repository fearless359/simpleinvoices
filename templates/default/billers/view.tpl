{*
 * Script: details.tpl
 *   Biller details template
 *
 * Last edited:
 *   2018-09-21 by Rich Rowley to add signature field.
 *   2008-08-25
 *
 * License:
 *   GPL v3 or above
 *}
<!--suppress HtmlFormInputWithoutLabel -->
<div class="si_form">
    <table>
        <tr>
            <th class="details_screen">{$LANG.billerName}:</th>
            <td class="si_input">{$biller.name}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.street}:</th>
            <td class="si_input">{$biller.street_address}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.street2}:</th>
            <td class="si_input">{$biller.street_address2}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.city}:</th>
            <td class="si_input">{$biller.city}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.zip}:</th>
            <td class="si_input">{$biller.zip_code}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.state}:</th>
            <td class="si_input">{$biller.state}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.country}:</th>
            <td class="si_input">{$biller.country}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.mobilePhone}:</th>
            <td class="si_input">{$biller.mobile_phone}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.phoneUc}:</th>
            <td class="si_input">{$biller.phone}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.fax}:</th>
            <td class="si_input">{$biller.fax}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.email}:</th>
            <td class="si_input">{$biller.email}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.signature}:</th>
            <td class="si_input">{$biller.signature}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.paypalBusinessName}:</th>
            <td class="si_input">{$biller.paypal_business_name}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.paypalNotifyUrl}:</th>
            <td class="si_input">{$biller.paypal_notify_url}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.paypalReturnUrl}:</th>
            <td class="si_input">{$biller.paypal_return_url}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.ewayCustomerId}:</th>
            <td class="si_input">{$biller.eway_customer_id}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.paymentsGatewayApiId}:</th>
            <td class="si_input">{$biller.paymentsgateway_api_id}</td>
        </tr>
        {if !empty($customFieldLabel.biller_cf1)}
            <tr>
                <th class="details_screen">{$customFieldLabel.biller_cf1|htmlSafe}:</th>
                <td class="si_input">{$biller.custom_field1}</td>
            </tr>
        {/if}
        {if !empty($customFieldLabel.biller_cf2)}
            <tr>
                <th class="details_screen">{$customFieldLabel.biller_cf2|htmlSafe}:</th>
                <td class="si_input">{$biller.custom_field2}</td>
            </tr>
        {/if}
        {if !empty($customFieldLabel.biller_cf3)}
            <tr>
                <th class="details_screen">{$customFieldLabel.biller_cf3|htmlSafe}:</th>
                <td class="si_input">{$biller.custom_field3}</td>
            </tr>
        {/if}
        {if !empty($customFieldLabel.biller_cf4)}
            <tr>
                <th class="details_screen">{$customFieldLabel.biller_cf4|htmlSafe}:</th>
                <td class="si_input">{$biller.custom_field4}</td>
            </tr>
        {/if}
        <tr>
            <th class="details_screen">{$LANG.logoFile}:</th>
            <td class="si_input">
                {if $biller.logo != ''}
                    <img src="templates/invoices/logos/{$biller.logo}" alt="{$biller.logo}">
                    <br/>
                    {$biller.logo}
                {/if}
            </td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.invoiceFooter}:</th>
            <td class="si_input">{$biller.footer}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.notes}:</th>
            <td class="si_input">{$biller.notes}</td>
        </tr>
        <tr>
            <th class="details_screen">{$LANG.enabled}:</th>
            <td class="si_input">{$biller.enabled_text}</td>
        </tr>
    </table>
</div>
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=billers&amp;view=edit&amp;id={$biller.id}" class="positive">
        <img src="images/report_edit.png" alt=""/>
        {$LANG.edit}
    </a>
    <a href="index.php?module=billers&amp;view=manage" class="negative">
        <img src="images/cross.png" alt=""/>
        {$LANG.cancel}
    </a>
</div>
