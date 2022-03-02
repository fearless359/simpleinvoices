{*
 *  Script: index.tpl
 *      SI Info Index template
 *
 *  Last modified:
 *      20211102 by Richard Rowley to remove load of header.tpl and remove
 *          the return to previous screen button as both were extraneous.
 *      20210618 by Richard Rowley convert to grid layout and add
 *          delay__display logic.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
*}
<div class="delay__display">
    <h1 class="align__text-center margin__top-0-75">{$LANG.aboutUc} {$LANG.simpleInvoices}</h1>
    <div class="align__text-center margin__top-1">{$aboutInfo}</div>
    <div class="container fonts__size-2 bold">
        <ul class="li__type-none">
            <li><a href="{$siUrl}/documentation/general/change_log.php">{$LANG.changeLog}</a><br/></li>
            <li><a href="{$siUrl}/documentation/general/gpl.php">GPL v3</a><br/></li>
        </ul>
    </div>
    {literal}
        <script>
            $(document).ready(function () {
                $("div.delay__display").removeClass('delay__display');
            });
        </script>
    {/literal}
</div>
