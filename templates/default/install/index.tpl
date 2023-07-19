{*
 *  Script: index.tpl
 *      SimpleInvoces installation template
 *
 *  Last edited:
 * 	    20210702 by Rich Rowley to convert to grid layout
 *
 *  License:
 *      GPL v3 or above
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
{include file=$path|cat:'inc_head.tpl'}

<div class="bold align__text-center margin__bottom-2 fonts__size-2">
    {$LANG.toUc} {$LANG.install} {$LANG.simpleInvoices} {$LANG.please}:
</div>
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="cols__3-span-7">
            <ol>
                <li>{$LANG.createBlankMySql}.</li>
                <li>{$LANG.enterCorrectDatabase} <strong><em>{$configFile}</em></strong> {$LANG.file}.</li>
                <li>{$LANG.reviewUc} {$LANG.the} {$LANG.connection} {$LANG.details} {$LANG.below} {$LANG.andLc} {$LANG.if}
                    {$LANG.correct}, {$LANG.click} {$LANG.the} <strong>{$LANG.installUc} {$LANG.databaseUc}</strong> {$LANG.button}.</li>
            </ol>
        </div>
    </div>
    <div class="grid__container grid__head-6">
        <div class="cols__1-span-6 bold align__text-center underline fonts__size-2">
            <em>{$configFile}</em> {$LANG.databaseUc} {$LANG.settings}
        </div>
    </div>
    <div class="grid__container grid__head-6">
        <div class="cols__3-span-1 bold underline">{$LANG.propertyUc}</div>
        <div class="cols__4-span-2 bold underline">{$LANG.valueUc}</div>
    </div>
    <div class="grid__container grid__head-6">
        <div class="cols__3-span-1 bold">{$LANG.hostUc}:</div>
        <div class="cols__4-span-3">{$config.databaseHost}</div>
    </div>
    <div class="grid__container grid__head-6">
        <div class="cols__3-span-1 bold">{$LANG.databaseUc}:</div>
        <div class="cols__4-span-3">{$config.databaseDbname}</div>
    </div>
    <div class="grid__container grid__head-6">
        <div class="cols__3-span-1 bold">{$LANG.username}:</div>
        <div class="cols__4-span-3">{$config.databaseUsername}</div>
    </div>
    <div class="grid__container grid__head-6">
        <div class="cols__3-span-1 bold">{$LANG.password}:</div>
        <div class="cols__4-span-3">**********</div>
    </div>
</div>
<div class="align__text-center margin__top-3 margin__bottom-2">
    <a href="index.php?module=install&amp;view=structure" class="button positive">
        <img src="images/tick.png" alt=""/>{$LANG.installUc} {$LANG.databaseUc}
    </a>
</div>

{include file=$path|cat:'inc_foot.tpl'}
