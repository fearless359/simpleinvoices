<script type="text/javascript">

{literal}

			var columns = 4;
			var padding = 12;
			var grid_width = $('.col').width();
			
			grid_width = grid_width - (columns * padding);
			percentage_width = grid_width / 100; 
		
			
			$('#manageGrid').flexigrid
			(
			{
			url: 'index.php?module=preferences&view=xml',
			dataType: 'xml',
			colModel : [
				{display: '{/literal}{$LANG.actions}{literal}', name : 'actions', width : 10 * percentage_width, sortable : false, align: 'center'},
                {display: '{/literal}{$LANG.description}{literal}', name : 'pref_description', width : 60 * percentage_width, sortable : true, align: 'left'},
                {display: '{/literal}{$LANG.language}{literal}', name : 'language', width : 10 * percentage_width, sortable : true, align: 'center'},
                {display: '{/literal}{$LANG.locale}{literal}', name : 'locale', width : 10 * percentage_width, sortable : true, align: 'center'},
				{display: '{/literal}{$LANG.enabled}{literal}', name : 'enabled', width : 10 * percentage_width, sortable : true, align: 'center'}
				
				],
				

			searchitems : [
				{display: '{/literal}{$LANG.id}{literal}', name : 'pref_id'},
				{display: '{/literal}{$LANG.description}{literal}', name : 'pref_description', isdefault: true}
				],
			sortname: 'pref_description',
			sortorder: 'asc',
			usepager: true,
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
