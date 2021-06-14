<div class="si_message">
    {$LANG.thankYou}&nbsp;{$LANG.beforeStarting}
</div>
{$buttonNumber = 1}
<div class="grid__area">
    {if empty($billers)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-10 si_center si_toolbar">
                <span class="bold">{$buttonNumber}:&nbsp;</span>
                <a href="index.php?module=billers&amp;view=create" class="positive">
                    <img src="images/user_add.png" alt=""/>
                    {$LANG.addNewBiller}
                </a>
            </div>
        </div>
        {$buttonNumber = $buttonNumber + 1}
    {/if}
    {if empty($customers)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-10 si_center si_toolbar">
                <span class="bold">{$buttonNumber}:&nbsp;</span>
                <a href="index.php?module=customers&amp;view=create" class="positive">
                    <img src="images/vcard_add.png" alt=""/>
                    {$LANG.customerAdd}
                </a>
            </div>
        </div>
        {$buttonNumber = $buttonNumber + 1}
    {/if}
    {if empty($products)}
        <div class="grid__container grid__head-10">
            <div class="cols__1-span-10 si_center si_toolbar">
                <span class="bold">{$buttonNumber}:&nbsp;</span>
                <a href="index.php?module=products&amp;view=create" class="positive">
                    <img src="images/cart_add.png" alt=""/>
                    {$LANG.addNewProduct}
                </a>
            </div>
        </div>
        {$buttonNumber = $buttonNumber + 1}
    {/if}
    <div class="grid__container grid__head-10">
        <div class="cols__1-span-10 si_center bold">{$LANG.setupCustomization}</div>
        <div class="cols__1-span-10 si_center si_toolbar">
            <span class="bold">{$buttonNumber}:&nbsp;</span>
            <a href="index.php?module=system_defaults&amp;view=manage" class="">
                <img src="images/cog_edit.png" alt=""/>
                {$LANG.siDefaults}
            </a>
        </div>
    </div>
</div>
