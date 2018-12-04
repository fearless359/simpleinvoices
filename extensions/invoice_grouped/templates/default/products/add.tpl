
{* if bill is updated or saved.*}

{if !empty($smarty.post.description) && isset($smarty.post.id) }
	{include file="templates/default/products/save.tpl"}
{else}
{* if  name was inserted *} 
	{if isset($smarty.post.id)}
		<div class="validation_alert">
		<img src="images/common/important.png" />
		You must enter a description for the product
		</div>
		<hr />
	{/if}
<form name="frmpost" action="index.php?module=products&amp;view=add" method="post" id="frmpost" onsubmit="return checkForm(this);">


<table class="center">
	<tr>
		<td class="details_screen">{$LANG.description} 
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{$LANG.required_field}">
			<img src="{$help_image_path}required-small.png" /></a>
		</td>
		<td><input type="text" name="description" value="{if isset($smarty.post.description)}{$smarty.post.description}{/if}" size="50" id="description" class="required edit" onblur="checkField(this);" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.unit_price}</td>
		<td><input type="text" class="edit" name="unit_price" value="{if isset($smarty.post.unit_price)}{$smarty.post.unit_price}{/if}"  size="25" /></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.default_tax}</td>
		<td>
		<select name="default_tax_id">
		    <option value=''></option>
			{foreach from=$taxes item=tax}
				<option value="{if isset($tax.tax_id)}{$tax.tax_id}{/if}">{$tax.tax_description}</option>
			{/foreach}
		</select>
		</td>
	</tr>
	{if !empty($customFieldLabel.product_cf1)}
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf1}
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
			<img src="{$help_image_path}help-small.png" /></a>
		</td>
		<td>
		<select name="custom_field1">
				<option value=""></option>
			{foreach from=$product_group item=pg}
				<option value="{if isset($pg.name)}{$pg.name}{/if}">{$pg.name}</option>
			{/foreach}
		</select>
		</td>
	</tr>
	{/if}
	{if !empty($customFieldLabel.product_cf2)}
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf2} 
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
			<img src="{$help_image_path}help-small.png" alt="" /></a>
		</td>
		<td><input type="text" class="edit" name="custom_field2" value="{if isset($smarty.post.custom_field2)}{$smarty.post.custom_field2}{/if}" size="50" /></td>
	</tr>
	{/if}
	{if !empty($customFieldLabel.product_cf3)}
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf3} 
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
			<img src="{$help_image_path}help-small.png" alt="" /></a>
		</td>
		<td><input type="text" class="edit" name="custom_field3" value="{if isset($smarty.post.custom_field3)}{$smarty.post.custom_field3}{/if}" size="50" /></td>
	</tr>
	{/if}
	{if !empty($customFieldLabel.product_cf4)}
	<tr>
		<td class="details_screen">{$customFieldLabel.product_cf4} 
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_custom_fields" title="{$LANG.custom_fields}">
			<img src="{$help_image_path}help-small.png" alt="" /></a>
		</td>
		<td><input type="text" class="edit" name="custom_field4" value="{if isset($smarty.post.custom_field4)}{$smarty.post.custom_field4}{/if}" size="50" /></td>
	</tr>
	{/if}
	<tr>
		<td class="details_screen">{$LANG.notes}</td>
		<td><textarea class="editor" name="notes" rows="8" cols="50" />{if isset($smarty.post.notes)}{$smarty.post.notes|unescape}{/if}</textarea></td>
	</tr>
	<tr>
		<td class="details_screen">{$LANG.enabled}</td>
		<td>
			{html_options class=edit name=enabled options=$enabled selected=1}
		</td>
	</tr>
</table>
<br />
<table class="center" >
	<tr>
		<td>
			<button type="submit" class="positive" name="id" value="{$LANG.save}">
			    <img class="button_img" src="images/common/tick.png" alt="" /> 
				{$LANG.save}
			</button>

			<input type="hidden" name="op" value="insert_product" />
		
			<a href="index.php?module=products&amp;view=manage" class="negative">
		        <img src="images/common/cross.png" alt="" />
	        	{$LANG.cancel}
    		</a>
	
		</td>
	</tr>
</table>


</form>
	{/if}
