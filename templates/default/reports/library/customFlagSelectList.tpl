<div class="grid__container grid__head-10">
    <label for="customFlagId" class="cols__2-span-3 align__text-right margin__right-1">{$LANG.excludeUc} {$LANG.customFlagUc} #:</label>
    <select name="customFlag" id="customFlagId" class="cols__5-span-2">
        <option value="0" {if $customFlag == 0} selected {/if}>{$LANG.none}</option>
        {foreach $customFlagLabels as $ndx => $label}
            {if $label != ''}
                <option value="{$ndx+1}" {if $customFlag - 1 == $ndx} selected {/if}>{$ndx+1}&nbsp;-&nbsp;{$label}</option>
            {/if}
        {/foreach}
    </select>
</div>
