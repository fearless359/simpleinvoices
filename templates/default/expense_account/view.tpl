<br/>
<div class="si_form" id="si_form_cust">
    <div class="si_cust_info">
        <table class="center">
        <tr>
                <th class="details_screen">{$LANG.nameUc}:&nbsp;</th>
                <td>{$expense_account.name}</td>
            </tr>
        </table>
        <br/>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=expense_account&amp;view=edit&amp;id={$expense_account.id}" class="positive">
                <img src="../../../images/add.png" alt=""/>
                {$LANG.edit}
            </a>
            <a href="index.php?module=expense_account&amp;view=manage"
               class="negative"> <img src="../../../images/cross.png" alt="{$LANG.cancel}" />
                {$LANG.cancel}
            </a>
        </div>
    </div>
</div>
