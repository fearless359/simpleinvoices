{*
 * Script: manage.tpl
 * 	 Custom fields manage template
 *
 * License:
 *	 GPL v2 or above
 *}
{if $number_of_rows == 0}
    <div class="si_message">{$LANG.noInvoices}.</div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.customField}</th>
            <th>{$LANG.customLabel}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $cfs as $cf}
            <tr>
                <td class="si_center">
                    <a class="index_table" title="{$cf.vname}"
                       href="index.php?module=custom_fields&amp;view=view&amp;id={$cf['cf_id']}">
                        <img src="../../../images/view.png" class="action" alt="{$cf.vname}"/>
                    </a>
                    <a class="index_table" title="{$cf.ename}"
                       href="index.php?module=custom_fields&amp;view=edit&amp;id={$cf['cf_id']}">
                        <img src="../../../images/edit.png" class="action" alt="{$cf.ename}"/>
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
        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpWhatAreCustomFields" title="{$LANG.whatAreCustomFields}">
            {$LANG.whatAreCustomFields}
            <img src="{$helpImagePath}help-small.png" alt=""/>
        </a>
        ::
        <a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=helpManageCustomFields" title="{$LANG.whatsThisPageAbout}">
            {$LANG.whatsThisPageAbout}
            <img src="{$helpImagePath}help-small.png" alt=""/>
        </a>
    </div>
{/if}
