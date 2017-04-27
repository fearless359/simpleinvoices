{*
/*
 * Script: ./extensions/matts_luxury_pack/templates/default/hooks.tpl
 * 	Put code into sections via code hooks
 *
 * Authors:
 *	git0matt@gmail.com
 *
 * Last edited:
 * 	2016-09-10
 *
 * License:
 *	GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
*}
{strip}

{*if $hook_biller_add_table}{$hook_biller_add_table}{/if*}


{capture name="hook_auth_login_hidden"}
<input type="hidden" name="from" value="{$from}" />
{/capture}


{capture name="hook_auth_login_footer"}
		<div class="si_wrap">
			{$LANG.thank_you_inv}<a href="http://www.simpleinvoices.org">{$LANG.simple_invoices}</a> | 
			<a href="http://www.simpleinvoices.org/+">{$LANG.forum}</a> | 
			<a href="http://www.simpleinvoices.org/blog">{$LANG.blog}</a> |
			{$version_name|htmlsafe}
		</div>
{/capture}


{/strip}
