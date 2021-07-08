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
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-2 bold">{$LANG.amountUc}:</div>
        <div class="cols__6-span-5">{$expense.amount|utilCurrency}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-2 bold">{$LANG.tax}:</div>
        <div class="cols__6-span-5">
            {foreach $detail.expense_tax_grouped as $tax}
                {$tax.tax_name}: {$tax.tax_amount|utilCurrency}
            {/foreach}
        </div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-2 bold">{$LANG.totalUc}:</div>
        <div class="cols__6-span-5">{$detail.expense_tax_total|utilCurrency}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-2 bold">{$LANG.expenseAccount}:</div>
        <div class="cols__6-span-5">{$expense.ea_name}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-2 bold">{$LANG.dateUc}:</div>
        <div class="cols__6-span-5">{$expense.date|htmlSafe}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-2 bold">{$LANG.billerUc}:</div>
        <div class="cols__6-span-5">{if isset($expense.b_name)}{$expense.b_name}{/if}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-2 bold">{$LANG.customerUc}:</div>
        <div class="cols__6-span-5">{if isset($expense.c_name)}{$expense.c_name}{/if}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-2 bold">{$LANG.invoiceUc}:</div>
        <div class="cols__6-span-5">{if isset($detail.invoice.index_name)}{$detail.invoice.index_name}{/if}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-2 bold">{$LANG.productUc}:</div>
        <div class="cols__6-span-5">{if isset($expense.p_desc)}{$expense.p_desc}{/if}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-2 bold">{$LANG.status}:</div>
        <div class="cols__6-span-5">{$expense.status_wording}</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-2 bold">{$LANG.notes}:</div>
    </div>
    <div class="grid__container grid__head-10">
        <div class="cols__4-span-7">{$expense.note|outHtml}</div>
    </div>
</div>
<div class="align__text-center margin__top-2 margin__bottom-2">
    <a href="index.php?module=expense&amp;view=edit&amp;id={$expense.eid}" class="button positive">
        <img src="images/add.png" alt="{$LANG.edit}"/>{$LANG.edit}
    </a>
    <a href="index.php?module=expense&amp;view=manage" class="button negative">
        <img src="images/cross.png" alt="{$LANG.cancel}"/>{$LANG.cancel}
    </a>
</div>
