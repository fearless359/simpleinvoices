{*
/*
* Script: manage.tpl
* 	 Extensions manage template
*
* Authors:
*	 Justin Kelly, Ben Brown, Marcel van Dorp
*
* Last edited:
* 	 2009-02-12
*
* License:
*	 GPL v2 or above
*/
*}
<!--		Note: Manage extensions is still a work-in-progress-->
{if isset($messages) && $messages}
{if $messages|count}
{foreach from=$messages item=msg}
	<div class="si_message">
		{$msg}
	</div>
{/foreach}
{else}
	<div class="si_message">
		{$messages}
	</div>
{/if}
{/if}

{if $exts == null}

	<p><em>No extensions registered</em></p>

{else}

	<table id="manageGrid" style="display:none"></table>

	{include file=$path|cat:'manage.js.tpl'}
 
{/if}
