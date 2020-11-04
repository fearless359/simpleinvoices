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
    <a href="index.php?module=expense_account&amp;view=create" class="positive">
        <img src="images/add.png" alt=""/>
        {$LANG.addNewExpenseAccount}
    </a>
</div>
<br />
{if $numberOfRows == 0 }
    {$display_block}
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.nameUc}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $expense_accounts as $expense_account}
            <tr>
                <td class="si_center">
                    <a class='index_table' title='{$LANG.view}' href='index.php?module=expense_account&amp;view=view&amp;id={$expense_account.id}'>
                        <img src='images/view.png' style="height:16px;border:0" alt="{$LANG.view}" />
                    </a>
                    <a class='index_table' title='{$LANG.edit}' href='index.php?module=expense_account&amp;view=edit&amp;id={$expense_account.id}'>
                        <img src='images/edit.png' style="height:16px;border:0" alt="{$LANG.edit}" />
                    </a>
                </td>
                <td>{$expense_account.name}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [1, "asc"]
                ],
                "columnDefs": [
                    {"targets": 0, "orderable": false}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
