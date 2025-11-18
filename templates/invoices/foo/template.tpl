<!--suppress HtmlRequiredLangAttribute -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="{$css}" type="text/css" rel="stylesheet" media="all">
    <title>Test</title>
</head>
<body>
{custom_flag_template customFieldLabels=$customFieldLabels customer=$customer invoice=$invoice}
<div>

    <!-- Customer info & Summary - start -->
    <div>
        <table style="width:100%;">
            {custom_flag_template1 cust_cnt=$cust_cnt cust=$cust ndx=$ndx}
        </table>
    </div>

    <!-- Customer section - end -->
    <!-- Transaction section - begin -->
    <div>
        <table style="width:100%;">
            {foreach $invoiceItems as $invoiceItem}
                {if !empty($invoiceItem.description)}
                    <tr>
                        <td class="font2">&nbsp;</td>
                        <td class="font2"
                            style="text-align:justify;margin-left:4px;padding-left:16px;margin-right:4px;padding-right:24px;"
                            colspan="2">
                            {$invoiceItem.description|nl2br|outHtml}
                        </td>
                        <td class="font2">&nbsp;</td>
                    </tr>
                {/if}
            {/foreach}
        </table>
    </div>
</div>
</body>
</html>
