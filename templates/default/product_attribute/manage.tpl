{*
 *  Script: manage.tpl
 * 	  Product attributes manage template
 *
 *  Authors:
 *	  Justin Kelly, Ben Brown
 *
 *  Last edited:
 *    2018-12-15 by Richard Rowley
 *
 *  License:
 *	  GPL v3 or above
 *}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=product_attribute&amp;view=create" class="">
        <img src="images/add.png" alt=""/>
        {$LANG.addProductAttribute}
    </a>
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noProductAttributes}</div>
{else}
    <table id="si-data-table" class="display responsive compact">
        <thead>
        <tr>
            <th class="si_center">{$LANG.actions}</th>
            <th class="si_left">{$LANG.nameUc}</th>
            <th class="si_left">{$LANG.type}</th>
            <th class="si_center">{$LANG.enabled}</th>
            <th class="si_center">{$LANG.visible}</th>
        </tr>
        </thead>
    </table>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "orderClasses": false,
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
