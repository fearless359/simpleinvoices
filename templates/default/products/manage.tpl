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
        <img src="images/add.png" alt=""/>
        {$LANG.addNewProduct}
    </a>
</div>

{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noProducts}</div>
{else}
    <table id="si-data-table" class="display responsive compact">
        <thead>
            <tr>
                <th class="si_center">{$LANG.actions}</th>
                <th class="si_left">{$LANG.descriptionUc}</th>
                <th class="si_left">{$LANG.productGroupUc}</th>
                <th class="si_right">{$LANG.unitPrice}</th>
                <th class="si_right">{$LANG.markupUc} {$LANG.priceUc}</th>
                <th class="si_right">{$LANG.quantityShort}</th>
                <th class="si_center">{$LANG.enabled}</th>
            </tr>
        </thead>
    </table>
    <!--suppress JSUnusedLocalSymbols -->
    <script>
        let productGroupEnabled = {$defaults.product_groups} == {$smarty.const.ENABLED},
            inventoryEnabled = {$defaults.inventory} == {$smarty.const.ENABLED}
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "orderClasses": false,
                "deferRender": true,
                "columns": [
                    { "data": "action" },
                    { "data": "description" },
                    { "data": "product_group"},
                    { "data": "unit_price",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currency_code']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "markup_price",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currency_code']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "quantity",
                        "render": function(data, type, row) {
                            let val = data.toString();
                            return parseFloat(val);
                        }
                    },
                    { "data": "enabled" },
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "orderable": false },
                    {"targets": 1, "width": "30%" },
                    {"targets": 2,
                        "visible": productGroupEnabled
                    },
                    {"targets": 3, "className": 'dt-body-right' },
                    {"targets": 4, "className": 'dt-body-right',
                        "visible": productGroupEnabled
                    },
                    {"targets": 5, "className": 'dt-body-right',
                        "visible": inventoryEnabled
                    },
                    {"targets": 6, "className": 'dt-body-center' }
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
