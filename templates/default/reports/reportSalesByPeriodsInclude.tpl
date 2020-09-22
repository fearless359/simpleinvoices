<table class="si_report_table">
    <thead>
    <tr>
        <th>
            {if $show_rates}
                <a class="but_show_rates si_button_mini" href="#">%</a>
            {/if}
        </th>
        {foreach $years as $year}
            <th><b>{if isset($year)}{$year|htmlSafe}{/if}</b></th>
            {if $show_rates}
                <th class="rate"></th>
            {/if}
        {/foreach}
    </tr>
    </thead>
    <tbody>
    {foreach $this_data.months as $month => $amount}
        <tr class="tr_{cycle values="A,B"}">
            <th>{"2000-$month-01"|utilDate:'month'|htmlSafe|ucfirst}</th>
            {foreach $years as $year}
                <td>{$amount.$year|utilNumber:0|default:'-'}</td>
                {if $show_rates}
                    <td class="rate{if $this_data.months_rate.$month.$year < 0} neg_rate{/if}">
                        {if $this_data.months_rate.$month.$year}
                            {$this_data.months_rate.$month.$year|utilNumber:$rate_precision}%
                        {/if}
                    </td>
                {/if}
            {/foreach}
        </tr>
    {/foreach}
    </tbody>
    <tfoot>
    <tr>
        <th>{$LANG.total}</th>
        {foreach $years as $year}
            <td>{$this_data.total.$year|utilNumber:0|default:'-'}</td>
            {if $show_rates}
                <td class="rate{if $this_data.total_rate.$year < 0} neg_rate{/if}">
                    {if $this_data.total_rate.$year}
                        {$this_data.total_rate.$year|utilNumber:$rate_precision}%
                    {/if}
                </td>
            {/if}
        {/foreach}
    </tr>
    </tfoot>
</table>
