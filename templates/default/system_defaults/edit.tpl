<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=system_defaults&amp;view=save" >
<div class="si_center">
    <h3>{$LANG.edit} {$description|htmlSafe}</h3>
</div>
<div class="si_form">
    <table>
        <tr>
            <th>{$description|htmlSafe}</th>
            <td>{$value}</td>
        </tr>
    </table>
</div>
<br />
<div class="si_toolbar si_toolbar_form">
    {if isset($default)}
        <button type="submit" class="positive" name="submit" value="{$LANG.save}">
            <img class="button_img" src="images/tick.png" alt=""/>
            {$LANG.save}
        </button>
    {/if}
    <a href="index.php?module=system_defaults&amp;view=manage" class="negative">
        <img src="images/cross.png" alt=""/>
        {$LANG.cancel}
    </a>
</div>
<input type="hidden" name="name" value="{if isset($default)}{$default|htmlSafe}{/if}">
<input type="hidden" name="op" value="update_system_defaults"/>
</form>
