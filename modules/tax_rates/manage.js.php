<script>
{literal}
    var columns = 6;
    var padding = 12;
    var grid_width = $('.col').width();

    grid_width = grid_width - (columns * padding);
    percentage_width = grid_width / 100;


    $('#manageGrid').flexigrid({
        url: 'index.php?module=tax_rates&view=xml',
        dataType: 'xml',
        colModel : [
            {display: '{/literal}{$LANG.actions}{literal}'    , name : 'actions'        , width : 10 * percentage_width, sortable : false, align: 'center'},
            {display: '{/literal}{$LANG.id}{literal}'         , name : 'tax_id'         , width : 10 * percentage_width, sortable : true , align: 'right'},
            {display: '{/literal}{$LANG.description}{literal}', name : 'tax_description', width : 55 * percentage_width, sortable : true , align: 'left'},
            {display: '{/literal}{$LANG.rate}{literal}'       , name : 'tax_percentage' , width : 15 * percentage_width, sortable : true , align: 'right'},
            {display: '{/literal}{$LANG.enabled}{literal}'    , name : 'enabled'        , width : 10 * percentage_width, sortable : true , align: 'center'}
        ],
        searchitems : [
            {display: '{/literal}{$LANG.id}{literal}'            , name : 'tax_id'},
            {display: '{/literal}{$LANG.description}{literal}'   , name : 'tax_description', isdefault: true},
            {display: '{/literal}{$LANG.tax_percentage}{literal}', name : 'tax_percentage'}
        ],
        sortname: 'tax_description',
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
