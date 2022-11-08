{*
 *  Script: manage.tpl
 *      Manage System Defaults template
 *
 *  Last modified:
 *      20210618 by Richard Rowley to convert to grid layout.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
*}
<div class="grid__area margin__bottom-2">
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.companyLogo}:
            <img class="tooltip" title="{$LANG.helpCompanyLogo}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=company_logo' tabindex="10"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-5 margin__left-0-5">{$defaults.company_logo}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.companyNameItemLabel}:
            <img class="tooltip" title="{$LANG.helpCompanyNameItem}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=company_name_item' tabindex="20"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaults.company_name_item}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.defaultBiller}:
            <img class="tooltip" title="{$LANG.helpDefaultBiller}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=biller' tabindex="30"
               class="margin__left-1">
            <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaultBiller.name}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.defaultCustomer}:
            <img class="tooltip" title="{$LANG.helpDefaultCustomer}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=customer' tabindex="40"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaultCustomer.name}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.defaultInvoice}:
            <img class="tooltip" title="{$LANG.helpDefaultInvoice}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=default_invoice' tabindex="50"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaults.default_invoice}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.defaultInvoicePreference}:
            <img class="tooltip" title="{$LANG.helpDefaultInvoicePreference}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=preference_id' tabindex="60"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaultPreference.pref_description}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.defaultInvTemplate}:
            <img class="tooltip" title="{$LANG.helpDefaultInvoiceTemplateText}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_inv_template' tabindex="70"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaults.template}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.defaultNumberItems}:
            <img class="tooltip" title="{$LANG.helpDefaultNumberItems}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=line_items' tabindex="80"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaults.line_items}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.defaultPaymentType}:
            <img class="tooltip" title="{$LANG.helpDefaultPaymentType}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_payment_type' tabindex="90"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaultPaymentType}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.defaultTax}:
            <img class="tooltip" title="{$LANG.helpDefaultTax}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax' tabindex="100"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaultTax.tax_description}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.delete}:
            <img class="tooltip" title="{$LANG.helpDelete}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=delete' tabindex="110"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.helpDelete}" alt="{$LANG.delete}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{if $defaultDelete == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.displayDepartment}:
            <img class="tooltip" title="{$LANG.helpDisplayDepartment}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=display_department' tabindex="115"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.helpDisplayDepartment}" alt="{$LANG.displayDepartment}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{if $defaultDisplayDepartment == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.expenseUc}:
            <img class="tooltip" title="{$LANG.helpExpense}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=expense' tabindex="120"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{if $defaultExpense == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.inventory}:
            <img class="tooltip" title="{$LANG.helpInventory}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=inventory' tabindex="130"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{if $defaultInventory == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.invoiceDescriptionOpen}:
            <img class="tooltip" title="{$LANG.helpInvoiceDescriptionOpen}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=invoice_description_open' tabindex="135"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{if $defaultInvoiceDescriptionOpen == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.language}:
            <img class="tooltip" title="{$LANG.helpLanguage}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=language' tabindex="140"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaultLanguage}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.logging}:
            <img class="tooltip" title="{$LANG.helpLogging}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=logging' tabindex="150"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{if $defaultLogging == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.passwordMinLength}:
            <img class="tooltip" title="{$LANG.helpPasswordMinLength}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_min_length' tabindex="160"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaultPasswordMinLength}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.numberOfTaxesPerLineItem}:
            <img class="tooltip" title="{$LANG.helpTaxesPerLineItem}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax_per_line_item' tabindex="170"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaults.tax_per_line_item}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.passwordLower}:
            <img class="tooltip" title="{$LANG.helpPasswordLower}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_lower' tabindex="190"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{if $defaultPasswordLower == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.passwordNumber}:
            <img class="tooltip" title="{$LANG.helpPasswordNumber}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_number' tabindex="200"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{if $defaultPasswordNumber == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.passwordSpecial}:
            <img class="tooltip" title="{$LANG.helpPasswordSpecial}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_special' tabindex="210"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{if $defaultPasswordSpecial == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.passwordUpper}:
            <img class="tooltip" title="{$LANG.helpPasswordUpper}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_upper' tabindex="220"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{if $defaultPasswordUpper == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.productAttributes}:
            <img class="tooltip" title="{$LANG.helpProductAttributes}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=product_attributes' tabindex="223"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{if $defaultProductAttributes == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.paymentDeleteDays}:
            <img class="tooltip" title="{$LANG.helpPaymentDeleteDays}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=payment_delete_days' tabindex="224"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaults.payment_delete_days}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.productGroupsUc}:
            <img class="tooltip" title="{$LANG.helpProductGroups}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=product_groups' tabindex="226"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{if $defaultProductGroups == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.sessionTimeout}:
            <img class="tooltip" title="{$LANG.helpSessionTimeout}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=session_timeout' tabindex="230"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{$defaults.session_timeout}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__1-span-4 bold align__text-right margin__right-1">{$LANG.subCustomer}:
            <img class="tooltip" title="{$LANG.helpSubCustomer}" src="{$helpImagePath}help-small.png" alt=""/>
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=sub_customer' tabindex="180"
               class="margin__left-1">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__5-span-6 margin__left-0-5">{if $defaultSubCustomer == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    {* This section will insert any extensions that add system-default fields *}
    {* If you create such an extension, please follow the standard above the pust a semi-coloan
       after the field label, sets tabindex=-1" to the help anchor, and a tabindex with a valur
       that causes tabbing to hit your field after the others. This starts at 240 currently. *}
    {if $perform_extension_insertions}
        {section name=idx loop=$extension_insertion_files}
            {if $extension_insertion_files[idx].module  == 'system_defaults'}
                {include file=$extension_insertion_files[idx].file}
            {/if}
        {/section}
    {/if}
</div> 
