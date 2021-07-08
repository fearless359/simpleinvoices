{*
 *  Script: structure.tpl
 *      Installation essential data selection template
 *
 *  Last edited:
 * 	    20210702 by Rich Rowley use new class styling options.
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
{include file=$path|cat:'inc_head.tpl'}

<div class="align__text-center">
    <p>{$LANG.theUc} {$LANG.simpleInvoices} {$LANG.database} {$LANG.tables} {$LANG.have}
       {$LANG.been} {$LANG.created}. {$LANG.clickUc} {$LANG.the} <strong>{$LANG.installUc}
       {$LANG.essentialUc} {$LANG.dataUc}</strong> {$LANG.button} {$LANG.below} {$LANG.to}
       {$LANG.continue} {$LANG.with} {$LANG.the} {$LANG.installation}.</p>
</div>
<div class="align__text-center margin__top-3 margin__bottom-2">
    <a href="index.php?module=install&amp;view=essential" class="button positive">
        <img src="images/tick.png" alt="positive" />{$LANG.installUc}&nbsp;{$LANG.essentialUc}&nbsp;{$LANG.dataUc}
    </a>
    <a href="index.php" class="button negative">
        <img src="images/cross.png" alt="negative" />{$LANG.cancel}
    </a>
</div>

{include file=$path|cat:'inc_foot.tpl'}
