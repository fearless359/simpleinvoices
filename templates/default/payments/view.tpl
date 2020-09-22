{if $num_payment_recs == 0}
  <meta http-equiv="refresh" content="2;URL=index.php?module=invoices&amp;view=manage" />
  <div class='si_message_error'>{$LANG['zeroInvoiceAmt']}</div>
{else}
  {if $num_payment_recs > 1}
    <h3>{$LANG.moreThanOnePymtRec}</h3>
  {/if}
  <div class="si_form" id="si_form_pay_details">
    <table>
      <tr>
        <th class="details_screen">{$LANG.paymentId}: </th>
        <td>{$payment.id|htmlSafe}</td>
        <th class="details_screen">{$LANG.invoiceId}: </th>
        <td><a href='index.php?module=invoices&amp;view=quick_view&amp;id={$payment.ac_inv_id|htmlSafe}&amp;action=view'>{$payment.iv_index_id|htmlSafe}</a></td>
      </tr>
      <tr>
        <th class="details_screen">{$LANG.amountUc}: </th>
        <td>{$payment.ac_amount|utilNumber}</td>
        <th class="details_screen">{$LANG.dateUc}: </th>
        <td>{$payment.date|htmlSafe}</td>
      </tr>
      <tr>
        <th class="details_screen">{$LANG.biller}: </th>
        <td colspan="3">{$payment.bname|htmlSafe}</td>
      </tr>
      <tr>
        <th class="details_screen">{$LANG.customer}: </th>
        <td colspan="3">{$payment.cname|htmlSafe}</td>
      </tr>
      <tr>
        <th class="details_screen">{$LANG.paymentType}: </th>
        <td>{$paymentType.pt_description|htmlSafe}</td>
        <th class="details_screen">{$LANG.checkNumber}: </th>
        <td>{if strtolower($paymentType.pt_description)=="check"}{$payment.ac_check_number|htmlSafe}{/if}</td>
      </tr>
      <tr>
        <th class="details_screen">{$LANG.onlinePaymentId}: </th>
        <td>{$payment.online_payment_id|htmlSafe}</td>
        <td colspan="2"></td>
      </tr>
      <tr>
        <th class="details_screen">{$LANG.notes}: </th>
        <td colspan="3">{$payment.ac_notes|outHtml}
      </tr>
    </table>
    <div class="si_toolbar si_toolbar_form">
      <a href="index.php?module=payments&amp;view=manage" class="negative"><img src="../../../images/cross.png" alt="" />{$LANG.cancel}</a>
    </div>
  </div>
{/if}
