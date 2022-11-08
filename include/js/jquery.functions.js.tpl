{literal}
<script>
    /*
    * Product Change - updates line item with product price info
    */
    function invoiceProductChange(product, rowNumber, quantity, productGroupsEnabled) {

        $('#gmail_loading').show();
        $.ajax({
            type: 'GET',
            url: './index.php?module=invoices&view=product_ajax&id=' + product + '&row=' + rowNumber,
            data: "id: " + product,
            dataType: "json",
            success: function (data) {
                $('#gmail_loading').hide();

                $("#json_html" + rowNumber).remove();

                if (quantity === undefined || quantity === "") {
                    $("#quantity" + rowNumber).attr("value", "1");
                }

                if (productGroupsEnabled === '1') {
                    $("#unit_price" + rowNumber).val(data['markup_price']);
                } else {
                    $("#unit_price" + rowNumber).val(data['unit_price']);
                }

                $("#tax_id\\[" + rowNumber + "\\]\\[0\\]").val(data['default_tax_id']);
                if (data['default_tax_id_2'] === null) {
                    $("#tax_id\\[" + rowNumber + "\\]\\[1\\]").val('');
                } else {
                    $("#tax_id\\[" + rowNumber + "\\]\\[1\\]").val(data['default_tax_id_2']);
                }

                if (data['show_description'] === "Y") {
                    $("tbody#row" + rowNumber + " tr.details").show();
                } else {
                    $("tbody#row" + rowNumber + " tr.details").hide();
                }

                let desc_row = $("#description" + rowNumber);
                let row_val = desc_row.val();
                if (!row_val || row_val === '') {
                    if (data['notes_as_description'] === "Y") {
                        desc_row.val(data['notes']);
                    } else {
                        desc_row.val('');
                        desc_row.attr('placeholder', '{/literal}{$LANG.descriptionUc}{literal}')
                    }
                }

                if (data['json_html'] !== "") {
                    $("tbody#row" + rowNumber + " tr.details").before(data['json_html']);
                }
            }

        });
    }

    /*
     * Before allowing form to be submitted, make sure there is consistency between
     * the payment type and the check number. If there is and the payment type is
     * for a check, do a final validation of the check number.
     */
    function verifyPaymentTypeAndCheckNumberConsistent(e)
    {
        let pymtTypeId = $('#pymtTypeId');
        let tagName = pymtTypeId.prop('tagName').toUpperCase();
        let pymtType;
        if (tagName === "SELECT" && !pymtTypeId.prop('disabled')) {
            let pymtTypeSelected = pymtTypeId.find(':selected');
            pymtType = pymtTypeSelected.text().toLowerCase();
        } else {
            pymtTypeId = $('#pymtTypeIdInput');
            pymtType = pymtTypeId.val().toLowerCase();
        }

        let chkNumId = $('#checkNumberId');
        let ckNumber = chkNumId.val();

        let errStyle = 'style="font-size:2rem; color:red;"';
        let msgStyle = 'style="margin:20px auto; color:darkslategrey;"'

        if (pymtType === '{/literal}{$LANG.check}{literal}' && ckNumber.length === 0) {
            alertify.alert('<h2 ' + msgStyle + '>{/literal}{$LANG.checkNumberSet}{literal}</h2>')
                    .set({
                        transition: 'zoom',
                        label: '{/literal}{$LANG.closeUc}{literal}',
                        modal: true,
                        resizable: true
                    })
                    .resizeTo('60%', 200)
                    .setHeader('<em ' + errStyle + '>{/literal}{$LANG.errorUcAll}{literal}</em>');
            e.preventDefault();
        } else if (pymtType !== '{/literal}{$LANG.check}{literal}' && ckNumber.length !== 0) {
            alertify.alert('<h2 ' + msgStyle + '>{/literal}{$LANG.clearCheckNumber}{literal}</h2>')
                    .set({
                        transition: 'zoom',
                        label: '{/literal}{$LANG.closeUc}{literal}',
                        modal: true,
                        resizable: true
                    })
                    .resizeTo('60%', 200)
                    .setHeader('<em ' + errStyle + '>{/literal}{$LANG.errorUcAll}{literal}</em>');
            e.preventDefault();
        } else if (ckNumber.length !== 0) {
            let pattern = /^(\d+ *)|(N\/A)$/i;
            if (!(pattern).test(ckNumber)) {
                alertify.alert('<h2 ' + msgStyle + '>{/literal}{$LANG.enterValidCheckNumber}{literal}</h2>')
                        .set({
                            transition: 'zoom',
                            label: '{/literal}{$LANG.closeUc}{literal}',
                            modal: true,
                            resizable: true
                        })
                        .resizeTo('60%', 200)
                        .setHeader('<em ' + errStyle + '>{/literal}{$LANG.errorUcAll}{literal}</em>');
                e.preventDefault();
            }
        }
    }

    /*
     * Assure proper check number format if present and notify user if
     * payment warehouse record will be created or if amount over the
     * payment warehouse balance.
     */
    function validateCheckNumber()
    {
        let elem = $('#pymtTypeId');
        let tagName = elem.prop('tagName').toUpperCase();
        let pymtType;
        if (tagName === "SELECT") {
            let pymtTypeSelected = elem.find(':selected');
            pymtType = pymtTypeSelected.text().toLowerCase();
        } else {
            pymtType = elem.val().toLowerCase();
        }

        let chkNumId = $('#checkNumberId');
        let ckNumber = chkNumId.val();

        let warning = 'style="font-size:2rem; color:darkorange;"';
        let msgStyle = 'style="margin:20px auto; color:darkslategrey;"'

        if (pymtType === '{/literal}{$LANG.check}{literal}') {
            let pattern = /^(\d+ *)|(N\/A)$/i;
            if (!(pattern).test(ckNumber)) {
                alertify.alert('<h2 ' + msgStyle + '>{/literal}{$LANG.enterValidCheckNumber}{literal}</h2>')
                        .set({
                            transition: 'zoom',
                            label: '{/literal}{$LANG.closeUc}{literal}',
                            modal: true,
                            resizable: true
                        })
                        .resizeTo('60%', 200)
                        .setHeader('<em ' + warning + '>{/literal}{$LANG.warningUcAll}{literal}</em>');
                return false;
            }
        } else if (ckNumber.length > 0) {
            alertify.alert('<h2 ' + msgStyle + '>{/literal}{$LANG.clearCheckNumber}{literal}</h2>')
                    .set({
                        transition: 'zoom',
                        label: '{/literal}{$LANG.closeUc}{literal}',
                        modal: true,
                        resizable: true
                    })
                    .resizeTo('60%', 200)
                    .setHeader('<em ' + warning + '>{/literal}{$LANG.warningUcAll}{literal}</em>');
            return false;
        }
    }

    /*
     * Check payment against payment warehouse and report appropriate warnings.
     */
    function checkPaymentInWarehouse()
    {
        let elem = $('#amountId');
        let amt = parseFloat(elem.val());
        let owing = parseFloat(elem.attr('data-owing'));
        let warehousedPayment = parseFloat(elem.attr("data-warehoused-payment"));
        if (amt > owing || warehousedPayment > 0 && amt > warehousedPayment) {
            let currencyCode = elem.attr('data-currency-code');
            let locale = elem.attr('data-locale').replace(/_/, "-");
            let formatter = new Intl.NumberFormat(locale, {
                style   : 'currency',
                currency: currencyCode
            });
            let fmtAmt = formatter.format(amt);
            let fmtOwing = formatter.format(owing);
            let fmtWarehousedPayment = formatter.format(warehousedPayment);

            let warning = 'style="font-size:2rem; color:darkorange;"';
            let msgStyle = 'style="margin:20px auto; color:darkslategrey;"'

            let maxAmt = 0;
            let paymentMsg = {/literal}'{$LANG.paymentUc} {$LANG.amount} (' + fmtAmt + ') {$LANG.exceeds} '{literal};
            if (warehousedPayment > 0) {
                if (amt > owing && owing <= warehousedPayment) {
                    paymentMsg += {/literal}'{$LANG.amount} {$LANG.owing} (' + fmtOwing + '). {$LANG.reduceUc} {$LANG.payment} ' +
                        '{$LANG.accordingly}.';{literal}
                    maxAmt = owing;
                } else {
                    paymentMsg += {/literal}'{$LANG.warehoused} {$LANG.balance} (' + fmtWarehousedPayment + '). ' +
                        '{$LANG.theUc} {$LANG.warehoused} {$LANG.balance} {$LANG.must} {$LANG.be} {$LANG.applied} ' +
                        '{$LANG.before} {$LANG.additional} {$LANG.funds} {$LANG.can} {$LANG.be} {$LANG.paid}.';{literal}
                    maxAmt = warehousedPayment;
                }
            } else {
                paymentMsg += {/literal}'{$LANG.amount} {$LANG.owing} (' + fmtOwing + '). {$LANG.excessUc} {$LANG.will} ' +
                    '{$LANG.be} {$LANG.warehoused} {$LANG.if} {$LANG.left} {$LANG.unchanged}.';{literal}
            }

            if (maxAmt > 0) {
                elem.attr("max", maxAmt);
            }

            alertify.alert('<h2 ' + msgStyle + '>' + paymentMsg + '</h2>')
                    .set({
                        transition: 'zoom',
                        label: '{/literal}{$LANG.closeUc}{literal}',
                        modal: true,
                        resizable: true
                    })
                    .resizeTo('60%', 250)
                    .setHeader('<em ' + warning + '>{/literal}{$LANG.warningUcAll}{literal}</em>');
        }
    }

</script>
{/literal}
