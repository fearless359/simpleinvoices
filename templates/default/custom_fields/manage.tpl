{*
 * Script: manage.tpl
 * 	 Custom fields manage template
 *
 * License:
 *	 GPL v2 or above
 *}
{if $number_of_rows == 0}
    <div class="si_message">{$LANG.no_invoices}.</div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.custom_field}</th>
            <th>{$LANG.custom_label}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $cfs as $cf}
            <tr>
                <td class="si_center">
                    <a class="index_table" title="{$cf.vname}"
                       href="index.php?module=custom_fields&amp;view=details&amp;id={$cf['cf_id']}&amp;action=view" >
                        <img src="../../../images/view.png" class="action" />
                    </a>
                    <a class="index_table" title="{$cf.ename}"
                       href="index.php?module=custom_fields&amp;view=details&amp;id={$cf['cf_id']}&amp;action=edit" >
                        <img src="../../../images/edit.png" class="action" />
                    </a>
                </td>
                <td>{$cf['field_name_nice']}</td>
                <td>{$cf['cf_custom_label']}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "lengthMenu": [[-1], ["All"]],
                "order": [
                    [1, "asc"]
                ],
                "columnDefs": [
                    {"targets": 0, "orderable": false}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
    <div class="si_help_div">
        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_what_are_custom_fields" title="{$LANG.what_are_custom_fields}">
            {$LANG.what_are_custom_fields}
            <img src="{$help_image_path}help-small.png" alt=""/>
        </a>
        ::
        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_manage_custom_fields" title="{$LANG.whats_this_page_about}">
            {$LANG.whats_this_page_about}
            <img src="{$help_image_path}help-small.png" alt=""/>
        </a>
    </div>
{/if}
