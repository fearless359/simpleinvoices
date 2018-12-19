{*
 *  Script: manage.tpl
 *      Customer manage template
 *
 *  License:
 *      GPL v3 or above
 *
 *  Last modified:
 *      2018-12-10 by Richard Rowley
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
{if $first_run_wizard == true}
    <div class="si_message">
        {$LANG.thank_you} {$LANG.before_starting}
    </div>
    <table class="si_table_toolbar">
        {if $number_of_billers == 0}
            <tr>
                <th>{$LANG.setup_as_biller}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=billers&amp;view=add" class="positive">
                        <img src="images/common/user_add.png" alt=""/>
                        {$LANG.add_new_biller}
                    </a>
                </td>
            </tr>
        {/if}
        {if $number_of_customers}
            <tr>
                <th>{$LANG.setup_add_customer}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=customers&amp;view=add" class="positive">
                        <img src="images/common/vcard_add.png" alt=""/>
                        {$LANG.customer_add}
                    </a>
                </td>
            </tr>
        {/if}
        {if $number_of_products}
            <tr>
                <th>{$LANG.setup_add_products}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=products&amp;view=add" class="positive">
                        <img src="images/common/cart_add.png" alt=""/>
                        {$LANG.add_new_product}
                    </a>
                </td>
            </tr>
        {/if}
        <tr>
            <th>{$LANG.setup_customisation}</th>
            <td class="si_toolbar">
                <a href="index.php?module=system_defaults&amp;view=manage" class="">
                    <img src="images/common/cog_edit.png" alt=""/>
                    {$LANG.si_defaults}
                </a>
            </td>
        </tr>
    </table>
{else}
    <div class="si_toolbar si_toolbar_top">
        <a href="index.php?module=customers&amp;view=add" class="">
            <img src="images/famfam/add.png" alt=""/>
            {$LANG.customer_add}
        </a>
    </div>
    {if $number_of_rows == 0}
        <div class="si_message">{$LANG.no_customers}</div>
    {else}
        <table id="si-data-table" class="display" >
            <thead>
            <tr>
                <th>{$LANG.actions}</th>
                <th>{$LANG.name}</th>
                <th>{$LANG.customer_department}</th>
                <th>{$LANG.last_invoice}</th>
                <th>{$LANG.total}</th>
                <th>{$LANG.paid}</th>
                <th>{$LANG.owing}</th>
                <th>{$LANG.enabled}</th>
            </tr>
            </thead>
            <tbody>
            {foreach $customers as $customer}
                <tr>
                    <td>
                        <a class="index_table" title="{$customer['vname']}" href="index.php?module=customers&amp;view=details&amp;id={$customer['id']}&amp;action=view">
                            <img src="images/common/view.png" class="action" alt="view" />
                        </a>
                        <a class="index_table" title="{$customer['ename']}" href="index.php?module=customers&amp;view=details&amp;id={$customer['id']}&amp;action=edit">
                            <img src="images/common/edit.png" class="action" alt="edit" />
                        </a>
                        <a class="index_table" title="{$defaultinv}" href="index.php?module=invoices&amp;view=usedefault&amp;customer_id={$customer['id']}&amp;action=view">
                            <img src="images/common/add.png" class="action" alt="add" />
                        </a>
                    </td>
                    <td>{$customer['name']}</td>
                    <td>{$customer['department']}</td>
                    <td class="si_right">
                        <a class="index_table" title="quick view"
                           href="index.php?module=invoices&amp;view=quick_view&amp;id={$customer['last_inv_id']}">{$customer['last_index_id']}</a>
                    </td>
                    <td class="si_right">{$customer['total']|siLocal_currency}</td>
                    <td class="si_right">{$customer['paid']|siLocal_currency}</td>
                    <td class="si_right">{$customer['owing']|siLocal_currency}</td>
                    <td class="si_center">
                        <!-- This span is here for datatables to order on -->
                        <span style="display: none">{$customer['enabled_text']}</span>
                        <img src="{$customer['enabled_image']}" alt="{$customer['enabled_text']}" title="{$customer['enabled_text']}" />
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
        <script>
            {literal}
            $(document).ready(function() {
                $('#si-data-table').DataTable({
                    "lengthMenu": [[15,20,25,30, -1], [15,20,25,30,"All"]],
                    "order": [
                        [7, "desc"],
                        [1, "asc"]
                    ],
                    "columnDefs": [
                        { "targets": 0, "orderable": false }
                    ],
                    "colReorder": true
                });
            });
            {/literal}
        </script>
    {/if}
{/if}