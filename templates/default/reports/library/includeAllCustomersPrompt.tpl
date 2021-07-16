<div class="grid__container grid__head-10">
    <div class="cols__5-span-5">
        <div class="grid__container grid__head-checkbox">
            <input type="checkbox" name="includeAllCustomers" id="includeAllCustomersId" class="cols__1-span-1 margin__top-0-75"
                    {if $includeAllCustomers == "yes"} checked {/if} value="yes"/>
            <label for="includeAllCustomersId" class="cols__2-span-1">{$LANG.includeUc} {$LANG.all} {$LANG.customers}</label>
        </div>
    </div>
</div>
