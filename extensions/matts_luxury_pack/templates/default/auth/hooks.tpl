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
{*assign var=inc value=$smarty.template|dirname}
{assign var=pos value=$inc|strrpos:'/':-4}
{assign var=pth value=$inc|substr:0:$pos}
{assign var=mypos value=$smarty.template|strrpos:'/'}
{assign var=myroot value=$smarty.template|substr:1:$mypos-1*}


{*if isset($hook_biller_add_table) && $hook_biller_add_table}{$hook_biller_add_table}{/if*}
{capture name="hook_auth_login_hidden"}
<input type="hidden" name="from" value="{$from}" />
{/capture}

{*
{capture name=""}
{/capture}
*}

{/strip}
