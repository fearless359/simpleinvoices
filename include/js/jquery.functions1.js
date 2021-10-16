/*
* Script: jquery.functions1.js
* Purpose: jquery/javascript functions for Simple Invoices
*/

function ShowDialog(modal)
{
    let overlay = $("#overlay");
    overlay.show();
    $("#dialog").fadeIn(300);

    if (modal) {
        overlay.unbind("click");
    } else {
        overlay.click(function () {
            HideDialog();
        });
    }
}

function HideDialog()
{
    $("#overlay").hide();
    $("#dialog").fadeOut(300);
}

// delete new rows not currently in the database
function deleteRow(row_number) {
    //	$('#row'+row_number).hide();
    $('#row' + row_number).remove();
}

// delete existing rows currently in the database.
// This sets up to delete the item when the form is saved.
function deleteLineItem(row_number) {
    $('#row' + row_number).hide();
    $('#quantity' + row_number).removeAttr('value');
    $('#delete' + row_number).attr('value', 'yes');
}

/*
* Product Change -Inventory  - updates cost from  product info
*/
function product_inventory_change(product, existing_cost) {
    let gmail_loading = $('#gmail_loading');
    gmail_loading.show();
    $.ajax({
        type: 'GET',
        url: './index.php?module=invoices&view=product_inventory_ajax&id=' + product,
        data: "id: " + product,
        dataType: "json",
        success: function (data) {
            gmail_loading.hide();
            if (existing_cost !== null) {
                $("#cost").attr("value", data['cost']);
            }
        }
    });
}

/*
 * Function: count_invoice_line_items
 * Purpose: find the last line item and update max_items so /modules/invoice/save.php can access it
 */
function count_invoice_line_items() {
    let lastRow = $('#itemtable div#qtyColumn:last');
    let lastRowId = $("input[id^='quantity']", lastRow).attr("id");
    lastRowId = parseInt(lastRowId.slice(8)); //using 8 as 'quantity' has eight letters and want to get the number that is after that
    $("#max_items").attr('value', lastRowId);
    siLog('debug', 'Max Items = ' + lastRowId);
}

/*
 * Reloads sub_customer list when parent ID changed.
 */
function invoiceCustomerChange(customerId) {
    $('#gmail_loading').show();
    $.ajax({
        type : 'GET',
        url : './index.php?module=invoices&view=subCustomerAjax&id=' + customerId,
        dataType : "json",
        success : function(data) {
            $("#subCustId")
                .html(data)
                .css('display', 'inline-block');
            $("#noSubCustomers")
                .css('display', 'none');
        },
        error : function() {
            $("#subCustId")
                .html('')
                .css('display', 'none');
            $("#noSubCustomers")
                .css('display', 'inline-block');
        },
        complete : function() {
            $('#gmail_loading').hide();
        }
    });
}

/*
* function: siLog
* purpose: wrapper function for blackbirdjs logging
* if debugging is OFF in config.ini - then blackbirdjs.js wont be loaded in header.tpl and normal call to log.debug would fail and cause problems
*/
function siLog(level, message) {
    let log_level = "log." + level + "('" + message + "')";

    //if blackbirdjs is loaded (ie. debug in config.ini is on) run - else do nothing
    if (window.log) {
        eval(log_level);
    }
}

