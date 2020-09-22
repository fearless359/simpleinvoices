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
  <a href="index.php?module=product_attribute&amp;view=create" class="">
    <img src="../../../images/add.png" alt="" />
    {$LANG.addProductAttribute}
  </a>
</div>
{if $number_of_rows == 0}
  <div class="si_message">{$LANG.noProductAttributes}</div>
{else}
  <table id="si-data-table" class="display compact">
    <thead>
    <tr>
      <th>{$LANG.actions}</th>
      <th>{$LANG.name}</th>
      <th>{$LANG.type}</th>
      <th>{$LANG.enabled}</th>
      <th>{$LANG.visible}</th>
    </tr>
    </thead>
    <tbody>
    {foreach $product_attributes as $product_attribute}
      <tr>
        <td class="si_center">
          <a class="index_table" title="{$product_attribute['vname']}"
             href="index.php?module=product_attribute&amp;view=view&amp;id={$product_attribute['id']}">
            <img src="../../../images/view.png" alt="{$product_attribute['vname']}" class="action"/>
          </a>
          <a class="index_table" title="{$product_attribute['ename']}"
             href="index.php?module=product_attribute&amp;view=edit&amp;id={$product_attribute['id']}">
            <img src="../../../images/edit.png" alt="{$product_attribute['ename']}" class="action"/>
          </a>
        </td>
        <td>{$product_attribute['name']}</td>
        <td>{$product_attribute['type_name']|capitalize}</td>
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
          [3, "desc"],
          [4, "desc"],
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
