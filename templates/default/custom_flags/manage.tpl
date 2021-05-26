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
    <table id="si-data-table" class="display responsive compact">
        <thead>
        <tr>
            <th class="si_center">{$LANG.actions}</th>
            <th class="si_left">{$LANG.associatedTable}</th>
            <th class="si_center">{$LANG.flagNumber}</th>
            <th class="si_left">{$LANG.fieldLabelUc}</th>
            <th class="si_center">{$LANG.enabled}</th>
            <th class="si_left">{$LANG.fieldHelpUc}</th>
        </tr>
        </thead>
    </table>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "orderClasses": false,
                "deferRender": true,
                "responsive": true,
                "columns": [
                    { "data": "action" },
                    { "data": "associatedTable" },
                    { "data": "flgId" },
                    { "data": "fieldLabel" },
                    { "data": "enabled"},
                    { "data": "fieldHelp"}
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [1, "asc"],
                    [2, 'asc']
                ],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false},
                    {"targets": [2, 4], "className": 'dt-body-center'},
                    {"targets": 5, "orderable": false}
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
