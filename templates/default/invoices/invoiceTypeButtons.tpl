<div class="si_filters si_buttons_invoice_header">
    <span class="si_filters_links">
        <a href="index.php?module=invoices&amp;view=itemised{if isset($template)}&amp;template={$template}{/if}{if isset($defaultCustomerID)}&amp;customer_id={$defaultCustomerID}{/if}"
           class="first{if $view=='itemised'} selected{/if}">
            <img class="action" src="images/edit.png" alt=""/>
            {$LANG.itemizedStyle}
        </a>
        <a href="index.php?module=invoices&amp;view=total{if isset($template)}&amp;template={$template}{/if}{if isset($defaultCustomerID)}&amp;customer_id={$defaultCustomerID}{/if}"
           class="{if $view=='total'}selected{/if}">
            <img class="action" src="images/page_white_edit.png" alt=""/>
            {$LANG.totalStyle}
        </a>
    </span>
    <span class="si_filters_title">
        <a class="cluetip" href="#" title="{$LANG.invoiceType}"
           rel="index.php?module=documentation&amp;view=view&amp;page=helpInvoiceTypes">
            <img src="{$helpImagePath}help-small.png" alt=""/>
        </a>
    </span>
</div>
