{literal}
	$('#a_backup_options').livequery('click',function (e) {
		e.preventDefault();
		if ($('#backup_options')[0].style.display=='none'||!$('#backup_options')[0].style.display)
		{
			$('#backup_options').show();
			$(this).find("img")[0].style.display = 'none';
		}
	});
	$('#a_hide_backup_options').livequery('click',function (e) {
		e.preventDefault();
alert('hiding');
		if ($('#backup_options')[0].style.display!='none')
		{
			$('#a_backup_options').find('img')[0].style.display = 'inline';
			$('#backup_options').hide();
		}
	});
{/literal}