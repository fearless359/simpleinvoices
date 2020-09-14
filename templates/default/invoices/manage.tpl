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
            <img src="../../../images/add.png" alt="" />
            {$LANG.new_invoice}
        </a>
    </div>
    <div class="si_message">{$LANG.no_invoices}</div>
{else}
    <div class="si_filters_invoices si_buttons_manage_invoices">
        <span class='si_filters_title'>{$LANG.filters}:</span>
        <span class='si_filters_links'>
            <a href="index.php?module=invoices&amp;view=manage"
               class="first{if !isset($smarty.get.having) || empty($smarty.get.having)} selected{/if}">
              {$LANG.all}
            </a>
            <a href="index.php?module=invoices&amp;view=manage&amp;having=money_owed"
               class="{if isset($smarty.get.having) && $smarty.get.having=='money_owed'}selected{/if}">
              {$LANG.due}
            </a>
            <a href="index.php?module=invoices&amp;view=manage&amp;having=paid"
               class="{if isset($smarty.get.having) && $smarty.get.having=='paid'}selected{/if}">
              {$LANG.paid}
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
            <img src="../../../images/add.png" alt=""/>
            {$LANG.new_invoice}
        </a>
    </div>
    <table id="si-data-table" class="display compact" >
        <thead>
            <tr>
                <th>{$LANG.actions}</th>
                <th>{$LANG.invoice_uc}#</th>
                <th>{$LANG.biller}</th>
                <th>{$LANG.customer}</th>
                <th>{$LANG.to_uc}</th>
                <th>{$LANG.total}</th>
                <th>{$LANG.owing_uc}</th>
                <th>{$LANG.aging}</th>
            </tr>
        </thead>
    </table>
    <div id="overlay" class="web_dialog_overlay"></div>
    <div id="dialog" class="web_dialog flora" title="Export" style="display: none;">
        <div class="si_toolbar si_toolbar_dialog">
            <a title="{$LANG.export_tooltip} {$LANG.export_pdf_tooltip}" class="export_pdf export_window">
                <img src="../../../images/page_white_acrobat.png" alt=""/>
                {$LANG.export_pdf}
            </a>
            <a title="{$LANG.export_tooltip} {$LANG.export_xls_tooltip} .{$config.exportSpreadsheet}" class="export_xls export_window">
                <img src="../../../images/page_white_excel.png" alt=""/>
                {$LANG.export_xls}
            </a>
            <a title="{$LANG.export_tooltip} {$LANG.export_doc_tooltip} .{$config.exportWordProcessor}" class="export_doc export_window">
                <img src="images/page_white_word.png" alt=""/>
                {$LANG.export_doc}
            </a>
            <button id="webDialogClose">Close</button>
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
                    { "data": "date" },
                    {"data": "total",
                        "render": function(data, type, row) {
                            let formatter = new Intl.NumberFormat(row['locale'], {
                                'style': 'currency',
                                'currency': row['currency_code']
                            });
                            return formatter.format(data);
                        }
                    },
                    {
                        "data": "owing",
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
                "order": [
                    [1, "desc"]
                ],
                "columnDefs": [
                    { "targets": 0, "width": "12%", "className": 'dt-body-center', "orderable": false },
                    { "targets": [1,4,7], "className": 'dt-body-center' },
                    { "targets": 4, "width": "10%" },
                    { "targets": [5,6], "className": 'dt-body-right' }
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
