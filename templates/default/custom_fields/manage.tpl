{*
 *  Script: manage.tpl
 * 	    Custom fields manage template
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
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noInvoices}.</div>
{else}
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th class="align__text-center">{$LANG.actions}</th>
            <th>{$LANG.customField}</th>
            <th>{$LANG.customLabel}</th>
        </tr>
        </thead>
    </table>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "orderClasses": false,
                "responsive": true,
                "columns": [
                    { "data": "action" },
                    { "data": "fieldNameNice" },
                    { "data": "cfCustomLabel"}
                ],
                "lengthMenu": [[-1], ["All"]],
                "order": [
                    [1, "asc"]
                ],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
    <div class="si_help_div">
        <a class="tooltip" href="#" title="{$LANG.helpWhatAreCustomFields}">
            {$LANG.whatAreCustomFields}
            <img src="{$helpImagePath}help-small.png" alt=""/>
        </a>
        ::
        <a class="tooltip" href="#" title="{$LANG.helpManageCustomFields}">
            {$LANG.whatsThisPageAbout}
            <img src="{$helpImagePath}help-small.png" alt=""/>
        </a>
    </div>
{/if}
