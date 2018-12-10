{include file="templates/default/headline.xml"}
<!DOCTYPE html>
<html>
<head>
{strip}
    {assign var='tmp_lang_module' value="title_module_`$module`"}
    {assign var='tmp_lang_module' value=$LANG.$tmp_lang_module|default:$LANG.$module|default:$module}
    {assign var='tmp_lang_view' value="title_view_`$view`"}
    {assign var='tmp_lang_view' value=$LANG.$tmp_lang_view|default:$LANG.$view|default:$view}
    {$smarty.capture.hook_head_start}
{/strip}
    <title>{$tmp_lang_module} : {$tmp_lang_view} - {$LANG.company_name} </title>
    <meta charset="UTF-8" />
    <meta name="robots" content="noindex, nofollow" />

    <link rel="shortcut icon" href="images/common/favicon.ico" />

    <link rel="stylesheet" type="text/css" href="include/jquery/jquery-ui-1.12.1.custom/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="include/jquery/jquery-ui-1.12.1.custom/jquery-ui.structure.css" />
    <link rel="stylesheet" type="text/css" href="include/jquery/jquery-ui-1.12.1.custom/jquery-ui.theme.css" />

    <link rel="stylesheet" type="text/css" href="include/jquery/css/main.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="include/jquery/css/print.css" media="print" />

    <link rel="stylesheet" type="text/css" href="node_modules/datatables.net-dt/css/jquery.dataTables.css" />

    <script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="include/jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>

    <script src="node_modules/datatables.net/js/jquery.dataTables.js"></script>
    {$extension_jquery_files }
    {if $config->debug->level == "All"}
        <link rel="stylesheet" type="text/css" href="library/blackbirdjs/blackbird.css" />
        <script src="library/blackbirdjs/blackbird.js"></script>
    {/if}

    <script type="text/javascript" src="include/jquery/jquery.validationEngine.js"></script>

{$smarty.capture.hook_head_end}
</head>
<body class="body_si body_module_{$module} body_view_{$view}">
{$smarty.capture.hook_body_start}
<div class="si_grey_background"></div>
