<div class="grid__container grid__head-10">
    <div class="cols__5-span-5">
        <div class="grid__container grid__head-checkbox">
            <input type="checkbox" name="includePaidInvoices" id="includePaidInvoicesId" class="cols__1-span-1 margin__top-0-75"
                    {if isset($includePaidInvoices) && $includePaidInvoices == "yes"} checked {/if} value="yes"/>
            <label for="includePaidInvoicesId" class="cols__2-span-1">{$LANG.includeUc} {$LANG.paid} {$LANG.invoices}</label>
        </div>
    </div>
</div>
