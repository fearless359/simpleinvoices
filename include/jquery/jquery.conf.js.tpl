{literal}
<script>
    $(document).ready(function () {
        /* Product Change - updates line item with product price info */
        $(".product_change").change(function () {
            let $row_number = $(this).attr("rel");
            let $product = $(this).val();
            let $quantity = $("#quantity" + $row_number).attr("value");
            invoice_product_change($product, $row_number, $quantity);
            siLog('debug', '{/literal}{$LANG.description}{literal}');
        });

        //delete line in invoice
        $(document).on("click", ".trash_link", (function (e) {
            e.preventDefault();
            id = $(this).attr("rel");
            {/literal}
            {if $config->confirm->deleteLineItem}
            {literal}
            let delete_function = function () {
                delete_row(id);
            }
            {/literal}
            $("#confirm_delete_line_item").data('delete_function', delete_function);
            $("#confirm_delete_line_item").dialog('open');
            {else}
            delete_row(id);
            {/if}
            {literal}
        }));

        //delete line in invoice
        $(document).on("click", ".trash_link_edit", (function (e) {
            e.preventDefault();
            id = $(this).attr("rel");

            {/literal}
            {if $config->confirm->deleteLineItem}
            {literal}
            let delete_function = function () {
                delete_line_item(id);
            }
            {/literal}
            $("#confirm_delete_line_item").data('delete_function', delete_function);
            $("#confirm_delete_line_item").dialog('open');
            {else}
            delete_line_item(id);
            {/if}
            {literal}
        }));
    });
</script>
{/literal}
