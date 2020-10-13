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
    <a href="index.php?module=product_value&amp;view=create" class="">
        <img src="../../../images/add.png" alt=""/>
        {$LANG.addProductValue}
    </a>
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noProductValues}</div>
{else}
    <table id="si-data-table" class="display compact">
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
                       href="index.php?module=product_value&amp;view=view&amp;id={$product_value['id']}">
                        <img src="../../../images/view.png" alt="{$product_value['vname']}" class="action" />
                    </a>
                    <a class="index_table" title="{$product_value['ename']}"
                       href="index.php?module=product_value&amp;view=edit&amp;id={$product_value['id']}">
                        <img src="../../../images/edit.png" alt="{$product_value['ename']}" class="action"/>
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
