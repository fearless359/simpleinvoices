{literal}
    <script src="include/js/globalizedNumberValidator.js"></script>

<!--suppress JSUnresolvedFunction -->
<script>
    function verifyErrorsResolved(event) {
        if ((document).getElementById("errorMsgId")) {
            let errStyle = 'style="font-size:2rem; color:red;"';
            let msgStyle = 'style="margin:20px auto; color:darkslategrey;"';
            let msg = '{/literal}{$LANG.resolveUc} {$LANG.errors} {$LANG.before} {$LANG.submitting} {$LANG.changes}!{literal}'
            alertify.alert('<h2 ' + msgStyle + '>' + msg + '</h2>')
                    .set({
                        transition: 'zoom',
                        label: '{/literal}{$LANG.closeUc}{literal}',
                        modal: true,
                        resizable: true
                    })
                    .resizeTo('60%', 200)
                    .setHeader('<em ' + errStyle + '>{/literal}{$LANG.errorUcAll}{literal}</em>');
            event.preventDefault();
        }
    }

    // See if amount greater than payment due or warehoused amount
    $(".checkForWarehousedAmount").change(function (event) {
        let elem = $(this);

        // noinspection JSUnresolvedFunction
        let locale = retrieveLocale($('#localeId'));

        let errorMsg = '{/literal}{$LANG.invalidUc} {$LANG.number} {$LANG.entered}{literal}';

        let val = elem.val();

        // Get the number of fractional digits to require for locale
        let intl = new Intl.NumberFormat(locale, {style: 'currency', currency: 'USD'});
        let options = intl.resolvedOptions();
        let fracDigits = options.minimumFractionDigits;

        if (validateNumber(event, elem, locale, val, fracDigits, errorMsg) !== false) {
            let currencyCode = $('#currencyCodeId').val();
            if (checkPaymentInWarehouse(event, elem, locale, currencyCode, val) === false) {
                event.preventDefault();
                elem.focus();
            }
        } else {
            event.preventDefault();
            elem.focus();
        }
    });

    // Check payment against payment warehouse and report appropriate warnings.
    // Returns false if error found. Else returns true.
    function checkPaymentInWarehouse(event, elem, locale, currencyCode, val) {
        let amt = normalizeFloatVal(val, locale);
        let owing = normalizeFloatVal(elem.attr('data-owing'), locale);
        let warehousedPayment = normalizeFloatVal(elem.attr("data-warehoused-payment"), locale);

        let result = true;
        if (amt > owing || warehousedPayment > 0 && amt > warehousedPayment) {
            let msgStyle = 'style="margin:20px auto; color:darkslategrey;"';
            let warning = 'style="font-size:2rem; color:darkorange;"';
            let warningMsg = '{/literal}{$LANG.warningUcAll}{literal}';
            let error = 'style="font-size:2.5rem; color:red;"';
            let errorMsg = '{/literal}{$LANG.errorUcAll}{literal}';

            let msgType = warning;
            let msg = warningMsg;

            let formatter = new Intl.NumberFormat(locale, {style: 'currency', currency: currencyCode});
            let fmtAmt = formatter.format(amt);
            let fmtOwing = formatter.format(owing);
            let fmtWarehousedPayment = formatter.format(warehousedPayment);

            let maxAmt = 0;
            let paymentMsg = {/literal}'{$LANG.paymentUc} {$LANG.amount} (' + fmtAmt + ') {$LANG.exceeds} '{literal};
            if (warehousedPayment > 0) {
                if (amt > owing && owing <= warehousedPayment) {
                    paymentMsg += {/literal}'{$LANG.amount} {$LANG.owing} (' + fmtOwing + '). {$LANG.reduceUc} {$LANG.payment} ' +
                        '{$LANG.accordingly} {$LANG.asLc} {$LANG.warehoused} {$LANG.balance} {$LANG.already} {$LANG.exists}.';{literal}
                    maxAmt = owing;
                    msgType = error;
                    msg = errorMsg;
                    result = false;
                } else {
                    paymentMsg += {/literal}'{$LANG.warehoused} {$LANG.balance} (' + fmtWarehousedPayment + '). ' +
                        '{$LANG.theUc} {$LANG.warehoused} {$LANG.balance} {$LANG.must} {$LANG.be} {$LANG.applied} ' +
                        '{$LANG.before} {$LANG.additional} {$LANG.funds} {$LANG.can} {$LANG.be} {$LANG.paid}.';{literal}
                    maxAmt = warehousedPayment;
                    msgType = error;
                    msg = errorMsg;
                    result = false;
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
                        label     : '{/literal}{$LANG.closeUc}{literal}',
                        modal     : true,
                        resizable : true
                    })
                    .resizeTo('60%', 250)
                    .setHeader('<em ' + msgType + '>' + msg + '</em>');
        }
        return result;
    }

    $(".changePaymentWarehouseCustomer").change(function () {
        let option = $('option:selected', this);
        let newLocale = retrieveLocale(option, true);
        let newCurrencyCode = option.attr('data-currency-code');

        let localeElem = $('#localeId');
        let oldLocale = retrieveLocale(localeElem);
        localeElem.val(newLocale);
        let currencyCodeElem = $('#currencyCodeId');
        currencyCodeElem.val(newCurrencyCode);

        let balanceElem = $('#balanceId');
        let balance = normalizeFloatVal(balanceElem.val(), oldLocale);

        if (!isNaN(balance)) {
            let formatter = new Intl.NumberFormat(newLocale);
            let fmtdBalance = formatter.format(balance);
            balanceElem.val(fmtdBalance);
        }
    });

    $(".validateWholeNumber").change(function (event) {
        let locale = retrieveLocale($("#localeId"));
        let errorMsg = '{/literal}{$LANG.invalidUc} {$LANG.value} {$LANG.entered}. ' +
            '{$LANG.mustUc} {$LANG.be} {$LANG.a} {$LANG.whole} {$LANG.number}.{literal}';
        let val = $(this).val();
        let elem = $(this);
        validateNumber(event, elem, locale, val, 0, errorMsg);
    });

    $('.invoicePreference').change(function () {
        let option = $('option:selected', this);
        let locale = retrieveLocale(option, true);
        let currencyCode = option.attr('data-currency-code');
        $(this).attr('data-curr-pref-id', option.val());

        invoicePreferenceChange(locale, currencyCode);
    });

    $('.adjustTaxSign').change(function () {
        let percentage = parseFloat($(this).val());
        let typeElem = $('#typeId');
        let selectedValue = typeElem.val();
        // Make sure the setting of the rate type is reasonably consistent with
        // the value of the rate field.
        if ((isNaN(percentage) || percentage === 0) && selectedValue.length > 0) {
            typeElem.val('');
        } else if (!isNaN(percentage) && percentage !== 0 && selectedValue.length === 0) {
            typeElem.val('%');
        }
    });

    $('.checkTaxPercentage').change(function () {
        let typeVal = $(this).val();
        let percentage = parseFloat($('#percentageId').val());
        // Issue warning if rate type changed to value inconsistent with the rate field.
        let warnMsg;
        if ((isNaN(percentage) || percentage === 0) && typeVal.length > 0) {
            warnMsg = {/literal}'{$LANG.rateUc} {$LANG.type} {$LANG.must} {$LANG.be} ' +
                '{$LANG.empty} {$LANG.if} {$LANG.rateUc} {$LANG.is} {$LANG.notLc} {$LANG.set}.';{literal}
        } else if (!isNaN(percentage) && percentage !== 0 && typeVal.length === 0) {
            warnMsg = {/literal}'{$LANG.rateUc} {$LANG.type} {$LANG.must} {$LANG.be} ' +
                '{$LANG.set} {$LANG.if} {$LANG.rateUc} {$LANG.is} {$LANG.set}.';{literal}
        }

        if (warnMsg !== undefined) {
            let warning = 'style="font-size:2rem; color:darkorange;"';
            let msgStyle = 'style="margin:20px auto; color:darkslategrey;"';
            alertify.alert('<h2 ' + msgStyle + '>' + warnMsg + '</h2>')
                    .set({
                        transition: 'zoom',
                        label     : '{/literal}{$LANG.closeUc}{literal}',
                        modal     : true,
                        resizable : true
                    })
                    .resizeTo('60%', 250)
                    .setHeader('<em ' + warning + '>{/literal}{$LANG.warningUcAll}{literal}</em>');
        }
    });

    $('.cronInvoiceChange').change(function () {
        // Get current locale setting
        let localeElem = $('#localeId');
        let origLocale = retrieveLocale(localeElem);

        // Get new locale from selected item.
        let option = $('option:selected', this);
        let newLocale = retrieveLocale(option, true);

        let newCurrencyCode = option.attr('data-currency-code');

        // Check if locale changed. Update recurrence value if it has.
        if (origLocale !== newLocale) {
            // Update to new locale and currency code
            localeElem.val(newLocale);

            $('#currencyCodeId').val(newCurrencyCode);

            // Check to see if there is a recurrence value to change
            let recurElem = $('#recurrenceId');
            let recurVal = recurElem.val();
            if (recurVal.length > 0) {
                let fmtr = new Intl.NumberFormat(newLocale);
                // Normalize value then format for new locale
                recurVal = normalizeFloatVal(recurVal, origLocale);
                let fmtdRecurVal = fmtr.format(recurVal);
                recurElem.attr('value', fmtdRecurVal);
            }
        }
    });

    $('.expenseInvoiceChange').change(function () {
        // Get current locale setting
        let localeElem = $('#localeId');
        let origLocale = retrieveLocale(localeElem);

        // Get new locale from selected item.
        let option = $('option:selected', this);
        let newLocale = retrieveLocale(option, true);

        let newCurrencyCode = option.attr('data-currency-code');

        let newPrecision = option.attr('data-precision');

        // Check if locale changed. Update recurrence value if it has.
        if (origLocale !== newLocale) {
            // Update to new locale and currency code
            localeElem.val(newLocale);

            $('#currencyCodeId').val(newCurrencyCode);

            // Check to see if there is a recurrence value to change
            let amountElem = $('#amountId');
            let amountVal = amountElem.val();
            if (amountVal.length > 0) {
                let fmtr;
                if (newPrecision === undefined) {
                    fmtr = new Intl.NumberFormat(newLocale);
                } else {
                    fmtr = new Intl.NumberFormat(newLocale, { minimumFractionDigits: newPrecision} );
                }

                // Normalize value then format for new locale
                amountVal = normalizeFloatVal(amountVal, origLocale);
                let fmtdAmountVal = fmtr.format(amountVal);
                amountElem.val(fmtdAmountVal);
            }
        }
    });

    // functions below affect fields that can be added to screen after it
    // is initially displayed.

    $(document).on('change', '.validateQuantity', function (event) {
        let locale = retrieveLocale($("#localeId"));
        let errorMsg = '{/literal}{$LANG.invalidUc} {$LANG.quantity} {$LANG.value} {$LANG.entered}{literal}';
        let val = $(this).val();
        let elem = $(this);
        let fracDigits = elem.attr('data-decimal-places');
        if (fracDigits === undefined) {
            fracDigits = 2;
        }

        validateNumber(event, elem, locale, val, fracDigits, errorMsg);
    });

    $(document).on('change', ".validateNumber", function (event) {
        // noinspection JSUnresolvedFunction
        let locale = retrieveLocale($("#localeId"));
        let errorMsg = '{/literal}{$LANG.invalidUc} {$LANG.number} {$LANG.entered}{literal}';
        let elem = $(this);
        let val = elem.val();
        let currencyCodeElem = $('#currencyCodeId');
        let currencyCode = currencyCodeElem.val();

        // Get the number of fractional digits to require for locale
        let intl = new Intl.NumberFormat(locale, {style: 'currency', currency: currencyCode});
        let options = intl.resolvedOptions();
        let fracDigits = options.minimumFractionDigits;

        validateNumber(event, elem, locale, val, fracDigits, errorMsg);
    });

    $(document).on('change', '.productChange', function () {
        let product = $(this).val();
        let rowNumber = $(this).attr("data-row-num");
        let productGroupsEnabled = $(this).attr("data-product-groups-enabled");
        let qtyElem = $('#quantity' + rowNumber);
        let qtyVal = qtyElem.val();
        let locale = retrieveLocale($('#localeId'));
        let currencyCodeElem = $('#currencyCodeId');
        let currencyCode = currencyCodeElem.val();

        let intl = new Intl.NumberFormat(locale, {style: 'currency', currency: currencyCode});
        let options = intl.resolvedOptions();
        let fracDigits = options.minimumFractionDigits;

        let desc = '{/literal}{$LANG.descriptionUc}{literal}';

        // This function is in the jquery_functions.js.tpl file and will resolve at runtime.
        // noinspection JSUnresolvedFunction
        invoiceProductChange(product, rowNumber, qtyVal, productGroupsEnabled, locale, fracDigits, desc);
    });

</script>
{/literal}
