{*
 *  Script: manage.tpl
 *      Manage payment types template
 *
 * Authors:
 *   Justin Kelly, Nicolas Ruflin, Ben Brown
 *
 *  Last edited:
 *      2016-08-15
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=payment_types&amp;view=create">
        <img src="../../../images/add.png" alt=""/>
        {$LANG.addNewPaymentType}
    </a>
</div>
{if $number_of_rows == 0}
    <div class="si_message">{$LANG.noPaymentTypes}</div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.descriptionUc}</th>
            <th>{$LANG.enabled}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $payment_types as $payment_type}
            <tr>
                <td class="si_center">
                    <a class="index_table" title="{$payment_type['vname']}"
                       href="index.php?module=payment_types&amp;view=view&amp;id={$payment_type['pt_id']}">
                        <img src="../../../images/view.png" alt="view" class="action"/>
                    </a>
                    <a class="index_table" title="{$payment_type['ename']}"
                       href="index.php?module=payment_types&amp;view=edit&amp;id={$payment_type['pt_id']}">
                        <img src="../../../images/edit.png" alt="edit" class="action"/>
                    </a>
                </td>
                <td>{$payment_type['pt_description']}</td>
                <td class="si_center">
                    <!-- The span field is for field sorting -->
                    <span style="display:none">{$payment_type['enabled_text']}</span>
                    <img src="{$payment_type['image']}" alt="{$LANG.enabled}">
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
                    [2, "desc"],
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
