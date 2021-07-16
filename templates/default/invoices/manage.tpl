{*
 *  Script: manage.tpl
 *      Manage invoices template
 *
 *  Last modified:
 *      20210624 by Richard Rowley to add cell-border class to table tag.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
 *}
{if $number_of_invoices == 0}
    <div class="align__text-center margin__bottom-2">
        <a href="index.php?module=invoices&amp;view=itemized" class="">
            <button><img src="images/add.png" alt=""/>{$LANG.newInvoice}</button>
        </a>
    </div>
    <div class="si_message">{$LANG.noInvoices}</div>
{else}
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-3 align__text-center">
            <a href="index.php?module=invoices&amp;view=itemized">
                <button><img src="images/add.png" alt=""/>{$LANG.newInvoice}</button>
            </a>
        </div>
        <div class="cols__7-span-4 grid__justify-content-end">
                <span class='cols__1-span-1 si_filters_title'>{$LANG.filters}:</span>
                <span class='cols__2-span-5 si_filters_links'>
                    <a href="index.php?module=invoices&amp;view=manage"
                       class="first{if !isset($smarty.get.having) || empty($smarty.get.having)} selected{/if}">{$LANG.allUc}</a>
                    <a href="index.php?module=invoices&amp;view=manage&amp;having=money_owed"
                       class="{if isset($smarty.get.having) && $smarty.get.having=='money_owed'}selected{/if}">{$LANG.due}</a>
                    <a href="index.php?module=invoices&amp;view=manage&amp;having=paid"
                       class="{if isset($smarty.get.having) && $smarty.get.having=='paid'}selected{/if}">{$LANG.paidUc}</a>
                    <a href="index.php?module=invoices&amp;view=manage&amp;having=draft"
                       class="{if isset($smarty.get.having) && $smarty.get.having=='draft'}selected{/if}">{$LANG.draft}</a>
                    <a href="index.php?module=invoices&amp;view=manage&amp;having=real"
                       class="{if isset($smarty.get.having) && $smarty.get.having=='real'}selected{/if}">{$LANG.real}</a>
                </span>
        </div>
    </div>
    <table id="si-data-table" class="display responsive compact cell-border">
        <thead>
        <tr>
            <th class="align__text-center">{$LANG.actions}</th>
            <th class="align__text-center">{$LANG.invoiceUc}#</th>
            <th>{$LANG.billerUc}</th>
            <th>{$LANG.customerUc}</th>
            <th class="align__text-center">{$LANG.preferenceUc}</th>
            <th class="align__text-center">{$LANG.dateUc}</th>
            <th class="align__text-right">{$LANG.totalUc}</th>
            <th class="align__text-right">{$LANG.owingUc}</th>
            <th class="align__text-right">{$LANG.aging}</th>
        </tr>
        </thead>
    </table>
    {* This is the dialog box for the document export pdf, doc and xls files *}
    <div id="dialog" class="web_dialog flora" title="Export" style="display: none;">
        <div class="align__text-center toolbar_dialog">
            <a title="{$LANG.exportUc} {$LANG.exportPdfTooltip}"
               class="button square export_pdf export_window">
                <img src="images/page_white_acrobat.png" alt="{$LANG.exportPdf}"/>{$LANG.exportPdf}
            </a>
            <a title="{$LANG.exportUc} {$LANG.exportXlsTooltip} .{$config.exportSpreadsheet}"
               class="button square export_xls export_window">
                <img src="images/page_white_excel.png" alt="{$LANG.exportXls}"/>{$LANG.exportXls}
            </a>
            <a title="{$LANG.exportUc} {$LANG.exportDocTooltip} .{$config.exportWordProcessor}"
               class="button square export_doc export_window">
                <img src="images/page_white_word.png" alt="{$LANG.exportDoc}"/>{$LANG.exportDoc}
            </a>
            <button id="webDialogClose" class="submit square">{$LANG.closeUc}</button>
        </div>
    </div>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "orderClasses": false,
                "deferRender": true,
                "responsive": true,
                "columns": [
                    {"data": "action"},
                    {"data": "index_id"},
                    {"data": "biller"},
                    {"data": "customer"},
                    {"data": "preference"},
                    {"data": "date"},
                    {
                        "data": "total",
                        "render": function (data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currency_code']
                            });
                            return formatter.format(data);
                        }
                    },
                    {
                        "data": "owing",
                        "render": function (data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currency_code']
                            });
                            return formatter.format(data);
                        }
                    },
                    {"data": "aging"}
                ],
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "columnDefs": [
                    {
                        "targets": 0,
                        "width": "12%",
                        "className": 'dt-body-center',
                        "orderable": false
                    },
                    {"targets": [1, 4, 5, 7], "className": 'dt-body-center'},
                    {"targets": 5, "width": "10%"},
                    {"targets": [6, 7, 8], "className": 'dt-body-right'}
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
