{*
 * Script: manage.tpl
 * 	 Custom fields manage template
 *
 * License:
 *	 GPL v2 or above
 *}
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noInvoices}.</div>
{else}
    <table id="si-data-table" class="display responsive compact">
        <thead>
        <tr>
            <th class="si_center">{$LANG.actions}</th>
            <th class="si_left">{$LANG.customField}</th>
            <th class="si_left">{$LANG.customLabel}</th>
        </tr>
        </thead>
    </table>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "orderClasses": false,
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
