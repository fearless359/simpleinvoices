{*
 * Script: manage.tpl
 *      Manage invoices template
 *
 * License:
 *     GPL v2 or above
 *
 * Website:
 *    https://simpleinvoices.group
 *}
{if $number_of_invoices == 0}
    <div class="si_toolbar si_toolbar_top si_toolbar_top_left">
        <a href="index.php?module=invoices&amp;view=itemised" class="">
            <img src="images/add.png" alt="" />
            {$LANG.newInvoice}
        </a>
    </div>
    <div class="si_message">{$LANG.noInvoices}</div>
{else}
    <div class="si_filters_invoices si_buttons_manage_invoices">
        <span class='si_filters_title'>{$LANG.filters}:</span>
        <span class='si_filters_links'>
            <a href="index.php?module=invoices&amp;view=manage"
               class="first{if !isset($smarty.get.having) || empty($smarty.get.having)} selected{/if}">
              {$LANG.allUc}
            </a>
            <a href="index.php?module=invoices&amp;view=manage&amp;having=money_owed"
               class="{if isset($smarty.get.having) && $smarty.get.having=='money_owed'}selected{/if}">
              {$LANG.due}
            </a>
            <a href="index.php?module=invoices&amp;view=manage&amp;having=paid"
               class="{if isset($smarty.get.having) && $smarty.get.having=='paid'}selected{/if}">
              {$LANG.paidUc}
            </a>
            <a href="index.php?module=invoices&amp;view=manage&amp;having=draft"
               class="{if isset($smarty.get.having) && $smarty.get.having=='draft'}selected{/if}">
              {$LANG.draft}
            </a>
            <a href="index.php?module=invoices&amp;view=manage&amp;having=real"
               class="{if isset($smarty.get.having) && $smarty.get.having=='real'}selected{/if}">
              {$LANG.real}
            </a>
        </span>
    </div>
    <div class="si_toolbar si_toolbar_top si_toolbar_top_left">
        <a href="index.php?module=invoices&amp;view=itemised" class="">
            <img src="images/add.png" alt=""/>
            {$LANG.newInvoice}
        </a>
    </div>
    <table id="si-data-table" class="display compact" >
        <thead>
            <tr>
                <th>{$LANG.actions}</th>
                <th>{$LANG.invoiceUc}#</th>
                <th>{$LANG.billerUc}</th>
                <th>{$LANG.customerUc}</th>
                <th>{$LANG.preferenceUc}</th>
                <th>{$LANG.dateUc}</th>
                <th>{$LANG.totalUc}</th>
                <th>{$LANG.owingUc}</th>
                <th>{$LANG.aging}</th>
            </tr>
        </thead>
    </table>
    <div id="overlay" class="web_dialog_overlay"></div>
    <div id="dialog" class="web_dialog flora" title="Export" style="display: none;">
        <div class="si_toolbar si_toolbar_dialog">
            <a title="{$LANG.exportUc} {$LANG.exportPdfTooltip}" class="export_pdf export_window">
                <img src="images/page_white_acrobat.png" alt=""/>
                {$LANG.exportPdf}
            </a>
            <a title="{$LANG.exportUc} {$LANG.exportXlsTooltip} .{$config.exportSpreadsheet}" class="export_xls export_window">
                <img src="images/page_white_excel.png" alt=""/>
                {$LANG.exportXls}
            </a>
            <a title="{$LANG.exportUc} {$LANG.exportDocTooltip} .{$config.exportWordProcessor}" class="export_doc export_window">
                <img src="images/page_white_word.png" alt=""/>
                {$LANG.exportDoc}
            </a>
            <button id="webDialogClose">{$LANG.closeUc}</button>
        </div>
    </div>
    <script>
        {literal}
        $(document).ready(function() {
            $('#si-data-table').DataTable({
                "ajax": "./public/data.json",
                "orderClasses": false,
                "deferRender": true,
                "columns": [
                    { "data": "action" },
                    { "data": "index_id" },
                    { "data": "biller" },
                    { "data": "customer" },
                    { "data": "preference"},
                    { "data": "date" },
                    { "data": "total",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currency_code']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "owing",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currency_code']
                            });
                            return formatter.format(data);
                        }
                    },
                    { "data": "aging" }
                ],
                "lengthMenu": [[15,20,25,30, -1], [15,20,25,30,"All"]],
                "columnDefs": [
                    { "targets": 0, "width": "12%", "className": 'dt-body-center', "orderable": false },
                    { "targets": [1,4,5,7], "className": 'dt-body-center' },
                    { "targets": 5, "width": "10%" },
                    { "targets": [6, 7], "className": 'dt-body-right' }
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
