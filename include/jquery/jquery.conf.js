$(document).ready(function () {

    $("#Container").after('<div id="confirm_delete_line_item" style="visibility: hidden;" title="Delete this line item?" > ' +
                          '  <div style = "padding-right: 2em;" > If you choose "Delete" the line item will be removed on Save.< /div>' +
                          '</div>');

    $("#confirm_delete_line_item").dialog({
        autoOpen: false,
        bgiframe: true,
        resizable: false,
        modal: true,
        width: 300,
        height: 170,
        overlay: {
            backgroundColor: '#000',
            opacity: 0.5
        },
        buttons: {
            Delete: function () {
                let delete_function = $("#confirm_delete_line_item").data('delete_function');
                (delete_function)();
                $(this).dialog('close');
            },
            Cancel: function () {
                $(this).dialog('close');
            }
        }
    });

    // Load the jquery datePicker with out config
    if ($.datePicker) {
        $.datePicker.setDateFormat('ymd', '-');
        $('input.date-picker').datePicker({startDate: '01/01/1970'});
        $('input#date2').datePicker({endDate: '01/01/1970'});
    }

    if ($(".showdownloads")) {
        $(".showdownloads").click(function () {
            let offset = $(this).offset();
            $(this)
                .next(".downloads")
                .css("top", offset.top + "px")
                .css("left", offset.left + "px")
                .css("position", "absolute")
                .css("background-color", "#F1F1F1")
                .css("padding", "5px")
                .css("border", "solid 1px #CCC")
                .hover(function () {
                }, function () {
                    $(this).hide()
                })
                .show();
            return false;
        })
    }

    if ($("#ac_me")) {
        $("#ac_me").autocomplete("index.php?module=payments&amp;view=process_ajax", {
            minChars: 1, matchSubset: 1, matchContains: 1, cacheLength: 10, onItemSelect: selectItem,
            ormatItem: formatItem, selectOnly: 1
        });
    }

    if ($('#tabs_customer'))
        $('#tabs_customer').tabs();

    if ($('#trigger-tab'))
        $('#trigger-tab').after('<p><a href="#" onclick="$(\'#container-1\').triggerTab(3); return false;">Activate third tab</a></p>');

    if ($('#custom-tab-by-hash')) {
        $('#custom-tab-by-hash').click(function () {
            let win = window.open(this.href, '', 'directories,location,menubar,resizable,scrollbars,status,toolbar');
            win.focus();
        });
    }

    /*Load the cluetip - only if cluetip plugin has been loaded*/
    if (jQuery.cluetip) {
        $('a.cluetip').cluetip({
            activation: 'click',
            sticky: true,
            cluetipClass: 'notice',
            fx: {
                open: 'fadeIn', // can be 'show' or 'slideDown' or 'fadeIn'
                openSpeed: '70'
            },
            arrows: true,
            closePosition: 'title',
            closeText: '<img src="images/common/cross.png" alt=""/>'
        });
    }

    //load the configs for the html editor
    if ($('.editor') && $('.editor')[0] &&
        typeof($('.editor')[0].contentEditable) != 'undefined' && !(
            (navigator.userAgent.match(/iPhone/i) ||
                navigator.userAgent.match(/iPod/i) ||
                navigator.userAgent.match(/iPad/i)) &&
            navigator.userAgent.match(/CPU\sOS\s[0123]_\d/i))) { //only if it is supported | iPhone OS <= 3.2 incorrectly report it
        $('.editor').wysiwyg({
            controls: {
                html: {visible: true},
                createLink: {visible: false},
                insertImage: {visible: false},
                separator00: {visible: false, separator: false},
                separator01: {visible: false, separator: false},
                separator02: {visible: false, separator: false},
                separator03: {visible: false, separator: false},
                separator04: {visible: false, separator: false},
                separator05: {visible: false, separator: false},
                separator06: {visible: false, separator: false},
                separator07: {visible: false, separator: false},
                separator08: {visible: false, separator: false},
                separator09: {visible: false, separator: false},
                h1mozilla: {visible: false},
                h2mozilla: {visible: false},
                h3mozilla: {visible: false},
                h1: {visible: false},
                h2: {visible: false},
                h3: {visible: false},
                increaseFontSize: {visible: false},
                decreaseFontSize: {visible: false},
                insertOrderedList: {visible: true},
                insertUnorderedList: {visible: true}
            }
        });
    }

    // Product Change - updates line item with product price info
    $(".product_change").livequery('change', function () {
        let row_number = $(this).attr("rel");
        let description = $(this).attr("data_description");
        let product = $(this).val();
        let quantity = $("#quantity" + row_number).attr("value");
        invoice_product_change(product, row_number, quantity, description);
        siLog('debug', description);
    });

    // Product Inventory Change - updates line item with product price info
    $(".product_inventory_change").livequery('change', function () {
        let $product = $(this).val();
        let $existing_cost = $("#cost").val();
        product_inventory_change($product, $existing_cost);
    });

    //delete line in invoice
    $(".trash_link").livequery('click', function (e) {
        e.preventDefault();
        id = $(this).attr("rel");
        let data_delete_line_item = $(this).attr("data_delete_line_item");
        if (data_delete_line_item) {
            let delete_function = function () {
                delete_row(id);
            }
            $("#confirm_delete_line_item").data('delete_function', delete_function);
            $("#confirm_delete_line_item").dialog('open');
        } else {
            delete_row(id);
        }
    });

    // Enable/Disable the clear data check box based on Custom Field Label change.
    $("#cf_custom_label_maint").livequery('change', function () {
        if ($(this).val() == "")
            $('#clear_data_option').removeAttr('disabled', 'disabled');
        else
            $('#clear_data_option').attr('disabled', 'disabled');
    });

    //delete line in invoice
    $(".trash_link_edit").livequery('click', function (e) {
        e.preventDefault();
        id = $(this).attr("rel");
        let data_delete_line_item = $(this).attr("data_delete_line_item");
        if (data_delete_line_item) {
            let delete_function = function () {
                delete_line_item(id);
            }
            $("#confirm_delete_line_item").data('delete_function', delete_function);
            $("#confirm_delete_line_item").dialog('open');
        } else {
            delete_line_item(id);
        }
    });

    //add new line item in invoices
    $("a.add_line_item").click(function (e) {
        e.preventDefault();
        let description = $(this).attr('data_description');
        add_line_item(description);
    });

    //calc number of line items
    $(".invoice_save").click(function () {
        $('#gmail_loading').show();
        siLog('debug', 'invoice save');
        count_invoice_line_items();
        siLog('debug', 'invoice save- post count');
        $('#gmail_loading').hide();
    });

    //Autofill "Description" into the invoice items description/notes textarea
    $(".detail").livequery(function () {
        let description = $(".detail").val();
        let lang_description = $(this).attr('data_description');
        if (description == "") {
            $(".detail").val(lang_description);
            $(".detail").css({color: '#b2adad'});
        }

        $(".detail").focus(function () {
            if ($(this).val() == lang_description) {
                $(this).val("").css({color: '#333'});
            }
        });
    })

    //Export dialog window - onclick export button close window
    $(".export_window").livequery('click', function () {
        $('.ui-dialog-titlebar-close').trigger('click');
    });

    // Product Change - updates line item with product price info
    $(".invoice_export_dialog").livequery('click', function () {
        let row_number = $(this).attr("rel");
        let spreadsheet = $(this).attr("data_spreadsheet");
        let wordprocessor = $(this).attr("data_wordprocessor");
        siLog('debug', spreadsheet);
        export_invoice(row_number, spreadsheet, wordprocessor);
    });

});
