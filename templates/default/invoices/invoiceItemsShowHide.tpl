<div class="si_toolbar si_toolbar_inform">
    <a href="#" class="add_line_item" data-description="{$LANG.descriptionUc}">
        <img src="images/add.png" alt=""/>
        {$LANG.addNewRow}
    </a>
    <a href='#' class="show_details"
       style="display: {if $defaults.invoice_description_open == $smarty.const.ENABLED}none{else}inline-block{/if};"
       title="{$LANG.showDetails}">
        <img src="images/page_white_add.png" alt=""/>
        {$LANG.showDetails}
    </a>
    <a href='#' class="hide_details"
       style="display: {if $defaults.invoice_description_open != $smarty.const.ENABLED}none{else}inline-block{/if};"
       title="{$LANG.hideDetails}">
        <img src="images/page_white_delete.png" alt=""/>
        {$LANG.hideDetails}
    </a>
</div>
<br/>
