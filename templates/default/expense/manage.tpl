{*
 *  Script: manage.tpl
 * 	    Products manage template
 *
 *  License:
 *	    GPL v3 or above
 *
 *  Website:
 *	    https://simpleinvoices.group
 *}
<div class="si_toolbar si_toolbar_form">
    <a href="{$add_button_link}" class="positive">
        <img src="../../../images/add.png" alt=""/>
        {$add_button_msg}
    </a>
</div>
<br />
{if $number_of_rows == 0 }
    {$display_block}
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.dateUc}</th>
            <th>{$LANG.amountUc}</th>
            <th>{$LANG.tax}</th>
            <th>{$LANG.totalUc}</th>
            <th>{$LANG.expenseAccounts}</th>
            <th>{$LANG.biller}</th>
            <th>{$LANG.customer}</th>
            <th>{$LANG.invoiceUc}</th>
            <th>{$LANG.status}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $expenses as $expense}
            <tr>
                <td class="si_center">
                    <a class='index_table' title='{$expense['vname']}'
                       href='index.php?module=expense&amp;view=view&amp;id={$expense['EID']}'>
                        <img src='../../../images/view.png' class='action' alt="{$expense['vname']}"/>
                    </a>
                    <a class='index_table' title='{$expense['ename']}'
                        href='index.php?module=expense&amp;view=edit&amp;id={$expense['EID']}'>
                        <img src='../../../images/edit.png' class='action' alt="{$expense['ename']}"/>
                    </a>
                </td>
                <td>{$expense['date']}</td>
                <td class="right">{$expense['amount']|utilCurrency}</td>
                <td class="right">
                {if (!empty($expense['tax']))}
                    {$expense['tax']|utilCurrency}
                {/if}
                </td>
                <td class="right">
                {if (!empty($expense['total']))}
                    {$expense['total']|utilCurrency}
                {/if}
                </td>
                <td>{$expense['ea_name']}</td>
                <td>{$expense['b_name']}</td>
                <td>{$expense['c_name']}</td>
                <td class="si_right">{$expense['iv_id']}</td>
                <td>{$expense['status_wording']}</td>
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
                    [1, "desc"]
                ],
                "columnDefs": [
                    {"targets": 0,"width": "10%", "className": 'dt-body-center', "orderable": false},
                    {"targets": 1,"width": "10%", "className": 'dt-body-center'},
                    {"targets": 2,"className": 'dt-body-right'},
                    {"targets": 3,"className": 'dt-body-right'},
                    {"targets": 4,"className": 'dt-body-right'}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
