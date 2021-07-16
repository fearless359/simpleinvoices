<div class="grid__container grid__head-10">
    <div class="cols__5-span-5">
        <div class="grid__container grid__head-checkbox">
            <input type="checkbox" name="displayDetail" id="displayDetailId" class="cols__1-span-1 margin__top-0-75"
                    {if isset($smarty.post.displayDetail) && $smarty.post.displayDetail == "yes"} checked {/if} value="yes"/>
            <label for="displayDetailId" class="cols__2-span-1">{$LANG.display} {$LANG.detail}</label>
        </div>
    </div>
</div>
