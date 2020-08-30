{*
 *  Script: manage.tpl
 *      Extensions manage template
 *
 *  Authors:
 *      Justin Kelly, Ben Brown, Marcel van Dorp
 *
 *  Last edited:
 *      2018-12-19 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *}
{if $number_of_rows == 0}
    <p><em>No extensions registered</em></p>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.name}</th>
            <th>{$LANG.description_uc}</th>
            <th>{$LANG.status}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $extensions as $extension}
            <tr>
                <td class="si_center">
                    {if $extension.name == 'core'}
                        {$LANG.always_enabled}
                    {else}
                        {if $extension.registered == $smarty.const.ENABLED}
                            <a class="index_table" title="{$extension.plugin_unregister}"
                               href="index.php?module=extensions&amp;view=register&amp;id={$extension.id}&amp;action=unregister">
                                <span style="display:none">{$extension.plugin_unregister}</span>
                                {$extension.image}
                            </a>
                            &nbsp;
                            {if $extension.enabled == $smarty.const.ENABLED}
                                <a class="index_table" title="{$extension.plugin_disable}"
                                   href="index.php?module=extensions&amp;view=manage&amp;id={$extension.id}&amp;action=toggle">
                                    <span style="display:none">{$extension.plugin_disable}</span>
                                    {$lights[2]}
                                </a>
                            {else}
                                <a class="index_table" title="{$extension.plugin_enable}"
                                   href="index.php?module=extensions&amp;view=manage&amp;id={$extension.id}&amp;action=toggle">
                                    <span style="display:none">{$extension.plugin_enable}</span>
                                    {$lights[2]}
                                </a>
                            {/if}
                        {else}
                            <a class="index_table" title="{$extension.plugin_registered}"
                               href="index.php?module=extensions&amp;view=register&amp;name={$extension.name}&amp;action=register&amp;description={$extension['description']}">
                                <span style="display:none">{$extension.plugin_registered}</span>
                                {$extension.image}
                            </a>
                        {/if}
                    {/if}
                </td>
                <td>{$extension.name}</td>
                <td>{$extension.description}</td>
                <td class="si_center">
                    <span style="display: none;">{$extension.enabled}{$extension.registered}</span>
                    {$lights[$extension.enabled]}&nbsp;{$plugins[$extension.registered]}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [3, "desc"],
                    [1, 'asc']
                ],
                "columnDefs": [
                    {"targets": 0, "orderable": false}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
