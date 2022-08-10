{literal}
<script>
    /*
    * Product Change - updates line item with product price info
    */
    function invoiceProductChange(product, rowNumber, quantity, productGroupsEnabled) {

        $('#gmail_loading').show();
        $.ajax({
            type: 'GET',
            url: './index.php?module=invoices&view=product_ajax&id=' + product + '&row=' + rowNumber,
            data: "id: " + product,
            dataType: "json",
            success: function (data) {
                $('#gmail_loading').hide();

                $("#json_html" + rowNumber).remove();

                if (quantity === undefined || quantity === "") {
                    $("#quantity" + rowNumber).attr("value", "1");
                }

                if (productGroupsEnabled === '1') {
                    $("#unit_price" + rowNumber).val(data['markup_price']);
                } else {
                    $("#unit_price" + rowNumber).val(data['unit_price']);
                }

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
                    $("tbody#row" + rowNumber + " tr.details").before(data['json_html']);
                }
            }

        });
    }
</script>
{/literal}
