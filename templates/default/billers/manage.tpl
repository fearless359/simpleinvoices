{*
 *  Script: manage.tpl
 *      Biller manage template
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
    <a href="index.php?module=billers&amp;view=create" class="">
        <button><img src="images/add.png" alt=""/>{$LANG.addNewBiller}</button>
    </a>
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noBillers}</div>
{else}
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th class="align__text-center">{$LANG.actions}</th>
            <th>{$LANG.nameUc}</th>
            <th>{$LANG.street}</th>
            <th>{$LANG.city}</th>
            <th class="align__text-center">{$LANG.state}</th>
            <th class="align__text-right">{$LANG.zip}</th>
            <th>{$LANG.email}</th>
            <th class="align__text-center">{$LANG.enabled}</th>
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
                    { "data": "streetAddress" },
                    { "data": "city"},
                    { "data": "state" },
                    { "data": "zipCode"},
                    { "data": "email"},
                    { "data": "enabled"},
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [7, "desc"],
                    [1, "asc"]
                ],
                "columnDefs": [
                    { "targets": 0, "className": 'dt-body-center', "orderable": false},
                    { "targets": 4, "className": 'dt-body-center' },
                    { "targets": 5, "className": 'dt-body-right' },
                    { "targets": 7, "className": 'dt-body-center' }
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
