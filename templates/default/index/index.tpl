{if $first_run_wizard == true}
    <div class="si_message">
        {$LANG.thankYou} {$LANG.beforeStarting}
    </div>
    <table class="si_table_toolbar">
        {if empty($billers)}
            <tr>
                <th>{$LANG.setupAsBiller}</th>
                <td class="si_toolbar">
                    <a href="index.php?module=billers&amp;view=create" class="positive">
                        <img src="images/user_add.png" alt=""/>
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
                        <img src="images/vcard_add.png" alt=""/>
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
                        <img src="images/cart_add.png" alt=""/>
                        {$LANG.addNewProduct}
                    </a>
                </td>
            </tr>
        {/if}
        <tr>
            <th>{$LANG.setupCustomization}</th>
            <td class="si_toolbar">
                <a href="index.php?module=system_defaults&amp;view=manage" class="">
                    <img src="images/cog_edit.png" alt=""/>
                    {$LANG.siDefaults}
                </a>
            </td>
        </tr>
    </table>
{else}
    <div class="si_index si_index_home">
        <div class="si_index_help">
            <h2>{$LANG.needHelp}</h2>
            <a href="https://simpleinvoices.group/" target="_blank">{$LANG.helpCommunityForums}</a><br/>
        </div>
        <h2>{$LANG.startWorkingUc}</h2>
        <div class="si_toolbar">
            <a href="index.php?module=invoices&amp;view=itemised" class="">
                <img src="images/add.png" alt=""/>{$LANG.addNewInvoice}
            </a>
            <a href="index.php?module=customers&amp;view=create" class="">
                <img src="images/vcard_add.png" alt=""/>{$LANG.addCustomer}
            </a>
            <a href="index.php?module=products&amp;view=create" class="">
                <img src="images/cart_add.png" alt=""/>{$LANG.addNewProduct}
            </a>
        </div>
        <h2 class="align_left">{$LANG.dontIForgetTo}</h2>
        <div class="si_toolbar">
            <a href="index.php?module=options&amp;view=index" class="">
                <img src="images/cog_edit.png" alt=""/>{$LANG.customizeUc}
            </a>
            <a href="index.php?module=options&amp;view=backup_database" class="">
                <img src="images/database_save.png" alt=""/>{$LANG.backupYourDatabase}
            </a>
        </div>
    </div>
{/if}
