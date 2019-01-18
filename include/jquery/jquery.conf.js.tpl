{literal}
<script>
    $(document).ready(function () {
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

        /* Product Change - updates line item with product price info */
        $(".product_change").change(function () {
            var $row_number = $(this).attr("rel");
            var $product = $(this).val();
            var $quantity = $("#quantity" + $row_number).attr("value");
            invoice_product_change($product, $row_number, $quantity);
            siLog('debug', '{/literal}{$LANG.description}{literal}');
        });

        /* Product Inventory Change - updates line item with product price info */
        $(".product_inventory_change").change(function () {
            var $product = $(this).val();
            var $existing_cost = $("#cost").val();
            product_inventory_change($product, $existing_cost);
        });

        //delete line in invoice
        $(".trash_link").click(function (e) {
            e.preventDefault();
            id = $(this).attr("rel");
            {/literal}
            {if $config->confirm->deleteLineItem}
            {literal}
            var delete_function = function () {
                delete_row(id);
            }
            {/literal}
            $("#confirm_delete_line_item").data('delete_function', delete_function);
            $("#confirm_delete_line_item").dialog('open');
            {else}
            delete_row(id);
            {/if}
            {literal}
        });

        //delete line in invoice
        $(".trash_link_edit").click(function (e) {
            e.preventDefault();
            id = $(this).attr("rel");

            {/literal}
            {if $config->confirm->deleteLineItem}
            {literal}
            var delete_function = function () {
                delete_line_item(id);
            }
            {/literal}
            $("#confirm_delete_line_item").data('delete_function', delete_function);
            $("#confirm_delete_line_item").dialog('open');
            {else}
            delete_line_item(id);
            {/if}
            {literal}
        });

        //add new line item in invoices
        $(document).on("click", "a.add_line_item", (function (e) {
            e.preventDefault();
            add_line_item();
        }));
// unhide.description, unhide.note, description
        //show invoice item line details
        $(document).on("click", "a.show_details", (function (e) {
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
            siLog('debug', 'invoice save');
            count_invoice_line_items();
            siLog('debug', 'invoice save- post count');
            $('#gmail_loading').hide();
        });

        // Export dialog window - onclick export button close window
        $(".export_window").click(function () {
            $('.ui-dialog-titlebar-close').trigger('click');
        });

        // Product Change - updates line item with product price info
        $(document).on("click", ".invoice_export_dialog", (function () {
            let row_number = $(this).attr("rel");
            let spreadsheet = $(this).attr("data_spreadsheet");
            let wordprocessor = $(this).attr("data_wordprocessor");
            siLog('debug', spreadsheet);
            export_invoice(row_number, spreadsheet, wordprocessor);
        }));

    });

</script>
{/literal}
