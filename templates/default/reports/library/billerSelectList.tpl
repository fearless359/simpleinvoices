<div class="grid__container grid__head-10">
    <label for="billerId" class="cols__2-span-3">{$LANG.billersUc}:</label>
    <select name="billerId" id="billerId" class="cols__5-span-5">
        <option {if empty($billerId)}selected{/if} value=0>{$LANG.allUc}&nbsp;{$LANG.billersUc}</option>
        {foreach $billers as $biller}
            <option {if $biller.id == $billerId}selected{/if} value={$biller.id}>{$biller.name}</option>
        {/foreach}
    </select>
</div>
