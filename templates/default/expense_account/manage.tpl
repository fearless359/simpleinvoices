{*
 *  Script: manage.tpl
 *      Products manage template
 *
 *  Last Modified:
 *      2018-10-27 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=expense_account&amp;view=create" class="positive">
        <img src="images/add.png" alt=""/>
        {$LANG.addNewExpenseAccount}
    </a>
</div>
<br />
{if $numberOfRows == 0 }
    {$display_block}
{else}
    <table id="si-data-table" class="display responsive compact">
        <thead>
        <tr>
            <th class="si_center">{$LANG.actions}</th>
            <th class="si_left">{$LANG.nameUc}</th>
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
