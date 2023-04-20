{literal}
<script>
    /*
     * Before allowing form to be submitted, make sure there is consistency between
     * the payment type and the check number. If there is and the payment type is
     * for a check, do a final validation of the check number.
     */
    function verifyPaymentTypeAndCheckNumberConsistent(e)
    {
        let pymtTypeId = $('#pymtTypeId');
        let tagName = pymtTypeId.prop('tagName').toUpperCase();
        let pymtType;
        if (tagName === "SELECT" && !pymtTypeId.prop('disabled')) {
            let pymtTypeSelected = pymtTypeId.find(':selected');
            pymtType = pymtTypeSelected.text().toLowerCase();
        } else {
            pymtTypeId = $('#pymtTypeIdInput');
            pymtType = pymtTypeId.val().toLowerCase();
        }

        let chkNumId = $('#checkNumberId');
        let ckNumber = chkNumId.val();

        let errStyle = 'style="font-size:2rem; color:red;"';
        let msgStyle = 'style="margin:20px auto; color:darkslategrey;"'

        if (pymtType === '{/literal}{$LANG.check}{literal}' && ckNumber.length === 0) {
            alertify.alert('<h2 ' + msgStyle + '>{/literal}{$LANG.checkNumberSet}{literal}</h2>')
                    .set({
                        transition: 'zoom',
                        label: '{/literal}{$LANG.closeUc}{literal}',
                        modal: true,
                        resizable: true
                    })
                    .resizeTo('60%', 200)
                    .setHeader('<em ' + errStyle + '>{/literal}{$LANG.errorUcAll}{literal}</em>');
            e.preventDefault();
        } else if (pymtType !== '{/literal}{$LANG.check}{literal}' && ckNumber.length !== 0) {
            alertify.alert('<h2 ' + msgStyle + '>{/literal}{$LANG.clearCheckNumber}{literal}</h2>')
                    .set({
                        transition: 'zoom',
                        label: '{/literal}{$LANG.closeUc}{literal}',
                        modal: true,
                        resizable: true
                    })
                    .resizeTo('60%', 200)
                    .setHeader('<em ' + errStyle + '>{/literal}{$LANG.errorUcAll}{literal}</em>');
            e.preventDefault();
        } else if (ckNumber.length !== 0) {
            let pattern = /^(\d+ *)|(N\/A)$/i;
            if (!(pattern).test(ckNumber)) {
                alertify.alert('<h2 ' + msgStyle + '>{/literal}{$LANG.enterValidCheckNumber}{literal}</h2>')
                        .set({
                            transition: 'zoom',
                            label: '{/literal}{$LANG.closeUc}{literal}',
                            modal: true,
                            resizable: true
                        })
                        .resizeTo('60%', 200)
                        .setHeader('<em ' + errStyle + '>{/literal}{$LANG.errorUcAll}{literal}</em>');
                e.preventDefault();
            }
        }
    }

    /*
     * Assure proper check number format if present and notify user if
     * payment warehouse record will be created or if amount over the
     * payment warehouse balance.
     */
    function validateCheckNumber()
    {
        let elem = $('#pymtTypeId');
        let tagName = elem.prop('tagName').toUpperCase();
        let pymtType;
        if (tagName === "SELECT") {
            let pymtTypeSelected = elem.find(':selected');
            pymtType = pymtTypeSelected.text().toLowerCase();
        } else {
            pymtType = elem.val().toLowerCase();
        }

        let chkNumId = $('#checkNumberId');
        let ckNumber = chkNumId.val();

        let warning = 'style="font-size:2rem; color:darkorange;"';
        let msgStyle = 'style="margin:20px auto; color:darkslategrey;"'

        if (pymtType === '{/literal}{$LANG.check}{literal}') {
            let pattern = /^(\d+ *)|(N\/A)$/i;
            if (!(pattern).test(ckNumber)) {
                alertify.alert('<h2 ' + msgStyle + '>{/literal}{$LANG.enterValidCheckNumber}{literal}</h2>')
                        .set({
                            transition: 'zoom',
                            label: '{/literal}{$LANG.closeUc}{literal}',
                            modal: true,
                            resizable: true
                        })
                        .resizeTo('60%', 200)
                        .setHeader('<em ' + warning + '>{/literal}{$LANG.warningUcAll}{literal}</em>');
                return false;
            }
        } else if (ckNumber.length > 0) {
            alertify.alert('<h2 ' + msgStyle + '>{/literal}{$LANG.clearCheckNumber}{literal}</h2>')
                    .set({
                        transition: 'zoom',
                        label: '{/literal}{$LANG.closeUc}{literal}',
                        modal: true,
                        resizable: true
                    })
                    .resizeTo('60%', 200)
                    .setHeader('<em ' + warning + '>{/literal}{$LANG.warningUcAll}{literal}</em>');
            return false;
        }
    }

</script>
{/literal}
