{*
 *  Script: manage.tpl
 *      Products manage template
 *
 *  Modified:
 *      2018-12-13 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=products&amp;view=add" class="">
        <img src="images/famfam/add.png" alt=""/>
        {$LANG.add_new_product}
    </a>
</div>

{if $number_of_rows == 0 }
    <div class="si_message">{$LANG.no_products}</div>
{else}
    <table id="si-data-table" class="display">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.name}</th>
            <th>{$LANG.unit_price}</th>
            {if $defaults['inventory'] == $smarty.const.ENABLED}
                <th>{$LANG.quantity}</th>
            {/if}
            <th>{$LANG.enabled}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $products as $product}
            <tr>
                <td class="si_center">
                    <a class="index_table" title="{$product['vname']}"
                       href="index.php?module=products&amp;view=details&amp;id={$product['id']}&amp;action=view">
                        <img src="images/common/view.png" alt="{$product['vname']}" height="16" border="-5px" />
                    </a>
                    <a class="index_table" title="{$product['ename']}"
                       href="index.php?module=products&amp;view=details&amp;id={$product['id']}&amp;action=edit">
                        <img src="images/common/edit.png" alt="{$product['ename']}" height="16" border="-5px"/>
                    </a>
                </td>
                <td>{$product['description']}</td>
                <td class="si_right">{$product['unit_price']|siLocal_currency}</td>
                {if $defaults['inventory'] == $smarty.const.ENABLED}
                    <td class="si_right">{$product['quantity']|siLocal_number}</td>
                {/if}
                <td class="si_center">
                    <span style="display:none">{$product['enabled_text']}</span>
                    <img src="{$product['image']}" alt="enabled">
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
                    [4, "desc"],
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
