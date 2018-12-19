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
            <img src="images/common/add.png" alt="" />
            {$LANG.new_invoice}
        </a>
    </div>
    <div class="si_message">{$LANG.no_invoices}</div>
{else}
    <div class="si_filters_invoices si_buttons_manage_invoices">
        <span class='si_filters_title'>{$LANG.filters}:</span>
        <span class='si_filters_links'>
            <a href="index.php?module=invoices&amp;view=manage" class="first{if $get_having==''} selected{/if}">
              {$LANG.all}
            </a>
            <a href="index.php?module=invoices&amp;view=manage&amp;having=money_owed" class="{if $get_having=='money_owed'}selected{/if}">
              {$LANG.due}
            </a>
            <a href="index.php?module=invoices&amp;view=manage&amp;having=paid" class="{if $get_having=='paid'}selected{/if}">
              {$LANG.paid}
            </a>
            <a href="index.php?module=invoices&amp;view=manage&amp;having=draft" class="{if $get_having=='draft'}selected{/if}">
              {$LANG.draft}
            </a>
            <a href="index.php?module=invoices&amp;view=manage&amp;having=real" class="{if $get_having=='real'}selected{/if}">
              {$LANG.real}
            </a>
        </span>
    </div>
    <div class="si_toolbar si_toolbar_top si_toolbar_top_left">
        <a href="index.php?module=invoices&amp;view=itemised" class="">
            <img src="images/common/add.png" alt=""/>
            {$LANG.new_invoice}
        </a>
    </div>
    <table id="si-data-table" class="display" >
        <thead>
            <tr>
                <th>{$LANG.actions}</th>
                <th>{$LANG.invoice}#</th>
                <th>{$LANG.biller}</th>
                <th>{$LANG.customer}</th>
                <th>{$LANG.date_upper}</th>
                <th>{$LANG.total}</th>
                <th>{$LANG.owing}</th>
                <th>{$LANG.aging}</th>
            </tr>
        </thead>
        <tbody>
        {foreach $invoices as $invoice}
            <tr>
                <td>
                    <a class='index_table' title="{$LANG['quick_view_tooltip']}" href="index.php?module=invoices&amp;view=quick_view&amp;id={$invoice['id']}">
                        <img src="images/common/view.png" class="action" alt="view" />
                    </a>
                    {if !$read_only}
                        <a class="index_table" title="{$LANG['edit_view_tooltip']}" href="index.php?module=invoices&amp;view=details&amp;id={$invoice['id']}&amp;action=view">
                            <img src="images/common/edit.png" class="action" alt="edit" />
                        </a>
                    {/if}
                    <a class="index_table" title="{$LANG['print_preview_tooltip']}" href="index.php?module=export&amp;view=invoice&amp;id={$invoice['id']}&amp;format=print">
                        <img src="images/common/printer.png" class="action" alt="print" />
                    </a>
                    <a title="{$LANG['export_tooltip']}" class="invoice_export_dialog" href="#" rel="{$invoice['id']}" data-spreadsheet="{$config->export->spreadsheet}" data-wordprocessor="{$config->export->wordprocessor}">
                        <img src="images/common/page_white_acrobat.png" class="action" alt="spreadsheet"/>
                    </a>
                    {if !$read_only}
                        <!-- Alternatively: The Owing column can have the link on the amount instead of the payment icon code here -->
                        {if isset($invoice['status']) && $invoice['owing'] > 0}
                            <!-- Real Invoice Has Owing - Process payment -->
                            <a title="{$LANG['process_payment']}" class="index_table" href="index.php?module=payments&amp;view=process&amp;id={$invoice['id']}&amp;op=pay_selected_invoice">
                                <img src="images/common/money_dollar.png" class="action" alt="payment"/>
                            </a>
                        {elseif isset($invoice['status'])}
                            <!-- Real Invoice Payment Details if not Owing (get different color payment icon) -->
                            <a title="{$LANG['process_payment']}" class="index_table" href="index.php?module=payments&amp;view=details&amp;ac_inv_id={$invoice['id']}&amp;action=view">
                                <img src="images/common/money_dollar.png" class="action" alt="payment" />
                            </a>
                        {else}
                            <!-- Draft Invoice Just Image to occupy space till blank or greyed out icon becomes available -->
                            <img src="images/common/money_dollar.png" class="action" alt="payment" />
                        {/if}
                    {/if}
                    <a title="{$LANG['email']}" class="index_table" href="index.php?module=invoices&amp;view=email&amp;stage=1&amp;id={$invoice['id']}">
                        <img src="images/common/mail-message-new.png" class="action" alt="email" />
                    </a>
                </td>
                <td class="si_center">{$invoice['index_id']}</td>
                <td>{$invoice['biller']}</td>
                <td>{$invoice['customer']}</td>
                <td class="si_center">{$invoice['date']|siLocal_date}</td>
                <td class="si_right">{$invoice['invoice_total']|siLocal_currency}</td>
                {if isset($invoice['status'])}
                    <td class="si_right">{$invoice['owing']|siLocal_currency}</td>
                    <td class="si_right">{$invoice['aging']}</td>
                {else}
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                {/if}
            </tr>
        {/foreach}
        </tbody>
    </table>
    {*{include file='modules/invoices/manage.js.php'}*}
    <div id="export_dialog" class="flora" title="Export" style="display: none;">
        <div class="si_toolbar si_toolbar_dialog">
            <a title="{$LANG.export_tooltip} {$LANG.export_pdf_tooltip}" class="export_pdf export_window">
                <img src="images/common/page_white_acrobat.png" alt=""/>
                {$LANG.export_pdf}
            </a>
            <a title="{$LANG.export_tooltip} {$LANG.export_xls_tooltip} .{$config->export->spreadsheet}" class="export_xls export_window">
                <img src="images/common/page_white_excel.png" alt=""/>
                {$LANG.export_xls}
            </a>
            <a title="{$LANG.export_tooltip} {$LANG.export_doc_tooltip} .{$config->export->wordprocessor}" class="export_doc export_window">
                <img src="images/common/page_white_word.png" alt=""/>
                {$LANG.export_doc}
            </a>
        </div>
    </div>
    <script>
        {literal}
        $(document).ready(function() {
            $('#si-data-table').DataTable({
                "lengthMenu": [[15,20,25,30, -1], [15,20,25,30,"All"]],
                "order": [
                    [1, "desc"]
                ],
                "columnDefs": [
                    { "targets": 0, "width": "16%", "orderable": false },
                    { "targets": 1, "width": "9%" },
                    { "targets": 4, "width": "11%" },
                    { "targets": [5,6,7], "width": "10%" }
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
