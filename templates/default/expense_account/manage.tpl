{*
 *  Script: manage.tpl
 *      Products manage template
 *
 *  Last Modified:
 *      2018-10-27 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=expense_account&amp;view=add" class="positive">
        <img src="images/famfam/add.png" alt=""/>
        {$LANG.add_new_expense_account}
    </a>
</div>
<br />
{if $number_of_rows == 0 }
    {$display_block}
{else}
    <table id="manageGrid" style="display: none"></table>
    {include file='modules/expense_account/manage.js.php'}
{/if}
