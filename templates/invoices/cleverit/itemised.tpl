            <table style="table-layout: fixed; border-collapse: collapse; margin-left: auto; margin-right: auto; width: 100%;border: none;">
                <thead>
                    <tr class="center">
                        <!-- Itemized Template -->
                        <th class="center" style="width: 5%;">{$LANG.item}</th>
                        <th class="center" style="width: 35%;">{$LANG.productsUc}</th>
                        <th class="center" style="width: 10%;">{$LANG.unitOfMeasurement}</th>
                        <th class="center" style="width: 10%;">{$LANG.quantity}</th>
                        <th class="center">{$LANG.productUnitPrice}</th>
                        <th class="center">{$LANG.productValue}</th>
                        {if $invoice.total_tax|utilNumber > 0}
                        <th class="center">{$LANG.tax}</th>
                        {/if}
                    </tr>
                    <tr class="center">
                        <th class="center">0</th>
                        <th class="center">1</th>
                        <th class="center">2</th>
                        <th class="center">3</th>
                        <th class="center">4</th>
                        <th class="center">5 (3 x 4)</th>
                        {if $invoice.total_tax|utilNumber > 0}
                        <th class="center">6</th>
                        {/if}
                    </tr>
                </thead>
                <tbody>
                    {* Invoice Type 2 or Type 3 - Itemized, formerly Type 2 and 3 were the same info merely displayed in slightly different order *} {if ($invoice.type_id == 2) || ($invoice.type_id == 3)} {foreach $invoiceItems as $index =>
                    $invoiceItem}
                    <tr class="clean">
                        <td class="clean center bleft">{$invoiceItem@iteration}</td>
                        <td class="clean left bleft">
                            {$invoiceItem.product.description|htmlSafe}
                            {if $invoiceItem.product.custom_field1 != null}<strong>{$customFieldLabels.product_cf1}:</strong> {$invoiceItem.product.custom_field1|htmlSafe} {/if}
                            {if $invoiceItem.product.custom_field2 != null}<strong>{$customFieldLabels.product_cf2}:</strong> {$invoiceItem.product.custom_field2|htmlSafe} {/if}
                            {if $invoiceItem.product.custom_field3 != null}<strong>{$customFieldLabels.product_cf3}:</strong> {$invoiceItem.product.custom_field3|htmlSafe} {/if}
                            {if $invoiceItem.product.custom_field4 != null}<strong>{$customFieldLabels.product_cf4}:</strong> {$invoiceItem.product.custom_field4|htmlSafe} {/if}
                        </td>
                        <td class="clean center bleft">{$LANG.um_buc}</td>
                        <td class="clean center bleft">{$invoiceItem.quantity|utilNumberTrim}</td>
                        <td class="clean center bleft">{$invoiceItem.unit_price|utilNumber} {$preference.pref_currency_sign}</td>
                        <td class="clean center bleft">{$invoiceItem.gross_total|utilNumber} {$preference.pref_currency_sign}</td>
                        {if $invoice.total_tax|utilNumber > 0}
                        <td class="clean center bleft bright">{$invoiceItem.tax_amount|utilNumber}</td>
                        {/if}
                    </tr>
                    {if $invoiceItem.description != null}
                    <tr class="clean">
                        <td class="clean center bleft">&nbsp;</td>
                        <td class="clean left bleft">{$invoiceItem.description|htmlSafe}</td>
                        <td class="clean center bleft">&nbsp;</td>
                        <td class="clean center bleft">&nbsp;</td>
                        <td class="clean center bleft">&nbsp;</td>
                        <td class="clean center bleft">&nbsp;</td>
                        {if $invoice.total_tax|utilNumber > 0}
                        <td class="clean center bleft  bright"></td>
                        {/if}
                    </tr>
                    {/if}
                    {/foreach} {/if}
                    <!-- Tax & Total section start -->
                    {if $invoice.total_tax|utilNumber < 1}
                    <tr class="clean btop">
                        <td class="clean center" colspan="5">&nbsp;</td>
                        <td class="clean center" style="font-weight: bold;">&nbsp;</td>
                    </tr>
                    <tr class="clean">
                        <td class="clean center" colspan="4">&nbsp;</td>
                        <td class="clean center subtotal">{$LANG.subTotal}:</td>
                        <td class="clean center subtotal">{$invoice.gross|utilNumber} {$preference.pref_currency_sign}</td>
                    </tr>
                    <tr class="clean">
                        <td class="clean center" colspan="4"></td>
                        <td class="clean center">{$LANG.amountUc}&nbsp;{$preference.pref_inv_wording|htmlSafe}:</td>
                        <td class="clean center">{$invoice.total|utilNumber} {$preference.pref_currency_sign}</td>
                    </tr>
                    {else}
                    <tr class="clean btop">
                        <td class="clean center" colspan="5">&nbsp;</td>
                        <td class="clean center" style="font-weight: bold;">&nbsp;</td>
                        <td class="clean center" style="font-weight: bold;">&nbsp;</td>
                    </tr>
                    <tr class="clean">
                        <td class="clean center" colspan="5">&nbsp;</td>
                        <td class="center subtotal">{$LANG.subTotal}:</td>
                        <td class="center subtotal">{$invoice.gross|utilNumber} {$preference.pref_currency_sign}</td>
                    </tr>
                    <tr class="clean">
                        <td class="clean center" colspan="5">&nbsp;</td>
                        <td class="clean center subtotal">{$LANG.taxTotal}&nbsp;</td>
                        <td class="clean center subtotal">{$invoice.total_tax|utilNumber} {$preference.pref_currency_sign}</td>
                    </tr>
                    <tr class="clean">
                        <td class="clean center" colspan="5">&nbsp;</td>
                        <td class="clean center total">{$LANG.amountUc}&nbsp;{$preference.pref_inv_wording|htmlSafe}:</td>
                        <td class="clean center total">{$invoice.total|utilNumber} {$preference.pref_currency_sign}</td>
                    </tr>
                    {/if}
                    <!-- Tax & Total section end -->
                </tbody>
            </table>