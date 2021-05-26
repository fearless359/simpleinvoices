{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
		 params=[
		     'fileName' => "reportDebtorsByAmount",
		     'includePaidInvoices' => $includePaidInvoices|urlencode,
		     'title' => $title|urlencode
		 ]
}
{if $menu}
	<form name="frmpost" method="POST" id="frmpost"
		  action="index.php?module=reports&amp;view=reportDebtorsByAmount">
		<div class="si_form si_form_search">
			<table class="center">
				<tr>
					<td class="details_screen si_right nowrap" style="padding-right: 10px; width: 47%;">
						<label for="includePaidInvoicesId">{$LANG.includeUc} {$LANG.paid} {$LANG.invoices}:</label>
					</td>
					<td><input type="checkbox" name="includePaidInvoices" id="includePaidInvoicesId"
						{if isset($smarty.post.includePaidInvoices) && $smarty.post.includePaidInvoices == "yes"} checked {/if} value="yes"/>
					</td>
				</tr>
			</table>
			<br/>
			{include file=$path|cat:"library/runReportButton.tpl" value="reportDebtorsByAmount" label=$LANG.runReport}
			<br/>
		</div>
	</form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
	{include file=$path|cat:"reportDebtorsByAmountBody.tpl"}
{/if}
