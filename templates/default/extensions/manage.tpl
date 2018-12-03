{*
 * Script: manage.tpl
 *   Extensions manage template
 *
 * Authors:
 *   Justin Kelly, Ben Brown, Marcel van Dorp
 *
 * Last edited:
 *   2018-11-25 by Richard Rowley
 *
 * License:
 *   GPL v2 or above
 *}
{if !isset($exts)}
  <p><em>No extensions registered</em></p>
{else}
  <table id="manageGrid" style="display:none"></table>
  {include file='modules/extensions/manage.js.php'}
{/if}
