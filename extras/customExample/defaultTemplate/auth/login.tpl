<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>SimpleInvoices - Login</title>
    <link rel="stylesheet" type="text/css" href="templates/default/css/info.css"/>
    <link rel="stylesheet" type="text/css" href="extras/customExample/myMedias/my.css"/>
</head>
<body class="login">
<div id='myHeader'>
    <div id='myLogo'></div>
    My Header
</div>
<div class="Container">
    {if $errorMessage }
        <p class="center"><strong style="color: #990000;">{if isset($errorMessage)}{$errorMessage|outHtml}{/if}</strong><br/><br/></p>
    {/if}
    <div id="Dialog">
        <div class="center">
            <h1>{$LANG.companyName}</h1>
            <form action="" method="post" id="frmLogin" name="frmLogin">
                <input type="hidden" name="action" value="login"/>
                <table>
                    <tr>
                        <td>
                            {$LANG.email}:
                        </td>
                        <td>
                            <input name="user" size="25" type="text" title="user" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {$LANG.password}:
                        </td>
                        <td>
                            <input name="pass" size="25" type="password" title="password" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <input type="submit" value="login"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <br/>
    </div>
    <br/>
    <a href="https://simpleinvoices.group" target="_blank">{$LANG.poweredBy}&nbsp;{$LANG.simpleInvoices}</a>
</div>

<script>
    document.frmLogin.user.focus();
</script>

</body>
</html>
