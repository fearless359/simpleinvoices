{*
 *  Script: index.tpl
 *      Customize options template
 *
 *  Last edited:
 * 	    20210702 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="grid__area">
    <div class="grid__container grid__head-12">
        <h3 class="cols__1-span-3 margin__top-2">{$LANG.systemSettings}</h3>
        <a href="index.php?module=custom_flags&amp;view=manage" class="cols__4-span-2 button square margin__top-1-5 margin__right-1" tabindex="10">
            <img src="images/brick_edit.png" alt="{$LANG.customFlagsUc}"/>&nbsp;{$LANG.customFlagsUc}
        </a>
        <a href="index.php?module=custom_fields&amp;view=manage" class="cols__6-span-2 button square margin__top-1-5 margin__right-1" tabindex="20">
            <img src="images/brick_edit.png" alt="{$LANG.customFieldsUc}"/>&nbsp;{$LANG.customFieldsUc}
        </a>
        <a href="index.php?module=extensions&amp;view=manage" class="cols__8-span-2 button square margin__top-1-5 margin__right-1" tabindex="30">
            <img src="images/brick_edit.png" alt="{$LANG.extensionsUc}"/>&nbsp;{$LANG.extensionsUc}
        </a>
        <a href="index.php?module=system_defaults&amp;view=manage" class="cols__10-span-2 button square margin__top-1-5 margin__right-1" tabindex="40">
            <img src="images/cog_edit.png" alt="{$LANG.siDefaults}"/>&nbsp;{$LANG.siDefaults}
        </a>
    </div>
    <div class="grid__container grid__head-12">
        <h3 class="cols__1-span-3 margin__top-2">{$LANG.invoiceSettings}</h3>
        <a href="index.php?module=preferences&amp;view=manage" class="cols__4-span-3 button square margin__top-1-5 margin__right-1" tabindex="50">
            <img src="images/page_white_edit.png" alt="{$LANG.invoicePreferences}"/>&nbsp;{$LANG.invoicePreferences}
        </a>
        <a href="index.php?module=payment_types&amp;view=manage" class="cols__7-span-3 button square margin__top-1-5 margin__right-1" tabindex="60">
            <img src="images/creditcards.png" alt="{$LANG.paymentTypes}"/>&nbsp;{$LANG.paymentTypes}
        </a>
        <a href="index.php?module=tax_rates&amp;view=manage" class="cols__10-span-2 button square margin__top-1-5 margin__right-1" tabindex="70">
            <img src="images/money_delete.png" alt="{$LANG.taxRates}"/>&nbsp;{$LANG.taxRates}
        </a>
    </div>
    <div class="grid__container grid__head-12">
        <h3 class="cols__1-span-3 margin__top-2">{$LANG.databaseActions}</h3>
        <a href="index.php?module=options&amp;view=backup_database" class="cols__4-span-3 button square margin__top-1-5 margin__right-1" tabindex="80">
            <img src="images/database_save.png" alt="{$LANG.backupDatabase}"/>&nbsp;{$LANG.backupDatabase}
        </a>
        <a href="index.php?module=options&amp;view=manage_cronlog" class="cols__7-span-2 button square margin__top-1-5 margin__right-1" tabindex="90">
            <img src="images/database_table.png" alt="{$LANG.cronUc} {$LANG.logUc}"/>&nbsp;{$LANG.cronUc}&nbsp;{$LANG.logUc}
        </a>
        <a href="index.php?module=options&amp;view=manage_sqlpatches" class="cols__9-span-4 button square margin__top-1-5 margin__right-1" tabindex="100">
            <img src="images/database.png" alt="{$LANG.databaseUpgradeManager}"/>&nbsp;{$LANG.databaseUpgradeManager}
        </a>
    </div>
