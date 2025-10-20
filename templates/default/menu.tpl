{*
 *  Script: menu.tpl
 *      SI Menu template
 *
 *  Last modified:
 *      20210618 by Richard Rowley to set font size.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
*}
<div class="delay__display container">
    <div id="si_header">
        {$smarty.capture.hook_topmenu_start}
        {if !empty($smarty.capture.hook_topmenu_section01_replace)}
            {$smarty.capture.hook_topmenu_section01_replace}
        {else}
            <div class="si_wrap">
                <!-- SECTION:help -->
                {$LANG.hello} {if isset($smarty.session.username)}{$smarty.session.username|htmlSafe}{/if} |
                <a href="index.php?module=si_info&amp;view=index">{$LANG.aboutUc}</a> |
                <a href="https://simpleinvoices.group" target="_blank" style="color:white;"
                   title="SimpleInvoices Group">{$LANG.help}</a>
                <!-- SECTION:auth -->
                {if $config.authenticationEnabled == $smarty.const.ENABLED} |
                    {if isset($smarty.session.id)}
                        <a href="index.php?module=auth&amp;view=logout">{$LANG.logout}</a>
                        {if $smarty.session.domain_id != 1} | Domain: {$smarty.session.domain_id} - {$smarty.session.domain_name}{/if}
                    {else}
                        <a href="index.php?module=auth&amp;view=login">{$LANG.login}</a>
                    {/if}
                {/if}
            </div>
        {/if}
        {$smarty.capture.hook_topmenu_end}
    </div>
    <nav class="menu__mobile">
        {include file="templates/default/menuContent.tpl" menuClass="menu__mobile_" includeHash="yes"}
    </nav>
    <nav class="menu flora">
        {include file="templates/default/menuContent.tpl" menuClass="menu__" includeHash="no"}
    </nav>
    {literal}
        <script>
            $(document).ready(function () {
                $("div.delay__display").removeClass('delay__display');
            });
        </script>
    {/literal}
</div>
