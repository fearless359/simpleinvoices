<div class="si_message">
    {$LANG.thankYou}&nbsp;{$LANG.beforeStarting}
</div>
{$buttonNumber = 1}
<div class="flex__area">
    {if empty($billers)}
        <div class="flex__container">
            <span class="bold margin__top-1">{$buttonNumber}:&nbsp;</span>
            <a href="index.php?module=billers&amp;view=create" class="button positive">
                <img src="images/user_add.png" alt=""/>{$LANG.addNewBiller}
            </a>
        </div>
        {$buttonNumber = $buttonNumber + 1}
    {/if}
    {if empty($customers)}
        <div class=flex__container>
            <span class="bold margin__top-1">{$buttonNumber}:&nbsp;</span>
            <a href="index.php?module=customers&amp;view=create" class="button positive">
                <img src="images/vcard_add.png" alt=""/>{$LANG.customerAdd}
            </a>
        </div>
        {$buttonNumber = $buttonNumber + 1}
    {/if}
    {if empty($products)}
        <div class="flex__container">
            <span class="bold margin__top-1">{$buttonNumber}:&nbsp;</span>
            <a href="index.php?module=products&amp;view=create" class="button positive">
                <img src="images/cart_add.png" alt=""/>{$LANG.addNewProduct}
            </a>
        </div>
        {$buttonNumber = $buttonNumber + 1}
    {/if}
    <div class="flex__container">
        <span>{$LANG.setupCustomization}</span>
    </div>
    <div class="flex__container">
        <span class="bold margin__top-1">{$buttonNumber}:&nbsp;</span>
        <a href="index.php?module=system_defaults&amp;view=manage" class="">
            <button><img src="images/cog_edit.png" alt=""/>{$LANG.siDefaults}</button>
        </a>
    </div>
</div>
