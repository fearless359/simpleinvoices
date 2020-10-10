{include file=$path|cat:"library/reportTitle.tpl" title=$title}
{include file=$path|cat:"library/exportButtons.tpl"
         params=[
             'fileName' => "reportPastDue",
             'displayDetail' => $displayDetail|urlencode,
             'title' => $title|urlencode
         ]
}
{if $menu}
  <form name="frmpost" method="POST" id="frmpost"
        action="index.php?module=reports&amp;view=reportPastDue">
    <table class="center">
      {include file=$path|cat:"library/displayDetail.tpl"}
    </table>
    <br/>
    {include file=$path|cat:"library/runReportButton.tpl" value="reportPastDue" label=$LANG.runReport}
    <br/>
  </form>
{/if}
{if isset($smarty.post.submit) || $view == "export"}
  {include file=$path|cat:"reportPastDueBody.tpl"}
{/if}
