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
            <th>{$LANG.actions}</th>
            <th>{$LANG.nameUc}</th>
            <th class="si_right">{$LANG.markupUc}</th>
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
                    { "data": "markUp"}
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [1, "asc"]
                ],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false},
                    {"targets": 2, "className": 'dt-body-right'}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
