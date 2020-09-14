<!--suppress HtmlFormInputWithoutLabel, HtmlUnknownTag -->
<form name="frmpost" method="POST" id="frmpost"
      action="index.php?module=expense&amp;view=save&amp;id={$smarty.get.id|urlencode}">
    <div class="si_form" id="si_form_cust_edit">
        <table class="center" style="width:90%;">
            <tr>
                <th class="details_screen">{$LANG.amount_uc}</th>
                <td>
                    <input name="amount" class="si_input validate[required]" value="{$expense.amount|utilNumber}"/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.expense_accounts}</th>
                <td>
                    <select name="expense_account_id" class="si_input validate[required]">
                        <option value=''></option>
                        {foreach from=$detail.expense_accounts item=expense_account}
                            <option {if $expense_account.id == $expense.ea_id}selected{/if}
                                    value="{if isset($expense_account.id)}{$expense_account.id}{/if}">{$expense_account.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.date_formatted}</th>
                <td>
                    <input type="text" class="si_input validate[required,custom[date],length[0,10]] date-picker"
                           size="10" name="date" id="date" value='{$expense.date}'/>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.biller}</th>
                <td>
                    <select name="biller_id" class="si_input validate[required]">
                        <option value=''></option>
                        {foreach from=$detail.billers item=biller}
                            <option {if $biller.id == $expense.b_id} selected {/if}
                                    value="{if isset($biller.id)}{$biller.id}{/if}">{$biller.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.customer}</th>
                <td>
                    <select name="customer_id" class="si_input ">
                        <option value=''></option>
                        {foreach from=$detail.customers item=customer}
                            <option {if $customer.id == $expense.c_id} selected {/if}
                                    value="{if isset($customer.id)}{$customer.id}{/if}">{$customer.name}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.invoice_uc}</th>
                <td>
                    <select name="invoice_id" class="si_input ">
                        <option value=''></option>
                        {foreach from=$detail.invoices item=invoice}
                            <option value="{if isset($invoice.id)}{$invoice.id|htmlSafe}{/if}" {if $invoice.id ==  $expense.iv_id}selected{/if} >
                                Inv#{$invoice.index_id}: ({$invoice.biller|htmlSafe}, {$invoice.customer|htmlSafe})
                            </option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="details_screen">{$LANG.product_uc}</th>
                <td>
                    <select name="product_id" class="si_input ">
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
                    <th class="details_screen" colspan="1">{$LANG.tax}</th>
                    <td>
                        <table class="left">
                            <tr>
                            {section name=tax start=0 loop=$defaults.tax_per_line_item step=1}
                                <td>
                                    <select id="tax_id[0][{$smarty.section.tax.index}]" name="tax_id[0][{$smarty.section.tax.index}]" class="si_input">
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
                    </td>
                </tr>
            {/if}
            <tr>
                <th class="details_screen">{$LANG.status}</th>
                <td>
                    {* enabled block *}
                    <select name="status" class="si_input ">
                        <option value="{$smarty.const.ENABLED }" {if isset($expense.status) && $expense.status == $smarty.const.ENABLED }selected{/if}>{$LANG.paid}</option>
                        <option value="{$smarty.const.DISABLED}" {if isset($expense.status) && $expense.status == $smarty.const.DISABLED}selected{/if}>{$LANG.not_paid}</option>
                    </select>
                    {* /enabled block*}
                </td>
            </tr>
            <tr>
                <th class="details_screen" colspan="2">{$LANG.notes}</th>
            </tr>
            <tr>
                <td colspan="2">
                    <input name="note" id="note" {if isset($expense.note)}value="{$expense.note|outHtml}"{/if} type="hidden">
                    <trix-editor input="note" class="si_input "></trix-editor>
                </td>
            </tr>
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
        <input type="hidden" name="op" value="edit"/>
        <input type="hidden" name="domain_id" value="{if isset($expense.domain_id)}{$expense.domain_id}{/if}"/>
    </div>
</form>
