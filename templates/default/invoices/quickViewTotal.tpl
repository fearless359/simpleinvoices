<div class='row'>
    <div class="col__20">
        <span class="label">{$LANG.descriptionUc}:</span>
    </div>
    <div class="col__80">
        <span class="inputText">{$invoiceItems[0].description|outHtml}</span>
    </div>
</div>
<div class="grid__area">
    <div class="grid__container grid__head-10 margin__top-1">
        <div class="cols__1-span-7"></div>
        <div class="cols__8-span-1">
            <div class="align__text-right bold align__text-right">{$LANG.grossTotal}</div>
        </div>
        <div class="cols__9-span-1">
            <div class="align__text-right bold align__text-right">{$LANG.tax}</div>
        </div>
        <div class="cols__10-span-1">
            <div class="align__text-right bold align__text-right">{$LANG.totalUc}</div>
        </div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-7"></div>
        <div class="cols__8-span-1">
            <div class="align__text-right">{$invoiceItems[0].gross_total|utilCurrency:$locale:$currencyCode}</div>
        </div>
        <div class="cols__9-span-1">
            <div class="align__text-right">{$invoiceItems[0].tax_amount|utilCurrency:$locale:$currencyCode}</div>
        </div>
        <div class="cols__10-span-1">
            <div class="align__text-right underline">{$invoiceItems[0].total|utilCurrency:$locale:$currencyCode}</div>
        </div>
    </div>
</div>
{if !empty($invoice.slaes_representative)}
    <div class="row">
        <div class="col__20">
            <span class="label">{$LANG.salesRep}:</span>
        </div>
        <div class="col__80">
            <span class="inputText">{$invoice.sales_representative}</span>
        </div>
    </div>
{/if}
