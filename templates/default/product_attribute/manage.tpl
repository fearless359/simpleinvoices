{*
 *  Script: manage.tpl
 * 	    Product attributes manage template
 *
 *  Authors:
 *	    Justin Kelly, Ben Brown
 *
 *  Last modified:
 *      20210618 by Richard Rowley to add cell-border class to table tag.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
<div class="align__text-center margin__bottom-2">
    <a href="index.php?module=product_attribute&amp;view=create" class="">
        <button><img src="images/add.png" alt=""/>{$LANG.addProductAttribute}</button>
    </a>
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noProductAttributes}</div>
{else}
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th class="align__text-center">{$LANG.actions}</th>
            <th>{$LANG.nameUc}</th>
            <th>{$LANG.type}</th>
            <th class="align__text-center">{$LANG.enabled}</th>
            <th class="align__text-center">{$LANG.visible}</th>
        </tr>
        </thead>
    </table>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "orderClasses": false,
                "deferRender": true,
                "responsive": true,
                "columns": [
                    {"data": "action"},
                    {"data": "name"},
                    {"data": "typeName"},
                    {"data": "enabled"},
                    {"data": "visible"}
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [3, "desc"],
                    [4, "desc"],
                    [2, "asc"],
                    [1, "asc"]

                ],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false},
                    {"targets": [3, 4], "className": 'dt-body-center'}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
