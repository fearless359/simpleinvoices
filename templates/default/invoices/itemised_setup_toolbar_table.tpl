<table class="si_table_toolbar">
    {if empty($billers)}
        <tr>
            <th>{$LANG.setupAsBiller}</th>
            <td class="si_toolbar">
                <a href="index.php?module=billers&amp;view=create" class="positive">
                    <img src="../../../images/user_add.png" alt=""/>
                    {$LANG.addNewBiller}
                </a>
            </td>
        </tr>
    {/if}
    {if empty($customers)}
        <tr>
            <th>{$LANG.setupAddCustomer}</th>
            <td class="si_toolbar">
                <a href="index.php?module=customers&amp;view=create" class="positive">
                    <img src="../../../images/vcard_add.png" alt=""/>
                    {$LANG.customerAdd}
                </a>
            </td>
        </tr>
    {/if}
    {if empty($products)}
        <tr>
            <th>{$LANG.setupAddProducts}</th>
            <td class="si_toolbar">
                <a href="index.php?module=products&amp;view=create" class="positive">
                    <img src="../../../images/cart_add.png" alt=""/>
                    {$LANG.addNewProduct}
                </a>
            </td>
        </tr>
    {/if}
    <tr>
        <th>{$LANG.setupCustomization}</th>
        <td class="si_toolbar">
            <a href="index.php?module=system_defaults&amp;view=manage" class="">
                <img src="../../../images/cog_edit.png" alt=""/>
                {$LANG.siDefaults}
            </a>
        </td>
    </tr>
</table>
