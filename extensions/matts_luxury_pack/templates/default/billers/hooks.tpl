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


{capture name="hook_biller_add_table"}
		<tr>
			<th>{$LANG.inv_template}</th>
			<td>{html_options name=invoice_template output=$tmpls values=$tmpls selected=$smarty.post.invoice_template}</td>
		</tr>
		<tr>
			<th>{$LANG.delnote_template}</th>
			<td>{html_options name=delnote_template output=$tmpls values=$tmpls selected=$smarty.post.delnote_template}</td>
		</tr>
{/capture}


{*if isset($hook_biller_add_table) && $hook_biller_add_table}{$hook_biller_add_table}{/if*}

{*
{capture name=""}
{/capture}
*}

{/strip}
