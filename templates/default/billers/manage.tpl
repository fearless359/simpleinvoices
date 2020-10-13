{*
 *  Script: manage.tpl
 *      Biller manage template
 *
 *  License:
 *      GPL v3 or above
 *
 *  Last modified:
 *      2018-12-10 by Richard Rowley
 *
 *  Website:
 *      https://simpleinvoices.group
 *}
<div class="si_toolbar si_toolbar_top">
    <a href="index.php?module=billers&amp;view=create" class="">
        <img src="../../../images/add.png" alt=""/>
        {$LANG.addNewBiller}
    </a>
</div>
{if $numberOfRows == 0}
    <div class="si_message">{$LANG.noBillers}</div>
{else}
    <table id="si-data-table" class="display compact">
        <thead>
        <tr>
            <th>{$LANG.actions}</th>
            <th>{$LANG.nameUc}</th>
            <th>{$LANG.street}</th>
            <th>{$LANG.city}</th>
            <th>{$LANG.state}</th>
            <th>{$LANG.zip}</th>
            <th>{$LANG.email}</th>
            <th>{$LANG.enabled}</th>
        </tr>
        </thead>
        <tbody>
        {foreach $billers as $biller}
            <tr>
                <td>
                    <a class="index_table" title="{$biller['vname']}" href="index.php?module=billers&amp;view=view&amp;id={$biller['id']}">
                        <img src="../../../images/view.png" class="action" alt="view"/>
                    </a>
                    <a class="index_table" title="{$biller['ename']}" href="index.php?module=billers&amp;view=edit&amp;id={$biller['id']}">
                        <img src="../../../images/edit.png" class="action" alt="edit"/>
                    </a>
                </td>
                <td>{$biller['name']}</td>
                <td>{$biller['street_address']}</td>
                <td>{$biller['city']}</td>
                <td>{$biller['state']}</td>
                <td class="si_right">{$biller['zip_code']}</td>
                <td>{$biller['email']}</td>
                <td class="si_center">
                    <!-- This span is here for datatables to order on -->
                    <span style="display: none">{$biller['enabled_text']}</span>
                    <img src="{$biller['image']}" alt="{$biller['enabled_text']}" title="{$biller['enabled_text']}"/>
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
                    [7, "desc"],
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
