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
<form name="frmpost" action="index.php?module=invoices&amp;view=save" method="post"">
    <div id="gmail_loading" class="gmailLoader si_hide" style="float:right;">
        <img src="images/common/gmail-loader.gif" alt="{$LANG.loading} ..."/>
        {$LANG.loading} ...
    </div>
    {if $first_run_wizard == true}
    <div class="si_message">
        {$LANG.thank_you} {$LANG.before_starting}
    </div>
    {include file="$path/itemised_setup_toolbar_table.tpl"}
    {else}
    <div class="si_invoice_form">
        {include file="$path/header.tpl" }
        {include file="$path/itemised_itemtable.tpl" }
        <div class="si_toolbar si_toolbar_inform">
            <a href="#" class="add_line_item" data_description="{$LANG.description}">
                <img src="images/common/add.png" alt=""/>
                {$LANG.add_new_row}
            </a>
            <a href='#' class="show-details" title="{$LANG.show_details}"
               onclick="$('.details').addClass('si_show').removeClass('si_hide');$('.show-details').addClass('si_hide').removeClass('si_show');">
                <img src="images/common/page_white_add.png" alt=""/>
                {$LANG.show_details}
            </a>
            <a href='#' class="details si_hide" title="{$LANG.hide_details}"
               onclick="$('.details').removeClass('si_show').addClass('si_hide');$('.show-details').addClass('si_show').removeClass('si_hide');">
                <img src="images/common/page_white_delete.png" alt=""/>
                {$LANG.hide_details}
            </a>
        </div>
        {include file="$path/itemised_invoice_bot.tpl" }
        <br/>
        <input type="hidden" id="max_items" name="max_items" value="{$smarty.section.line.index|htmlsafe}"/>
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
               rel="index.php?module=documentation&amp;view=view&amp;page=help_invoice_custom_fields" >
                <img src="{$help_image_path}help-small.png" alt=""/>
                {$LANG.want_more_fields}
            </a>
        </div>
    </div>
</form>
{/if}
