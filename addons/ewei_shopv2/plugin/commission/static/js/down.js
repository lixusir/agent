define(['core', 'tpl'], function(core, tpl) {
    var modal = {
        page: 1,
        level: '',
        state:0,
    };
    modal.init = function() {
        $('.fui-content').infinite({
            onLoading: function() {
                modal.getList()
            }
        });
        if (modal.page == 1) {
            modal.getList()
        }

        $("#get_state").click(function (){

            var state = $(this).attr('data-score');

            if(state == 1){

                $(this).attr('data-score',0);

                $(this).text('参与砍价');

                $("#get_score").text('当前代表参与砍价的团队');
            }

            if(state == 0){

                $(this).attr('data-score',1);

                $(this).text('未参与砍价');

                $("#get_score").text('当前代表未参与砍价的团队');

            }

            $('.fui-content').infinite('init');
            $('.content-empty').hide(), $('.infinite-loading').show(), $('#container').html('');

            modal.page = 1,modal.state=state, modal.getList()
        })

        FoxUI.tab({
            container: $('#tab'),
            handlers: {
                level1: function() {
                    modal.changeTab(1)
                },
                level2: function() {
                    modal.changeTab(2)
                },
                level3: function() {
                    modal.changeTab(3)
                }
            }
        })

    };


    modal.changeTab = function(level) {
        $('.fui-content').infinite('init');
        $('.content-empty').hide(), $('.infinite-loading').show(), $('#container').html('');
        modal.page = 1, modal.level = level, modal.getList()
    };
    modal.getList = function() {
        core.json('commission/down/get_list', {
            page: modal.page,
            level: modal.level,
            state:modal.state
        }, function(ret) {
            var result = ret.result;
            if (result.total <= 0) {
                $('#container').hide();
                $('.content-empty').show();
                $('.fui-title').hide();
                $('.fui-content').infinite('stop')
            } else {
                $('#container').show();
                $('.content-empty').hide();
                $('.fui-title').show();
                $('.fui-content').infinite('init');
                if (result.list.length <= 0 || result.list.length < result.pagesize) {
                    $('.fui-content').infinite('stop')
                }
            }
            modal.page++;
            core.tpl('#container', 'tpl_commission_down_list', result, modal.page > 1)
        })
    };
    return modal
});