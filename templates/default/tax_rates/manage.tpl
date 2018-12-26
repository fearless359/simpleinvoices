{*
 *  Script: manage.tpl
 *      Tax Rates manage template
 *
 *  Authors:
 *      Justin Kelly, Ben Brown
 *
 *  Last edited:
 *      2018-12-12 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *}

<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=tax_rates&amp;view=add" class="">
        <img src="images/common/add.png" alt=""/>
        {$LANG.add_new_tax_rate}
    </a>
</div>

{if $number_of_rows == 0}
    <div class="si_message">{$LANG.no_tax_rates}</div>
{else}
    <table id="si-data-table" class="display">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.description}</th>
            <th>{$LANG.rate}</th>
            <th>{$LANG.enabled}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $taxes as $tax}
            <tr>
                <td class="si_center">
                    <a class="index_table" title="{$tax['vname']}"
                       href="index.php?module=tax_rates&amp;view=details&amp;id={$tax['tax_id']}&amp;action=view">
                        <img src="images/common/view.png" height="16" border="-5px" alt="view"/>
                    </a>
                    <a class="index_table" title="{$tax['ename']}"
                       href="index.php?module=tax_rates&amp;view=details&amp;id={$tax['tax_id']}&amp;action=edit">
                        <img src="images/common/edit.png" height="16" border="-5px" alt="edit"/>
                    </a>
                </td>
                <td>{$tax['tax_description']}</td>
                <td class="si_right">{$tax['tax_percentage']|siLocal_number} {$tax['type']}</td>
                <td class="si_center">
                    <span style="display:none">{$tax['enabled_text']}</span>
                    <img src="{$tax['image']}" alt="enabled">
                </td>
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
