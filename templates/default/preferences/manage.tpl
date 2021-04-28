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
    <a href="index.php?module=preferences&amp;view=create" class="">
        <img src="images/add.png" alt=""/>
        {$LANG.addNewPreference}
    </a>
</div>

{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noPreferences}</div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.idUc}</th>
            <th>{$LANG.descriptionUc}</th>
            <th>{$LANG.invoiceNumberingGroup}</th>
            <th>{$LANG.setAging}</th>
            <th>{$LANG.language}</th>
            <th>{$LANG.locale}</th>
            <th>{$LANG.enabled}</th>
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
    <a class="cluetip" href="#" title="{$LANG.whatsAllThisInvPref}"
       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvPrefWhatThe">
        <img src="{$helpImagePath}help-small.png" alt=""/>
        {$LANG.whatsAllThisInvPref}
    </a>
</div>
