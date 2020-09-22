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
        <img src="../../../images/add.png" alt=""/>
        {$LANG.addNewTaxRate}
    </a>
</div>

{if $number_of_rows == 0}
    <div class="si_message">{$LANG.noTaxRates}</div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.descriptionUc}</th>
            <th>{$LANG.rate}</th>
            <th>{$LANG.enabled}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $taxes as $tax}
            <tr>
                <td class="si_center">
                    <a class="index_table" title="{$tax['vname']}"
                       href="index.php?module=tax_rates&amp;view=view&amp;id={$tax['tax_id']}">
                        <img src="../../../images/view.png" class="action" alt="{$tax['vname']}"/>
                    </a>
                    <a class="index_table" title="{$tax['ename']}"
                       href="index.php?module=tax_rates&amp;view=edit&amp;id={$tax['tax_id']}">
                        <img src="../../../images/edit.png" class="action" alt="{$tax['ename']}"/>
                    </a>
                </td>
                <td>{$tax['tax_description']}</td>
                <td class="si_right">{$tax['tax_percentage']|utilNumber} {$tax['type']}</td>
                <td class="si_center">
                    <span style="display:none">{$tax['enabled_text']}</span>
                    <img src="{$tax['image']}" alt="enabled">
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
