<div class="si_filters align__text-center margin__bottom-2">
    <span class="si_filters_links">
        <a href="index.php?module=invoices&amp;view=itemized{if isset($template)}&amp;template={$template}{/if}{if isset($defaultCustomerID)}&amp;customer_id={$defaultCustomerID}{/if}"
           class="first{if $view=='itemized'} selected{/if}">
            <img class="action" src="images/edit.png" alt="{$LANG.itemizedStyle}"/>{$LANG.itemizedStyle}
        </a>
        <a href="index.php?module=invoices&amp;view=total{if isset($template)}&amp;template={$template}{/if}{if isset($defaultCustomerID)}&amp;customer_id={$defaultCustomerID}{/if}"
           class="{if $view=='total'}selected{/if}">
            <img class="action" src="images/page_white_edit.png" alt="{$LANG.totalStyle}"/>{$LANG.totalStyle}
        </a>
    </span>
    <span class="si_filters_title">
        <img class="tooltip" title="{$LANG.helpInvoiceTypes}" src="{$helpImagePath}help-small.png" alt=""/>
    </span>
</div>
