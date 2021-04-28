{*
 *  Script: manage.tpl
 *      User manage template
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
    <a href="index.php?module=user&amp;view=create" class="">
        <img src="images/add.png" alt=""/>
        {$LANG.userAdd}
    </a>
</div>

{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noUsers}</div>
{else}
    <table id="si-data-table" class="display compact" >
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.username}</th>
            <th>{$LANG.email}</th>
            <th>{$LANG.role}</th>
            <th>{$LANG.enabled}</th>
            <th>{$LANG.userId}</th>
        </tr>
        </thead>
    </table>
    <script>
        {literal}
        $(document).ready(function() {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "orderClasses": false,
                "columns": [
                    { "data": "action" },
                    { "data": "userName" },
                    { "data": "email" },
                    { "data": "roleName"},
                    { "data": "enabled" },
                    { "data": "uid"},
                ],
                "lengthMenu": [[15,20,25,30, -1], [15,20,25,30,"All"]],
                "order": [
                    [4, "desc"],
                    [1, "asc"]
                ],
                "columnDefs": [
                    { "targets": 0, "className": 'dt-body-center', "orderable": false },
                    { "targets": 4, "className": 'dt-body-center' }
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
