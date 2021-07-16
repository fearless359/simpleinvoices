<div class='grid__area'>
    <div>&nbsp;</div>
    <div class="grid__container grid__head-6">
        <div class="cols__1-span-6 bold">{$LANG.descriptionUc}</div>
    </div>
    <div class="grid__container grid__head-6">
        <div class="cols__1-span-6">{$invoiceItems[0].description|outHtml}</div>
    </div>
    <div>&nbsp;</div>
    <div class="grid__container grid__head-6">
        <div class="cols__1-span-2 bold">{$LANG.salesRepresentative}:</div>
        <div class="cols__3-span-4">{$invoice.sales_representative}</div>
    </div>
    <div class="grid__container grid__head-6">
        <div>&nbsp;</div>
    </div>
    <div class="grid__container grid__head-6">
        <div class="cols__1-span-3"></div>
        <div class="align__text-right bold">{$LANG.grossTotal}</div>
        <div class="align__text-right bold">{$LANG.tax}</div>
        <div class="align__text-right bold">{$LANG.totalFullUc}</div>
    </div>
    <div class="grid__container grid__head-6">
        <div class="cols__1-span-3"></div>
        <div class="align__text-right">{$invoiceItems[0].gross_total|utilCurrency:$locale:$currencyCode}</div>
        <div class="align__text-right">{$invoiceItems[0].tax_amount|utilCurrency:$locale:$currencyCode}</div>
        <div class="align__text-right underline">{$invoiceItems[0].total|utilCurrency:$locale:$currencyCode}</div>
    </div>
    <div class="grid__container grid__head-6">
        <div>&nbsp;</div>
    </div>
</div>
