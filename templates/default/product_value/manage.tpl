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
        <img src="images/add.png" alt=""/>
        {$LANG.addProductValue}
    </a>
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noProductValues}</div>
{else}
    <table id="si-data-table" class="display responsive compact">
        <thead>
        <tr>
            <th class="si_center">{$LANG.actions}</th>
            <th class="si_left">{$LANG.attribute}</th>
            <th class="si_left">{$LANG.value}</th>
            <th class="si_center">{$LANG.enabled}</th>
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
                    { "data": "action" },
                    { "data": "name" },
                    { "data": "value"},
                    { "data": "enabled" }
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [3, "desc"],
                    [1, "asc"],
                    [2, "asc"]
                ],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false},
                    {"targets": 3, "className": 'dt-body-center'}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
