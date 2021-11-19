<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH, "sm_shop")?>

<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH, "sm_shop")?>
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <a v-bind:href="config.web_url + '&r=banner.page_edit'"
                       data-toggle="tooltip" title="" class="btn btn-primary"
                       data-original-title="添加"><i class="fa fa-plus"></i>
                    </a>
                    <a href="" data-toggle="tooltip" title=""
                       class="btn btn-default" data-original-title="重建"><i class="fa fa-refresh"></i>
                    </a>
                </div>
                <h1>横幅列表</h1>
                <ul class="breadcrumb">
                    <li><a href="">首页</a></li>
                    <li><a href="">横幅列表</a></li>
                </ul>
            </div>
        </div>
        <div class="container-fluid cye-lm-tag">
            <div class="panel panel-default cye-lm-tag">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-list"></i> 横幅列表</h3>
                </div>
                <div class="panel-body cye-lm-tag">
                    <form action=""
                          method="post" enctype="multipart/form-data" id="form-banner">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <td style="width: 1px;" class="text-center">
                                        <input type="checkbox" onclick="$('input[name*=\'selected\']').trigger('click');"></td>
                                    <td class="text-left">
                                        <a  class="asc">横幅位置</a>
                                    </td>
                                    <td class="text-left">
                                        <a >状态</a>
                                    </td>
                                    <td class="text-right">管理</td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item in banner_list" >
                                    <td class="text-center">
                                        <input type="checkbox" v-model="selected_banner_id" v-bind:value="item.banner_id" >
                                    </td>
                                    <td class="text-left">
                                        <span v-if="item.name=='home'">首页</span>
                                    </td>
                                    <td class="text-left">
                                        <span v-show="item.status==1">启用</span>
                                        <span v-show="item.status==0">禁用</span>
                                    </td>
                                    <td class="text-right">
                                        <a v-bind:href="config.web_url + '&r=banner.page_edit&banner_id=' + item.banner_id "
                                           data-toggle="tooltip" title=""
                                           class="btn btn-primary" data-original-title="编辑">
                                            <i class="fa fa-pencil"></i>
                                        </a>

                                        <a  v-on:click="banner_remove( item )"
                                            class="btn btn-danger" data-original-title="删除">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </form>
                    <div class="row cye-lm-tag">
                        <div class="col-sm-6 text-left"></div>
                        <div class="col-sm-6 text-right cye-lm-tag">显示 1 - 4 / 合计 4（共 1 页）</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include $this->template('common/footer', TEMPLATE_INCLUDEPATH, "sm_shop")?>

<script>
    var vue = new Vue( {
        el:'#container',
        name: "banner",

        data:function(){

            return {

                config:{

                    web_url:'<?php echo $this->url_pre?>'
                },
                selected_banner_id:[],
                banner_list:[],

            }
        },

        methods:{

            get_list:function(  ){

                var t = this;
                var url = this.config.web_url + '&r=banner.index';

                axios.get( url ).then(function( res ){

                    t.banner_list = res.data;

                });
            },

            banner_remove:function( banner ){

                var t = this;
                var url = this.config.web_url + '&r=banner.delete';
                url += '&banner_id=' + banner.banner_id;

                axios.get( url ).then(function( res ){

                    t.get_list();

                });
            }
        },

        created:function(){

            this.get_list();

        }
    });
</script>

<style scoped>

</style>