{*
 *  Script: manage.tpl
 *      Customer manage template
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
{if $first_run_wizard == true}
    <div class="si_message">
        {$LANG.thankYou} {$LANG.beforeStarting}
    </div>
    <div class="grid__area">
        {if $number_of_billers == 0}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-7 align__text-right margin__top-0-75 margin__right-1">{$LANG.setupAsBiller}</div>
                <div class="cols__8-span-3">
                    <a href="index.php?module=billers&amp;view=create" class="button positive">
                        <img src="images/user_add.png" alt=""/>{$LANG.addNewBiller}
                    </a>
                </div>
            </div>
        {/if}
        {if $number_of_customers == 0}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-7 align__text-right margin__top-0-75 margin__right-1">{$LANG.setupAddCustomer}</div>
                <div class="cols__8-span-3">
                    <a href="index.php?module=customers&amp;view=create" class="button positive">
                        <img src="images/vcard_add.png" alt=""/>{$LANG.customerAdd}
                    </a>
                </div>
            </div>
        {/if}
        {if $number_of_products == 0}
            <div class="grid__container grid__head-10">
                <div class="cols__1-span-7 align__text-right margin__top-0-75 margin__right-1">{$LANG.setupAddProducts}</div>
                <div class="cols__8-span-3">
                    <a href="index.php?module=products&amp;view=create" class="button positive">
                        <img src="images/cart_add.png" alt=""/>{$LANG.addNewProduct}
                    </a>
                </div>
            </div>
        {/if}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-7 align__text-right margin__top-0-75 margin__right-1">{$LANG.setupCustomization}</div>
            <div class="cols__8-span-3">
                <a href="index.php?module=system_defaults&amp;view=manage" class="">
                    <button><img src="images/cog_edit.png" alt=""/>{$LANG.siDefaults}</button>
                </a>
            </div>
        </div>
    </div>
{else}
    <div class="align__text-center margin__bottom-2"
         {if $smarty.session.role_name == 'customer'}style="display:none"{/if}>
        <a href="index.php?module=customers&amp;view=create" class="">
            <button><img src="images/add.png" alt=""/>{$LANG.customerAdd}</button>
        </a>
    </div>
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
            <tr>
                <th class="align__text-center">{$LANG.actions}</th>
                <th>{$LANG.nameUc}</th>
                <th>{$deptOrPhoneFieldLabel}</th>
                <th class="align__text-center">{$LANG.lastInvoice}</th>
                <th class="align__text-right">{$LANG.totalUc}</th>
                <th class="align__text-right">{$LANG.paidUc}</th>
                <th class="align__text-right">{$LANG.owingUc}</th>
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
                    { "data": "name" },
                    { "data": "departmentOrPhone" },
                    { "data": "quickView" },
                    { "data": "total",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currencyCode']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "paid",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currencyCode']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "owing",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currencyCode']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "enabled" },
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "columnDefs": [
                    {"targets": 0, "className": 'dt-body-center', "width": "8%", "orderable": false },
                    {"targets": 1 },
                    {"targets": 2 },
                    {"targets": 3, "className": 'dt-body-center' },
                    {"targets": 4, "className": 'dt-body-right' },
                    {"targets": 5, "className": 'dt-body-right' },
                    {"targets": 6, "className": 'dt-body-right' },
                    {"targets": 7, "className": 'dt-body-center'}
                ],
                "colReorder": true,
                "order": [
                    [7, 'desc'],
                    [1, 'asc']
                ]
            });
        });
        {/literal}
    </script>
{/if}
