{*
 *  Script: manage.tpl
 *      Manage payment types template
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin, Ben Brown
 *
 *  Last edited:
 *      2016-08-15
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=payment_types&amp;view=create">
        <img src="images/add.png" alt=""/>
        {$LANG.addNewPaymentType}
    </a>
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noPaymentTypes}</div>
{else}
    <table id="si-data-table" class="display responsive compact">
        <thead>
        <tr>
            <th class="si_center">{$LANG.actions}</th>
            <th class="si_left">{$LANG.descriptionUc}</th>
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
