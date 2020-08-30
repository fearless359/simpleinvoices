<div class="si_form">
    <table>
        <tr>
            <th class="details_screen">{$LANG.company_logo}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.company_logo} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_company_logo" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=company_logo' tabindex="10">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaults.company_logo}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.company_name_item_label}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.company_name_item_label} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_company_name_item" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=company_name_item' tabindex="20">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaults.company_name_item}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.default_biller}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.default_biller} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_default_biller" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=biller' tabindex="30">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaultBiller.name}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.default_customer}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.default_customer} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_default_customer" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=customer' tabindex="40">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaultCustomer.name}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.default_invoice}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.default_invoice} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_default_invoice" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=default_invoice' tabindex="50">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaults.default_invoice}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.default_invoice_preference}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.default_invoice_preference} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_default_invoice_preference" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=preference_id' tabindex="60">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaultPreference.pref_description}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.default_inv_template}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.default_inv_template} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_default_invoice_template_text" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_inv_template' tabindex="70">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaults.template}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.default_number_items}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.default_number_items} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_default_number_items" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=line_items' tabindex="80">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaults.line_items}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.default_payment_type}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.default_payment_type} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_default_payment_type" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=def_payment_type' tabindex="90">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaultPaymentType}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.default_tax}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.default_tax} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_default_tax" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax' tabindex="100">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaultTax.tax_description}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.delete}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.delete} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_delete" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=delete' tabindex="110">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{if $defaultDelete == 1}{$LANG.enabled}{else}{$LANG.disabled}{/if}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.expense_uc}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.expense_uc} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_expense" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=expense' tabindex="120">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{if $defaultExpense == 1}{$LANG.enabled}{else}{$LANG.disabled}{/if}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.inventory}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.inventory} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_inventory" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=inventory' tabindex="130">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{if $defaultInventory == 1}{$LANG.enabled}{else}{$LANG.disabled}{/if}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.language}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.language} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_language" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=language' tabindex="140">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaultLanguage}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.logging}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.logging} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_logging" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=logging' tabindex="150">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{if $defaultLogging == 1}{$LANG.enabled}{else}{$LANG.disabled}{/if}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.password_min_length}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.password_min_length} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_password_min_length" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_min_length' tabindex="160">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaultPasswordMinLength}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.number_of_taxes_per_line_item}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.number_of_taxes_per_line_item} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_taxes_per_line_item" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=tax_per_line_item' tabindex="170">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaults.tax_per_line_item}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.product_attributes}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.product_attributes} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_product_attributes" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=product_attributes' tabindex="180">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{if $defaultProductAttributes == 1}{$LANG.enabled}{else}{$LANG.disabled}{/if}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.password_lower}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.password_lower} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_password_lower" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_lower' tabindex="190">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{if $defaultPasswordLower == 1}{$LANG.enabled}{else}{$LANG.disabled}{/if}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.password_number}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.password_number} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_password_number" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_number' tabindex="200">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{if $defaultPasswordNumber == 1}{$LANG.enabled}{else}{$LANG.disabled}{/if}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.password_special}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.password_special} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_password_special" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_special' tabindex="210">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{if $defaultPasswordSpecial == 1}{$LANG.enabled}{else}{$LANG.disabled}{/if}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.password_upper}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.password_upper} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_password_upper" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=password_upper' tabindex="220">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{if $defaultPasswordUpper == 1}{$LANG.enabled}{else}{$LANG.disabled}{/if}</td>
        </tr>

        <tr>
            <th class="details_screen">{$LANG.session_timeout}:
                <a class="cluetip" href="#" title="{$LANG.help} {$LANG.for} {$LANG.session_timeout} {$LANG.setting}"
                   rel="index.php?module=documentation&amp;view=view&amp;page=help_session_timeout" tabindex="-1">
                    <img src="{$helpImagePath}help-small.png" alt=""/>
                </a>
            </th>
            <td>
                <a href='index.php?module=system_defaults&amp;view=edit&amp;submit=session_timeout' tabindex="230">
                    <img src="../../../images/edit.png" title="{$LANG.edit}" alt="{$LANG.edit}"/>
                </a>
            </td>
            <td tabindex="-1">{$defaults.session_timeout}</td>
        </tr>

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
    </table>
</div> 
