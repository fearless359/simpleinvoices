{* preload the headers (for faster browsing) *}
{include file='templates/default/header.tpl'}

<div class="loginForm">
    <div class="loginForm__box">
        <form action="" method="post" id="frmLogin" name="frmLogin">
            <input type="hidden" name="action" value="login"/>
            <div class="loginForm__header">
                <img src="{$logoImage}" alt="logo" class="login__logo"
                     style="width: {$logoImageWidth}; height: {$logoImageHeight};">
                <span class="loginForm__company">{$logoCompanyName}</span>
            </div>
            <div class="loginForm__group">
                <input type="text" name="user" id="userId" class="loginForm__input" placeholder="{$PLACEHOLDERS['id']}"/>
                <label for="userId" class="loginForm__label">{$LANG.idUc}</label>
            </div>

            <div class="loginForm__group">
                <input type="password" name="pass" id="password" class="loginForm__input" placeholder="{$PLACEHOLDERS['password']}" value=""/></td>
                <label for="password" class="loginForm__label">{$LANG.password}</label>
            </div>
            {if $errorMessage }
                <div class="error_line">{$errorMessage|outHtml}</div>
            {/if}

            <div class="loginForm__group">
                <div class="loginForm__button">
                    <button type="submit" value="login" class="btn btn--grey">{$LANG.login}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="si_footer">
    <div class="si_wrap">
        <a href="https://simpleinvoices.group">{$LANG.poweredBy}&nbsp;{$LANG.simpleInvoices}</a>
    </div>
</div>
<script>
    {literal}
    $(document).ready(function () {
        $('.login__box').hide()
            .slideDown(500);
    });
    document.frmLogin.user.focus();
    {/literal}
</script>
{* Note that these tags have matches in header.tpl file *}
</body>
</html>
