<?php
/*
 * Help is specified in two ways:
 *  1) Fixed help messages from the lang.php file using the 'page' attribute in the
 *     'cluetip' link. Ex:
 *          <a class="cluetip" href="#"
 *             rel="index.php?module=documentation&amp;view=view&amp;page=help_company_logo"
 *             title="{$LANG.company_logo}">
 *            <img src="{$helpImagePath}help-small.png" alt="" />
 *          </a>
 *
 *  2) Custom help messages specified in on the page in the 'help' attribute in the
 *     'cluetip' link. Ex:
 *          <a class="cluetip" href="#"
 *             rel="index.php?module=documentation&amp;view=view&amp;help={$cflg.field_help}"
 *             title="{$LANG.custom_flags_upper}">
 *            <img src="{$helpImagePath}help-small.png" alt="" />
 *          </a>
 */
global $LANG, $smarty;

$menu = false;

if (isset($_GET['help'])) {
    $page = $_GET['help'];
}
else {
    $get_page = $_GET['page'];
    $page = isset($LANG[$get_page]) ? $LANG[$get_page] : $LANG['no_help_page'];
}

$smarty->assign("page", $page);
