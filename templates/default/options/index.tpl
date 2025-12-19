{*
 *  Script: index.tpl
 *      Customize options template
 *
 *  Last edited:
 *      20251207 by Rich Rowley to convert grid to flex format.
 * 	    20210702 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="form__container">
    <div class="flex__container flex__start">
        <h3>{$LANG.systemSettings}:</h3>
        <a href="index.php?module=custom_flags&amp;view=manage" class="button square" tabindex="10">
            <img src="images/brick_edit.png" alt="{$LANG.customFlagsUc}" class="desktopOnly"/>{$LANG.customFlagsUc}
        </a>
        <a href="index.php?module=custom_fields&amp;view=manage" class="button square" tabindex="20">
            <img src="images/brick_edit.png" alt="{$LANG.customFieldsUc}" class="desktopOnly"/>{$LANG.customFieldsUc}
        </a>
        <a href="index.php?module=extensions&amp;view=manage" class="button square" tabindex="30">
            <img src="images/brick_edit.png" alt="{$LANG.extensionsUc}" class="desktopOnly"/>{$LANG.extensionsUc}
        </a>
        <a href="index.php?module=system_defaults&amp;view=manage" class="button square" tabindex="40">
            <img src="images/cog_edit.png" alt="{$LANG.siDefaults}" class="desktopOnly"/>{$LANG.siDefaults}
        </a>
    </div>
    <div class="flex__container flex__start">
        <h3>{$LANG.invoiceSettings}:</h3>
        <a href="index.php?module=preferences&amp;view=manage" class="button square" tabindex="50">
            <img src="images/page_white_edit.png" alt="{$LANG.invoicePreferences}"
                 class="desktopOnly"/>{$LANG.invoicePreferences}
        </a>
        <a href="index.php?module=payment_types&amp;view=manage" class="button square" tabindex="60">
            <img src="images/creditcards.png" alt="{$LANG.paymentTypes}" class="desktopOnly"/>{$LANG.paymentTypes}
        </a>
        <a href="index.php?module=tax_rates&amp;view=manage" class="button square" tabindex="70">
            <img src="images/money_delete.png" alt="{$LANG.taxRates}" class="desktopOnly"/>{$LANG.taxRates}
        </a>
    </div>
    <div class="flex__container flex__start">
        <h3>{$LANG.databaseActions}:</h3>
        <a href="index.php?module=options&amp;view=backup_database" class="button square" tabindex="80">
            <img src="images/database_save.png" alt="{$LANG.backupDatabase}"/>{$LANG.backupDatabase}
        </a>
        <a href="index.php?module=options&amp;view=manage_cronlog" class="button square" tabindex="90">
            <img src="images/database_table.png" alt="{$LANG.cronUc} {$LANG.logUc}"/>{$LANG.cronUc}
            &nbsp;{$LANG.logUc}
        </a>
        <a href="index.php?module=options&amp;view=manage_sqlpatches" class="button square" tabindex="100">
            <img src="images/database.png" alt="{$LANG.databaseUpgradeManager}"/>{$LANG.databaseUpgradeManager}
        </a>
    </div>
