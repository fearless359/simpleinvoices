{literal}
<script>
    $(document).ready(function () {
        let idx =
        {/literal}
        {if !isset($activeTab)}0
        {elseif $activeTab == '#money'}1
        {elseif $activeTab == '#people'}2
        {elseif $activeTab == '#product'}3
        {elseif $activeTab == '#settings'}4
        {else}0
        {/if}
        {literal};
        let mobile = $('.menu__mobile');
        let menu = $('.menu');

        menu.tabs({
            active: idx,
            scrollable: true,
            responsive: true
        });

        if ($(window).width < 768) { // Check if the screen width is less than 768px
            menu.hide(); // Hide the tabs
            menu.tabs("option", "active", false); // Deactivate all menu tabs
        } else {
            mobile.hide(); // Hide the tabs
        }
    })

</script>
{/literal}

