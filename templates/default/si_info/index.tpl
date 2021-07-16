{*
 *  Script: index.tpl
 *      SI Info Index template
 *
 *  Last modified:
 *      20210618 by Richard Rowley convert to grid layout and add
 *          delay__display logic.
 *
 *  Website:
 *      https://simpleinvoices.group
 *
 *  License:
 *      GPL v3 or above
*}
{include file='templates/default/header.tpl'}
<div class="delay__display">
    <h1 class="align__text-center margin__top-0-75">About SimpleInvoices</h1>
    <div class="align__text-center margin__top-0-75">
        <a href="index.php?module=invoices&amp;view=manage">
            <button>Return To Invoices</button>
        </a>
    </div>
    <div class="container fonts__size-2 bold">
        <ul class="li__type-none">
            <li><a href="{$siUrl}/documentation/general/about.php">About</a><br/></li>
            <li><a href="{$siUrl}/documentation/general/change_log.php">Change Log</a><br/></li>
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
