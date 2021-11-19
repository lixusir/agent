<?php defined('IN_IA') or exit('Access Denied');?><?php include $this->template('common/header', TEMPLATE_INCLUDEPATH, "sm_shop")?>
<div id="container">
    <?php include $this->template('common/nav', TEMPLATE_INCLUDEPATH, "sm_shop")?>
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">

                    </div>

                <h1>插件</h1>
                <ul class="breadcrumb">
                    <li><a >首页</a></li>
                    <li><a >插件列表</a></li>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-list"></i> 插件列表</h3>
                    </div>
                    <div class="panel-body plugin-list" >
                        <el-row v-if="plugin_list.length>0">
                            <el-col :span="8" v-for="(plugin,index) in plugin_list">
                                <el-card :body-style="{ padding: '0px' }">

                                    <a class="plugin-card" :href="plugin.link">
                                        <img :src="plugin.logo"
                                             style="width:100px; height:100px"
                                             class="image">
                                        <span>{{plugin.name}}</span>
                                    </a>

                                </el-card>
                            </el-col>
                        </el-row>
                        <el-row v-if="plugin_list.length==0">

                            <span>您当前没有安装插件，可以到微擎安装：
                                <a href="http://s.w7.cc/module-25562.html">神秘商城</a>

                            </span>
                        </el-row>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<?php include $this->template('common/footer', TEMPLATE_INCLUDEPATH, "sm_shop")?>

<script>

    var vue = new Vue({
        el: '#container',
        name: "plugin-list",

        data:function () {

            return{
                plugin_list:[],
                url_pre:'<?php echo $this->url_pre;?>',
            }

        },

        methods:{

            get_list:function(){

                var t = this;
                var url = this.url_pre + '&r=plugin.index';

                axios.get( url ).then(function( res ){

                    t.plugin_list = res.data;
                });

            },

        },

        created:function(){

            this.get_list();

        }

    });
</script>