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
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.companyLogo}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.companyLogo} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpCompanyLogo" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=company_logo' tabindex="10">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaults.company_logo}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.companyNameItemLabel}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.companyNameItemLabel} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpCompanyNameItem" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=company_name_item' tabindex="20">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaults.company_name_item}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.defaultBiller}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.defaultBiller} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpDefaultBiller" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=biller' tabindex="30">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaultBiller.name}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.defaultCustomer}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.defaultCustomer} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpDefaultCustomer" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=customer' tabindex="40">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaultCustomer.name}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.defaultInvoice}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.defaultInvoice} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpDefaultInvoice" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=default_invoice' tabindex="50">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaults.default_invoice}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.defaultInvoicePreference}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.defaultInvoicePreference} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpDefaultInvoicePreference" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=preference_id' tabindex="60">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaultPreference.pref_description}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.defaultInvTemplate}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.defaultInvTemplate} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpDefaultInvoiceTemplateText" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_inv_template' tabindex="70">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaults.template}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.defaultNumberItems}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.defaultNumberItems} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpDefaultNumberItems" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=line_items' tabindex="80">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaults.line_items}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.defaultPaymentType}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.defaultPaymentType} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpDefaultPaymentType" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_payment_type' tabindex="90">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaultPaymentType}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.defaultTax}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.defaultTax} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpDefaultTax" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax' tabindex="100">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaultTax.tax_description}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.delete}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.delete} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpDelete" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=delete' tabindex="110">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{if $defaultDelete == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.expenseUc}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.expenseUc} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpExpense" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=expense' tabindex="120">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{if $defaultExpense == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.inventory}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.inventory} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInventory" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=inventory' tabindex="130">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{if $defaultInventory == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.invoiceDescriptionOpen}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.invoiceDescriptionOpen} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpInvoiceDescriptionOpen" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=invoice_description_open' tabindex="135">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{if $defaultInvoiceDescriptionOpen == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.language}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.language} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpLanguage" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=language' tabindex="140">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaultLanguage}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.logging}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.logging} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpLogging" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=logging' tabindex="150">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{if $defaultLogging == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.passwordMinLength}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.passwordMinLength} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpPasswordMinLength" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_min_length' tabindex="160">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaultPasswordMinLength}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.numberOfTaxesPerLineItem}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.numberOfTaxesPerLineItem} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpTaxesPerLineItem" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax_per_line_item' tabindex="170">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaults.tax_per_line_item}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.passwordLower}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.passwordLower} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpPasswordLower" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_lower' tabindex="190">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{if $defaultPasswordLower == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.passwordNumber}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.passwordNumber} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpPasswordNumber" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_number' tabindex="200">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{if $defaultPasswordNumber == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.passwordSpecial}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.passwordSpecial} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpPasswordSpecial" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_special' tabindex="210">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{if $defaultPasswordSpecial == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.passwordUpper}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.passwordUpper} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpPasswordUpper" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_upper' tabindex="220">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{if $defaultPasswordUpper == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.productAttributes}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.productAttributes} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpProductAttributes" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=product_attributes' tabindex="223">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{if $defaultProductAttributes == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.productGroupsUc}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.productGroupsUc} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpProductGroups" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=product_groups' tabindex="226">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{if $defaultProductGroups == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.sessionTimeout}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.sessionTimeout} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpSessionTimeout" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=session_timeout' tabindex="230">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{$defaults.session_timeout}</div>
    </div>

    <div class="grid__container grid__head-10">
        <div class="cols__2-span-3 bold">{$LANG.subCustomer}:
            <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.subCustomer} {$LANG.setting}"
               rel="index.php?module=documentation&amp;view=view&amp;page=helpSubCustomer" tabindex="-1">
                <img src="{$helpImagePath}help-small.png" alt=""/>
            </a>
        </div>
        <div class="cols__5-span-1 align__text-center">
            <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=sub_customer' tabindex="180">
                <img src="images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
            </a>
        </div>
        <div class="cols__6-span-5">{if $defaultSubCustomer == $smarty.const.ENABLED}{$LANG.enabled}{else}{$LANG.disabled}{/if}</div>
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
