{* preload the headers (for faster browsing) *}
{if isset($header_tpl) && $header_tpl}
{include file=$header_tpl}
{else}
{if file_exists($path|cat:'../header.tpl')}{include file=$path|cat:'../header.tpl'}{else}{if file_exists('templates/default/header.tpl')}{include file='templates/default/header.tpl'}{/if}{/if}
{/if}
{if $smarty.capture.hook_auth_login_top}{$smarty.capture.hook_auth_login_top}{/if}
	<div class="si_wrap">
		<form action="" method="post" id="frmLogin" name="frmLogin">
{if $hook_auth_login_hidden}{$hook_auth_login_hidden}
{else}
{if $smarty.capture.hook_auth_login_hidden}{$smarty.capture.hook_auth_login_hidden}{/if}
{/if}
			<input type="hidden" name="action" value="login" />
			<div class="si_box">
				<h1>{$LANG.company_name}</h1>
				<div class="si_box_auth_pad">
					<table>
						<tr>
							<th>{$LANG.email}</th>
							<td><input name="user" size="25" type="text" title="user" value="" /></td>
						</tr>
						<tr>
							<th>{$LANG.password}</th>
							<td><input name="pass" size="25" type="password" title="password" value="" /></td>
						</tr>
						<tr>
							<th>&nbsp;</th>
							<td class='td_error'>{if $errorMessage}<div class="si_error_line">{$errorMessage|outhtml}</div>{/if}</td>
						</tr>
					</table>
					<div class="si_toolbar">
						<button type="submit" value="login">{if isset($LANG.login) && $LANG.login}{$LANG.login}{else}Login{/if}</button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div id="si_footer">
{if $smarty.capture.hook_auth_login_footer}{$smarty.capture.hook_auth_login_footer}
{else}
		<div class="si_wrap">
			<a href="http://www.simpleinvoices.org">{$LANG.powered_by}&nbsp;{$LANG.simple_invoices}</a>
		</div>
{/if}
	</div>
{literal}
	<script>
		$(document).ready(function(){
			$('.si_box').hide();
			$('.si_box').slideDown(500);
		});
		document.frmLogin.user.focus();
	</script>
{/literal}
</body>
</html>
