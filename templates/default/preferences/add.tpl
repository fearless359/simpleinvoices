{if empty($smarty.post.p_description) && isset($smarty.post.submit) }
		<div class="validation_alert"><img src="images/important.png" alt="" />
		You must enter a description for the preference</div>
		<hr />
{/if}
<form name="frmpost" method="POST" id="frmpost"
	  action="index.php?module=preferences&amp;view=save">

<div class="si_form">
	<table>
		<tr>
			<th>{$LANG.description} 
				<a class="cluetip" href="#" title="{$LANG.required_field}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_description">
				  <img src="{$helpImagePath}required-small.png" alt="" />
				</a>	
			</th>
			<td>
				<input type="text" class="validate[required]" name="p_description" value="{if isset($smarty.post.p_description)}{$smarty.post.p_description|htmlsafe}{/if}" size="25" />
			</td>
		</tr>
		<tr>
			<th>{$LANG.currency_sign} 
				<a class="cluetip" href="#" title="{$LANG.currency_sign}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<input type="text" name="p_currency_sign"  value="{if isset($smarty.post.p_currency_sign)}{$smarty.post.p_currency_sign|htmlsafe}{/if}" size="15" />
				<a class="cluetip" href="#" title="{$LANG.currency_sign}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_currency_sign">
					{$LANG.currency_sign_non_dollar}
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</td>
		</tr>
		<tr>
			<th>{$LANG.currency_code} 
				<a class="cluetip" href="#" title="{$LANG.currency_code}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_currency_code">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<input type="text" name="currency_code"  value="{if isset($smarty.post.currency_code)}{$smarty.post.currency_code|htmlsafe}{/if}" size="15" />
			</td>
		</tr>
		<tr>
			<th>{$LANG.invoice_heading} 
				<a class="cluetip" href="#" title="{$LANG.invoice_heading}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_heading">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<input type="text" name="p_inv_heading"  value="{if isset($smarty.post.p_inv_heading)}{$smarty.post.p_inv_heading|htmlsafe}{/if}" size="50" />
			</td>
		</tr>
		<tr>
			<th>{$LANG.invoice_wording}
				<a class="cluetip" href="#" title="{$LANG.invoice_wording}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_wording">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<input type="text" name="p_inv_wording"  value="{if isset($smarty.post.p_inv_wording)}{$smarty.post.p_inv_wording|htmlsafe}{/if}" size="50" />
			</td>
		</tr>
		<tr>
			<th>{$LANG.invoice_detail_heading}
				<a class="cluetip" href="#" title="{$LANG.invoice_detail_heading}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_heading">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<input type="text" name="p_inv_detail_heading"  value="{if isset($smarty.post.p_inv_detail_heading)}{$smarty.post.p_inv_detail_heading|htmlsafe}{/if}" size="50" />
			</td>
		</tr>
		<tr>
			<th>{$LANG.invoice_detail_line}
				<a class="cluetip" href="#" title="{$LANG.invoice_detail_line}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			</td>
			<td>
				<input type="text" name="p_inv_detail_line"  value="{if isset($smarty.post.p_inv_detail_line)}{$smarty.post.p_inv_detail_line|htmlsafe}{/if}" size="75" />
			</td>
		</tr>
		<tr>
			<th>{$LANG.include_online_payment}
				<a class="cluetip" href="#" title="{$LANG.invoice_detail_line}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_detail_line">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<input type=checkbox name=include_online_payment[] value='paypal'>{$LANG.paypal}
				<input type=checkbox name=include_online_payment[] value='eway_merchant_xml'>{$LANG.eway_merchant_xml}
				<input type=checkbox name=include_online_payment[] value='paymentsgateway'>{$LANG.paymentsgateway}
			</td>
		</tr>
		<tr>
			<th>{$LANG.invoice_payment_method}
				<a class="cluetip" href="#" title="{$LANG.invoice_payment_method}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_payment_method">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<input type="text" name="p_inv_payment_method"  value="{if isset($smarty.post.p_inv_payment_method)}{$smarty.post.p_inv_payment_method|htmlsafe}{/if}" size="50" />
			</td>
		</tr>
		<tr>
			<th>{$LANG.invoice_payment_line_1_name}
				<a class="cluetip" href="#" title="{$LANG.invoice_payment_line_1_name}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_name">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<input type="text" name="p_inv_payment_line1_name"  value="{if isset($smarty.post.p_inv_payment_line1_name)}{$smarty.post.p_inv_payment_line1_name|htmlsafe}{/if}" size="50" />
			</td>
		</tr>
		<tr>
			<th>{$LANG.invoice_payment_line_1_value}
				<a class="cluetip" href="#" title="{$LANG.invoice_payment_line_1_value}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line1_value">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<input type="text" name="p_inv_payment_line1_value"  value="{if isset($smarty.post.p_inv_payment_line1_value)}{$smarty.post.p_inv_payment_line1_value|htmlsafe}{/if}" size="50" />
			</td>
		</tr>
		<tr>
			<th>{$LANG.invoice_payment_line_2_name}
				<a class="cluetip" href="#" title="{$LANG.invoice_payment_line_2_name}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_name">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<input type="text" name="p_inv_payment_line2_name"  value="{if isset($smarty.post.p_inv_payment_line2_name)}{$smarty.post.p_inv_payment_line2_name|htmlsafe}{/if}" size="50" />
			</td>
		</tr>
		<tr>
			<th>{$LANG.invoice_payment_line_2_value}
				<a class="cluetip" href="#" title="{$LANG.invoice_payment_line_2_value}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_payment_line2_value">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<input type="text" name="p_inv_payment_line2_value"  value="{if isset($smarty.post.p_inv_payment_line2_value)}{$smarty.post.p_inv_payment_line2_value|htmlsafe}{/if}" size="50" />
			</td>
		</tr>
		<tr>
			<th>{$LANG.status}
				<a class="cluetip" href="#" title="{$LANG.status}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_status">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<select name="status">
				<option value="1" selected>{$LANG.real}</option>
				<option value="0">{$LANG.draft}</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>{$LANG.invoice_numbering_group} 
				<a class="cluetip" href="#" title="{$LANG.invoice_numbering_group}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_numbering_group">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td >
			{if !isset($preferences) }
				<p><em>{$LANG.no_preferences}</em></p>
			{else}
				<select name="index_group">
					<option value="">{$LANG.use_this_pref}</option>
				{foreach from=$preferences item=preference}
					<option {if $preference.pref_id == $defaults.preference} selected {/if} value="{$preference.pref_id|htmlsafe}">{$preference.pref_description|htmlsafe} ({$preference.pref_id|htmlsafe})</option>
				{/foreach}
				</select>
			{/if}
			
			</td>
		</tr>
		<tr>
			<th>{$LANG.set_aging}
				<a class="cluetip" href="#" title="{$LANG.set_enabled}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_set_aging">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<select name="set_aging">
					<option value="1">{$LANG.enabled}</option>
					<option value="0" selected>{$LANG.disabled}</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>{$LANG.locale} 
				<a class="cluetip" href="#" title="{$LANG.locale}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_locale">
				<img src="{$helpImagePath}help-small.png" alt="" /></a>
			</th>
			<td >
				<select name="locale">
				{foreach from=$localelist key=locale item=value}
					<option {if $locale == $config->local->locale} selected {/if} value="{if isset($locale)}{$locale|htmlsafe}{/if}">{$locale|htmlsafe}</option>
				{/foreach}
				</select>
			</td>
		</tr>	
		<tr>
			<th>{$LANG.enabled}
				<a class="cluetip" href="#" title="{$LANG.enabled}"
				   rel="index.php?module=documentation&amp;view=view&amp;page=help_inv_pref_invoice_enabled">
					<img src="{$helpImagePath}help-small.png" alt="" />
				</a>
			</th>
			<td>
				<select name="pref_enabled">
				<option value="1" selected>{$LANG.enabled}</option>
				<option value="0">{$LANG.disabled}</option>
				</select>
			</td>
		</tr>
	</table>

	<div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="insert_preference" value="{$LANG.save}">
                <img class="button_img" src="../../../images/tick.png" alt="" />
                {$LANG.save}
            </button>

            <a href="index.php?module=preferences&amp;view=manage" class="negative">
                <img src="../../../images/cross.png" alt="" />
                {$LANG.cancel}
            </a>
	</div>
</div>

<input type="hidden" name="op" value="insert_preference" />        
</form>
