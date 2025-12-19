{*
 * Script: details.tpl
 *      Invoice entry template
 *
 *  Last modified:
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *
 * License:
 *      GPL v3 or above
 *
 * Website:
 *      https://simpleinvoices.group
 *}
<div class="form__container delay__display">
    {include file="$path/invoiceTypeButtons.tpl" }
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=invoices&amp;view=save">
        {if $first_run_wizard == true}
            <div class="si_message">
                {$LANG.thankYou} {$LANG.beforeStarting}
            </div>
            {include file="$path/../initialSetupButtons.tpl"}
        {else}
            {include file="$path/invoiceBillerCustFields.tpl" }
            {include file="$path/itemizedItemtable.tpl" }
            {include file="$path/invoiceItemsShowHide.tpl" }
            {include file="$path/itemizedInvoiceBot.tpl" }
            <br/>
            <input type="hidden" id="max_items" name="max_items" value="{$dynamic_line_items|htmlSafe}"/>
            <input type="hidden" id="typeId" name="type" value="2"/>
            <div class="align__text-center">
                <button type="submit" class="invoice_save" name="submit" value="{$LANG.save}">
                    <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
                </button>
                <a href="index.php?module=invoices&amp;view=manage" class="button negative">
                    <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
                </a>
            </div>
            <div class="si__help-div">
                <a class="tooltip" title="{$LANG.helpInvoiceCustomFields}">
                    <img src="{$helpImagePath}help-small.png" alt="{$LANG.wantMoreFields}"/>{$LANG.wantMoreFields}
                </a>
            </div>
        {/if}
        <input type="hidden" name="op" value="create"/>
    </form>
    <script>
        // This causes the tabs to appear after being rendered
        {literal}
        $(document).ready(function () {
            $("div.delay__display").removeClass("delay__display");
        });
        {/literal}
    </script>
</div>
