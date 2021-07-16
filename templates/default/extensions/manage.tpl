{*
 *  Script: manage.tpl
 *      Extensions manage template
 *
 *  Authors:
 *      Justin Kelly, Ben Brown, Marcel van Dorp
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
{if $numberOfRows == 0}
    <p><em>{$LANG.noUc} {$LANG.extensons} {$LANG.registered}</em></p>
{else}
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th class="align__text-center">{$LANG.actions}</th>
            <th>{$LANG.nameUc}</th>
            <th>{$LANG.descriptionUc}</th>
            <th class="align__text-center">{$LANG.status}</th>
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
                    { "data": "action" },
                    { "data": "name" },
                    { "data": "description" },
                    { "data": "status" }
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [3, "desc"],
                    [1, 'asc']
                ],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false},
                    {"targets": 3, "className": 'dt-body-center'},
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
