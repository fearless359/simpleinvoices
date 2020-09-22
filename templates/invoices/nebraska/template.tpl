<!--suppress HtmlRequiredLangAttribute -->
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <link rel="stylesheet" type="text/css" href="{$css|urlSafe}" media="all">
      <title>{$preference.pref_inv_wording|htmlSafe}
         {$LANG.numberShort}: {$invoice.index_id|htmlSafe}
      </title>
   </head>
   <body>
      <header>
         <!-- Parties -->
		 <table style="table-layout: fixed; border-collapse:collapse; margin-left: auto; margin-right: auto; width: 100%;;">
            <tbody>
               <tr>
				  <!-- Biller -->
                  <td colspan="2"  style="text-align: left; width:300px;">
                     <table style="width:50%;">
                        <tr>
                           <td class='clean left'><strong>{$LANG.biller}</strong></td>
                           <td class='clean left' style='font-weight:bold'>{$biller.name|htmlSafe}</td>
                        </tr>
                        {if $biller.custom_field1 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.biller_cf1}:</td>
                           <td class='clean left'>{$biller.custom_field1|htmlSafe}</td>
                        </tr>
                        {/if} {if $biller.custom_field2 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.biller_cf2}:</td>
                           <td class='clean left'>{$biller.custom_field2|htmlSafe}</td>
                        </tr>
                        {/if} {if $biller.custom_field3 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.biller_cf3}:</td>
                           <td class='clean left'>{$biller.custom_field3|htmlSafe}</td>
                        </tr>
                        {/if} {if $biller.custom_field4 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.biller_cf4}:</td>
                           <td class='clean left'>{$biller.custom_field4|htmlSafe}</td>
                        </tr>
                        {/if}
                        <tr>
                           <td class='clean left'>{$LANG.addressUc}:</td>
                           <td class='clean left'>
                              {if $biller.street_address != null}{$biller.street_address|htmlSafe}{/if} {if $biller.city != null }{$biller.city|htmlSafe},{/if} {if $biller.state != null } {$biller.state|htmlSafe},{/if} {if $biller.zip_code != null } {$biller.zip_code|htmlSafe} {/if} {if $biller.country != null }, {$biller.country|htmlSafe}{/if}
                           </td>
                        </tr>
                        {if $biller.street_address2 != null}
                        <tr>
                           <td class='clean left'>{$LANG.addressUc}:</td>
                           <td class='clean left'>{$biller.street_address2|htmlSafe}</td>
                        </tr>
                        {/if} 
						{if $biller.phone != null }
                        <tr>
                           <td class='clean left'>{$LANG.phoneUc}:</td>
                           <td class='clean left'>{$biller.phone|htmlSafe}</td>
                        </tr>
                        {/if} {if $biller.mobile != null }
                        <tr>
                           <td class='clean left'>{$LANG.mobile}:</td>
                           <td class='clean left'>{$biller.mobile|htmlSafe}</td>
                        </tr>
                        {/if} {if $biller.email != null }
                        <tr>
                           <td class='clean left'>{$LANG.email}:</td>
                           <td class='clean left'>{$biller.email|htmlSafe}</td>
                        </tr>
                        {/if}
                     </table>
                  </td>
                  <!-- Customer -->
                  <td colspan="2"  style="text-align: right; width:300px;">
                     <table style="width:50%;">
                        <tr>
                           <td class='clean left'><b>{$LANG.customer}</b></td>
                           <td class='clean left' style='font-weight:bold'>{$customer.name|htmlSafe}</td>
                        </tr>
                        {if $customer.custom_field1 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.customer_cf1}:</td>
                           <td class='clean left'>{$customer.custom_field1|htmlSafe}</td>
                        </tr>
                        {/if} {if $customer.custom_field2 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.customer_cf2}:</td>
                           <td class='clean left'>{$customer.custom_field2|htmlSafe}</td>
                        </tr>
                        {/if} {if $customer.custom_field3 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.customer_cf3}:</td>
                           <td class='clean left'>{$customer.custom_field3|htmlSafe}</td>
                        </tr>
                        {/if} {if $customer.custom_field4 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.customer_cf4}:</td>
                           <td class='clean left'>{$customer.custom_field4|htmlSafe}</td>
                        </tr>
                        {/if}
                        <tr>
                           <td class='clean left'>{$LANG.addressUc}:</td>
                           <td class='clean left'>
                              {if $customer.street_address != null}{$customer.street_address|htmlSafe}{/if} {if $customer.city != null }{$customer.city|htmlSafe},{/if} {if $customer.state != null } {$customer.state|htmlSafe},{/if} {if $customer.zip_code != null } {$customer.zip_code|htmlSafe} {/if} {if $customer.country != null }, {$customer.country|htmlSafe}{/if}
                           </td>
                        </tr>
                        {if $customer.street_address2 != null}
                        <tr>
                           <td class='clean left'>{$LANG.addressUc2}:</td>
                           <td class='clean left'>{$customer.street_address2|htmlSafe}</td>
                        </tr>
                        {/if} {if $customer.phone != null }
                        <tr>
                           <td class='clean left'>{$LANG.phoneUc}:</td>
                           <td class='clean left'>{$customer.phone|htmlSafe}</td>
                        </tr>
                        {/if} {if $customer.mobile != null }
                        <tr>
                           <td class='clean left'>{$LANG.mobile}:</td>
                           <td class='clean left'>{$customer.mobile|htmlSafe}</td>
                        </tr>
                        {/if} {if $customer.email != null }
                        <tr>
                           <td class='clean left'>{$LANG.email}:</td>
                           <td class='clean left'>{$customer.email|htmlSafe}</td>
                        </tr>
                        {/if}
                     </table>
                  </td>
               </tr>
            </tbody>
         </table>
         <!-- Logo -->
         <table style="margin-left: auto; margin-right: auto; width:30%;">
            <tbody>
               <tr>
                  <td class="clean center" style="width:30%;">
                     <img src="{$logo|urlSafe}" alt="" />
                     <!-- Invoice details -->
                     <h2>{$preference.pref_inv_heading|htmlSafe}</h2>
                     <!-- Summary -->
                     {$LANG.numberShort} {$invoice.index_id} {if $invoice.custom_field1 != null} {$customFieldLabels.invoice_cf1|htmlSafe} {$invoice.custom_field1|htmlSafe} {/if}
                     <span>{$LANG.dateUc}: {$invoice.date|date_format:"%d.%m.%Y"}</span>
                  </td>
               </tr>
            </tbody>
         </table>
      </header>
      <!-- Itemization -->
      <article>
         <table style="table-layout: fixed; border-collapse:collapse; margin-left: auto; margin-right: auto; width: 100%;;">
            <thead>
               <tr>
                  {if ($invoice.type_id == 2) || ($invoice.type_id == 3) }
                    <th class="clean center bleft bdown">{$LANG.item}</th>
                    <th class="clean center bleft bdown">{$LANG.products}</th>
                    <th class="clean center bleft bdown">{$LANG.unitOfMeasurement}</th>
                    <th class="clean center bleft bdown">{$LANG.quantity}</th>
                    <th class="clean center bleft bdown">{$LANG.productUnitPrice}</th>
                    <th class="clean center bleft bdown">{$LANG.productValue}</th>
                  {/if}
                  {if $invoice.type_id == 1 }
                    <th class="clean center bleft bdown">{$LANG.descriptionUc}</th>
                  {/if}
               </tr>
               <tr>
                  <th class="clean center bleft bdown">0</th>
                  <th class="clean center bleft bdown">1</th>
                  <th class="clean center bleft bdown">2</th>
                  <th class="clean center bleft bdown">3</th>
                  <th class="clean center bleft bdown">4</th>
                  <th class="clean center bleft bdown">5 (3 x 4)</th>
               </tr>
            </thead>
            <tbody>
               {* Invoice Type 2 or Type 3 - Itemized, formerly Type 2 and 3 were the same info merely displayed in slightly different order *}
               {if ($invoice.type_id == 2) || ($invoice.type_id == 3)}
                  {foreach $invoiceItems as $index => $invoiceItem}
                     <tr class="clean left bleft">
                        <td class="clean center bleft">{$invoiceItem@iteration}</td>
                        <td class="clean left bleft">
                           {$invoiceItem.product.description|htmlSafe}
                           {if $invoiceItem.description != null}{$invoiceItem.description|htmlSafe}{/if}
                           {if $invoiceItem.product.custom_field1 != null}
                              {$customFieldLabels.product_cf1}: {$invoiceItem.product.custom_field1|htmlSafe}
                           {/if}
                           {if $invoiceItem.product.custom_field2 != null}
                              {$customFieldLabels.product_cf2}: {$invoiceItem.product.custom_field2|htmlSafe}
                           {/if}
                           {if $invoiceItem.product.custom_field3 != null}
                              {$customFieldLabels.product_cf3}: {$invoiceItem.product.custom_field3|htmlSafe}
                           {/if}
                           {if $invoiceItem.product.custom_field4 != null}
                              {$customFieldLabels.product_cf4}: {$invoiceItem.product.custom_field4|htmlSafe}
                           {/if}
                        </td>
                        <td class="clean center bleft">{$LANG.um_buc}</td>
                        <td class="clean center bleft">{$invoiceItem.quantity|utilNumberTrim}</td>
                        <td class="clean center bleft">{$invoiceItem.unit_price|utilNumber} {$preference.pref_currency_sign}</td>
                        <td class="clean center bleft">{$invoiceItem.gross_total|utilNumber} {$preference.pref_currency_sign}</td>
                     </tr>
                  {/foreach}
               {/if}
               <tr>
                  <td class="clean left bleft" rowspan="3" colspan="2" style='font-weight:bold'>{if $preference.pref_inv_detail_line != null}{$preference.pref_inv_detail_line|outHtml}{/if}</td>
                  <td class="clean left bleft" colspan="2">Intocmit de Cristian Sava <br>
                     Cnp:1790726513500, {if $biller.fax != null }{$LANG.CI}: {$biller.fax|htmlSafe}{/if}
                  </td>
                  <td class="clean center bleft bdown" style='font-weight:bold'>{$LANG.amountUc}:</td>
                  <td class="clean center bleft bdown" style='font-weight:bold'>{$invoice.total|utilNumber} {$preference.pref_currency_sign}</td>
               </tr>
               <tr class="clean left bleft">
                  <td class="clean left bleft" style="height: 23px" colspan="2">
                     {$LANG.attentionShort}: {$customer.attention|htmlSafe}
                     <br>
                     {if $customer.fax != null }{$LANG.CI}: {$customer.fax|htmlSafe}{/if}
                  </td>
               </tr>
               <tr class="clean left bleft">
                  <td class="clean left bleft" colspan="2">{$LANG.dateUc}: {$invoice.date|date_format:"%d.%m.%Y"}<br>Semnaturile:</td>
               </tr>
            </tbody>
         </table>
      </article>
      <!-- Notes -->
      <div class="footer">
         <table>
            <tbody>
               <!-- Details -->
               {if $preference.pref_inv_detail_heading != null}
               <tr>
                  <td class='clean left' style='font-weight:bold'>{$preference.pref_inv_detail_heading|htmlSafe}</td>
               </tr>
               {/if}
               {if $biller.footer != null}
               <tr>
                  <td class="clean left">{$biller.footer|outHtml}</td>
               </tr>
               {/if}
            </tbody>
         </table>
         {if ($invoice.type_id == 2 && $invoice.note != "") || ($invoice.type_id == 3 && $invoice.note != "") }
         <h1>{$LANG.notes}</h1>
         <p style="text-align: center;">{$invoice.note|outHtml}</p>
         {/if}
      </div>
   </body>
</html>
