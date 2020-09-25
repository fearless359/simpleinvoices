<tr>
    <td class="details_screen" style="text-align:right; padding-right: 10px; white-space: nowrap; width: 47%;">
        <label for="displayDetailId">{$LANG.display} {$LANG.detail}:</label>
    </td>
    <td><input type="checkbox" name="displayDetail" id="displayDetailId"
        {if isset($smarty.post.displayDetail) && $smarty.post.displayDetail == "yes"} checked {/if} value="yes"/>
    </td>
</tr>
