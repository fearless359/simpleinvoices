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
        $('#tabmenu').tabs({active: idx});
    });
</script>
{/literal}

