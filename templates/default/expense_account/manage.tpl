{*
 *  Script: manage.tpl
 *      Products manage template
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
<div class="align__text-center">
    <a href="index.php?module=expense_account&amp;view=create" class="button positive">
        <img src="images/add.png" alt=""/>{$LANG.addNewExpenseAccount}
    </a>
</div>
<br />
{if $numberOfRows == 0 }
    {$display_block}
{else}
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th class="align__text-center">{$LANG.actions}</th>
            <th>{$LANG.nameUc}</th>
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
                    { "data": "name" }
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [1, "asc"]
                ],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
