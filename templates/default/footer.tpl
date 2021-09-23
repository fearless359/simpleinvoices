</div>
<div id="si_footer">
    <div class="si_wrap">
        {$LANG.thankYouInv}
        <a href="https://simpleinvoices.group" target="_blank" title="SimpleInvoices Knowledge Base"><strong>{$LANG.simpleInvoices}</strong></a> | <a href="https://simpleinvoices.group" target="_blank"
                                                                                                                                                      title="SimpleInvoices Group Forum"><strong>{$LANG.forum}</strong></a>
    </div>
</div>
{$smarty.capture.hook_body_end}
<script>
    $(document).ready(function () {
        $(".tooltip").tooltipster({
            animation    : 'fade',
            contentAsHTML: true,
            delay        : 300,
            interactive  : true,
            maxWidth     : 500,
            theme        : 'tooltipster-light',
            trigger      : 'hover'
        });

        $("#frmpost").validate();

        jQuery.validator.addClassRules("validate-date", {
            dateISO: true
        });

        jQuery.validator.addClassRules("validate-number", {
            number: true
        });

        jQuery.validator.addMethod("quantity", function(value) {
            return /^\d*$|^\d*\.\d$|^\d*\.\d\d$|^-\d*$|^-\d*\.\d$|^-\d*\.\d\d$/.test(value);
        }, "Enter a number with no more than two decimal places. Negative numbers are allowed.");

        jQuery.validator.addClassRules("validate-quantity", {
            quantity: true
        });
    });
</script>
</body>
</html>
