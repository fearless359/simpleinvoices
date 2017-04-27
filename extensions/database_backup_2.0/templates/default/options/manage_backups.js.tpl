{*
/*
 * Script: ./extensions/better_database_backup/templates/default/options/manage_backups.js.php
 * 	Product Categories manage template
 *
 * Authors:
 *	 yumatechnical@gmail.com
 *
 * Last edited:
 * 	 2017-04-07
 *
 * License:
 *	 GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
*}
<script type="text/javascript">
{literal}
	var view_tooltip ="{/literal}{$LANG.quick_view_tooltip} {ldelim}1{rdelim}{literal}";
	var edit_tooltip = "{/literal}{$LANG.edit_view_tooltip} {$invoices.preference.pref_inv_wording} {ldelim}1{rdelim}{literal}";
	var inventory = "{/literal}{$defaults.inventory}{literal}";
	var columns = 3;
	var padding = 12;
	var grid_width = $('.col').width();
	grid_width = grid_width - (columns * padding);
	percentage_width = grid_width / 100;
        col_model = 	[
				{display: '{/literal}{$LANG.date}{literal}', name : 'ctime', width : 20 * percentage_width, sortable : true, align: 'center'},
				{display: '{/literal}{$LANG.filename}{literal}', name : 'name', width : 70 * percentage_width, sortable : true, align: 'left'},
				{display: '{/literal}{$LANG.size}{literal}', name : 'size', width : 10 * percentage_width, sortable : true, align: 'center'}
			];
	$('#manageGrid').flexigrid({
		url: 'index.php?module=options&view=backups_xml',
		dataType: 'xml',
		colModel : col_model,
		searchitems : [
			{display: '{/literal}{$LANG.date}{literal}', name : 'cdate'},
			{display: '{/literal}{$LANG.name}{literal}', name : 'name', isdefault: true}
			],
		sortname: '{/literal}{$smarty.get.sortname|default:'cdate'}{literal}',
		sortorder: '{/literal}{$smarty.get.sortorder|default:'desc'}{literal}',
		usepager: true,
		/*title: 'Manage Custom Fields',*/
		pagestat: '{/literal}{$LANG.displaying_items}{literal}',
		procmsg: '{/literal}{$LANG.processing}{literal}',
		nomsg: '{/literal}{$LANG.no_items}{literal}',
		pagemsg: '{/literal}{$LANG.page}{literal}',
		ofmsg: '{/literal}{$LANG.of}{literal}',
		useRp: false,
		rp: {/literal}{if $smarty.get.rp}{$smarty.get.rp}{elseif $defaults.default_nrows}{$defaults.default_nrows}{else}15{/if}{literal},
		showToggleBtn: false,
		showTableToggleBtn: false,
		height: 'auto'
	});
{/literal}
</script>
