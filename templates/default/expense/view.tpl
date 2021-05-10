<br/>
<div class="si_form" id="si_form_cust">
    <div class="si_cust_info">
        <table class="center">
            <tr>
                <th class="details_screen">{$LANG.amountUc}:</th>
                <td>{$expense.amount|utilCurrency}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.tax}:</th>
                <td>
                    {foreach $detail.expense_tax_grouped as $tax}
                        {$tax.tax_name}: {$tax.tax_amount|utilCurrency}
                    {/foreach}
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.totalUc}:</th>
                <td>{$detail.expense_tax_total|utilCurrency}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.expenseAccount}:</th>
                <td>{$expense.ea_name}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.dateUc}:</th>
                <td>{$expense.date|htmlSafe}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.billerUc}:</th>
                <td>{if isset($expense.b_name)}{$expense.b_name}{/if}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.customerUc}:</th>
                <td>{if isset($expense.c_name)}{$expense.c_name}{/if}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.invoiceUc}:</th>
                <td>{if isset($detail.invoice.index_name)}{$detail.invoice.index_name}{/if}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.productUc}:</th>
                <td>{if isset($expense.p_desc)}{$expense.p_desc}{/if}</td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.status}:</th>
                <td>{$expense.status_wording}</td>
            </tr>
            <tr>
                <th class="details_screen" colspan="2">{$LANG.notes}:</th>
            </tr>
            <tr>
                <td colspan="2">{$expense.note|outHtml}</td>
            </tr>
        </table>
        <br/>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=expense&amp;view=edit&amp;id={$expense.eid}" class="positive">
                <img src="images/add.png" alt=""/>
                {$LANG.edit}
            </a>
            <a href="index.php?module=expense&amp;view=manage"
               class="negative">
                <img src="images/cross.png" alt="{$LANG.cancel}"/>
                {$LANG.cancel}
            </a>
        </div>
    </div>
</div>
