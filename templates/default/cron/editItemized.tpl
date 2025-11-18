<form name="frmpost" method="POST" id="frmpost" action="index.php?module=cron&amp;view=saveInvoiceItems">
    <div class='flex__area'>
        <div class="flex__container flex__start">
            <div class="bold margin__right-1">{$LANG.cronUc} {$LANG.idUc}:</div>
            <div>{$cron.id|htmlSafe}</div>
        </div>
        <div class="flex__container flex__start">
            <div class="bold margin__right-1">{$LANG.toUc} {$LANG.beUc} {$LANG.copiedUc} {$LANG.from} {$preference.pref_inv_wording|htmlSafe} {$LANG.numberShort}:</div>
            <div>{$invoice.index_id|htmlSafe}</div>
        </div>
        <br/>
        {include file="$path/editItemizedInvoiceItems.tpl"}
        {include file="../invoices/invoiceItemsShowHide.tpl"}
        <div class="align__text-center">
            {* invoice_save class used to activate function in jquery.conf1.js file *}
            <button type="submit" class="invoice_save positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
            </button>
            <a href="index.php?module=cron&amp;view=edit&amp;id={$cron.id}" class="button negative">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
            </a>
        </div>
        <input type="hidden" name="cron_id" value="{$cron.id|htmlSafe}"/>
        <input type="hidden" name="op" value="edit"/>
        <input type="hidden" id="max_items" name="max_items" value="{$cronInvoiceItemCount|htmlSafe}">
    </div>
</form>
