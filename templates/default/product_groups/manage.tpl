{*
 *  Script: manage.tpl
 *      Biller manage template
 *
 *  License:
 *      GPL v3 or above
 *
 *  Last modified:
 *      2020-10-12 by Richard Rowley
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=product_groups&amp;view=create" class="">
        <img src="images/add.png" alt=""/>
        {$LANG.addUc} {$LANG.newUc} {$LANG.productGroupUc}
    </a>
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noUc} {$LANG.productGroupsUc}</div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th class="si_center">{$LANG.actions}</th>
            <th>{$LANG.nameUc}</th>
            <th class="si_right">{$LANG.markupUc}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $productGroups as $productGroup}
            <tr>
                <td class="si_center">
                    <a class="index_table" title="{$productGroup.vname}" href="index.php?module=product_groups&amp;view=view&amp;name={$productGroup.name}">
                        <img src="images/view.png" class="action" alt="view"/>
                    </a>
                    <a class="index_table" title="{$productGroup.ename}" href="index.php?module=product_groups&amp;view=edit&amp;name={$productGroup.name}">
                        <img src="images/edit.png" class="action" alt="edit"/>
                    </a>
                </td>
                <td>{$productGroup.name}</td>
                <td class="si_right">{$productGroup.markup}%</td>
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
