<div class="si_index si_index_settings">

    <h2>{$LANG.systemSettings}</h2>
    <div class="si_toolbar">
        <a href="index.php?module=custom_flags&amp;view=manage" class="" tabindex="10">
            <img src="../../../images/brick_edit.png" alt=""/>
            {$LANG.customFlagsUc}
        </a>

        <a href="index.php?module=custom_fields&amp;view=manage" class="" tabindex="20">
            <img src="../../../images/brick_edit.png" alt=""/>
            {$LANG.customFieldsUc}
        </a>

        <a href="index.php?module=extensions&amp;view=manage" class="" tabindex="30">
            <img src="../../../images/brick_edit.png" alt=""/>
            {$LANG.extensionsUc}
        </a>

        <a href="index.php?module=system_defaults&amp;view=manage" class="" tabindex="40">
            <img src="../../../images/cog_edit.png" alt=""/>
            {$LANG.siDefaults}
        </a>
    </div>

    <h2>{$LANG.invoiceSettings}</h2>
    <div class="si_toolbar">
        <a href="index.php?module=preferences&amp;view=manage" class="" tabindex="50">
            <img src="../../../images/page_white_edit.png" alt=""/>
            {$LANG.invoicePreferences}
        </a>

        <a href="index.php?module=payment_types&amp;view=manage" class="" tabindex="60">
            <img src="../../../images/creditcards.png" alt=""/>
            {$LANG.paymentTypes}
        </a>

        <a href="index.php?module=tax_rates&amp;view=manage" class="" tabindex="70">
            <img src="images/money_delete.png" alt=""/>
            {$LANG.taxRates}
        </a>
    </div>

    <h2>{$LANG.databaseActions}</h2>
    <div class="si_toolbar">
        <a href="index.php?module=options&amp;view=backup_database" class="" tabindex="80">
            <img src="../../../images/database_save.png" alt=""/>
            {$LANG.backupDatabase}
        </a>

        <a href="index.php?module=options&amp;view=manage_cronlog" class="" tabindex="90">
            <img src="../../../images/database_table.png" alt=""/>
            {$LANG.cronUc} {$LANG.databaseLog}
        </a>

        <a href="index.php?module=options&amp;view=manage_sqlpatches" class="" tabindex="100">
            <img src="images/database.png" alt=""/>
            {$LANG.databaseUpgradeManager}
        </a>
    </div>

</div>
