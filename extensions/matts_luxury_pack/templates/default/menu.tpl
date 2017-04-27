{*
/*
 * Script: ./extensions/matts_luxury_pack/templates/default/menu.tpl
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
<!-- BEFORE:billers -->
<li id="matts_luxury_pack" style="display: none"></li>
			<li><a{if isset($pageActive) && $pageActive=="customer_add"} class="active"{/if} href="index.php?module=customers&amp;view=add">{$LANG.add_customer}</a></li>
{*< !-- SECTION:add_product -- >
			<li><a{if isset($pageActive) && $pageActive=="product_add"} class="active"{/if} href="index.php?module=products&amp;view=add">{$LANG.add_product}</a></li>
{if $defaults.inventory == "1"}
    		<li><a{if isset($pageActive) && $pageActive=="inventory"} class="active"{/if} href="index.php?module=inventory&amp;view=manage">{$LANG.inventory}</a></li>
	{if isset($subPageActive) && $subPageActive=="inventory_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
	{if isset($subPageActive) && $subPageActive=="inventory_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
	{if isset($subPageActive) && $subPageActive=="inventory_add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
{/if}*}
{*< !-- SECTION:product_attributes -- >
{if $defaults.product_attributes}
   			<li><a{if isset($pageActive) && $pageActive=="product_attributes"} class="active"{/if} href="index.php?module=product_attribute&amp;view=manage">{$LANG.product_attributes}</a></li>
	{if isset($subPageActive) && $subPageActive=="product_attribute_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
	{if isset($subPageActive) && $subPageActive=="product_attribute_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
	{if isset($subPageActive) && $subPageActive=="product_attribute_add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
    		<li><a{if isset($pageActive) && $pageActive=="product_values"} class="active"{/if} href="index.php?module=product_value&amp;view=manage">{$LANG.product_values}</a></li>
	{if isset($subPageActive) && $subPageActive=="product_value_view"} <li><a class="active active_subpage" href="#">{$LANG.view}</a></li>{/if}
	{if isset($subPageActive) && $subPageActive=="product_value_edit"} <li><a class="active active_subpage" href="#">{$LANG.edit}</a></li>{/if}
	{if isset($subPageActive) && $subPageActive=="product_value_add"} <li><a class="active active_subpage" href="#">{$LANG.add}</a></li>{/if}
{/if}*}
{*< !-- SECTION:settings -- >
			<li><a{if isset($pageActive) && $pageActive=="setting"} class="active"{/if} href="index.php?module=options&amp;view=index">{$LANG.settings}</a></li>
			<li><a{if isset($pageActive) && $pageActive=="setting_extensions"} class="active"{/if} href="index.php?module=extensions&amp;view=manage">{$LANG.extensions}</a></li>*}
<!-- BEFORE:reports -->
			<li><a{if isset($pageActive) && $pageActive=="debug"} class="active"{/if} href="index.php?module=index&amp;view=mydebug">{$LANG.debug}</a></li>
