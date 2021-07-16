<div>
    <a href="#" class="addLineItem button small" data-description="{$LANG.descriptionUc}">
        <img src="images/add.png" alt="{$LANG.addNewRow}"/>{$LANG.addNewRow}
    </a>
    <a href='#' class="show_details button small" title="{$LANG.showDetails}"
       {if $defaults.invoice_description_open == $smarty.const.ENABLED}style="display: none;"{/if}>
        <img src="images/page_white_add.png" alt="{$LANG.showDetails}"/>{$LANG.showDetails}
    </a>
    <a href='#' class="hide_details button small" title="{$LANG.hideDetails}"
       {if $defaults.invoice_description_open != $smarty.const.ENABLED}style="display: none;"{/if}>
        <img src="images/page_white_delete.png" alt="{$LANG.hideDetails}"/>{$LANG.hideDetails}
    </a>
</div>
<br/>
