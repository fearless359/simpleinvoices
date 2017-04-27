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

{assign var=inc value=$mlprel|substr:2|cat:'templates/default/'}

{if file_exists($inc|cat:'auth/hooks.tpl')}{include file=$inc|cat:'auth/hooks.tpl'}{/if}
{if file_exists($inc|cat:'billers/hooks.tpl')}{include file=$inc|cat:'billers/hooks.tpl'}{/if}
{if file_exists($inc|cat:'customers/hooks.tpl')}{include file=$inc|cat:'customers/hooks.tpl'}{/if}
{if file_exists($inc|cat:'index/hooks.tpl')}{include file=$inc|cat:'index/hooks.tpl'}{/if}
{if file_exists($inc|cat:'invoices/hooks.tpl')}{include file=$inc|cat:'invoices/hooks.tpl'}{/if}
{if file_exists($inc|cat:'products/hooks.tpl')}{include file=$inc|cat:'products/hooks.tpl'}{/if}
{if file_exists($inc|cat:'user/hooks.tpl')}{include file=$inc|cat:'user/hooks.tpl'}{/if}

{/strip}
