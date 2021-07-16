<div class="grid__area">
    <div class="pad__top-1 align__text-right">
        <a href='#' title="{$LANG.showDetails}"  class="showItemized"
           onclick="$('.fullItemized').show();$('.hideItemized').show();$('.showItemized').hide();">
            <img src="images/magnifier_zoom_in.png" alt="{$LANG.showDetails}"/>
        </a>
        <a href='#' title="{$LANG.hideDetails}"  class="hideItemized" style="display:none;"
           onclick="$('.fullItemized').hide();$('.hideItemized').hide();$('.showItemized').show();">
            <img src="images/magnifier_zoom_out.png" alt="{$LANG.hideDetails}"/>
        </a>
    </div>
</div>
<div class="grid__area">
    <div class="grid__container grid__head-10">
        <div class="bold align__text-right pad__right-1">{$LANG.quantityShort}</div>
        <div class="cols__2-span-5 bold">{$LANG.item}</div>
        <div class="cols__7-span-2 bold align__text-right">{$LANG.unitCost}</div>
        <div class="cols__9-span-2 bold align__text-right">{$LANG.priceUc}</div>
    </div>
</div>
