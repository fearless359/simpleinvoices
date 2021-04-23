{*
 *  Script: itemised.tpl
 *      Itemized invoice template
 *
 *  License:
 *      GPL v3 or above
 *
 *  Last modified:
 *      2018-10-06 by Richard Rowley
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=invoices&amp;view=save">
    <div id="gmail_loading" class="gmailLoader si_hide" style="float:right;">
        <img src="images/gmail-loader.gif" alt="{$LANG.loading} ..."/>
        {$LANG.loading} ...
    </div>
    {if $first_run_wizard == true}
        <div class="si_message">
            {$LANG.thankYou} {$LANG.beforeStarting}
        </div>
        {include file="$path/itemised_setup_toolbar_table.tpl"}
    {else}
        <div class="si_invoice_form">
            {include file="$path/header.tpl" }
            {include file="$path/itemised_itemtable.tpl" }
            <div class="si_toolbar si_toolbar_inform">
                <a href="#" class="add_line_item" data-description="{$LANG.descriptionUc}">
                    <img src="images/add.png" alt=""/>
                    {$LANG.addNewRow}
                </a>
                <a href='#' class="show_details {if $defaults.invoice_description_open == $smarty.const.ENABLED}si_hide{/if}"
                   title="{$LANG.showDetails}">
                    <img src="images/page_white_add.png" alt=""/>
                    {$LANG.showDetails}
                </a>
                <a href='#' class="hide_details {if $defaults.invoice_description_open == $smarty.const.DISABLED}si_hide{/if}"
                   title="{$LANG.hideDetails}">
                    <img src="images/page_white_delete.png" alt=""/>
                    {$LANG.hideDetails}
                </a>
            </div>
            {include file="$path/itemised_invoice_bot.tpl" }
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
</form>
