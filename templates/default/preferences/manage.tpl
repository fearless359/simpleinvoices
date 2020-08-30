{*
 *  Script: manage.tpl
 * 	    Invoice Preferences manage template
 *
 *  Authors:
 *	    Justin Kelly, Ben Brown
 *
 *  Last edited:
 * 	    2018-12-12 by Richard Rowley
 *
 *  License:
 *	    GPL v3 or above
 *}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=preferences&amp;view=add" class="">
        <img src="../../../images/add.png" alt=""/>
        {$LANG.add_new_preference}
    </a>
</div>

{if $number_of_rows == 0}
    <div class="si_message">{$LANG.no_preferences}</div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.id}</th>
            <th>{$LANG.description_uc}</th>
            <th>{$LANG.invoice_numbering_group}</th>
            <th>{$LANG.set_aging}</th>
            <th class="si_center">{$LANG.language}</th>
            <th class="si_center">{$LANG.locale}</th>
            <th class="si_center">{$LANG.enabled}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $preferences as $preference}
            <tr>
                <td class="si_center">
                    <a class="index_table' title='{$preference.vname}"
                       href="index.php?module=preferences&amp;view=details&amp;id={$preference.pref_id}&amp;action=view" >
                        <img src="../../../images/view.png" class="action" />
                    </a>
                    <a class="index_table" title="{$preference.ename}"
                       href="index.php?module=preferences&amp;view=details&amp;id={$preference.pref_id}&amp;action=edit" >
                        <img src="../../../images/edit.png" class="action" />
                    </a>
                </td>
                <td>{$preference['pref_id']}</td>
                <td>{$preference['pref_description']}</td>
                <td class="si_center">{$preference['invoice_numbering_group']}</td>
                <td class="si_center">
                    <!-- here so field can be sorted -->
                    <span style="display: none">{$preference['set_aging_text']}</span>
                    <img src="{$preference['set_aging_image']}" alt="set aging flag">
                </td>
                <td class="si_center">{$preference['language']}</td>
                <td class="si_center">{$preference['locale']}</td>
                <td class="si_center">
                    <!-- here so field can be sorted -->
                    <span style="display: none">{$preference['enabled_text']}</span>
                    <img src="{$preference['image']}" alt="enabled flag">
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
                    [7, "desc"],
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
{/if}
<div class="si_help_div">
    <a class="cluetip" href="#" title="{$LANG.whats_all_this_inv_pref}"
       rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_what_the">
        <img src="{$helpImagePath}help-small.png" alt=""/>
        {$LANG.whats_all_this_inv_pref}
    </a>
</div>
