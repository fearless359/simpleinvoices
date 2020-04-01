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
function delete_row(row_number) {
    //	$('#row'+row_number).hide();
    $('#row' + row_number).remove();
}

// delete existing rows currently in the database.
// This sets up to delete the item when the form is saved.
function delete_line_item(row_number) {
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
    let lastRow = $('#itemtable tbody.line_item:last');
    let rowID_last = $("input[id^='quantity']", lastRow).attr("id");
    rowID_last = parseInt(rowID_last.slice(8)); //using 8 as 'quantity' has eight letters and want to get the number that is after that
    $("#max_items").attr('value', rowID_last);
    siLog('debug', 'Max Items = ' + rowID_last);
}

/*
* function: siLog
* purpose: wrapper function for blackbirdjs logging
* if debugging is OFF in config.php - then blackbirdjs.js wont be loaded in header.tpl and normal call to log.debug would fail and cause problems
*/
function siLog(level, message) {
    let log_level = "log." + level + "('" + message + "')";

    //if blackbirdjs is loaded (ie. debug in config.php is on) run - else do nothing
    if (window.log) {
        eval(log_level);
    }
}

/*
* function: add_line_item
* purpose: to add a new line item in invoice creation page
* */
function add_line_item() {
    let gmail_loading = $('#gmail_loading');
    gmail_loading.show();

    //clone the last tr in the item table
    let jq_selector = '#itemtable tbody.line_item:last';
    let lastRow = $(jq_selector).clone();
    let clonedRow = $(jq_selector).clone();

    //find the Id for the row from the quantity if
    // let rowID_old = $("input[id^='quantity']", clonedRow).attr("id");
    let rowID_old = $(".delete_link", clonedRow).attr("data-row-num");
    if (rowID_old === undefined) {
        alert('Invalid invoice. No existing rows to clone.');
        return false;
    }
    let rowID_last = $(".delete_link", lastRow).attr("data-row-num");
    rowID_old = parseInt(rowID_old);
    rowID_last = parseInt(rowID_last);

    //create next row id
    let rowID_new = rowID_last + 1;

    siLog('debug', 'Line item ' + rowID_new + 'added');

    clonedRow.attr("id", "row" + rowID_new);

    // update hidden delete field
    clonedRow.find("#delete" + rowID_old).attr("id", "delete" + rowID_new);
    clonedRow.find("#delete" + rowID_new).attr("name", "delete" + rowID_new);
    clonedRow.find("#delete" + rowID_new).attr("value", "");

    // update hidden line_item field - is for id of invoice_items so must be cleared.
    clonedRow.find("#line_item" + rowID_old).attr("id", "line_item" + rowID_new);
    clonedRow.find("#line_item" + rowID_new).attr("name", "line_item" + rowID_new);
    clonedRow.find("#line_item" + rowID_new).attr("value", "");

    clonedRow.find("#delete_link" + rowID_old).attr("id", "delete_link" + rowID_new);
    // clonedRow.find("#delete_link" + rowID_new).attr("name", "delete_link" + rowID_new);
    clonedRow.find("#delete_link" + rowID_new).attr("href", "#");
    clonedRow.find("#delete_link" + rowID_new).attr("data-row-num", rowID_new);
    clonedRow.find("#delete_link" + rowID_new).attr("style", "display:inline;");

    // trash can image - it might be blank if only one item row.
    clonedRow.find("#delete_image" + rowID_old).attr("id", "delete_image" + rowID_new);
    clonedRow.find("#delete_image" + rowID_new).attr("src", "./images/common/delete_item.png");

    clonedRow.find("#quantity" + rowID_old).attr("id", "quantity" + rowID_new);
    clonedRow.find("#quantity" + rowID_new).attr("name", "quantity" + rowID_new);
    clonedRow.find("#quantity" + rowID_new).attr("value","");
    clonedRow.find("#quantity" + rowID_new).attr("data-row-num", rowID_new);
    clonedRow.find("#quantity" + rowID_new).removeClass("validate[required,min[.01],custom[number]]");

    clonedRow.find("#products" + rowID_old).attr("id", "products" + rowID_new);
    clonedRow.find("#products" + rowID_new).attr("name", "products" + rowID_new);
    clonedRow.find("#products" + rowID_new).attr("data-row-num", rowID_new);
    clonedRow.find("#products" + rowID_new).find('option:selected').removeAttr("selected");
    clonedRow.find("#products" + rowID_new).prepend(new Option("", ""));
    clonedRow.find("#products" + rowID_new).find('option:eq(0)').attr('selected', true);
    clonedRow.find("#products" + rowID_new).removeClass("validate[required]");

    clonedRow.find("#unit_price" + rowID_old).attr("id", "unit_price" + rowID_new);
    clonedRow.find("#unit_price" + rowID_new).attr("name", "unit_price" + rowID_new);
    clonedRow.find("#unit_price" + rowID_new).attr("value","");
    clonedRow.find("#unit_price" + rowID_new).attr("data-row-num", rowID_new);
    clonedRow.find("#unit_price" + rowID_new).removeClass("validate[required]");

    clonedRow.find("#description" + rowID_old).attr("id", "description" + rowID_new);
    clonedRow.find("#description" + rowID_new).attr("name", "description" + rowID_new);
    clonedRow.find("#description" + rowID_new).css({color: "#b2adad"});
    clonedRow.find("#description" + rowID_new).attr("data-row-num", rowID_new);
    clonedRow.find("#description" + rowID_new).val("");

    clonedRow.find("#tax_id\\[" + rowID_old + "\\]\\[0\\]").attr("id", "tax_id[" + rowID_new + "][0]");
    clonedRow.find("#tax_id\\[" + rowID_new + "\\]\\[0\\]").attr("name", "tax_id[" + rowID_new + "][0]");
    clonedRow.find("#tax_id\\[" + rowID_new + "\\]\\[0\\]").attr("data-row-num", rowID_new);

    clonedRow.find("#tax_id\\[" + rowID_old + "\\]\\[1\\]").attr("id", "tax_id[" + rowID_new + "][1]");
    clonedRow.find("#tax_id\\[" + rowID_new + "\\]\\[1\\]").attr("name", "tax_id[" + rowID_new + "][1]");
    clonedRow.find("#tax_id\\[" + rowID_new + "\\]\\[1\\]").attr("data-row-num", rowID_new);

    clonedRow.find("#json_html" + rowID_old).remove();

    $('#itemtable').append(clonedRow);

    gmail_loading.hide();
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
