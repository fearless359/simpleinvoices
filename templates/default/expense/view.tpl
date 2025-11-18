{*
 * Script: view.tpl
 *   Expense details template
 *
 * Last edited:
 *   20210622 by Rich Rowley to convert to grid layout.
 *
 * License:
 *   GPL v3 or above
*}
<div class="flex__area">
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.amountUc}:</div>
        <div>{$expense.amount|utilCurrency:$expense.locale:$expense.currency_code}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.tax}:</div>
        <div>
            {foreach $detail.expense_tax_grouped as $tax}
                {$tax.tax_name}: {$tax.tax_amount|utilCurrency:$expense.locale:$expense.currency_code}
            {/foreach}
        </div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.totalUc}:</div>
        <div>{$detail.expense_tax_total|utilCurrency:$expense.locale:$expense.currency_code}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.expenseAccount}:</div>
        <div>{$expense.ea_name}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.dateUc}:</div>
        <div>{$expense.date|htmlSafe}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.billerUc}:</div>
        <div>{if isset($expense.b_name)}{$expense.b_name}{/if}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.customerUc}:</div>
        <div>{if isset($expense.c_name)}{$expense.c_name}{/if}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.invoiceUc}:</div>
        <div>{if isset($detail.invoice.index_name)}{$detail.invoice.index_name}{/if}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.productUc}:</div>
        <div>{if isset($expense.p_desc)}{$expense.p_desc}{/if}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold margin__right-1">{$LANG.status}:</div>
        <div>{$expense.status_wording}</div>
    </div>
    <div class="flex__container flex__start">
        <div class="bold">{$LANG.notes}:</div>
    </div>
    <div class="flex__container flex__start">
        <div>{$expense.note|outHtml}</div>
    </div>
</div>
<div class="align__text-center margin__top-3 margin__bottom-2">
    <a href="index.php?module=expense&amp;view=edit&amp;id={$expense.eid}" class="button positive">
        <img src="images/add.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=expense&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
