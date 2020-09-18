{*
/*
* Script: manage_sqlpatches.tpl
* 	 Manage sql patches template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*
* Website:
*	https://simpleinvoices.group
*/
*}

<h3>{$LANG.databaseUc} {$LANG.patches} {$LANG.applied} {$LANG.to} {$LANG.simpleInvoices}</h3>
<hr />
	<table class="manage" id="live-grid" class="center">
	<colgroup>
		<col style='width:20%;' />
		<col style='width:60%;' />
		<col style='width:20%;' />
	</colgroup>
	<thead>
	<tr>
		<th class="sortable">{$LANG.patchUc} {$LANG.id}</th>
		<th class="sortable">{$LANG.description_uc}</th>
		<th class="sortable">{$LANG.releaseUc}</th>
	</tr>
	</thead>
	{foreach $patches as $patch}
		<tr>
			<td class='index_table'>{$patch.sql_patch_ref|htmlSafe}</td>
			<td class='index_table'>{$patch.sql_patch|htmlSafe|nl2br}</td>
			<td class='index_table'>{$patch.sql_release|htmlSafe}</td>
		</tr>
	{/foreach}
</table>
