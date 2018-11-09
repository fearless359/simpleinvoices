<script>
{literal}
    var columns = 5;
    var padding = 12;
    var grid_width = $('.col').width();

    var LANG_rate = {/literal}'{$LANG.rate}'{literal};

    grid_width = grid_width - (columns * padding);
    percentage_width = grid_width / 100;

    $('#manageGrid').flexigrid({
        url: 'index.php?module=extensions&view=xml',
        dataType: 'xml',
        colModel : [
            {display: '{/literal}{$LANG.actions}{literal}'    , name : 'actions'    , width : 10 * percentage_width, sortable : false, align: 'center'},
            {display: '{/literal}{$LANG.id}{literal}'         , name : 'id'         , width : 10 * percentage_width, sortable : true, align: 'left'},
            {display: '{/literal}{$LANG.name}{literal}'       , name : 'name'       , width : 30 * percentage_width, sortable : true, align: 'left'},
            {display: '{/literal}{$LANG.description}{literal}', name : 'description', width : 40 * percentage_width, sortable : true, align: 'left'},
            {display: '{/literal}{$LANG.status}{literal}'     , name : 'enabled'    , width : 10 * percentage_width, sortable : true, align: 'center'}
        ],
        searchitems : [
            {display: '{/literal}{$LANG.id}{literal}', name : 'id'},
            {display: '{/literal}{$LANG.name}{literal}', name : 'name'},
            {display: '{/literal}{$LANG.description}{literal}', name : 'description', isdefault: true}
        ],
        sortname: 'name',
        sortorder: 'asc',
        usepager: true,
        /*title: 'Manage Custom Fields',*/
        pagestat: '{/literal}{$LANG.displaying_items}{literal}',
        procmsg: '{/literal}{$LANG.processing}{literal}',
        nomsg: '{/literal}{$LANG.no_items}{literal}',
        pagemsg: '{/literal}{$LANG.page}{literal}',
        ofmsg: '{/literal}{$LANG.of}{literal}',
        useRp: false,
        rp: 25,
        showToggleBtn: false,
        showTableToggleBtn: false,
        height: 'auto'
    });
{/literal}
</script>
