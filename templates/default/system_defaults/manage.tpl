{*
 *  Script: manage.tpl
 *      Manage System Defaults template
 *
 *  Last modified:
 *      20251202 by Rich Rowley to use column size layout for responsiveness and appearence.
 *      20251110 by Rich Rowley to use flex layout for responsive interface.
 *      20210618 by Richard Rowley to convert to grid layout.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
*}
<div class="form__container">
    <div class="row">
        <div class="col__30">
            <label for="companyLogo">{$LANG.companyLogo}:
                <img class="tooltip" title="{$LANG.helpCompanyLogo}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=company_logo' tabindex="10"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="companyLogo" value="{$defaults.company_logo}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="companyName">{$LANG.companyNameItemLabel}:
                <img class="tooltip" title="{$LANG.helpCompanyNameItem}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=company_name_item' tabindex="20"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="companyName" value="{$defaults.company_name_item}" disabled>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="defaultBiller">{$LANG.defaultBiller}:
                <img class="tooltip" title="{$LANG.helpDefaultBiller}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=biller' tabindex="30"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="defaultBiller" value="{$defaultBiller.name}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="defaultCustomer">{$LANG.defaultCustomer}:
                <img class="tooltip" title="{$LANG.helpDefaultCustomer}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=customer' tabindex="40"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="defaultCustomer" value="{$defaultCustomer.name}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="defaultInvoice">{$LANG.defaultInvoice}:
                <img class="tooltip" title="{$LANG.helpDefaultInvoice}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=default_invoice' tabindex="50"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="defaultInvoice" value="{$defaults.default_invoice}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="defaultInvoicePreference">{$LANG.defaultInvoicePreference}:
                <img class="tooltip" title="{$LANG.helpDefaultInvoicePreference}" src="{$helpImagePath}help-small.png"
                     alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=preference_id' tabindex="60"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="defaultInvoicePreference" value="{$defaultPreference.pref_description}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="defaultsTemplate">{$LANG.defaultInvTemplate}:
                <img class="tooltip" title="{$LANG.helpDefaultInvoiceTemplateText}" src="{$helpImagePath}help-small.png"
                     alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_inv_template' tabindex="70"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="defaultsTemplate" value="{$defaults.template}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="defaultNumberItems">{$LANG.defaultNumberItems}:
                <img class="tooltip" title="{$LANG.helpDefaultNumberItems}" src="{$helpImagePath}help-small.png"
                     alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=line_items' tabindex="80"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="defaultNumberItems" value="{$defaults.line_items}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="defaultPaymentType">{$LANG.defaultPaymentType}:
                <img class="tooltip" title="{$LANG.helpDefaultPaymentType}" src="{$helpImagePath}help-small.png"
                     alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_payment_type' tabindex="90"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="defaultPaymentType" value="{$defaultPaymentType}" disabled>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="defaultTax">{$LANG.defaultTax}:
                <img class="tooltip" title="{$LANG.helpDefaultTax}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax' tabindex="100"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="defaultTax" value="{$defaultTax.tax_description}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="delete">{$LANG.delete}:
                <img class="tooltip" title="{$LANG.helpDelete}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=delete' tabindex="110"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.helpDelete}" alt="{$LANG.delete}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="delete"
                   value="{if $defaultDelete == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}"
                   disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="displayDepartment">{$LANG.displayDepartment}:
                <img class="tooltip" title="{$LANG.helpDisplayDepartment}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=display_department' tabindex="115"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.helpDisplayDepartment}" alt="{$LANG.displayDepartment}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="displayDepartment"
                   value="{if $defaultDisplayDepartment == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}"
                   disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="expense">{$LANG.expenseUc}:
                <img class="tooltip" title="{$LANG.helpExpense}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=expense' tabindex="120"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="expense"
                   value="{if $defaultExpense == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}"
                   disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="inventory">{$LANG.inventory}:
                <img class="tooltip" title="{$LANG.helpInventory}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=inventory' tabindex="130"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="inventory"
                   value="{if $defaultInventory == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}"
                   disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="invoiceDescriptionOpen">{$LANG.invoiceDescriptionOpen}:
                <img class="tooltip" title="{$LANG.helpInvoiceDescriptionOpen}" src="{$helpImagePath}help-small.png"
                     alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=invoice_description_open'
                   tabindex="135"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="invoiceDescriptionOpen"
                   value="{if $defaultInvoiceDescriptionOpen == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}"
                   disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="invoiceDisplayDays">{$LANG.invoiceDisplayDays}:
                <img class="tooltip" title="{$LANG.helpInvoiceDisplayDays}" src="{$helpImagePath}help-small.png"
                     alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=invoice_display_days' tabindex="224"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="invoiceDisplayDays" value="{$defaults.invoice_display_days}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="language">{$LANG.language}:
                <img class="tooltip" title="{$LANG.helpLanguage}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=language' tabindex="140"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="language" value="{$defaultLanguage}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="logging">{$LANG.logging}:
                <img class="tooltip" title="{$LANG.helpLogging}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=logging' tabindex="150"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="logging"
                   value="{if $defaultLogging == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}"
                   disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="passwordMinLength">{$LANG.passwordMinLength}:
                <img class="tooltip" title="{$LANG.helpPasswordMinLength}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_min_length' tabindex="160"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="passwordMinLength" value="{$defaultPasswordMinLength}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="numberOfTaxesPerLineItem">{$LANG.numberOfTaxesPerLineItem}:
                <img class="tooltip" title="{$LANG.helpTaxesPerLineItem}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax_per_line_item' tabindex="170"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="numberOfTaxesPerLineItem" value="{$defaults.tax_per_line_item}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="passwordLower">{$LANG.passwordLower}:
                <img class="tooltip" title="{$LANG.helpPasswordLower}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_lower' tabindex="190"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="passwordLower"
                   value="{if $defaultPasswordLower == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}"
                   disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="passwordNumber">{$LANG.passwordNumber}:
                <img class="tooltip" title="{$LANG.helpPasswordNumber}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_number' tabindex="200"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="passwordNumber"
                   value="{if $defaultPasswordNumber == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}"
                   disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="passwordSpecial">{$LANG.passwordSpecial}:
                <img class="tooltip" title="{$LANG.helpPasswordSpecial}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_special' tabindex="210"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="passwordSpecial"
                   value="{if $defaultPasswordSpecial == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}"
                   disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="passwordUpper">{$LANG.passwordUpper}:
                <img class="tooltip" title="{$LANG.helpPasswordUpper}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_upper' tabindex="220"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="passwordUpper"
                   value="{if $defaultPasswordUpper == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}"
                   disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="productAttributes">{$LANG.productAttributes}:
                <img class="tooltip" title="{$LANG.helpProductAttributes}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=product_attributes' tabindex="223"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="productAttributes"
                   value="{if $defaultProductAttributes == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}"
                   disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="paymentDeleteDays">{$LANG.paymentDeleteDays}:
                <img class="tooltip" title="{$LANG.helpPaymentDeleteDays}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=payment_delete_days' tabindex="224"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="paymentDeleteDays" value="{$defaults.payment_delete_days}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="productGroups">{$LANG.productGroupsUc}:
                <img class="tooltip" title="{$LANG.helpProductGroups}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=product_groups' tabindex="226"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="productGroups"
                   value="{if $defaultProductGroups == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}"
                   disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="sessionTimeout">{$LANG.sessionTimeout}:
                <img class="tooltip" title="{$LANG.helpSessionTimeout}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=session_timeout' tabindex="230"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="sessionTimeout" value="{$defaults.session_timeout}" disabled/>
        </div>
    </div>

    <div class="row">
        <div class="col__30">
            <label for="subCustomer">{$LANG.subCustomer}:
                <img class="tooltip" title="{$LANG.helpSubCustomer}" src="{$helpImagePath}help-small.png" alt=""/>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=sub_customer' tabindex="180"
                   class="margin__left-1">
                    <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </label>
        </div>
        <div class="col__70">
            <input type="text" id="subCustomer"
                   value="{if $defaultSubCustomer == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}"
                   disabled/>
        </div>
    </div>

    {* This section will insert any extensions that add system-default fields *}
    {* If you create such an extension, please follow the standard above the pust a semi-coloan
       after the field label, sets tabindex=-1" to the help anchor, and a tabindex with a valur
       that causes tabbing to hit your field after the others. This starts at 240 currently. *}
    {if $performExtensionInsertions}
        {section name=idx loop=$extensionInsertionFiles}
            {if $extensionInsertionFiles[idx].module  == 'system_defaults'}
                {include file=$extensionInsertionFiles[idx].file}
            {/if}
        {/section}
    {/if}
</div> 
