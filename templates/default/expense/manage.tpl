{*
 *  Script: manage.tpl
 * 	    Products manage template
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *	    https://simpleinvoices.group *}
<div class="si_toolbar si_toolbar_form">
    <a href="{$add_button_link}" class="positive">
        <img src="images/famfam/add.png" alt=""/>
        {$add_button_msg}
    </a>
</div>
<br/>
{if $number_of_rows == 0 }
    {$display_block}
{else}
    <table id="manageGrid" style="display:none"></table>
    {include file='modules/expense/manage.js.php'}
{/if}
