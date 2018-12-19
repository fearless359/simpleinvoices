{*
 *	Script: manage.tpl
 * 		Payments manage template
 *
 *	Last edited:
 * 	 	2018-10-03 by Richard Rowley
 *
 * 	License:
 *	 GPL v3 or above
 *
 *	Website:
 *	https://simpleinvoices.group
 *}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=payments&amp;view=process&amp;op=pay_invoice" class="">
        <img src="images/famfam/add.png" alt=""/>
        {$LANG.process_payment}
    </a>
{if isset($smarty.get.id)}
    <a href="index.php?module=payments&amp;view=process&amp;id={$smarty.get.id|urlencode}&amp;op=pay_selected_invoice" class="">
        <img src="images/famfam/money.png" alt=""/>
        {$LANG.payments_filtered_invoice}
    </a>
{/if}
</div>
{if !isset($payments)}
    <div class="si_message">{$no_entry_msg}</div>
{else}
    <table id="si-data-table" class="display">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.payment}#</th>
            <th>{$LANG.invoice}#</th>
            <th>{$LANG.customer}</th>
            <th>{$LANG.biller}</th>
            <th>{$LANG.amount}</th>
            <th>{$LANG.type}</th>
            <th>{$LANG.date_upper}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $payments as $payment}
            <tr>
                <td class="si_center">
                    <a class="index_table" title="{$LANG['view']} {$LANG['payment']}# {$payment['id']}"
                       href="index.php?module=payments&amp;view=details&amp;id={$payment['id']}&amp;action=view">
                        <img src="images/common/view.png" height="16" border="-5px" />
                    </a>
                    <a class="index_table" title="{$LANG['print_preview_tooltip']} {$LANG['payment']}# {$payment['id']}"
                       href="index.php?module=payments&amp;view=print&amp;id={$payment['id']}">
                        <img src="images/common/printer.png" height="16" border="-5px" />
                    </a>
                </td>
                <td class="si_right">{$payment['id']}</td>
                <td class="si_right">{$payment['ac_inv_id']}</td>
                <td>{$payment['cname']}</td>
                <td>{$payment['bname']}</td>
                <td class="si_right">{$payment['ac_amount']|siLocal_currency}</td>
                <td>{$payment['type']}</td>
                <td>{$payment['date']|siLocal_date}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    <script>
        {literal}
        $(document).ready(function () {
            $('#si-data-table').DataTable({
                "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
                "columnDefs": [
                    {"targets": 0, "width": "8%", "orderable": false},
                    {"targets": 1, "width": "8%" },
                    {"targets": 2, "width": "8%" },
                    {"targets": 3, "width": "19%" },
                    {"targets": 4, "width": "19%" },
                    {"targets": 5, "width": "12%" },
                    {"targets": 6, "width": "13%" },
                    {"targets": 7, "width": "13%" }
                ],
                "colReorder": true
            });
        });
        {/literal}
    </script>
{/if}
