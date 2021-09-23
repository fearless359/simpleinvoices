<div class="grid__container grid__head-10">
    <label for="salesRepId" class="cols__2-span-3 align__text-right margin__right-1">{$LANG.salesRepresentative}:</label>
    <select name="salesRep" id="salesRepId" class="cols__5-span-4">
        <option value="">{$LANG.allUc}</option>
        {foreach $salesReps as $listSalesRep}
            <option {if $listSalesRep == $salesRep}selected{/if} value="{$listSalesRep}">{$listSalesRep}</option>
        {/foreach}
    </select>
</div>
