<div class="si_delay-display">
    {include file="$path/invoiceTypeButtons.tpl" }
    <form name="frmpost" method="POST" id="frmpost" action="index.php?module=invoices&amp;view=save">
        {if $first_run_wizard == true}
            <div class="si_message">
                {$LANG.thankYou} {$LANG.beforeStarting}
            </div>
            {include file="$path/../initialSetupButtons.tpl"}
        {else}
            <div id="itemtable" class="grid__area">
                {include file="$path/invoiceBillerCustFields.tpl" }
                {include file="$path/itemisedItemtable.tpl" }
                {include file="$path/invoiceItemsShowHide.tpl" }
                {include file="$path/itemisedInvoiceBot.tpl" }
                <br/>
                <input type="hidden" id="max_items" name="max_items" value="{if isset($smarty.section.line.index)}{$smarty.section.line.index|htmlSafe}{/if}"/>
                <input type="hidden" name="type" value="2"/>
                <div class="si_toolbar si_toolbar_form">
                    <button type="submit" class="invoice_save" name="submit" value="{$LANG.save}">
                        <img class="button_img" src="images/tick.png" alt=""/>{$LANG.save}
                    </button>
                    <a href="index.php?module=invoices&amp;view=manage" class="negative">
                        <img src="images/cross.png" alt=""/>
                        {$LANG.cancel}
                    </a>
                </div>
                <div class="si_help_div">
                    <a class="cluetip" href="#" title="{$LANG.wantMoreFields}"
                       rel="index.php?module=documentation&amp;view=view&amp;page=helpInvoiceCustomFields">
                        <img src="{$helpImagePath}help-small.png" alt=""/>
                        {$LANG.wantMoreFields}
                    </a>
                </div>
            </div>
        {/if}
        <input type="hidden" name="op" value="create"/>
    </form>
    <script>
        // This causes the tabs to appear after being rendered
        {literal}
        $(document).ready(function () {
            $("div.si_delay-display").removeClass("si_delay-display");
        });
        {/literal}
    </script>
</div>
