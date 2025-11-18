{*
 *  Script: view.tpl
 *      Biller details template
 *
 *  Last edited:
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      20210615 by Rich Rowley to convert to grid layout.
 *      20180921 by Rich Rowley to add signature field.
 *
 *  License:
 *      GPL v3 or above
*}
<div class="flex__area">
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.billerName}:</div>
        <div>{$biller.name}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.street}:</div>
        <div>{$biller.street_address}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.street2}:</div>
        <div>{$biller.street_address2}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.city}:</div>
        <div>{$biller.city}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.zip}:</div>
        <div>{$biller.zip_code}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.state}:</div>
        <div>{$biller.state}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.country}:</div>
        <div>{$biller.country}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.mobilePhone}:</div>
        <div>{$biller.mobile_phone}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.phoneUc}:</div>
        <div>{$biller.phone}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.fax}:</div>
        <div>{$biller.fax}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.email}:</div>
        <div>{$biller.email}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.signature}:</div>
        <div>{$biller.signature|outHtml}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.paypalBusinessName}:</div>
        <div>{$biller.paypal_business_name}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.paypalNotifyUrl}:</div>
        <div>{$biller.paypal_notify_url}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.paypalReturnUrl}:</div>
        <div>{$biller.paypal_return_url}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.ewayCustomerId}:</div>
        <div>{$biller.eway_customer_id}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.paymentsGatewayApiId}:</div>
        <div>{$biller.paymentsgateway_api_id}</div>
    </div>
    {if !empty($customFieldLabel.biller_cf1)}
        <div class="flex__container flex__start">
            <div class="bold margin__right-1">{$customFieldLabel.biller_cf1|htmlSafe}:</div>
            <div>{$biller.custom_field1}</div>
        </div>
    {/if}
    {if !empty($customFieldLabel.biller_cf2)}
        <div class="flex__container flex__start">
            <div class="bold margin__right-1">{$customFieldLabel.biller_cf2|htmlSafe}:</div>
            <div>{$biller.custom_field2}</div>
        </div>
    {/if}
    {if !empty($customFieldLabel.biller_cf3)}
        <div class="flex__container flex__start">
            <div class="bold margin__right-1">{$customFieldLabel.biller_cf3|htmlSafe}:</div>
            <div>{$biller.custom_field3}</div>
        </div>
    {/if}
    {if !empty($customFieldLabel.biller_cf4)}
        <div class="flex__container flex__start">
            <div class="bold margin__right-1">{$customFieldLabel.biller_cf4|htmlSafe}:</div>
            <div>{$biller.custom_field4}</div>
        </div>
    {/if}
    {if $biller.logo != ''}
        <div class="flex__container flex__start">
            <div class="bold margin__right-1 margin__top-2">{$LANG.logoFile}:</div>
            <div class="margin__right-2 margin__top-2">{$biller.logo}</div>
            <div>
                <img src="templates/invoices/logos/{$biller.logo}" alt="{$biller.logo}">
            </div>
        </div>
    {/if}
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.invoiceFooter}:</div>
        <div>{$biller.footer|outHtml}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.notes}:</div>
        <div>{$biller.notes|outHtml}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.enabled}:</div>
        <div>{$biller.enabled_text}</div>
    </div>
</div>
<br/>
<div class="align__text-center">
    <a href="index.php?module=billers&amp;view=edit&amp;id={$biller.id}" class="button positive">
        <img src="images/report_edit.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=billers&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
