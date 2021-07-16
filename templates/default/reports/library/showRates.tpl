<div class="grid__container grid__head-10">
    <div class="cols__5-span-5">
        <div class="grid__container grid__head-checkbox">
            <input type="checkbox" name="showRates" id="showRatesId" class="cols__1-span-1 margin__top-0-75"
                    {if isset($smarty.post.showRates) && $smarty.post.showRates == "yes"}checked{/if} value="yes"/>
            <label for="showRatesId" class="cols__2-span-1">{$LANG.showUc} {$LANG.ratesUc}</label>
        </div>
    </div>
</div>
