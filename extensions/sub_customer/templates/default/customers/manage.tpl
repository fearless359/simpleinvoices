{*
 *  Script: manage.tpl
 *      Customer manage template
 *
 *  License:
 *      GPL v3 or above
 *
 *  Last modified:
 *      2018-10-06 by Richard Rowley
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
        {if $number_of_customers == 0}
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
        {if $number_of_products == 0}
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
                    {$LANG.system_preferences}
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
    {if $number_of_customers == 0}
        <div class="si_message">{$LANG.no_customers}</div>
    {else}
        <script>
            {literal}
            $(document).ready(function() {
                $('#example').DataTable({
                    "lengthMenu": [[15, 20, 25, 30, -1],[15, 20, 25, 30, "All"]],
                    "columnDefs": [
                        { "orderable": false, "targets": 0 }
                    ]
                });
            });
            {/literal}
        </script>
        <table id="example" class="display" styles="width:100%">
            <thead>
            <tr>
                <th class="si_center">{$LANG.actions}</th>
                <th class="si_center">{$LANG.id}</th>
                <th class="si_center">{$LANG.name}</th>
                <th class="si_center">{$LANG.customer_department}</th>
                <th class="si_center">{$LANG.last_invoice}</th>
                <th class="si_center">{$LANG.total}</th>
                <th class="si_center">{$LANG.paid}</th>
                <th class="si_center">{$LANG.owing}</th>
                <th class="si_center">{$LANG.enabled}</th>
            </tr>
            </thead>
            <tbody>
            {foreach $customers as $customer}
                <tr>
                    <td>
                        <a class='index_table' title='{$LANG['view']} {$LANG['customer']} {$customer.name}'
                           href='index.php?module=customers&amp;view=details&amp;id={$customer['id']}&amp;action=view'>
                            <img src='images/common/view.png' class='action' />
                        </a>
                        <a class='index_table' title='{$LANG['edit']} {$LANG['customer']} {$customer.name}'
                           href='index.php?module=customers&amp;view=details&amp;id={$customer['id']}&amp;action=edit'>
                            <img src='images/common/edit.png' class='action' />
                        </a>
                        <a class='index_table' title='{$LANG['new_uppercase']} {$LANG['default_invoice']}'
                           href='index.php?module=invoices&amp;view=usedefault&amp;customer_id={$customer['id']}&amp;action=view'>
                            <img src='images/common/add.png' class='action' />
                        </a>
                    </td>
                    <td class="si_right">{$customer.id}</td>
                    <td>{$customer.name}</td>
                    <td>{$customer.department}</td>
                    <td class="si_right">
                        <a class='index_table' title='$vname'
                           href='index.php?module=invoices&amp;view=details&amp;id={$customer.last_invoice}&amp;action=view'>
                            {$customer.last_invoice}
                        </a>
                    </td>
                    <td class="si_right">{$customer.total|siLocal_currency|default:'-'}</td>
                    <td class="si_right">{$customer.paid|siLocal_currency|default:'-'}</td>
                    <td class="si_right">{$customer.owing|siLocal_currency|default:'-'}</td>
                    <td class="si_center"><img src='{$customer.enabled_image}' alt='{$customer.enabled}' title='{$customer.enabled}' /></td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}
{/if}
