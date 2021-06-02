{literal}
<script>
    $(document).on("click", ".delete_link", (function (e) {
        e.preventDefault();
        let row_num = $(this).attr("data-row-num");
        let deleteLineItem = $(this).attr("data-delete-line-item");
        let delete_function = function () {
            let item_id = $("#line_item" + row_num).val();
            if (item_id > 0) {
                delete_line_item(row_num); // hide on screen and flag for deletion
            } else {
                delete_row(row_num); // remove row.
            }
        }
        // If option set in config file, then prompt before deleting.
        if (deleteLineItem === "1") {
            {/literal}
            $("#confirm_delete_line_item")
                .data('delete_function', delete_function)
                .dialog('open');
            {literal}
        } else {
            delete_function();
        }
    }));

    //delete line in invoice
    $(document).on("click", "#xyztrash_link_new", (function (e) {
        e.preventDefault();
        let id = $(this).attr("rel");
        {/literal}
        {if $config.confirmDeleteLineItem}
            {literal}
            let delete_function = function () {
                delete_line_item(id);
            }
            {/literal}
            $("#confirm_delete_line_item")
                .data('delete_function', delete_function)
                .dialog('open');
        {else}
            delete_line_item(id);
        {/if}
        {literal}
    }));

    $(document).ready(function(){
        $("#Container").after(
            '<div id="confirm_delete_line_item" style="display: none;" title="Delete this line item?">' +
            '<div style="padding-right: 2em;">Choose "Delete" to remove this item. If an existing item, it will be removed from the database when you save the form.</div>' +
            '</div>'
        );

        $("#confirm_delete_line_item").dialog({
            autoOpen: false,
            bgiframe: true,
            resizable: false,
            modal: true,
            width:300,
            height:170,
            overlay: {
                backgroundColor: '#000',
                opacity: 0.5
            },
            buttons: {
                Delete: function() {
                    var delete_function = $("#confirm_delete_line_item").data('delete_function');
                    (delete_function)();
                    $(this).dialog('close');
                },
                Cancel: function() {
                    $(this).dialog('close');
                }
            }
        });
    });

</script>
{/literal}
