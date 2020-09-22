{*
 *  Script: manage.tpl
 *      Products manage template
 *
 *  Modified:
 *      2018-12-13 by Richard Rowley
 *
 *  License:
 *      GPL v3 or above
 *}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=products&amp;view=create" class="">
        <img src="../../../images/add.png" alt=""/>
        {$LANG.addNewProduct}
    </a>
</div>

{if $number_of_rows == 0 }
    <div class="si_message">{$LANG.noProducts}</div>
{else}
    <table id="si-data-table" class="display compact" >
        <thead>
            <tr>
                <th>{$LANG.actions}</th>
                <th>{$LANG.descriptionUc}</th>
                <th>{$LANG.unitPrice}</th>
                {if $defaults.inventory == $smarty.const.ENABLED}
                    <th>{$LANG.quantity}</th>
                {/if}
                <th>{$LANG.enabled}</th>
            </tr>
        </thead>
    </table>
    <!--suppress JSUnusedLocalSymbols -->
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "orderClasses": false,
                "deferRender": true,
                "columns": [
                    { "data": "action" },
                    { "data": "description" },
                    { "data": "unit_price",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currency_code']
                            });
                            return formatter.format(data);
                        }
                    },
                    {/literal}{if $defaults.inventory == $smarty.const.ENABLED}{literal}
                    { "data": "quantity",
                        "render": function(data, type, row) {
                            let val = data.toString();
                            return parseFloat(val);
                        }
                    },
                    {/literal}{/if}{literal}
                    { "data": "enabled" },
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false },
                    {"targets": 1 },
                    {"targets": 2, "className": 'dt-body-right' },
                    {/literal}{if $defaults.inventory == $smarty.const.ENABLED}{literal}
                    {"targets": 3, "className": 'dt-body-right' },
                    {"targets": 4, "className": 'dt-body-center' }
                    {/literal}{else}{literal}
                    {"targets": 3, "className": 'dt-body-center' }
                    {/literal}{/if}{literal}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
