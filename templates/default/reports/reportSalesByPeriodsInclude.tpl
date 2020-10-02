<table class="si_report_table">
    <thead>
    <tr>
        <th>%</th>
        {foreach $years as $year}
            <th><b>{if isset($year)}{$year|htmlSafe}{/if}</b></th>
            {if $showRates == 'yes'}
                {* This adds a column for the rates *}
                <th class="rate"></th>
            {/if}
        {/foreach}
    </tr>
    </thead>
    <tbody>
    {foreach $thisData.months as $month => $amount}
        <tr class="tr_{cycle values="A,B"}">
            <th>{"2000-$month-01"|utilDate:'month'|htmlSafe|ucfirst}</th>
            {foreach $years as $year}
                <td>{$amount.$year|utilNumber:0|default:'-'}</td>
                {if $showRates == 'yes'}
                    <td class="rate" style="color:{if $thisData.monthsRate.$month.$year < 0}#da100d;{else}#00b900;{/if}">
                        {if $thisData.monthsRate.$month.$year}
                            {$thisData.monthsRate.$month.$year|utilNumber:$ratePrecision}%
                        {/if}
                    </td>
                {/if}
            {/foreach}
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
    <tr>
        <th>{$LANG.totalUc}</th>
        {foreach $years as $year}
            <td>{$thisData.total.$year|utilNumber:0|default:'-'}</td>
            {if $showRates == 'yes'}
                <td class="rate" style="color:{if $thisData.totalRate.$year < 0}#da100d;{else}#00b900;{/if}">
                    {if $thisData.totalRate.$year}
                        {$thisData.totalRate.$year|utilNumber:$ratePrecision}%
                    {/if}
                </td>
            {/if}
        {/foreach}
    </tr>
    </tfoot>
</table>
