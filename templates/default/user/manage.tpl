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
    <a href="index.php?module=user&amp;view=add" class="">
        <img src="images/common/add.png" alt=""/>
        {$LANG.user_add}
    </a>
</div>

{if $number_of_rows == 0}
    <div class="si_message">{$LANG.no_users}</div>
{else}
    <table id="si-data-table" class="display" >
        <thead>
        <tr>
            <th class="si_center">{$LANG.actions}</th>
            <th>{$LANG.username}</th>
            <th>{$LANG.email}</th>
            <th>{$LANG.role}</th>
            <th class="si_center">{$LANG.enabled}</th>
            <th>{$LANG.user_id}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $users as $user}
            <tr>
                <td class="si_center">
                    <a class="index_table" title="{$user['vname']}"
                       href="index.php?module=user&amp;view=details&amp;id={$user['id']}&amp;action=view">
                        <img src="images/common/view.png" height="16" border="-5px" />
                    </a>
                    <a class="index_table" title="{$user['ename']}"
                       href="index.php?module=user&amp;view=details&amp;id={$user['id']}&amp;action=edit">
                        <img src="images/common/edit.png" height="16" border="-5px" />
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