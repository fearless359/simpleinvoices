{*debug*}
{*
/*
* Script: backup_database.tpl
* 	 Database backup template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}
<style>{literal}
#newbackupstatus {
	margin: auto;
	border: 1px solid;
}
#newbackupstatus tr {
	border: 1px solid;
}
#newbackupstatus td {
	border: 1px solid;
	padding: 2px 10px 2px 10px;
}
</style>{/literal}
{*$smarty.request|@print_r*}
{*$on|@print_r}<br /><hr />*}
{*$tables|@print_r}<br /><hr />*}
{*output:{$output|@print_r}<br /><hr />*}
{*$array2|@print_r}<br /><hr />*}
{*skipping-{foreach from=$array item=a}{$a}, {/foreach}<br />*}
	<div class="si_toolbar si_toolbar_top">
		<br />
		<table id="newbackupstatus">
			<tr>
				<th>{$LANG.status}</th>
				<th>{$LANG.tablename}</th>
			</tr>
{*$output|count*}
{if $output|count}
{	foreach from=$output item=out}
			<tr>
				<td align="center">
{					if $out.status=='success'}<img src="images/common/tick.png" alt="{$LANG.success}" title="{$LANG.success}" />{/if}
{					if $out.status=='skipped'}<img src="images/common/page_white_delete.png" alt="{$LANG.skipped}" title="{$LANG.skipped}" />{/if}
				</td>
				<td>{$out.table}</td>
			</tr>
{	/foreach}
{/if}
		</table>
{*assign var=pos value=$smarty.template|strrpos:'/'}
{assign var=inc value=$smarty.template|substr:0:$pos*}
{*$inc|cat:"/manage_backup.js.php"*}
{*include file=$inc|cat:"/manage_backup.js.php"*}
	</div>
	{$txt}
	<div class='si_help_div'>
		<a class='cluetip' href='#'
			rel='index.php?module=documentation&amp;view=view&amp;page=help_backup_database_fwrite' title='{$LANG.fwrite_error}'>
			<img src='{$help_image_path}help-small.png' alt='help' />
			{$LANG.fwrite_error}
		</a>
	</div>

