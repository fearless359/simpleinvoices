{*
* 	Script: manage_sqlpatches.tpl
* 	 	Manage sql patches template
*
* 	Authors:
*	 	Justin Kelly, Nicolas Ruflin
*
* 	Last edited:
* 	 	20210702 by Rich Rowley to use DataTables.
*
* 	License:
*	 	GPL v3 or above
*
* 	Website:
*		https://simpleinvoices.group
*}
<h3>{$LANG.databaseUc} {$LANG.patches} {$LANG.applied} {$LANG.to} {$LANG.simpleInvoices}</h3>
<hr />
<table id="data-table" class="display responsive compact cell-border">
	<thead>
	<tr>
		<th class="align__text-center">{$LANG.patchUc} {$LANG.idUc}</th>
		<th>{$LANG.descriptionUc}</th>
		<th>{$LANG.releaseUc}</th>
	</tr>
	</thead>
	<tbody>
	{foreach $patches as $patch}
		<tr>
			<td>{$patch.sql_patch_ref|htmlSafe}</td>
			<td>{$patch.sql_patch|htmlSafe|nl2br}</td>
			<td>{$patch.sql_release|htmlSafe}</td>
		</tr>
	{/foreach}
	</tbody>
</table>
<script>
	{literal}
	$(document).ready(function () {
		$('#data-table').DataTable({
			"order": [
				[0, "asc"]
			],
			"columnDefs": [
				{"targets": 0, "className": 'dt-body-center' }
			],
			"colReorder": true
		});
	});
	{/literal}
</script>
