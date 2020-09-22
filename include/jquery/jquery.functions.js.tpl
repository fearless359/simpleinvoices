{literal}
<script>
    /*
    * Product Change - updates line item with product price info
    */
    function invoice_product_change(product, row_number, quantity) {

        $('#gmail_loading').show();
        $.ajax({
            type: 'GET',
            url: './index.php?module=invoices&view=product_ajax&id=' + product + '&row=' + row_number,
            data: "id: " + product,
            dataType: "json",
            success: function (data) {
                $('#gmail_loading').hide();

                $("#json_html" + row_number).remove();

                if (quantity === undefined || quantity === "") {
                    $("#quantity" + row_number).attr("value", "1");
                }

                $("#unit_price" + row_number).attr("value", data['unit_price']);

                $("#tax_id\\[" + row_number + "\\]\\[0\\]").val(data['default_tax_id']);
                if (data['default_tax_id_2'] === null) {
                    $("#tax_id\\[" + row_number + "\\]\\[1\\]").val('');
                } else {
                    $("#tax_id\\[" + row_number + "\\]\\[1\\]").val(data['default_tax_id_2']);
                }

                if (data['show_description'] === "Y") {
                    $("tbody#row" + row_number + " tr.details").removeClass('si_hide');
                } else {
                    $("tbody#row" + row_number + " tr.details").addClass('si_hide');
                }

                let desc_row = $("#description" + row_number);
                let row_val = desc_row.val();
                let rel_attr = desc_row.attr('rel');
                if (!row_val || row_val === rel_attr || row_val === '{/literal}{$LANG.descriptionUc}{literal}') {
                    if (data['notes_as_description'] === "Y") {
                        desc_row.val(data['notes']);
                        desc_row.attr('rel', data['notes']);
                    } else {
                        desc_row.val('{/literal}{$LANG.descriptionUc}{literal}');
                        desc_row.attr('rel', '{/literal}{$LANG.descriptionUc}{literal}');

                    }
                }

                if (data['json_html'] !== "") {
                    $("tbody#row" + row_number + " tr.details").before(data['json_html']);
                }
            }

        });
    }
</script>
{/literal}
