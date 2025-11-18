<div class='flex__area'>
    <div>&nbsp;</div>
    <div class="flex__container flex__start">
        <div class="bold">{$LANG.descriptionUc}:</div>
    </div>
    <div class="flex__container flex__start">
        <div>{$invoiceItems[0].description|outHtml}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-7"></div>
        <div class="align__text-right bold align__text-right">{$LANG.grossTotal}</div>
        <div class="align__text-right bold align__text-right">{$LANG.tax}</div>
        <div class="align__text-right bold align__text-right">{$LANG.totalUc}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-7"></div>
        <div class="align__text-right">{$invoiceItems[0].gross_total|utilCurrency:$locale:$currencyCode}</div>
        <div class="align__text-right">{$invoiceItems[0].tax_amount|utilCurrency:$locale:$currencyCode}</div>
        <div class="align__text-right underline">{$invoiceItems[0].total|utilCurrency:$locale:$currencyCode}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.salesRepresentative}:</div>
        <div>{$invoice.sales_representative}</div>
    </div>
</div>
