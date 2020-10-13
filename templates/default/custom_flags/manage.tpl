{*
 *  Script: manage.php
 *      Custom flags manage page
 *
 *  Authors:
 *      Richard Rowley
 *
 *  Last edited:
 *      2018-12-13
 *
 *  License:
 *      GPL v3 or above
 *}
<br/>
<br/>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noCustomFlags}</div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.associatedTable}</th>
            <th>{$LANG.flagNumber}</th>
            <th>{$LANG.fieldLabelUc}</th>
            <th>{$LANG.enabled}</th>
            <th>{$LANG.fieldHelpUc}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $cflgs as $cflg}
            <tr>
                <td class="si_center">
                    <a class="index_table" title="{$cflg['vname']}"
                       href="index.php?module=custom_flags&amp;view=view&amp;id={$cflg['id']}">
                        <img src="../../../images/view.png" alt="{$cflg['vname']}"/>
                    </a>
                    <a class="index_table" title="{$cflg['ename']}"
                       href="index.php?module=custom_flags&amp;view=edit&amp;id={$cflg['id']}">
                        <img src="../../../images/edit.png" alt="{$cflg['ename']}"/>
                    </a>
                </td>
                <td>{$cflg['associated_table']}</td>
                <td class="si_center">{$cflg['flg_id']}</td>
                <td>{$cflg['field_label']}</td>
                <td class="si_center">
                    <!-- The span field allows this field to be orderable. -->
                    <span style="display:none;">{$cflg['enabled']}</span>
                    <img src="{$cflg['image']}" alt="{$cflg['enabled_text']}" title="{$cflg['enabled_text']}"
                </td>
                <td>{$cflg['field_help']}</td>
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
                    [1, "asc"],
                    [2, 'asc']
                ],
                "columnDefs": [
                    {"targets": [0, 5], "orderable": false}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
    <div class="si_help_div">
        <a class="cluetip" href="#"
           rel="index.php?module=documentation&amp;view=view&amp;page=helpWhatAreCustomFlags"
           title="{$LANG.whatAreCustomFlags}">{$LANG.whatAreCustomFlags}
            <img src="{$helpImagePath}help-small.png" alt=""/>
        </a>
        ::
        <a class="cluetip" href="#"
           rel="index.php?module=documentation&amp;view=view&amp;page=helpManageCustomFlags"
           title="{$LANG.whatsThisPageAbout}">{$LANG.whatsThisPageAbout}
            <img src="{$helpImagePath}help-small.png" alt=""/>
        </a>
    </div>
{/if}
