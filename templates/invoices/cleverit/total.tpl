            <table style="table-layout: fixed; border-collapse: collapse; margin-left: auto; margin-right: auto; width: 100%;border: none;">
                <thead>
                    <tr class="clean center bleft">
                        <!-- Total Template -->
                        <th class="center" style="width: 80%;">{$LANG.descriptionUc}</th>
                        <th class="center">{$LANG.productValue}</th>
                        {if $invoice.total_tax|utilNumber > 0}
                        <th class="center">{$LANG.tax}</th>
                        {/if}
                    </tr>
                    <tr>
                        <th class="center">1</th>
                        <th class="center">2</th>
                        {if $invoice.total_tax|utilNumber > 0}
                        <th class="center">3</th>
                        {/if}
                    </tr>
                </thead>
                <tbody>
                    {* Invoice Type 2 or Type 3 - Itemized, formerly Type 2 and 3 were the same info merely displayed in slightly different order *}
                    {foreach $invoiceItems as $index =>
                    $invoiceItem}
                    <tr class="clean">
                        <td class="clean left bleft">
                            {$invoiceItem.product.description|htmlSafe}
                        </td>
                        <td class="clean center bleft">{$invoiceItem.gross_total|utilNumber} {$preference.pref_currency_sign}</td>
                        {if $invoice.total_tax|utilNumber > 0}
                        <td class="clean center bleft bright">{$invoiceItem.tax_amount|utilNumber}</td>
                        {/if}
                    </tr>
                    {if $invoiceItem.description != null}
                    <tr class="clean">
                        <td class="clean left bleft">{$invoiceItems[0].description|outHtml}</td>
                        <td class="clean center bleft">&nbsp;</td>
                        {if $invoice.total_tax|utilNumber > 0}
                        <td class="clean center bleft  bright"></td>
                        {/if}
                    </tr>
                    {/if}
                    {/foreach}
                    <!-- Tax & Total section start -->
                    {if $invoice.total_tax|utilNumber < 1}
                    <tr class="clean btop">
                        <td class="clean center" colspan="1">&nbsp;</td>
                        <td class="clean center" style="font-weight: bold;">&nbsp;</td>
                    </tr>
                    <tr class="clean">
                        <td class="clean center" colspan="1"></td>
                        <td class="clean center">{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.amountUc}: {$invoice.total|utilNumber} {$preference.pref_currency_sign}</td>
                    </tr>
                    {else}
                    <tr class="clean btop">
                        <td class="clean center" colspan="1">&nbsp;</td>
                        <td class="clean center" style="font-weight: bold;">&nbsp;</td>
                        <td class="clean center" style="font-weight: bold;">&nbsp;</td>
                    </tr>
                    <tr class="clean">
                        <td class="clean center" colspan="1">&nbsp;</td>
                        <td class="clean center total">{$preference.pref_inv_wording|htmlSafe}&nbsp;{$LANG.amountUc}:</td>
                        <td class="clean center total">{$invoice.total|utilNumber} {$preference.pref_currency_sign}</td>
                    </tr>
                    {/if}
                    <!-- Tax & Total section end -->
                </tbody>
            </table>