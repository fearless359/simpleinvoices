
/*
 *  Get properly formatted locale from localeId hidden field.
 */
function retrieveLocale(elem, useDataField) {
    let locale;
    if (useDataField === undefined || !useDataField) {
        locale = elem.val();
    } else {
        locale = elem.attr('data-locale');
    }

    if (locale === undefined || locale.length === 0) {
        locale = 'en-US';
    } else {
        locale = locale.replace('_', '-');
    }
    return locale;
}

/*
 *  Globalized number format validation.
 *  Note: If an error occurs and this field has the special "checkForWarehousedAmount"
 *        class, the class will be changed to "checkForWarehousedAmountX" to prevent it
 *        from performing this validation. Similarly, if this field is valid and has
 *        the class "checkForWarehousedAmountX" from a previous error, the class will
 *        be reset to "checkForWarehousedAmount" so the validation will be performed.
 *
 */
function validateNumber(event, elem, locale, val, fracDigits, errorMsg) {
    // Get the group separator for the locale
    let group = new Intl.NumberFormat(locale).format(1111).replace(/1/g, '');

    // Get the decimal point character for the locale
    let decimalPoint = new Intl.NumberFormat(locale).format(1.1).replace(/1/g, '');

    let pattern = '^';
    let grpPos = val.indexOf(group);
    let groupSepPresent = grpPos > 0;

    // Add pattern part for each group preceding a group separator
    let lastGrpPos = 0;
    while (grpPos !== -1) {
        lastGrpPos = grpPos;
        grpPos = val.indexOf(group, grpPos + 1);
        if (pattern.length > 3) {
            // Not first group in pattern so must be 3 digits long
            pattern += '\\d{3}' + group;
        } else {
            // First group in pattern so 1 to 3 digits precede group separator
            pattern += '\\d{1,3}' + group;
        }
    }

    // If groups were encountered, there must be 3 digits before the decimal point.
    // Otherwise, there can be any number of digits before the decimal point.
    if (groupSepPresent) {
        pattern += '\\d{3}';
    } else {
        pattern += '\\d*';
    }

    // Add test for decimal point part if a decimal point is present.
    // Be sure to handle 0 digits in fractional part.
    if (val.indexOf(decimalPoint) !== -1 && fracDigits > 0) {
        pattern += '\\' + decimalPoint + '\\d{1,' + fracDigits + '}';
    }
    pattern += "$";

    if (new RegExp(pattern).test(val) === false) {
        // If the value is invalid, display an error message and add an error class to the input field
        event.preventDefault();
        elem.focus();
        elem.removeClass('success').addClass('error').next('.error').remove();
        elem.after('<span class="error" id="errorMsgId">' + errorMsg + '</span>');
        return false;
    }

    // If the value is valid, remove any error messages and add a success class to the input field
    elem.removeClass('error').addClass('success').next('#errorMsgId').remove();
    return true;
}

// Normalizes floatVal by converting it to US/mysql std format number.
function normalizeFloatVal(floatVal, locale) {
    let group = new Intl.NumberFormat(locale).format(1111).replace(/1/g, '');
    let decimalPoint = new Intl.NumberFormat(locale).format(1.1).replace(/1/g, '');
    return parseFloat(floatVal
        .replace(new RegExp('\\' + group, 'g'), '')
        .replace(new RegExp('\\' + decimalPoint), '.')
    );
}

function invoicePreferenceChange(newLocale, newCurrencyCode) {
    let localeElem = $('#localeId');
    let origLocale = retrieveLocale(localeElem);
    if (origLocale === newLocale) {
        // No change needed
        return;
    }
    localeElem.val(newLocale);
    $('#currencyCodeId').val(newCurrencyCode);

    let intl = new Intl.NumberFormat(newLocale, {style: 'currency', currency: newCurrencyCode});
    let options = intl.resolvedOptions();
    let fracDigits = options.minimumFractionDigits;

    $('#precisionId').val(fracDigits);

    let qtyFmtr = new Intl.NumberFormat(newLocale);
    let currFmtr = new Intl.NumberFormat(newLocale, {maximumFractionDigits: fracDigits, minimumFractionDigits: fracDigits});

    let maxItems = $('#max_items').val();
    for (let $i = 0; $i < maxItems; $i++) {
        let qtyElem = $('#quantity' + $i);
        let qtyVal = qtyElem.val();
        if (qtyVal.length !== 0) {
            qtyVal = normalizeFloatVal(qtyVal, origLocale);
            let fmtdQtyVal = qtyFmtr.format(qtyVal);
            qtyElem.val(fmtdQtyVal);
        }

        let priceElem = $('#unit_price' + $i);
        let priceVal = priceElem.val();
        if (priceVal.length !== 0) {
            priceVal = normalizeFloatVal(priceVal, origLocale);
            let fmtdPriceVal = currFmtr.format(priceVal);
            priceElem.val(fmtdPriceVal);
        }
    }
}

/*
* Product Change - updates line item with product price info
*/
function invoiceProductChange(product, rowNumber, qtyVal, productGroupsEnabled, locale, fracDigits, desc) {

    // noinspection JSJQueryEfficiency
    $('#gmail_loading').show();
    $.ajax({
        type: 'GET',
        url: './index.php?module=invoices&view=product_ajax&id=' + product + '&row=' + rowNumber +
            '&locale=' + locale + '&fracDigits=' + fracDigits,
        data: "id: " + product,
        dataType: "json",
        success: function (data) {
            $('#gmail_loading').hide();

            $("#json_html" + rowNumber).remove();

            let qtyElem = $('#quantity' + rowNumber);
            if (qtyVal === undefined || qtyVal.length === 0) {
                qtyElem.val("1");
            }

            let unitPriceElem = $('#unit_price' + rowNumber);
            let unitPrice;
            if (productGroupsEnabled === '1') {
                // unitPrice = unitPriceElem.val(data['markup_price']);
                unitPrice = data['markup_price'];
            } else {
                // unitPrice = unitPriceElem.val(data['unit_price']);
                unitPrice = data['unit_price'];
            }
            unitPriceElem.val(unitPrice);

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
                    desc_row.attr('placeholder', desc);
                }
            }

            if (data['json_html'] !== "") {
                $("tbody#row" + rowNumber + " tr.details").before(data['json_html']);
            }
        }

    });
}
