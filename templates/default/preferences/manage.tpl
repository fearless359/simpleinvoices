{*
 *  Script: manage.tpl
 * 	    Invoice Preferences manage template
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
<div class="align__text-center margin__bottom-2">
    <a href="index.php?module=preferences&amp;view=create" class="">
        <button><img src="images/add.png" alt=""/>{$LANG.addNewPreference}</button>
    </a>
</div>

{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noPreferences}</div>
{else}
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th class="align__text-center">{$LANG.actions}</th>
            <th class="align__text-right">{$LANG.idUc}</th>
            <th>{$LANG.descriptionUc}</th>
            <th class="align__text-center">{$LANG.invoiceNumberingGroup}</th>
            <th class="align__text-center">{$LANG.setAging}</th>
            <th class="align__text-center">{$LANG.language}</th>
            <th class="align__text-center">{$LANG.locale}</th>
            <th class="align__text-center">{$LANG.enabled}</th>
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
                    { "data": "prefId" },
                    { "data": "prefDescription" },
                    { "data": "invoiceNumberingGroup"},
                    { "data": "setAgingCol" },
                    { "data": "language"},
                    { "data": "locale"},
                    { "data": "enabled"},
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [7, "desc"],
                    [1, "asc"]
                ],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false},
                    {"targets": [1,3,4,5,6,7], "className": 'dt-body-center'}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
<div class="si_help_div">
    <a class="tooltip" href="#" title="{$LANG.helpInvPrefWhatThe}">
        <img src="{$helpImagePath}help-small.png" alt="{$LANG.whatsAllThisInvPref}"/>{$LANG.whatsAllThisInvPref}
    </a>
</div>
