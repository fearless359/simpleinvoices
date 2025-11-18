{if $first_run_wizard == true}
    {include file="$path/../initialSetupButtons.tpl" }
{else}
    <div class="flex__area">
        <div class="si__index-help">
            <a href="https://simpleinvoices.group/" target="_blank">
                <h2>{$LANG.needHelp}</h2>
                {$LANG.helpCommunityForums}
            </a>
            <br/>
        </div>
        <div class="flex__container flex__start">
            <h3>{$LANG.startWorkingUc}:&nbsp;</h3>
            <a href="index.php?module=invoices&amp;view=itemized" class="button square">
                <img src="images/add.png" alt="add" class="desktopOnly"/>{$LANG.addNewInvoice}
            </a>
            <a href="index.php?module=customers&amp;view=create" class="button square">
                <img src="images/vcard_add.png" alt="vcard_add" class="desktopOnly"/>{$LANG.addCustomer}
            </a>
            <a href="index.php?module=products&amp;view=create" class="button square">
                <img src="images/cart_add.png" alt="cart_add" class="desktopOnly"/>{$LANG.addNewProduct}
            </a>
        </div>
        <div class="flex__container flex__start">
            <h3>{$LANG.dontIForgetTo}:&nbsp;</h3>
            <a href="index.php?module=options&amp;view=index" class="button square">
                <img src="images/cog_edit.png" alt="cog_edit" class="desktopOnly"/>{$LANG.customizeUc}
            </a>
            <a href="index.php?module=options&amp;view=backup_database" class="button square">
                <img src="images/database_save.png" alt="database_save" class="desktopOnly"/>{$LANG.backupYourDatabase}
            </a>
        </div>
    </div>
{/if}
