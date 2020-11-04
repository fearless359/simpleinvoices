{include file=$path|cat:'inc_head.tpl'}

<table class="center" style="width:60%;;">
    <tr>
        <th style="font-weight: bold; text-align:center;">
            {$LANG.toUc} {$LANG.install} {$LANG.simpleInvoices} {$LANG.please}:
        </th>
    </tr>
    <tr>
        <th style="font-weight:normal;">
            <ol style="text-align: left;">
                <li>{$LANG.createBlankMySql}.</li>
                <li>{$LANG.enterCorrectDatabase}
                    <strong><em>{$config_file_path}</em></strong> {$LANG.file}.
                    {if $config_file_path == "config/config.ini"}
                        <p style="margin: 0 0 0 10px;">
                            <strong>{$LANG.note}:</strong> {$LANG.youUc} {$LANG.can} {$LANG.copy} {$LANG.the}
                            <strong>{$LANG.configIni}</strong> {$LANG.file} {$LANG.to} {$LANG.a} {$LANG.file} {$LANG.named}
                            <strong>{$LANG.customConfigIni}</strong> {$LANG.andLc} {$LANG.make} {$LANG.your} {$LANG.changes}
                            {$LANG.to} {$LANG.it}. {$LANG.theUc} {$LANG.advantage} {$LANG.is} {$LANG.that} {$LANG.future}
                            {$LANG.updates} {$LANG.to} {$LANG.simpleInvoices} {$LANG.will} {$LANG.notLc} {$LANG.write}
                            {$LANG.over} {$LANG.the} <strong>{$LANG.customConfigIni}</strong> {$LANG.file}; {$LANG.thus}
                            {$LANG.preserving} {$LANG.your} {$LANG.settings}.</p>
                    {/if}
                </li>
                <li>{$LANG.reviewUc} {$LANG.the} {$LANG.connection} {$LANG.details} {$LANG.below} {$LANG.andLc} {$LANG.if}
                    {$LANG.correct}, {$LANG.click} {$LANG.the} <strong>{$LANG.installUc} {$LANG.databaseUc}</strong> {$LANG.button}.</li>
            </ol>
        </th>
    </tr>
    <tr>
        <th style="text-align: center; font-weight: bold; text-decoration: underline;">
            <em>{$config_file_path}</em> {$LANG.database} {$LANG.settings}
        </th>
    </tr>
</table>
<br/>
<table class="center">
    <tr>
        <th class="si_right bold underline" style="margin-right: 0;">{$LANG.propertyUc}</th>
        <th class="left bold underline" style="text-align: left; padding-left: 40px;">{$LANG.value}</th>
    </tr>
    <tr>
        <td class="si_right" style="margin-right: 0;">{$LANG.hostUc}</td>
        <td class="si_left" style="padding-left: 40px;">{$config.databaseHost}</td>
    </tr>
    <tr>
        <td class="si_right" style="margin-right: 0;">{$LANG.databaseUc}</td>
        <td class="si_left" style="padding-left: 40px;">{$config.databaseDbname}</td>
    </tr>
    <tr>
        <td class="si_right" style="margin-right: 0;">{$LANG.username}</td>
        <td class="si_left" style="padding-left: 40px;">{$config.databaseUsername}</td>
    </tr>
    <tr>
        <td class="si_right" style="margin-right: 0;">{$LANG.password}</td>
        <td class="si_left" style="padding-left: 40px;">**********</td>
    </tr>
</table>
<div class="si_toolbar si_toolbar_form">
    <a href="index.php?module=install&amp;view=structure" class="positive">
        <img src="images/tick.png" alt=""/>
        {$LANG.installUc} {$LANG.databaseUc}
    </a>
</div>

{include file=$path|cat:'inc_foot.tpl'}
