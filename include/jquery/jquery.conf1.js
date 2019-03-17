$(document).ready(function () {

    // Click on export invoice button and build href for pdf, doc and xls
    $(document).on("click", ".invoice_export_dialog", (function () {
        let row_number = $(this).attr("rel");
        let spreadsheet = $(this).attr("data-spreadsheet");
        let wordprocessor = $(this).attr("data-wordprocessor");
        export_invoice(row_number, spreadsheet, wordprocessor);
        ShowDialog(true);
    }));

    // Export dialog window - onclick send href to create export file and close window
    $(".export_window").click(function() {
        HideDialog();
    });

    // Close button on export dialog
    $("#webDialogClose").click(function (e) {
        HideDialog();
        e.preventDefault();
    });

    /* Load the cluetip - only if cluetip plugin has been loaded */
    if (jQuery.cluetip) {
        $('a.cluetip').cluetip(
            {
                activation: 'click',
                sticky: true,
                cluetipClass: 'notice',
                fx: {
                    open: 'fadeIn', // can be 'show' or 'slideDown' or 'fadeIn'
                    openSpeed: '70'
                },
                arrows: true,
                closePosition: 'title',
                closeText: '<img src="./images/common/cross.png" alt="" />'
            }
        );
    }

    $('#tabs_customer').tabs();

    $(".date-picker").datepicker({
        dateFormat: "yy-mm-dd"
    });

    /* Product Inventory Change - updates line item with product price info */
    $(".product_inventory_change").change(function () {
        let $product = $(this).val();
        let $existing_cost = $("#cost").val();
        product_inventory_change($product, $existing_cost);
    });

    //add new line item in invoices
    $(document).on("click", "a.add_line_item", (function (e) {
        e.preventDefault();
        add_line_item();
    }));

    // unhide.description, unhide.note, description
    //show invoice item line details
    $(document).on("click", "a.show_details", (function () {
        let clonedRow = $('#itemtable tbody.line_item:first').clone();
        let rowID_old = $("input[id^='quantity']", clonedRow).attr("id");
        if (rowID_old === undefined) {
            alert('Invalid invoice. No existing rows to show.');
            return false;
        }
        $('.details').show(); // Show the details
        $('.hide_details').show(); // Show the hide details button
        $('.show_details').hide(); // Hide the show details button
    }));

    //hide invoice item line details
    $(document).on("click", "a.hide_details", (function () {
        let clonedRow = $('#itemtable tbody.line_item:first').clone();
        let rowID_old = $("input[id^='quantity']", clonedRow).attr("id");
        if (rowID_old === undefined) {
            alert('Invalid invoice. No existing rows to show.');
            return false;
        }
        $('.details').hide(); // Hide the details
        $('.hide_details').hide(); // Hide the hide details button
        $('.show_details').show(); // Show the show details button
    }));

    //calc number of line items
    $(".invoice_save").click(function () {
        $('#gmail_loading').show();
        count_invoice_line_items();
        $('#gmail_loading').hide();
    });
});
