<div class="grid__area">
    <div class="pad__top_1 si_right">
        <a href='#' title="{$LANG.showDetails}"  class="show_itemised"
           onclick="$('.full_itemised').show();$('.hide_itemised').show();$('.abbrev_itemised').hide();$('.show_itemised').hide();">
            <img src="images/magnifier_zoom_in.png" alt="{$LANG.showDetails}"/>
        </a>
        <a href='#' title="{$LANG.hideDetails}"  class="hide_itemised si_hide"
           onclick="$('.full_itemised').hide();$('.hide_itemised').hide();$('.abbrev_itemised').show();$('.show_itemised').show();">
            <img src="images/magnifier_zoom_out.png" alt="{$LANG.hideDetails}"/>
        </a>
    </div>
</div>
<div class="grid__area">
    <div class="grid__head-10">
        <div class="bold si_right pad__right_1">{$LANG.quantityShort}</div>
        <div class="cols__2-span-5 bold">{$LANG.item}</div>
        <div class="cols__7-span-2 bold si_right">{$LANG.unitCost}</div>
        <div class="cols__9-span-2 bold si_right">{$LANG.priceUc}</div>
    </div>
</div>
