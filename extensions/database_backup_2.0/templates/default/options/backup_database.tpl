{*debug*}
{*
/*
* Script: better_database_backup/backup_database.tpl
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
{*MOVED TO SEPARATE FILE*backup_database.css*FAILS*}
{if !isset($cihabs) && !isset($cihrel) && 0}
<style type="text/css">{literal}
#backup_options {
	border: 1px solid #ccc;
	margin-bottom: 10px;
	padding: 5px;
	display: none;
}
#backup_options table {
	margin: auto;
	box-shadow: inset 0 10px #fafafa, inset 0 -1px #cccccc;
	border: 1px solid #ccc;
}
#backup_options tr {		border: 1px solid #ccc;	}
#backup_options td, #backup_options th {
	/*border: 1px solid #ccc;*/
	background: #f7f7f7;
	text-align: left;
	border-right: 1px solid #ddd;
	border-left: 1px solid #fff;
	overflow: hidden;
	vertical-align: middle !important;
	border-top: 1px solid #fff;
	border-bottom: 1px solid #ddd;
	padding: 2px 10px 2px 10px;
}
#hide {				display: block;	}
#backup_options:target {	display: block;	}
#hide:target {			display: none;	}
.center {			margin: auto; text-align: center;	}
#database_save_submit {	background: transparent url("./images/common/database_save.png") left center no-repeat;	padding-left: 25px;	border: 0;	}
</style>{/literal}
{/if}

<form name="frmpost" action="index.php?module=options&amp;view=new_backup" method="post" onsubmit="return frmpost_Validator(this)">
	<div class="si_toolbar si_toolbar_top">
		<a href="javascript:void(false)">
			<input type="submit" id="database_save_submit" alt="new backup button" value="{$LANG.backup_database_now}" />
		</a>
	</div>
	<div>
		<a class='cluetip' href='#'
			rel='index.php?module=documentation&amp;view=view&amp;page=help_backup_database'
			title='{$LANG.database_backup}'>
			<img src='./images/common/important.png' alt='important' />
			{$LANG.more_info}
		</a>
	</div>
	<div class="si_center">
		<a id='a_backup_options' href='#backup_options' title='{$LANG.backup_options}'>
			<img src='./images/common/green_decending.gif' alt='green_decending' />
			{$LANG.backup_options}
		</a>
		<br />

		<div id="backup_options">
{if $tables|count}
			<table class="center">
				<caption>
					{$LANG.backup_tables}:
				</caption>
				<thead>
					<tr style="border: 1px solid">
						<th>{$LANG.action}</th>
						<th>{$LANG.tablename}</th>
					</tr>
				</thead>
				<tbody>
{foreach from=$tables item=tbl}
					<tr>
						<td>
							<input id="checkbox-{$tbl.name}" name="checkbox[{$tbl.name}]" class="backup_tables" type="checkbox"{if $tbl.action=='do'} checked{/if} />
						</td>
						<td>{$tbl.name}</td>
					</tr>
{/foreach}
				</tbody>
			</table>
{/if}
			<br />
			<div class="center">
				<button type="submit" name="submit" class="submit" value="1">
					<img src="./images/common/database_save.png" alt="new backup button"/>
					{$LANG.backup_database_now}
				</button>
			</div>
			<a href="#a_hide_backup_options" onclick="$('#a_backup_options').show();/*find('img')[0].style.display = 'inline';*/ $('#backup_options').hide()">
				<img src="images/common/green_acending.gif" alt="green_acending" />
				{$LANG.hide_options}
			</a>
		</div>

{if $number_of_rows == 0}

	</div>
	<div class="si_message">{$LANG.no_backups}</div>
</form>

{else}

	</div>
	{if $array}
	<div id="si_form">
		<span style="float: right;">
			<span class="si_filters_title">{$LANG.rows_per_page}:</span>
			<select id="selectrp" name="rp" onchange="location.href+='&amp;rp='+this.value">
		{foreach from=$array item=v}
				<option value="{$v}"{if $smarty.get.rp==$v || ($smarty.get.rp=='' && $defaults.default_nrows==$v)} selected="selected"{/if}>{$v}</option>
		{/foreach}
			</select>
		</span>
	</div>
	{/if}
	<div>
	<br clear=all />
		<table id="manageGrid" style="display: none"></table>
	{include file=$path|cat:"manage_backups.js.tpl"}
{*	{include file="extensions/better_database_backup/templates/default/options/manage_backups.js.tpl"}*}
	</div>
</form>

{/if}

<script type="text/javascript"><!--
{literal}
	$('#a_backup_options').livequery('click',function (e) {
		e.preventDefault();
		if ($('#backup_options')[0].style.display=='none'||!$('#backup_options')[0].style.display)
		{
			$('#backup_options').show();
			$(this).hide();//find("img")[0].style.display = 'none';
		}
	});
	$('#a_hide_backup_options').livequery('click',function (e) {
		e.preventDefault();
alert('hiding');
		if ($('#backup_options')[0].style.display!='none')
		{
			$('#a_backup_options').show();//find('img')[0].style.display = 'inline';
			$('#backup_options').hide();
		}
	});
//--></script>{/literal}
