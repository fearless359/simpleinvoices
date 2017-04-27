{*
/*
* Script: save.tpl
* 	Template used to redirect to the correct page, 
*	depending on the existence of a template
*
* Authors:
*	 Marcel van Dorp
*
* Last edited:
* 	 2009-02-08
*
* License:
*	 GPL v2 or above
*/
*}
{if $saved == true}
	<div class="si_message_ok">{$LANG.save_success}<br />{$LANG.redirect_extensions}</div>
{else}
	<div class="si_message_error">{$LANG.save_failure}<br />{$LANG.redirect_extensions}</div>
{/if}
  <meta http-equiv="refresh" content="2;URL=index.php?module=extensions&view=manage" />
