{literal}
<script>
    $(document).ready(function () {
        let idx =
        {/literal}
                {if !isset($active_tab)}0
                {elseif $active_tab == '#money'}1
                {elseif $active_tab == '#people'}2
                {elseif $active_tab == '#product'}3
                {elseif $active_tab == '#setting'}4
                {else}0
                {/if}
       {literal};
        $('#tabmenu').tabs({active: idx});
    });
</script>
{/literal}

