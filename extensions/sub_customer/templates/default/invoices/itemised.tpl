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
        <img src="images/common/gmail-loader.gif" alt="{$LANG.loading} ..."/>
        {$LANG.loading} ...
    </div>
    {if $first_run_wizard == true}
        <div class="si_message">
            {$LANG.thank_you} {$LANG.before_starting}
        </div>
        {include file="$real_path/itemised_setup_toolbar_table.tpl"}
    {else}
        <div class="si_invoice_form">
            {include file="$path/header.tpl" }
            {include file="$real_path/itemised_itemtable.tpl" }
            <div class="si_toolbar si_toolbar_inform">
                <a href="#" class="add_line_item" data-description="{$LANG.description}">
                    <img src="images/common/add.png" alt=""/>
                    {$LANG.add_new_row}
                </a>
                <a href='#' class="show_details" title="{$LANG.show_details}">
                    <img src="images/common/page_white_add.png" alt=""/>
                    {$LANG.show_details}
                </a>
                <a href='#' class="hide_details si_hide" title="{$LANG.hide_details}">
                    <img src="images/common/page_white_delete.png" alt=""/>
                    {$LANG.hide_details}
                </a>
            </div>
            {include file="$real_path/itemised_invoice_bot.tpl" }
            <br/>
            <input type="hidden" id="max_items" name="max_items" value="{if isset($smarty.section.line.index)}{$smarty.section.line.index|htmlsafe}{/if}"/>
            <input type="hidden" name="type" value="2"/>
            <div class="si_toolbar si_toolbar_form">
                <button type="submit" class="invoice_save" name="submit" value="{$LANG.save}">
                    <img class="button_img" src="images/common/tick.png" alt=""/>{$LANG.save}
                </button>
                <a href="index.php?module=invoices&amp;view=manage" class="negative">
                    <img src="images/common/cross.png" alt=""/>
                    {$LANG.cancel}
                </a>
            </div>
            <div class="si_help_div">
                <a class="cluetip" href="#" title="{$LANG.want_more_fields}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_custom_fields">
                    <img src="{$help_image_path}help-small.png" alt=""/>
                    {$LANG.want_more_fields}
                </a>
            </div>
        </div>
    {/if}
</form>
