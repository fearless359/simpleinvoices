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
    <meta charset="UTF-8"/>
    <meta name="robots" content="noindex, nofollow"/>

    <link rel="shortcut icon" href="images/common/favicon.ico"/>
    {literal}
        <link href="include/jquery/jquery-ui-1.12.1.custom/jquery-ui.css" rel="stylesheet" type="text/css"/>
        <link href="include/jquery/jquery-ui-1.12.1.custom/jquery-ui.structure.css" rel="stylesheet" type="text/css"/>
        <link href="include/jquery/jquery-ui-1.12.1.custom/jquery-ui.theme.css" rel="stylesheet" type="text/css"/>
        <link href="include/jquery/jQuery-Validation-Engine-master/css/validationEngine.jquery.css" rel="stylesheet" type="text/css"/>
        <style>
            .formError {
                position: relative !important;
                display: block !important;
                cursor: pointer !important;
                text-align: left !important;
                top: 0 !important;
                width: 40% !important;
            }
        </style>
        <link href="node_modules/datatables.net-dt/css/jquery.dataTables.css" rel="stylesheet" type="text/css"/>
        <link href="include/jquery/trix-master/dist/trix.css" rel="stylesheet" type="text/css">
        <link href="node_modules/qtip2/dist/jquery.qtip.css" rel="stylesheet" type="text/css">
        <link href="include/jquery/css/main.css" media="all" rel="stylesheet" type="text/css"/>
        <link href="include/jquery/css/print.css" media="print" rel="stylesheet" type="text/css"/>
        <script src="node_modules/jquery/dist/jquery.js"></script>
        <script src="include/jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
        <script src="node_modules/datatables.net/js/jquery.dataTables.js"></script>
        <script src="node_modules/qtip2/dist/jquery.qtip.js"></script>

        <script src="include/jquery/jQuery-Validation-Engine-master/js/languages/jquery.validationEngine-en.js"></script>
        <script src="include/jquery/jQuery-Validation-Engine-master/js/jquery.validationEngine.js"></script>
        <script>
            jQuery(document).ready(function () {
                // binds form submission and fields to the validation engine
                $('#frmpost').validationEngine({
                    promptPosition: "centerRight"
                });
            });
        </script>

        <script src="include/jquery/trix-master/dist/trix.js"></script>
    {/literal}
    {$extension_jquery_files }
    {include 'include/jquery/jquery.functions.js.tpl'}
    {include 'include/jquery/jquery.conf.js.tpl'}
    {if $config->debug->level == "All"}
    {literal}
        <!-- TODO: Look for replacement supported by node.js  -->
        <link rel="stylesheet" type="text/css" href="library/blackbirdjs/blackbird.css"/>
        <script src="library/blackbirdjs/blackbird.js"></script>
    {/literal}
    {/if}
    {$smarty.capture.hook_head_end}
</head>
<body class="body_si body_module_{$module} body_view_{$view}">
{$smarty.capture.hook_body_start}
<div class="si_grey_background"></div>
