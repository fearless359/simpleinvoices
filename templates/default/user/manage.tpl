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
            <th class="si_center">{$LANG.actions}</th>
            <th>{$LANG.username}</th>
            <th>{$LANG.email}</th>
            <th>{$LANG.role}</th>
            <th class="si_center">{$LANG.enabled}</th>
            <th>{$LANG.userId}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $users as $user}
            <tr>
                <td class="si_center">
                    <a class="index_table" title="{$user['vname']}"
                       href="index.php?module=user&amp;view=view&amp;id={$user['id']}">
                        <img src="images/view.png" class="action" alt="" />
                    </a>
                    <a class="index_table" title="{$user['ename']}"
                       href="index.php?module=user&amp;view=edit&amp;id={$user['id']}">
                        <img src="images/edit.png" class="action" alt="" />
                    </a>
                </td>
                <td>{$user['username']}</td>
                <td>{$user['email']}</td>
                <td>{$user['role_name']}</td>
                <td class="si_center">
                    <span style="display: none">{$user['enabled_text']}</span>
                    <img src="{$user['image']}"  alt='{$user['enabled_text']}' title='{$user['enabled_text']}' />
                </td>
                <td>{$user['uid']}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <script>
        {literal}
        $(document).ready(function() {
            $('#si-data-table').DataTable({
                "lengthMenu": [[15,20,25,30, -1], [15,20,25,30,"All"]],
                "order": [
                    [4, "desc"],
                    [1, "asc"]
                ],
                "columnDefs": [
                    { "targets": 0, "orderable": false }
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
