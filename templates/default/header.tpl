{include file="templates/default/headline.xml"}
<!DOCTYPE html>
<!--suppress HtmlRequiredLangAttribute -->
<html>
<head>
    {strip}
        {assign var='tmp_lang_module' value="title_module_`$module`"}
        {assign var='tmp_lang_module' value=$LANG.$tmp_lang_module|default:$LANG.$module|default:$module}
        {assign var='tmp_lang_view' value="title_view_`$view`"}
        {assign var='tmp_lang_view' value=$LANG.$tmp_lang_view|default:$LANG.$view|default:$view}
        {$smarty.capture.hook_head_start}
    {/strip}
    <title>{$tmp_lang_module} : {$tmp_lang_view} - {$LANG.companyName} </title>
    <meta charset="UTF-8"/>
    <meta name="robots" content="noindex, nofollow"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="../../images/favicon.ico"/>
    <link href="include/jquery/jquery-ui-1.12.1.custom/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link href="include/jquery/jquery-ui-1.12.1.custom/jquery-ui.structure.css" rel="stylesheet" type="text/css"/>
    <link href="include/jquery/jquery-ui-1.12.1.custom/jquery-ui.theme.css" rel="stylesheet" type="text/css"/>
    <link href="include/jquery/jQuery-Validation-Engine-master/css/validationEngine.jquery.css" rel="stylesheet" type="text/css"/>
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
   <link href="include/jquery/trix-master/dist/trix.css" rel="stylesheet" type="text/css">
   <link href="include/jquery/cluetip/jquery.cluetip.css" rel="stylesheet" type="text/css" />

   <script src="node_modules/jquery/dist/jquery.js"></script>
   <script src="include/jquery/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
   <script src="node_modules/datatables.net/js/jquery.dataTables.min.js"></script>
   <script src="node_modules/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
   <script src="include/jquery/cluetip/jquery.cluetip.js"></script>

   <script src="include/jquery/jQuery-Validation-Engine-master/js/languages/jquery.validationEngine-en.js"></script>
   <script src="include/jquery/jQuery-Validation-Engine-master/js/jquery.validationEngine.js"></script>
   <script>
   {literal}
   $(document).ready(function () {
       // binds form submission and fields to the validation engine
       $('#frmpost').validationEngine({ promptPosition:
               "centerRight",
               'custom_error_messages': {
                   '.creditCard': {
                       'required': { 'message': "An entry is required if associated CC field are not blank." }
                   }
               }
       });
   });
    {/literal}
    </script>

    <script src="include/jquery/trix-master/dist/trix.js"></script>
    {$extension_jquery_files }
    <script src="include/jquery/jquery.functions1.js"></script>
    {include 'include/jquery/jquery.functions.js.tpl'}
    <script src="include/jquery/jquery.conf1.js"></script>
    {include 'include/jquery/jquery.conf.js.tpl'}
{*    {if $config.debugLevel == "All"}*}
{*        <link rel="stylesheet" type="text/css" href="library/blackbirdjs/blackbird.css"/>*}
{*        <script src="library/blackbirdjs/blackbird.js"></script>*}
{*    {/if}*}
    <link rel="stylesheet" type="text/css" href="include/jquery/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="include/jquery/css/print.css" media="print"/>
    {$smarty.capture.hook_head_end}
</head>
{*<body class="body_si body_module_{$module} body_view_{$view}">*}
<body class="body_si body_module_{$module}">
{$smarty.capture.hook_body_start}
<div class="si_grey_background"></div>
