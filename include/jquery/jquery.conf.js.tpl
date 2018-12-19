{literal}
<script>
    $(function () {
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

        $(".date-picker").datepicker();

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
        $("a.add_line_item").click(function (e) {
            e.preventDefault();
            add_line_item();
            //(unused) already done in the add_line_item fn
            //autoFill($(".details"), "Description");
        });


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

        /* Product Change - updates line item with product price info */
        $(".invoice_export_dialog").click(function () {
            var $row_number = $(this).attr("rel");
            siLog('debug', "{/literal}$config->export->spreadsheet{literal}");
            export_invoice($row_number, '{/literal}{$config->export->spreadsheet}{literal}', '{/literal}{$config->export->wordprocessor}{literal}');
        });

    });

</script>
{/literal}
