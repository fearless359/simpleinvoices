<h3 class="align__text-center margin__bottom-2 bold underline">{$LANG.reportPeriod}</h3>
<div class="grid__container grid__head-10">
    <label for="startDateId" class="cols__2-span-3">{$LANG.startDate}:</label>
    <input type="text" name="startDate" id="startDateId" size="10" required readonly
           class="cols_5-span-2 date-picker"
           value='{if isset($startDate)}{$startDate}{/if}'/>
</div>
<div class="grid__container grid__head-10">
    <label for="endDateId" class="cols__2-span-3">{$LANG.endDate}:</label>
    <input type="text"  name="endDate" id="endDateId" size="10" required readonly
           class="cols__5-span-2S date-picker"
           value='{if isset($endDate)}{$endDate}{/if}'/>
</div>
