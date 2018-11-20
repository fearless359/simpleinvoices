
{* if customer is updated or saved.*}
{if $smarty.post.cancel ||
    ($smarty.post.name != "" && $smarty.post.submit != null) }
    {$display_block}
    {$refresh_redirect}
{else}
    {* if  name was inserted *}
    {if $smarty.post.submit !=null}
        <div class="validation_alert"><img src="images/common/important.png" alt=""/>
            You must enter a name for the product attribute
        </div>
        <hr/>
    {/if}
    <form name="frmpost" action="index.php?module=product_attribute&amp;view=add" method="post">
        <h3>{$LANG.add_product_attribute}</h3>
        <hr/>
        <table class="center">
            <tr>
                <th class="left">{$LANG.name}</th>
                <td><input type="text" name="name" value="{$smarty.post.name}" size="25"/></td>
            </tr>
            <tr>
                <th class="left">{$LANG.type}</th>
                <td>
                    <select name="type_id">
                        {foreach from=$types key=k item=v}
                            <option value="{$v.id}">{$LANG[$v.name]}</option>
                        {/foreach}
                    </select>
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.enabled}</th>
                <td>
                    {html_options class=edit name=enabled options=$enabled selected=1}
                </td>
            </tr>
            <tr>
                <th class="left">{$LANG.visible}</th>
                <td>
                    {html_options class=edit name=visible options=$enabled selected=1}
                </td>
            </tr>
        </table>
        <hr/>
        {*<div class="si_toolbar si_toolbar_form">*}
            {*<button type="submit" class="positive" name="submit" value="{$LANG.insert_product_attribute}">*}
                {*<img class="button_img" src="images/common/tick.png" alt="" />*}
                {*{$LANG.save}*}
            {*</button>*}
            {*<a href="index.php?module=cron&amp;view=manage" class="negative">*}
                {*<img src="images/common/cross.png" alt="" />*}
                {*{$LANG.cancel}*}
            {*</a>*}
        {*</div>*}
        <div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="{$LANG.insert_product_attribute}">
                <img class="button_img" src="images/common/tick.png" alt=""/>{$LANG.save}
            </button>
            <button type="submit" class="negative" name="cancel" value="{$LANG.cancel}">
                <img class="button_img" src="images/common/cross.png" alt=""/>
                {$LANG.cancel}
            </button>
        </div>
        <input type="hidden" name="op" value="insert_product_attribute"/>
    </form>
{/if}
