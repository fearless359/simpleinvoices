<!--suppress HtmlFormInputWithoutLabel, HtmlUnknownTag -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=expense&amp;view=save&amp;id={$smarty.get.id}">
    {if $smarty.get.action== 'view' }
        <br/>
        <table class="center">
            <tr>
                <th>{$LANG.amount_uc}:</th>
                <td>{$expense.amount|siLocal_currency}</td>
            </tr>
            <tr>
                <th>{$LANG.tax}:</th>
                <td>
                    {foreach $detail.expense_tax_grouped as $tax}
                        {$tax.tax_name}: {$tax.tax_amount|siLocal_currency}
                    {/foreach}
                </td>
            </tr>
            <tr>
                <th>{$LANG.total}:</th>
                <td>{$detail.expense_tax_total|siLocal_currency}</td>
            </tr>
            <tr>
                <th>{$LANG.expense_account}:&nbsp;</th>
                <td>{$expense.ea_name}</td>
            </tr>
            <tr>
                <th>{$LANG.date_uc}:</th>
                <td>{$expense.date|siLocal_date}</td>
            </tr>
            <tr>
                <th>{$LANG.biller}:</th>
                <td>{if isset($expense.b_name)}{$expense.b_name}{/if}</td>
            </tr>
            <tr>
                <th>{$LANG.customer}:</th>
                <td>{if isset($expense.c_name)}{$expense.c_name}{/if}</td>
            </tr>
            <tr>
                <th>{$LANG.invoice_uc}:</th>
                <td>{if isset($detail.invoice.index_name)}{$detail.invoice.index_name}{/if}</td>
            </tr>
            <tr>
                <th>{$LANG.product_uc}:</th>
                <td>{if isset($expense.p_desc)}{$expense.p_desc}{/if}</td>
            </tr>
            <tr>
                <th>{$LANG.status}:</th>
                <td>{$expense.status_wording}</td>
            </tr>
            <tr>
                <th colspan="2">{$LANG.notes}:</th>
            </tr>
            <tr>
                <td colspan="2">{$expense.note|outhtml}</td>
            </tr>
        </table>
        <br/>
        <div class="si_toolbar si_toolbar_form">
            <a href="index.php?module=expense&amp;view=details&amp;id={$expense.EID}&amp;action=edit" class="positive">
                <img src="../../../images/add.png" alt=""/>
                {$LANG.edit}
            </a>
            <a href="index.php?module=expense&amp;view=manage"
               class="negative"> <img src="../../../images/cross.png" alt="{$LANG.cancel}" />
                {$LANG.cancel}
            </a>
        </div>
    {elseif $smarty.get.action== 'edit' }
        <br/>
        <input type="hidden" name="op" value="edit"/>
        <input type="hidden" name="domain_id" value="{if isset($expense.domain_id)}{$expense.domain_id}{/if}"/>
        <table style="width:100%;">
            <tr>
                <th>{$LANG.amount_uc}</th>
                <td>
                    <input name="amount" class="validate[required]" value="{$expense.amount|siLocal_number}"/>
                </td>
            </tr>
            <tr>
                <th>{$LANG.expense_accounts}</th>
                <td>
                    <select name="expense_account_id" class="validate[required]">
                        <option value=''></option>
                        {foreach from=$detail.expense_accounts item=expense_account}
                            <option {if $expense_account.id == $expense.ea_id}selected{/if}
                                    value="{if isset($expense_account.id)}{$expense_account.id}{/if}">{$expense_account.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th>{$LANG.date_formatted}</th>
                <td>
                    <input type="text" class="validate[required,custom[date],length[0,10]] date-picker"
                           size="10" name="date" id="date" value='{$expense.date}'/>
                </td>
            </tr>
            <tr>
                <th>{$LANG.biller}</th>
                <td>
                    <select name="biller_id" class="validate[required]">
                        <option value=''></option>
                        {foreach from=$detail.billers item=biller}
                            <option {if $biller.id == $expense.b_id} selected {/if}
                                    value="{if isset($biller.id)}{$biller.id}{/if}">{$biller.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th>{$LANG.customer}</th>
                <td>
                    <select name="customer_id">
                        <option value=''></option>
                        {foreach from=$detail.customers item=customer}
                            <option {if $customer.id == $expense.c_id} selected {/if}
                                    value="{if isset($customer.id)}{$customer.id}{/if}">{$customer.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th>{$LANG.invoice_uc}</th>
                <td>
                    <select name="invoice_id">
                        <option value=''></option>
                        {foreach from=$detail.invoices item=invoice}
{*                            <option {if $invoice.id == $expense.iv_id} selected {/if}*}
{*                                    value="{$invoice.id}">{$invoice.index_name}</option>*}
                            <option value="{if isset($invoice.id)}{$invoice.id|htmlsafe}{/if}" {if $invoice.id ==  $expense.iv_id}selected{/if} >
                                Inv#{$invoice.index_id}: ({$invoice.biller|htmlsafe}, {$invoice.customer|htmlsafe})
                            </option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th>{$LANG.product_uc}</th>
                <td>
                    <select name="product_id">
                        <option value=''></option>
                        {foreach from=$detail.products item=product}
                            <option {if $product.id == $expense.p_id} selected {/if}
                                    value="{if isset($product.id)}{$product.id}{/if}">{$product.description}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            {if $defaults.tax_per_line_item > 0}
            <tr>
                <th>{$LANG.tax}</th>
                <td>
                    <table>
                        <tr>
                            {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                                <td>
                                    <select id="tax_id[0][{$smarty.section.tax.index}]" name="tax_id[0][{$smarty.section.tax.index}]">
                                        <option value=''></option>
                                        {assign var="index" value=$smarty.section.tax.index}
                                        {foreach $taxes as $tax}
                                            <option {if !empty($detail.expense_tax) && $tax.tax_id === $detail.expense_tax.$index.tax_id}selected {/if}
                                                    value='{$tax.tax_id}'>{$tax.tax_description}</option>
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
                <th>{$LANG.status}</th>
                <td>
                    {* enabled block *}
                    <select name="status">
                        <option value="{$smarty.const.ENABLED }" {if isset($expense.status) && $expense.status == $smarty.const.ENABLED }selected{/if}>{$LANG.paid}</option>
                        <option value="{$smarty.const.DISABLED}" {if isset($expense.status) && $expense.status == $smarty.const.DISABLED}selected{/if}>{$LANG.not_paid}</option>
                    </select>
                    {* /enabled block*}
                </td>
            </tr>
            <tr>
                <th>{$LANG.notes}</th>
            </tr>
            <tr>
                <td>
                    <input name="note" id="note" {if isset($expense.note)}value="{$expense.note|outhtml}"{/if} type="hidden">
                    <trix-editor input="note"></trix-editor>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
        </table>
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="save_product" value="{$LANG.save}">
                <img class="button_img" src="../../../images/tick.png" alt=""/>
                {$LANG.save}
            </button>
            <a href="index.php?module=expense&amp;view=manage" class="negative">
                <img src="../../../images/cross.png" alt=""/>
                {$LANG.cancel}
            </a>
        </div>
    {/if}
</form>
