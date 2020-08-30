<!--suppress HtmlRequiredLangAttribute -->
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <link rel="stylesheet" type="text/css" href="{$css|urlsafe}" media="all">
      <title>{$preference.pref_inv_wording|htmlsafe}
         {$LANG.number_short}: {$invoice.index_id|htmlsafe}
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
                           <td class='clean left' style='font-weight:bold'>{$biller.name|htmlsafe}</td>
                        </tr>
                        {if $biller.custom_field1 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.biller_cf1}:</td>
                           <td class='clean left'>{$biller.custom_field1|htmlsafe}</td>
                        </tr>
                        {/if} {if $biller.custom_field2 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.biller_cf2}:</td>
                           <td class='clean left'>{$biller.custom_field2|htmlsafe}</td>
                        </tr>
                        {/if} {if $biller.custom_field3 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.biller_cf3}:</td>
                           <td class='clean left'>{$biller.custom_field3|htmlsafe}</td>
                        </tr>
                        {/if} {if $biller.custom_field4 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.biller_cf4}:</td>
                           <td class='clean left'>{$biller.custom_field4|htmlsafe}</td>
                        </tr>
                        {/if}
                        <tr>
                           <td class='clean left'>{$LANG.address_uc}:</td>
                           <td class='clean left'>
                              {if $biller.street_address != null}{$biller.street_address|htmlsafe}{/if} {if $biller.city != null }{$biller.city|htmlsafe},{/if} {if $biller.state != null } {$biller.state|htmlsafe},{/if} {if $biller.zip_code != null } {$biller.zip_code|htmlsafe} {/if} {if $biller.country != null }, {$biller.country|htmlsafe}{/if}
                           </td>
                        </tr>
                        {if $biller.street_address2 != null}
                        <tr>
                           <td class='clean left'>{$LANG.address_uc}:</td>
                           <td class='clean left'>{$biller.street_address2|htmlsafe}</td>
                        </tr>
                        {/if} 
						{if $biller.phone != null }
                        <tr>
                           <td class='clean left'>{$LANG.phone}:</td>
                           <td class='clean left'>{$biller.phone|htmlsafe}</td>
                        </tr>
                        {/if} {if $biller.mobile != null }
                        <tr>
                           <td class='clean left'>{$LANG.mobile}:</td>
                           <td class='clean left'>{$biller.mobile|htmlsafe}</td>
                        </tr>
                        {/if} {if $biller.email != null }
                        <tr>
                           <td class='clean left'>{$LANG.email}:</td>
                           <td class='clean left'>{$biller.email|htmlsafe}</td>
                        </tr>
                        {/if}
                     </table>
                  </td>
                  <!-- Customer -->
                  <td colspan="2"  style="text-align: right; width:300px;">
                     <table style="width:50%;">
                        <tr>
                           <td class='clean left'><b>{$LANG.customer}</b></td>
                           <td class='clean left' style='font-weight:bold'>{$customer.name|htmlsafe}</td>
                        </tr>
                        {if $customer.custom_field1 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.customer_cf1}:</td>
                           <td class='clean left'>{$customer.custom_field1|htmlsafe}</td>
                        </tr>
                        {/if} {if $customer.custom_field2 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.customer_cf2}:</td>
                           <td class='clean left'>{$customer.custom_field2|htmlsafe}</td>
                        </tr>
                        {/if} {if $customer.custom_field3 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.customer_cf3}:</td>
                           <td class='clean left'>{$customer.custom_field3|htmlsafe}</td>
                        </tr>
                        {/if} {if $customer.custom_field4 != null }
                        <tr>
                           <td class='clean left'>{$customFieldLabels.customer_cf4}:</td>
                           <td class='clean left'>{$customer.custom_field4|htmlsafe}</td>
                        </tr>
                        {/if}
                        <tr>
                           <td class='clean left'>{$LANG.address_uc}:</td>
                           <td class='clean left'>
                              {if $customer.street_address != null}{$customer.street_address|htmlsafe}{/if} {if $customer.city != null }{$customer.city|htmlsafe},{/if} {if $customer.state != null } {$customer.state|htmlsafe},{/if} {if $customer.zip_code != null } {$customer.zip_code|htmlsafe} {/if} {if $customer.country != null }, {$customer.country|htmlsafe}{/if}
                           </td>
                        </tr>
                        {if $customer.street_address2 != null}
                        <tr>
                           <td class='clean left'>{$LANG.address_uc2}:</td>
                           <td class='clean left'>{$customer.street_address2|htmlsafe}</td>
                        </tr>
                        {/if} {if $customer.phone != null }
                        <tr>
                           <td class='clean left'>{$LANG.phone}:</td>
                           <td class='clean left'>{$customer.phone|htmlsafe}</td>
                        </tr>
                        {/if} {if $customer.mobile != null }
                        <tr>
                           <td class='clean left'>{$LANG.mobile}:</td>
                           <td class='clean left'>{$customer.mobile|htmlsafe}</td>
                        </tr>
                        {/if} {if $customer.email != null }
                        <tr>
                           <td class='clean left'>{$LANG.email}:</td>
                           <td class='clean left'>{$customer.email|htmlsafe}</td>
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
                     <img src="{$logo|urlsafe}" alt="" />
                     <!-- Invoice details -->
                     <h2>{$preference.pref_inv_heading|htmlsafe}</h2>
                     <!-- Summary -->
                     {$LANG.number_short} {$invoice.index_id} {if $invoice.custom_field1 != null} {$customFieldLabels.invoice_cf1|htmlsafe} {$invoice.custom_field1|htmlsafe} {/if}
                     <span>{$LANG.date_uc}: {$invoice.date|date_format:"%d.%m.%Y"}</span>
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
                  <th class="clean center bleft bdown">{$LANG.unit_of_measurement}</th>
                  <th class="clean center bleft bdown">{$LANG.quantity}</th>
                  <th class="clean center bleft bdown">{$LANG.product_unit_price}</th>
                    <th class="clean center bleft bdown">{$LANG.product_value}</th>
                  {/if} {if $invoice.type_id == 1 }
                  <th class="clean center bleft bdown">{$LANG.description_uc}</th>
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
               {* Invoice Type 2 or Type 3 - Itemized, formerly Type 2 and 3 were the same info merely displayed in slightly different order *} {if ($invoice.type_id == 2) || ($invoice.type_id == 3) } {foreach from=$invoiceItems item=invoiceItem key=index name=foo}
               <tr class="clean left bleft">
                  <td class="clean center bleft">{$smarty.foreach.foo.iteration}</td>
                  <td class="clean left bleft">{$invoiceItem.product.description|htmlsafe} {if $invoiceItem.description != null} {$invoiceItem.description|htmlsafe} {/if} {if $invoiceItem.product.custom_field1 != null} {$customFieldLabels.product_cf1}: {$invoiceItem.product.custom_field1|htmlsafe} {/if} {if $invoiceItem.product.custom_field2 != null} {$customFieldLabels.product_cf2}: {$invoiceItem.product.custom_field2|htmlsafe} {/if} {if $invoiceItem.product.custom_field3 != null} {$customFieldLabels.product_cf3}: {$invoiceItem.product.custom_field3|htmlsafe} {/if} {if $invoiceItem.product.custom_field4 != null} {$customFieldLabels.product_cf4}: {$invoiceItem.product.custom_field4|htmlsafe} {/if}</td>
                  <td class="clean center bleft">{$LANG.um_buc}</td>
                  <td class="clean center bleft">{$invoiceItem.quantity|siLocal_number_trim}</td>
                  <td class="clean center bleft">{$invoiceItem.unit_price|siLocal_number} {$preference.pref_currency_sign}</td>
                  <td class="clean center bleft">{$invoiceItem.gross_total|siLocal_number} {$preference.pref_currency_sign}</td>
               </tr>
               {/foreach} {/if}
               <tr>
                  <td class="clean left bleft" rowspan="3" colspan="2" style='font-weight:bold'>{if $preference.pref_inv_detail_line != null}{$preference.pref_inv_detail_line|outhtml}{/if}</td>
                  <td class="clean left bleft" colspan="2">Intocmit de Cristian Sava <br>
                     Cnp:1790726513500, {if $biller.fax != null }{$LANG.CI}: {$biller.fax|htmlsafe}{/if}
                  </td>
                  <td class="clean center bleft bdown" style='font-weight:bold'>{$LANG.amount_uc}:</td>
                  <td class="clean center bleft bdown" style='font-weight:bold'>{$invoice.total|siLocal_number} {$preference.pref_currency_sign}</td>
               </tr>
               <tr class="clean left bleft">
                  <td class="clean left bleft" style="height: 23px" colspan="2">
                     {$LANG.attention_short}: {$customer.attention|htmlsafe}
                     <br>
                     {if $customer.fax != null }{$LANG.CI}: {$customer.fax|htmlsafe}{/if}
                  </td>
               </tr>
               <tr class="clean left bleft">
                  <td class="clean left bleft" colspan="2">{$LANG.date_uc}: {$invoice.date|date_format:"%d.%m.%Y"}<br>Semnaturile:</td>
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
                  <td class='clean left' style='font-weight:bold'>{$preference.pref_inv_detail_heading|htmlsafe}</td>
               </tr>
               {/if}
               {if $biller.footer != null}
               <tr>
                  <td class="clean left">{$biller.footer|outhtml}</td>
               </tr>
               {/if}
            </tbody>
         </table>
         {if ($invoice.type_id == 2 && $invoice.note != "") || ($invoice.type_id == 3 && $invoice.note != "") }
         <h1>{$LANG.notes}</h1>
         <p style="text-align: center;">{$invoice.note|outhtml}</p>
         {/if}
      </div>
   </body>
</html>