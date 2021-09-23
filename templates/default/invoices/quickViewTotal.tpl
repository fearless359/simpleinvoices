<div class='grid__area'>
    <div>&nbsp;</div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-6 bold">{$LANG.descriptionUc}:</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-9">{$invoiceItems[0].description|outHtml}</div>
    </div>
    <div>&nbsp;</div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-2 bold align__text-right margin__right-1">{$LANG.salesRepresentative}:</div>
        <div class="cols__3-span-4">{$invoice.sales_representative}</div>
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
</div>
