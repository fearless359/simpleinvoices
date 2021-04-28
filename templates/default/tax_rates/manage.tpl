{*
 *  Script: manage.tpl
 *      Tax Rates manage template
 *
 *  Authors:
 *      Justin Kelly, Ben Brown
 *
 *  Last edited:
 *      2018-12-12 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *}

<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=tax_rates&amp;view=create" class="">
        <img src="images/add.png" alt=""/>
        {$LANG.addNewTaxRate}
    </a>
</div>

{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noTaxRates}</div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.descriptionUc}</th>
            <th class="si_right">{$LANG.rateUc}</th>
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
                    { "data": "taxDescription" },
                    { "data": "taxPercentage" },
                    { "data": "enabled"}
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "order": [
                    [3, "desc"],
                    [1, "asc"]
                ],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false},
                    {"targets": 2, "className": 'dt-body-right'},
                    {"targets": 3, "className": 'dt-body-center'}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
