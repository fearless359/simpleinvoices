{literal}
<script>
    $(document).ready(function () {
        //delete line in invoice
        $(document).on("click", ".trash_link", (function (e) {
            e.preventDefault();
            let id = $(this).attr("rel");
            {/literal}
            {if $config->confirm->deleteLineItem}
            {literal}
            let delete_function = function () {
                delete_row(id);
            }
            {/literal}
            $("#confirm_delete_line_item")
                .data('delete_function', delete_function)
                .dialog('open');
            {else}
            delete_row(id);
            {/if}
            {literal}
        }));

        //delete line in invoice
        $(document).on("click", ".trash_link_edit", (function (e) {
            e.preventDefault();
            let id = $(this).attr("rel");

            {/literal}
            {if $config->confirm->deleteLineItem}
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
    });
</script>
{/literal}
