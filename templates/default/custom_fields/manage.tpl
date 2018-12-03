{*
 * Script: manage.tpl
 * 	 Custom fields manage template
 *
 * License:
 *	 GPL v2 or above
 *}
{if !isset($cfs)}
	<div class="si_message">{$LANG.no_invoices}.</div>
{else}
    <table id="manageGrid" style="display:none"></table>
    {include file='modules/custom_fields/manage.js.php'}
    <div class="si_help_div">
	    <a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_what_are_custom_fields" title="{$LANG.what_are_custom_fields}">
            {$LANG.what_are_custom_fields}
            <img src="{$help_image_path}help-small.png" alt="" />
        </a>
        ::
	    <a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_manage_custom_fields" title="{$LANG.whats_this_page_about}">
            {$LANG.whats_this_page_about}
            <img src="{$help_image_path}help-small.png" alt="" />
        </a>
    </div>
{/if}
