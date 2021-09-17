{*
 *  Script: manage.php
 *      Custom flags manage page
 *
 *  Last modified:
 *      20210618 by Richard Rowley to add cell-border class to table tag.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
<br/>
<br/>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noCustomFlags}</div>
{else}
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th class="align__text-center">{$LANG.actions}</th>
            <th>{$LANG.associatedTable}</th>
            <th class="align__text-center">{$LANG.flagNumber}</th>
            <th>{$LANG.fieldLabelUc}</th>
            <th class="align__text-center">{$LANG.enabled}</th>
            <th>{$LANG.fieldHelpUc}</th>
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
        <a class="tooltip" href="#" title="{$LANG.helpWhatAreCustomFlags}">{$LANG.whatAreCustomFlags}
            <img src="{$helpImagePath}help-small.png" alt=""/>
        </a>
        ::
        <a class="tooltip" href="#" title="{$LANG.helpManageCustomFlags}">{$LANG.whatsThisPageAbout}
            <img src="{$helpImagePath}help-small.png" alt=""/>
        </a>
    </div>
{/if}
