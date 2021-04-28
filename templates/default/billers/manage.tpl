{*
 *  Script: manage.tpl
 *      Biller manage template
 *
 *  License:
 *      GPL v3 or above
 *
 *  Last modified:
 *      2018-12-10 by Richard Rowley
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=billers&amp;view=create" class="">
        <img src="images/add.png" alt=""/>
        {$LANG.addNewBiller}
    </a>
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noBillers}</div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.nameUc}</th>
            <th>{$LANG.street}</th>
            <th>{$LANG.city}</th>
            <th>{$LANG.state}</th>
            <th>{$LANG.zip}</th>
            <th>{$LANG.email}</th>
            <th>{$LANG.enabled}</th>
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
                    { "targets": 5, "className": 'dt-body-right' },
                    { "targets": 7, "className": 'dt-body-center' }
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
