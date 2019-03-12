{*
 *  Script: manage.tpl
 * 	  Product attributes manage template
 *
 *  Authors:
 *	  Justin Kelly, Ben Brown
 *
 *  Last edited:
 *    2018-12-15 by Richard Rowley
 *
 *  License:
 *	  GPL v3 or above
 *}
<div class="si_toolbar si_toolbar_top">
  <a href="index.php?module=product_attribute&amp;view=add" class="">
    <img src="images/common/add.png" alt="" />
    {$LANG.add_product_attribute}
  </a>
</div>
{if $number_of_rows == 0}
  <div class="si_message">{$LANG.no_product_attributes}</div>
{else}
  <table id="si-data-table" class="display compact">
    <thead>
    <tr>
      <th>{$LANG.actions}</th>
      <th>{$LANG.name}</th>
      <th>{$LANG.enabled}</th>
      <th>{$LANG.visible}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $product_attributes as $product_attribute}
      <tr>
        <td class="si_center">
          <a class="index_table" title="{$product_attribute['vname']}"
             href="index.php?module=product_attribute&amp;view=details&amp;id={$product_attribute['id']}&amp;action=view">
            <img src="images/common/view.png" alt="{$product_attribute['vname']}" height="16" border="-5px" />
          </a>
          <a class="index_table" title="{$product_attribute['ename']}"
             href="index.php?module=product_attribute&amp;view=details&amp;id={$product_attribute['id']}&amp;action=edit">
            <img src="images/common/edit.png" alt="{$product_attribute['ename']}" height="16" border="-5px"/>
          </a>
        </td>
        <td>{$product_attribute['name']}</td>
        <td class="si_center">
          <span style="display:none">{$product_attribute['enabled_text']}</span>
          <img src="{$product_attribute['enabled_image']}" alt="{$LANG.enabled}">
        </td>
        <td class="si_center">
          <span style="display:none">{$product_attribute['visible_text']}</span>
          <img src="{$product_attribute['visible_image']}" alt="{$LANG.visible}">
        </td>
      </tr>
    {/foreach}
    </tbody>
  </table>
  <script>
    {literal}
    $(document).ready(function () {
      $('#si-data-table').DataTable({
        "lengthMenu": [[15, 20, 25, 30, -1], [15, 20, 25, 30, "All"]],
        "order": [
          [2, "desc"],
          [3, "desc"],
          [1, "asc"]
        ],
        "columnDefs": [
          {"targets": 0, "orderable": false}
        ],
        "colReorder": true
      });
    });
    {/literal}
  </script>
{/if}
