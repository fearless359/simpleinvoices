{*
 *  Script: manage.tpl
 *      Manage payment types template
 *
 *  Authors:
 *      Justin Kelly, Nicolas Ruflin, Ben Brown
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
    <a href="index.php?module=payment_types&amp;view=create">
        <button><img src="images/add.png" alt="{$LANG.addNewPaymentType}"/>{$LANG.addNewPaymentType}</button>
    </a>
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noPaymentTypes}</div>
{else}
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th class="align__text-center">{$LANG.actions}</th>
            <th>{$LANG.descriptionUc}</th>
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
                "responsive": true,
                "columns": [
                    { "data": "action" },
                    { "data": "ptDescription" },
                    { "data": "enabled"}
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [2, "desc"],
                    [1, "asc"]
                ],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false},
                    {"targets": 2, "className": 'dt-body-center'}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
