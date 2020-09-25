/*
 * Call to reload sub_customer list if parent ID changed.
 */
$("#customer_id").change(function() {
    var $customer_id = $(this).val();
    invoice_customer_change($customer_id);
});

/*
 * Reloads sub_customer list when parent ID changed.
 */
function invoice_customer_change(customer_id) {
    $('#gmail_loading').show();
    $.ajax({
        type : 'GET',
        url : './index.php?module=invoices&;view=sub_customer_ajax&;id=' + customer_id,
        // data: "id: "+product_code,
        dataType : "json",
        success : function(data) {
            $('#gmail_loading').hide();
            $("#custom_field1").html(data);
        },
        complete : function() {
            $('#gmail_loading').hide();
        }
    });
}

