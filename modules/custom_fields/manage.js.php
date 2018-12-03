<script>
{literal}
			var columns = 4;
			var padding = 12;
			var grid_width = $('.col').width();
			
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 
		
			
			$('#manageGrid').flexigrid
			(
			{
			url: 'index.php?module=custom_fields&view=xml',
			dataType: 'xml',
			colModel : [
				{display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
				{display: '{/literal}{$LANG.id}{literal}', name : 'cf_id', width : 10 * percentage_width, sortable : false, align: 'left'},
				{display: "{/literal}{$LANG.custom_field}{literal}", name : 'cf_custom_field', width : 40 * percentage_width, sortable : false, align: 'left'},
				{display: "{/literal}{$LANG.custom_label}{literal}", name : 'cf_custom_label', width : 40 * percentage_width, sortable : false, align: 'left'}
				
				],
				

			sortname: 'cf_id',
			sortorder: 'asc',
			usepager: false,
			/*title: 'Manage Custom Fields',*/
			pagestat: '{/literal}{$LANG.displaying_items}{literal}',
			procmsg: '{/literal}{$LANG.processing}{literal}',
			nomsg: '{/literal}{$LANG.no_items}{literal}',
			pagemsg: '{/literal}{$LANG.page}{literal}',
			ofmsg: '{/literal}{$LANG.of}{literal}',
			useRp: false,
			rp: 25,
			showToggleBtn: false,
			showTableToggleBtn: false,
			height: 'auto'
			}
			);


{/literal}

</script>
