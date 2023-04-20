// Verify check number on change to number or type field.
$(document).on("change", ".validateCheckNumber", (function() {
    // noinspection JSUnresolvedFunction
    validateCheckNumber();
}))

// Call to reload sub_customer list if parent ID changed.
$(document).on("change", ".setSubCustomers", (function() {
    let customerId = $(this).val();
    invoiceCustomerChange(customerId);
}));

// Click on export invoice button and build href for pdf, doc and xls
$(document).on("click", ".invoice_export_dialog", (function () {
    let row_number = $(this).attr("data-row-num");
    let spreadsheet = $(this).attr("data-spreadsheet");
    let wordprocessor = $(this).attr("data-wordprocessor");
    export_invoice(row_number, spreadsheet, wordprocessor);
    showSiDialog(true);
}));

//add new line item in invoices
$(document).on("click", "a.addLineItem", (function (e) {
    e.preventDefault();
    addLineItem();
}));

$(document).ready(function () {
    // Form submission verification
    $('#pymtPostId').submit(function (e) {
        // Check number validation
        // noinspection JSUnresolvedFunction
        verifyPaymentTypeAndCheckNumberConsistent(e);
    });

    // Export dialog window - onclick send href to create export file and close window
    $(".export_window").click(function() {
        hideSiDialog();
    });

    // Close button on export dialog
    $("#webDialogClose").click(function (e) {
        hideSiDialog();
        e.preventDefault();
    });

    $('#tabs_customer').tabs();

    $(".date-picker").datepicker({
        dateFormat: "yy-mm-dd"
    });

    // Set warehoused attributes for screen fields when invoice field changed.
    $(".setWarehousedInfo").change(function() {
        setWarehouseInfoInAmountFields();
    });

    /* Product Inventory Change - updates line item with product price info */
    $(".productInventoryChange").change(function () {
        let $product = $(this).val();
        let $existingCost = $("#cost").val();
        productInventoryChange($product, $existingCost);
    });

    //calc number of line items
    $(".invoice_save").click(function () {
        // noinspection JSJQueryEfficiency
        $('#gmail_loading').show();
        count_invoice_line_items();
        // noinspection JSJQueryEfficiency
        $('#gmail_loading').hide();
    });
});
