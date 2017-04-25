{* tutorial extension *}
{*
<div id="si_header">
	{$smarty.capture.hook_topmenu_start}
	{if $smarty.capture.hook_topmenu_section01_replace ne ""}
		{$smarty.capture.hook_topmenu_section01_replace}
	{else}
	<div class="si_wrap">
<!-- SECTION:help -->
	{$LANG.hello} {$smarty.session.Zend_Auth.email|htmlsafe} |
		<a href="http://www.simpleinvoices.org/help" target="blank">{$LANG.help}</a> |
		<a href="index.php?module=si_info&amp;view=index" style="color:white;">{$LANG.information}</a>
<!-- SECTION:auth -->
	{if $config->authentication->enabled == 1} |
		{if $smarty.session.Zend_Auth.id == null}
			<a href="index.php?module=auth&amp;view=login">{$LANG.login}</a>
		{else}
			<a href="index.php?module=auth&amp;view=logout">{$LANG.logout}</a>
			{if $smarty.session.Zend_Auth.domain_id != 1} | Domain: {$smarty.session.Zend_Auth.domain_id}{/if}
		{/if}
	{/if}
	</div>
	{/if}
	{$smarty.capture.hook_topmenu_end}
</div>
*}
<!-- BEFORE:billers -->
	<li><a {if isset($pageActive) && $pageActive== "tutorial"} class="active"{/if} href="index.php?module=index&amp;view=tutorial">{$LANG.tutorial}tutorial</a></li>
