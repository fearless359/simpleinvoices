{include file=$path|cat:'inc_head.tpl'}

<div style="margin:0 auto 40px auto;width:50%;text-align:left;">
    <p>{$LANG.theUc} {$LANG.simpleInvoices} {$LANG.database} {$LANG.tables} {$LANG.have}
       {$LANG.been} {$LANG.created}. {$LANG.clickUc} {$LANG.the} <strong>{$LANG.installUc}
       {$LANG.essentialUc} {$LANG.dataUc}</strong> {$LANG.button} {$LANG.below} {$LANG.to}
       {$LANG.continue} {$LANG.with} {$LANG.the} {$LANG.installtion}.</p>
</div>
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=install&amp;view=essential" class="positive">
      <img src="images/tick.png" alt="" />
        {$LANG.installUc} {$LANG.essentialUc} {$LANG.dataUc}
    </a>
    <a href="index.php" class="negative">
        <img src="images/cross.png" alt="" />
        {$LANG.cancel}
    </a>
</div>

{include file=$path|cat:'inc_foot.tpl'}
