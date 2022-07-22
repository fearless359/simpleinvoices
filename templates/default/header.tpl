{include file="templates/default/headline.xml"}
<!DOCTYPE html>
<!--suppress HtmlRequiredLangAttribute -->
<html lang="en">
<head>
    {strip}
        {assign var='tmp_lang_module' value="title_module_`$module`"}
        {assign var='tmp_lang_module' value=$LANG.$tmp_lang_module|default:$LANG.$module|default:$module}
        {assign var='tmp_lang_view' value="title_view_`$view`"}
        {assign var='tmp_lang_view' value=$LANG.$tmp_lang_view|default:$LANG.$view|default:$view}
        {$smarty.capture.hook_head_start}
    {/strip}
    <title>{$tmp_lang_module} : {$tmp_lang_view} - {$LANG.companyNameItem|htmlSafe} </title>
    <meta charset="UTF-8"/>
    <meta name="robots" content="noindex, nofollow"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="../../images/favicon.ico"/>
    <link href="include/js/jquery-ui-1.13.1.custom/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link href="include/js/jquery-ui-1.13.1.custom/jquery-ui.structure.css" rel="stylesheet" type="text/css"/>
    <link href="include/js/jquery-ui-1.13.1.custom/jquery-ui.theme.css" rel="stylesheet" type="text/css"/>

    <link href="node_modules/tooltipster/dist/css/tooltipster.bundle.min.css" rel="stylesheet" type="text/css"/>
    <link href="node_modules/tooltipster/dist/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-light.min.css" rel="stylesheet" type="text/css"/>
    <!--suppress CssUnusedSymbol -->
    <style>
        {literal}
        /* Settings for the validationEngine */
        .formError {
            position: relative !important;
            display: block !important;
            cursor: pointer !important;
            text-align: left !important;
            top: 0 !important;
            width: 40% !important;
        }

        {/literal}
    </style>
    <link href="node_modules/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="include/js/trix-main/dist/trix.css" rel="stylesheet" type="text/css">

    <script src="node_modules/jquery/dist/jquery.js"></script>
    <script src="include/js/jquery-ui-1.13.1.custom/jquery-ui.js"></script>
    <script src="node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="node_modules/datatables.net-responsive/js/dataTables.responsive.min.js"></script>

    <script src="node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="node_modules/jquery-validation/dist/additional-methods.min.js"></script>

    <script src="node_modules/tooltipster/dist/js/tooltipster.bundle.min.js"></script>

    <script src="include/js/trix-main/dist/trix.js"></script>
    {$extension_jquery_files }
    <script src="include/js/jquery.functions1.js"></script>
    {include 'include/js/jquery.functions.js.tpl'}
    <script src="include/js/jquery.conf1.js"></script>
    {include 'include/js/jquery.conf.js.tpl'}
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    {$smarty.capture.hook_head_end}
</head>
{*<body class="body_si body_module_{$module} body_view_{$view}">*}
<body class="body_si body_module_{$module}">
{$smarty.capture.hook_body_start}
<div class="si_grey_background"></div>
