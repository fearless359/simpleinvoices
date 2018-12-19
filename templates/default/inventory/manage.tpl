{*
 *  Script: manage.tpl
 *      Manage invoices template
 *
 *  Modified:
 *      2018-12-14 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
*}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=inventory&amp;view=add" class="">
        <img src="images/common/add.png" alt=""/>
        {$LANG.new_inventory_movement}
    </a>
</div>
{if $number_of_rows == 0}
    <div class="si_message">{$LANG.no_inventory_movements}</div>
{else}
    <table id="si-data-table" class="display">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.date_upper}</th>
            <th>{$LANG.product}</th>
            <th>{$LANG.quantity}</th>
            <th>{$LANG.cost}</th>
            <th>{$LANG.total_cost}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $inventories as $inventory}
            <tr>
                <td class="si_center">
                    <a class="index_table" title="{$inventory['vname']}"
                       href="index.php?module=inventory&amp;view=details&amp;id={$inventory['id']}&amp;action=view">
                        <img src="images/common/view.png" alt="{$inventory['vname']}" height="16" border="-5px" />
                    </a>
                    <a class="index_table" title="{$inventory['ename']}"
                       href="index.php?module=inventory&amp;view=details&amp;id={$inventory['id']}&amp;action=edit">
                        <img src="images/common/edit.png" alt="{$inventory['ename']}" height="16" border="-5px"/>
                    </a>
                </td>
                <td class="si_center">{$inventory['date']}</td>
                <td>{$inventory['description']}</td>
                <td class="si_right">{$inventory['quantity']|siLocal_number_trim:0}</td>
                <td class="si_right">{$inventory['cost']|siLocal_currency}</td>
                <td class="si_right">{$inventory['total_cost']|siLocal_currency}</td>
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
                    [2, "asc"]
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

