{*
/*
 * Script: ./extensions/extensions_link/templates/default/menu.tpl
 * 	menu changes
 *
 * Authors:
 *	git0matt@gmail.com
 *
 * Last edited:
 * 	2016-09-24
 *
 * License:
 *	GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
*}
<!-- BEFORE:system_preferences -->
			<li><a{if (isset($pageActive) && $pageActive=="setting_extensions")||(isset($subPageActive) && $subPageActive=="setting_extensions")} class="active"{/if} href="index.php?module=extensions&amp;view=manage">{$LANG.extensions}</a></li>
