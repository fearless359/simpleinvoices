</div>
<div id="si_footer">
    <div class="si_wrap">
        {$LANG.thankYouInv}
        <a href="https://simpleinvoices.group" target="_blank" title="SimpleInvoices Knowledge Base"><strong>{$LANG.simpleInvoices}</strong></a> | <a href="https://simpleinvoices.group" target="_blank"
                                                                                                                                                      title="SimpleInvoices Group Forum"><strong>{$LANG.forum}</strong></a>
    </div>
</div>
{$smarty.capture.hook_body_end}
<!--suppress JSUnresolvedReference -->
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

        $("#frmpost").submit(function (event) {
            verifyErrorsResolved(event);
        });
    });
</script>
{include 'include/js/globalizedNumberValidator.js.tpl'}
</body>
</html>
