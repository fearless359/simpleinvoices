{if $first_run_wizard == true}
    {include file="$path/../initialSetupButtons.tpl" }
{else}
    <div class="grid__area">
        <div class="si__index-help">
            <a href="https://simpleinvoices.group/" target="_blank">
                <h2>{$LANG.needHelp}</h2>
                {$LANG.helpCommunityForums}
            </a>
            <br/>
        </div>
        <div class="grid__container grid__head-12">
            <h3 class="cols__1-span-3 margin__top-0-75">{$LANG.startWorkingUc}</h3>
            <a href="index.php?module=invoices&amp;view=itemized" class="cols__4-span-3 button square margin__right-1">
                <img src="images/add.png" alt="add"/>{$LANG.addNewInvoice}
            </a>
            <a href="index.php?module=customers&amp;view=create" class="cols__7-span-3 button square margin__right-1">
                <img src="images/vcard_add.png" alt="vcard_add"/>{$LANG.addCustomer}
            </a>
            <a href="index.php?module=products&amp;view=create" class="cols__10-span-3 button square margin__right-1">
                <img src="images/cart_add.png" alt="cart_add"/>{$LANG.addNewProduct}
            </a>
        </div>
        <div class="grid__container grid__head-12">
            <h3 class="cols__1-span-3 margin__top-0-75">{$LANG.dontIForgetTo}</h3>
            <a href="index.php?module=options&amp;view=index" class="cols__4-span-3 button square margin__right-1">
                <img src="images/cog_edit.png" alt="cog_edit"/>{$LANG.customizeUc}
            </a>
            <a href="index.php?module=options&amp;view=backup_database" class="cols__7-span-4 button square margin__right-1">
                <img src="images/database_save.png" alt="database_save"/>{$LANG.backupYourDatabase}
            </a>
        </div>
    </div>
{/if}
