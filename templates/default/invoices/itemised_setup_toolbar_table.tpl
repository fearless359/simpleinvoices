<table class="si_table_toolbar">
    {if empty($billers)}
        <tr>
            <th>{$LANG.setup_as_biller}</th>
            <td class="si_toolbar">
                <a href="index.php?module=billers&amp;view=create" class="positive">
                    <img src="../../../images/user_add.png" alt=""/>
                    {$LANG.add_new_biller}
                </a>
            </td>
        </tr>
    {/if}
    {if empty($customers)}
        <tr>
            <th>{$LANG.setup_add_customer}</th>
            <td class="si_toolbar">
                <a href="index.php?module=customers&amp;view=create" class="positive">
                    <img src="../../../images/vcard_add.png" alt=""/>
                    {$LANG.customer_add}
                </a>
            </td>
        </tr>
    {/if}
    {if empty($products)}
        <tr>
            <th>{$LANG.setup_add_products}</th>
            <td class="si_toolbar">
                <a href="index.php?module=products&amp;view=create" class="positive">
                    <img src="../../../images/cart_add.png" alt=""/>
                    {$LANG.add_new_product}
                </a>
            </td>
        </tr>
    {/if}
    <tr>
        <th>{$LANG.setup_customisation}</th>
        <td class="si_toolbar">
            <a href="index.php?module=system_defaults&amp;view=manage" class="">
                <img src="../../../images/cog_edit.png" alt=""/>
                {$LANG.si_defaults}
            </a>
        </td>
    </tr>
</table>
