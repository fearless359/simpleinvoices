<form name="frmpost" action="index.php?module=expense&view=save&id={$smarty.get.id}" method="post">
    {if $smarty.get.action== 'view' }
        <br/>
        <table class="center">
            <tr>
                <th class="left">{$LANG.amount}:</th>
                <td>{$expense.amount|siLocal_number}</td>
            </tr>
            <tr>
                <th class="left">{$LANG.tax}:</th>
                <td>
                    {foreach from=$detail.expense_tax_grouped item=tax}
                        {$tax.tax_name}: {$tax.tax_amount|siLocal_number}
                    {/foreach}
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.total}:</th>
                <td>{$detail.expense_tax_total|siLocal_number}</td>
            </tr>
            <tr>
                <th class="left">{$LANG.expense_accounts}:&nbsp;</th>
                <td>{$detail.expense_account.name}</td>
            </tr>
            <tr>
                <th class="left">{$LANG.date_upper}:</th>
                <td>{$expense.date|siLocal_date}</td>
            </tr>
            <tr>
                <th class="left">{$LANG.biller}:</th>
                <td>{$detail.biller.name}</td>
            </tr>
            <tr>
                <th class="left">{$LANG.customer}:</th>
                <td>{$detail.customer.name}</td>
            </tr>
            <tr>
                <th class="left">{$LANG.invoice}:</th>
                <td>{$detail.invoice.index_name}</td>
            </tr>
            <tr>
                <th class="left">{$LANG.product}:</th>
                <td>{$detail.product.description}</td>
            </tr>
            <tr>
                <th class="left">{$LANG.status}:</th>
                <td>{$detail.status_wording}</td>
            </tr>
            <tr>
                <th class="left" colspan="2">{$LANG.notes}:</th>
            </tr>
            <tr>
                <td colspan="2">{$expense.note|htmlsafe}</td>
            </tr>
        </table>
        <br/>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=expense&view=details&id={$expense.id}&action=edit" class="positive">
                <img src="images/famfam/add.png" alt=""/>
                {$LANG.edit}
            </a>
        </div>
    {elseif $smarty.get.action== 'edit' }
        <br/>
        <input type="hidden" name="op" value="edit"/>
        <input type="hidden" name="domain_id" value="{$expense.domain_id}"/>
        <table class="left" width="100%">
            <tr>
                <th class="left">{$LANG.amount}</th>
                <td>
                    <input name="amount" class="validate[required]" value="{$expense.amount|siLocal_number}"/>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.expense_accounts}</th>
                <td>
                    <select name="expense_account_id" class="validate[required]">
                        <option value=''></option>
                        {foreach from=$detail.expense_accounts item=expense_account}
                            <option {if $expense_account.id == $expense.expense_account_id}selected{/if}
                                    value="{$expense_account.id}">{$expense_account.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.date_formatted}</th>
                <td>
                    <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                           size="10" name="date" id="date" value='{$expense.date}'/>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.biller}</th>
                <td>
                    <select name="biller_id" class="validate[required]">
                        <option value=''></option>
                        {foreach from=$detail.billers item=biller}
                            <option {if $biller.id == $expense.biller_id} selected {/if}
                                    value="{$biller.id}">{$biller.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.customer}</th>
                <td>
                    <select name="customer_id">
                        <option value=''></option>
                        {foreach from=$detail.customers item=customer}
                            <option {if $customer.id == $expense.customer_id} selected {/if}
                                    value="{$customer.id}">{$customer.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.invoice}</th>
                <td>
                    <select name="invoice_id">
                        <option value=''></option>
                        {foreach from=$detail.invoices item=invoice}
                            <option {if $invoice.id == $expense.invoice_id} selected {/if}
                                    value="{$invoice.id}">{$invoice.index_name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.product}</th>
                <td>
                    <select name="product_id">
                        <option value=''></option>
                        {foreach from=$detail.products item=product}
                            <option {if $product.id == $expense.product_id} selected {/if}
                                    value="{$product.id}">{$product.description}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            {if $defaults.tax_per_line_item > 0}
            <tr>
                <th class="left">{$LANG.tax}</th>
                <td>
                    <table>
                        <tr>
                            {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                                <td>
                                    <select id="tax_id[0][{$smarty.section.tax.index}]" name="tax_id[0][{$smarty.section.tax.index}]">
                                        <option value=""></option>
                                        {assign var="index" value=$smarty.section.tax.index}
                                        {foreach from=$taxes item=tax}
                                            <option {if $tax.tax_id === $detail.expense_tax.$index.tax_id} selected {/if}
                                                    value="{$tax.tax_id}">{$tax.tax_description}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            {/section}
                        </tr>
                    </table>
                <td>
            </tr>
            {/if}
            <tr>
                <th class="left">{$LANG.status}</th>
                <td>
                    {* enabled block *}
                    <select name="status">
                        <option value="{$expense.status}" selected style="font-weight: bold;">{$detail.status_wording}</option>
                        <option value="1">{$LANG.paid}</option>
                        <option value="0">{$LANG.not_paid}</option>
                    </select>
                    {* /enabled block*}
                </td>
            </tr>
            <tr>
                <th class="left" colspan="2">{$LANG.notes}</th>
            </tr>
            <tr>
                <td colspan="2">
                    <textarea class="editor" name='note' rows="4">{$expense.note|htmlsafe}</textarea>
                </td>
            </tr>
            <tr><td colspan="2">&nbsp;</td></tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_product" value="{$LANG.save}">
                <img class="button_img" src="images/common/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=expense&amp;view=manage" class="negative">
                <img src="images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
    {/if}
</form>
