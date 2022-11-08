{literal}
<script>
    // unhide.description, unhide.note, description
    //show invoice item line details
    $(document).on("click", "a.show_details", function () {
        let clonedRow = $('#itemtable div.lineItem:first').clone();
        let rowID_old = $("input[id^='quantity']", clonedRow).attr("id");
        if (rowID_old === undefined) {
            let warnMsg = {/literal}'{$LANG.invalidUc} {$LANG.invoice}. {$LANG.noUc} {$LANG.existing} ' +
                '{$LANG.rows} {$LANG.to} {$LANG.show}.';{literal}
            let warning = 'style="font-size:2rem; color:darkorange;"';
            let msgStyle = 'style="margin:20px auto; color:darkslategrey;"'
            alertify.alert('<h2 ' + msgStyle + '>' + warnMsg + '</h2>')
                    .set({
                        transition: 'zoom',
                        label: '{/literal}{$LANG.closeUc}{literal}',
                        modal: true,
                        resizable: true
                    })
                    .resizeTo('60%', 250)
                    .setHeader('<em ' + warning + '>{/literal}{$LANG.warningUcAll}{literal}</em>');
            return false;
        }
        $('.details').show(); // Show the details
        $('.hide_details').show(); // Show the hide details button
        $('.show_details').hide(); // Hide the show details button
    });

    //hide invoice item line details
    $(document).on("click", "a.hide_details", (function () {
        let clonedRow = $('#itemtable div.lineItem:first').clone();
        let rowID_old = $("input[id^='quantity']", clonedRow).attr("id");
        if (rowID_old === undefined) {
            let warnMsg = {/literal}'{$LANG.invalidUc} {$LANG.invoice}. {$LANG.noUc} {$LANG.existing} ' +
                '{$LANG.rows} {$LANG.to} {$LANG.show}.';{literal}
            let warning = 'style="font-size:2rem; color:darkorange;"';
            let msgStyle = 'style="margin:20px auto; color:darkslategrey;"'
            alertify.alert('<h2 ' + msgStyle + '>' + warnMsg + '</h2>')
                    .set({
                        transition: 'zoom',
                        label: '{/literal}{$LANG.closeUc}{literal}',
                        modal: true,
                        resizable: true
                    })
                    .resizeTo('60%', 250)
                    .setHeader('<em ' + warning + '>{/literal}{$LANG.warningUcAll}{literal}</em>');
            return false;
        }

        $('.details').hide(); // Hide the details
        $('.hide_details').hide(); // Hide the hide details button
        $('.show_details').show(); // Show the show details button
    }));

    $(document).on("click", ".delete_link", function (e) {
        e.preventDefault();
        let rowNum = $(this).attr("data-row-num");
        let confirmDeleteLineItem = $(this).attr("data-delete-line-item");
        let delete_function = function () {
            let itemId = $("#line_item" + rowNum).val();
            if (itemId > 0) {
                deleteLineItem(rowNum); // hide on screen and flag for deletion
            } else {
                deleteRow(rowNum); // remove row.
            }
        }
        // If option set in config file, then prompt before deleting.
        if (confirmDeleteLineItem === "1") {
            {/literal}
            $("#confirm_delete_line_item")
                .data('delete_function', delete_function)
                .dialog('open');
            {literal}
        } else {
            delete_function();
        }
    });

    //delete line in invoice
    $(document).on("click", "#xyztrash_link_new", (function (e) {
        e.preventDefault();
        let id = $(this).attr("rel");
        {/literal}
        {if $config.confirmDeleteLineItem}
            {literal}
            let delete_function = function () {
                deleteLineItem(id);
            }
            {/literal}
            $("#confirm_delete_line_item")
                .data('delete_function', delete_function)
                .dialog('open');
        {else}
            deleteLineItem(id);
        {/if}
        {literal}
    }));

    $(document).ready(function(){
        $("#Container").after(
            '<div id="confirm_delete_line_item" style="display: none;" title="Delete this line item?">' +
            '<div style="padding-right: 2em;">{/literal}{$LANG.itemDeleteMsg}{literal}</div>' +
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
                    let delete_function = $("#confirm_delete_line_item").data('delete_function');
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
