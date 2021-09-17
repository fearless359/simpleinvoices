<?php
/*
 * Help is specified in two ways:
 *  1) Fixed help messages. Ex:
 *      <label for"fieldName" ....>
 *          <img class="tooltip" title="{$LANG.helpCompanyLogo} src="{$helpImagePath}help-small.png" alt="" />
 *      </label>
 *
 *  2) Custom help messages. Ex:
 *      <label for="custom_flags_{$cflg.flg_id}" class="cols__2-span-1 margin__top-0">{$cflg.field_label|trim|htmlSafe}
 *          {if strlen($cflg.field_help) > 0}
 *              <img class="tooltip" title="{$cflg.field_help}" src="{$helpImagePath}help-small.png" alt="help"/>
 *          {/if}
 *      </label>
 */
global $LANG, $smarty;

$menu = false;

if (isset($_GET['help'])) {
    $page = $_GET['help'];
}
else {
    $getPage = $_GET['page'];
    $page = $LANG[$getPage] ?? $LANG['noHelpPage'];
}

$smarty->assign("page", $page);
