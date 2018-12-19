{*
 *  Script: manage.tpl
 * 	    Invoice Product Values manage template
 *
 *  Modified:
 *      2018-12-15 by Richard Rowley
 *
 *  License:
 *	    GPL v3 or above
 *}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=product_value&amp;view=add" class="">
        <img src="images/common/add.png" alt=""/>
        {$LANG.add_product_value}
    </a>
</div>
{if $number_of_rows == 0}
    <div class="si_message">{$LANG.no_product_values}</div>
{else}
    <table id="si-data-table" class="display">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.attribute}</th>
            <th>{$LANG.value}</th>
            <th>{$LANG.enabled}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $product_values as $product_value}
            <tr>
                <td class="si_center">
                    <a class="index_table" title="{$product_value['vname']}"
                       href="index.php?module=product_value&amp;view=details&amp;id={$product_value['id']}&amp;action=view">
                        <img src="images/common/view.png" alt="{$product_value['vname']}" height="16" border="-5px" />
                    </a>
                    <a class="index_table" title="{$product_value['ename']}"
                       href="index.php?module=product_value&amp;view=details&amp;id={$product_value['id']}&amp;action=edit">
                        <img src="images/common/edit.png" alt="{$product_value['ename']}" height="16" border="-5px"/>
                    </a>
                </td>
                <td>{$product_value['name']}</td>
                <td>{$product_value['value']}</td>
                <td class="si_center">
                    <!-- Span is here to allow field to be sorted -->
                    <span style="display:none">{$product_value['enabled_text']}</span>
                    <img src="{$product_value['image']}" alt="enabled">
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
                    [3, "desc"],
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