/*
* function: addLineItem
* purpose: to add a new line item in invoice creation page
* */
function addLineItem() {
    let gmailLoading = $('#gmail_loading');
    gmailLoading.show();

    let numTaxItems = $("#itemtable").attr("data-number-tax-items");

    //clone the last row in the item table
    let jqSelector = '#itemtable div.lineItem:last';
    let lastRow = $(jqSelector).clone();
    let clonedRow = $(jqSelector).clone();

    //find the Id for the row from the quantity if
    // let oldRowId = $("input[id^='quantity']", clonedRow).attr("id");
    let oldRowId = $(".delete_link", clonedRow).attr("data-row-num");
    if (oldRowId === undefined) {
        alert('Invalid invoice. No existing rows to clone.');
        return false;
    }

    let lastRowId = $(".delete_link", lastRow).attr("data-row-num");
    oldRowId = parseInt(oldRowId);
    lastRowId = parseInt(lastRowId);

    //create next row id
    let rowID_new = lastRowId + 1;

    clonedRow.attr("id", "row" + rowID_new);

    // update hidden delete field
    clonedRow.find("#delete" + oldRowId).attr("id", "delete" + rowID_new);
    clonedRow.find("#delete" + rowID_new).attr("name", "delete" + rowID_new);
    clonedRow.find("#delete" + rowID_new).val("");

    // update hidden line_item field - is for id of invoice_items so must be cleared.
    clonedRow.find("#line_item" + oldRowId).attr("id", "line_item" + rowID_new);
    clonedRow.find("#line_item" + rowID_new).attr("name", "line_item" + rowID_new);
    clonedRow.find("#line_item" + rowID_new).val("");

    clonedRow.find("#delete_link" + oldRowId).attr("id", "delete_link" + rowID_new);
    // clonedRow.find("#delete_link" + rowID_new).attr("name", "delete_link" + rowID_new);
    clonedRow.find("#delete_link" + rowID_new).attr("href", "#");
    clonedRow.find("#delete_link" + rowID_new).attr("data-row-num", rowID_new);
    clonedRow.find("#delete_link" + rowID_new).attr("style", "display:inline;");

    // trash can image - it might be blank if only one item row.
    clonedRow.find("#delete_image" + oldRowId).attr("id", "delete_image" + rowID_new);
    clonedRow.find("#delete_image" + rowID_new).attr("src", "images/delete_item.png");

    clonedRow.find("#qtyColumn").css("grid-template-columns", "9% 7% 84%");

    clonedRow.find("#quantity" + oldRowId).attr("id", "quantity" + rowID_new);
    clonedRow.find("#quantity" + rowID_new).attr("name", "quantity" + rowID_new);
    clonedRow.find("#quantity" + rowID_new).val("");
    clonedRow.find("#quantity" + rowID_new).attr("data-row-num", rowID_new);
    clonedRow.find("#quantity" + rowID_new).removeClass("validate[required,min[.01],custom[number]]");

    clonedRow.find("#products" + oldRowId).attr("id", "products" + rowID_new);
    clonedRow.find("#products" + rowID_new).attr("name", "products" + rowID_new);
    clonedRow.find("#products" + rowID_new).attr("data-row-num", rowID_new);
    clonedRow.find("#products" + rowID_new).find('option:selected').removeAttr("selected");
    clonedRow.find("#products" + rowID_new).prepend(new Option("", ""));
    clonedRow.find("#products" + rowID_new).find('option:eq(0)').attr('selected', true);
    clonedRow.find("#products" + rowID_new).removeClass("validate[required]");

    clonedRow.find("#unit_price" + oldRowId).attr("id", "unit_price" + rowID_new);
    clonedRow.find("#unit_price" + rowID_new).attr("name", "unit_price" + rowID_new);
    clonedRow.find("#unit_price" + rowID_new).val("");
    clonedRow.find("#unit_price" + rowID_new).attr("data-row-num", rowID_new);
    clonedRow.find("#unit_price" + rowID_new).removeClass("validate[required]");

    if (numTaxItems > 0) {
        for (let idx = 0; idx < numTaxItems; idx++) {
            clonedRow.find("#tax_id\\[" + oldRowId + "\\]\\[" + idx + "\\]").attr("id", "tax_id[" + rowID_new + "][" + idx + "]");
            clonedRow.find("#tax_id\\[" + rowID_new + "\\]\\[" + idx + "\\]").attr("name", "tax_id[" + rowID_new + "][" + idx + "]");
            clonedRow.find("#tax_id\\[" + rowID_new + "\\]\\[" + idx + "\\]").attr("data-row-num", rowID_new);
            clonedRow.find("#tax_id\\[" + rowID_new + "\\]\\[" + idx + "\\]").find('option:selected').removeAttr("selected");
        }
    }

    clonedRow.find("#description" + oldRowId).attr("id", "description" + rowID_new);
    clonedRow.find("#description" + rowID_new).attr("name", "description" + rowID_new);
    clonedRow.find("#description" + rowID_new).attr("data-row-num", rowID_new);
    clonedRow.find("#description" + rowID_new).val("");

    clonedRow.find("#json_html" + oldRowId).remove();

    $('#itemtable').append(clonedRow);

    siLog('debug', 'Line item ' + rowID_new + 'added');

    gmailLoading.hide();
}

//the export dialog in the manage invoices page
function export_invoice(row_number, spreadsheet, wordprocessor) {
    let exp_dialog = $('#export_dialog');
    exp_dialog.show();
    $(".export_pdf").attr({
        href: "index.php?module=export&view=invoice&id=" + row_number + "&format=pdf"
    });
    $(".export_doc").attr({
        href: "index.php?module=export&view=invoice&id=" + row_number + "&format=file&filetype=" + wordprocessor
    });
    $(".export_xls").attr({
        href: "index.php?module=export&view=invoice&id=" + row_number + "&format=file&filetype=" + spreadsheet
    });
    exp_dialog.dialog({
        modal: true,
        height: 230,
        buttons: {
            "Cancel": function () {
                $(this).dialog("destroy");
            }
        },
        overlay: {
            opacity: 0.5,
            background: "black"
        },
        close: function () {
            $(this).dialog("destroy")
        }
    });
}
